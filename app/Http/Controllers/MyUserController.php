<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Branch;
use App\Schoolgroup;
use App\School;
use Session;
use Auth;

class MyUserController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function __construct()
     {
         $this->middleware('auth');
     }
    public function index()
    {
      if(adminchk())
	  {
		 
      $branch = branch::all();
      $schoolgroup = Schoolgroup::all();
      $user = user::all();
      $school = school::inNetwork(env('APP_SCHOOLGROUP','0'))->get();
      $resetSchools = School::whereIn('id', User::where('position', 0)->whereNotNull('school_id')->pluck('school_id'))
          ->orderBy('school_name')->get(['id', 'school_name']);
      $resetNetworks = Schoolgroup::whereIn('id', User::where('position', 5)->pluck('schoolgroup'))
          ->orderBy('schoolgroup_name')->get(['id', 'schoolgroup_name']);
      $resetCenters = Branch::whereIn('id', User::where('position', 1)->pluck('branch'))
          ->orderBy('branch_name')->get(['id', 'branch_name']);
      $data  = array(
        'data' => $user,
        'branch'=> $branch,
        'schoolgroup'=>$schoolgroup,
        'school'=>$school,
        'resetSchools'=>$resetSchools,
        'resetNetworks'=>$resetNetworks,
        'resetCenters'=>$resetCenters,
      );
      $schoolsWithUser = User::where('position', 0)->whereNotNull('school_id')->pluck('school_id')->filter(fn ($v) => is_int($v) || is_string($v))->flip();
      $networksWithUser = User::where('position', 5)->whereNotNull('schoolgroup')->pluck('schoolgroup')->filter(fn ($v) => is_int($v) || is_string($v))->flip();
      // ศูนย์ที่มีบัญชีแล้ว: เทียบทั้งรหัสและชื่อศูนย์ เพราะศูนย์ถูกคัดลอกเป็นแถวใหม่ทุกงานแข่งขัน (บัญชีเดิมอาจชี้รหัสศูนย์ของงานก่อน)
      $centerUserBranchIds = User::where('position', 1)->whereNotNull('branch')->pluck('branch')->filter(fn ($v) => is_int($v) || is_string($v))->values();
      $centerUserNames = Branch::withoutGlobalScopes()->whereIn('id', $centerUserBranchIds)->pluck('branch_name')->filter(fn ($v) => is_string($v) && $v !== '')->flip();
      $centersWithUser = $centerUserBranchIds->flip();
      $data['registerData'] = [
        'school' => School::with('schoolgroups')->orderBy('school_name')->get()->map(fn ($s) => [
          'id' => $s->id,
          'label' => $s->school_name.($s->school_code ? ' ('.$s->school_code.')' : ''),
          'name' => $s->school_name,
          'lastname' => (string) $s->school_detail,
          'username' => (string) $s->school_code,
          'logo' => $s->logo_url,
          'hasUser' => $schoolsWithUser->has($s->id),
        ])->values(),
        'network' => Schoolgroup::orderBy('id')->get()->map(fn ($g) => [
          'id' => $g->id,
          'label' => $g->schoolgroup_name,
          'name' => 'เครือข่ายสถานศึกษา',
          'lastname' => (string) $g->schoolgroup_name,
          'username' => 'area'.str_pad((string) $g->id, 2, '0', STR_PAD_LEFT),
          'logo' => null,
          'hasUser' => $networksWithUser->has($g->id),
        ])->values(),
        'center' => Branch::orderBy('id')->get()->map(fn ($b) => [
          'id' => $b->id,
          'label' => $b->branch_name,
          'name' => 'ศูนย์การแข่งขัน',
          'lastname' => (string) $b->branch_name,
          'username' => 'center'.str_pad((string) $b->id, 2, '0', STR_PAD_LEFT),
          'logo' => null,
          'hasUser' => $centersWithUser->has($b->id) || $centerUserNames->has($b->branch_name),
        ])->values(),
      ];
      return view('auth.adduser',$data);
	  }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      officerchk();
      return redirect()->route('myuser.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      adminchk();
      $request->validate([
          'account_type' => 'required|in:school,network,center,admin',
          'target_id' => 'required_unless:account_type,admin|nullable|integer',
          'name' => 'required|string|max:191',
          'lastname' => 'required|string|max:191',
          'phonenumber' => 'nullable|string|max:30',
          'email' => 'required|string|max:191|unique:users,email',
          'password' => 'nullable|string|min:4|max:100',
          'must_change_password' => 'nullable|boolean',
      ], [
          'target_id.required_unless' => 'กรุณาเลือกหน่วยงานที่จะผูกกับบัญชี',
          'email.unique' => 'Username นี้มีผู้ใช้แล้ว',
      ]);

      $user = new User;
      $type = $request->account_type;
      $targetId = (int) $request->target_id;

      if ($type === 'school') {
        $school = School::findOrFail($targetId);
        if (User::where('position', 0)->where('school_id', $school->id)->exists()) {
          return back()->withInput()->withErrors(['target_id' => 'โรงเรียนนี้มีบัญชีผู้ใช้แล้ว (1 โรงเรียนมี 1 บัญชี)']);
        }
        $user->position = 0;
        $user->school_id = $school->id;
        $user->schoolgroup = $school->school_group;
        $user->branch = $school->id; // ระบบเดิมเก็บรหัสโรงเรียนในคอลัมน์ branch ของบัญชีโรงเรียน
      } elseif ($type === 'network') {
        $group = Schoolgroup::findOrFail($targetId);
        if (User::where('position', 5)->where('schoolgroup', $group->id)->exists()) {
          return back()->withInput()->withErrors(['target_id' => 'เครือข่ายนี้มีบัญชีผู้ใช้แล้ว (1 เครือข่ายมี 1 บัญชี)']);
        }
        $user->position = 5;
        $user->schoolgroup = $group->id;
        $user->branch = (int) (User::where('position', 5)->value('branch') ?: 1);
      } elseif ($type === 'center') {
        $branch = Branch::findOrFail($targetId);
        $sameCenterIds = Branch::withoutGlobalScopes()->where('branch_name', $branch->branch_name)->pluck('id')->push($branch->id);
        if (User::where('position', 1)->whereIn('branch', $sameCenterIds)->exists()) {
          return back()->withInput()->withErrors(['target_id' => 'ศูนย์การแข่งขันนี้มีบัญชีผู้ใช้แล้ว (1 ศูนย์มี 1 บัญชี)']);
        }
        $user->position = 1;
        $user->branch = $branch->id;
        $user->schoolgroup = 0;
      } else {
        $user->position = 10;
        $user->branch = 0;
        $user->schoolgroup = 0;
      }

      $user->name = $request->name;
      $user->lastname = $request->lastname;
      $user->phonenumber = (string) $request->phonenumber;
      $user->email = trim($request->email);
      $user->password = \Illuminate\Support\Facades\Hash::make($request->filled('password') ? $request->password : $user->email);
      $user->must_change_password = $request->boolean('must_change_password', true);
      $user->save();

      Session::flash('massage', 'สร้างผู้ใช้ใหม่สำเร็จ: '.$user->email.($request->filled('password') ? '' : ' (รหัสผ่านเริ่มต้นเท่ากับ Username)'));
      return redirect('/myuser');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        adminchk();
        $user = User::find($id);
        $branch = branch::all();
        $schoolgroup = Schoolgroup::all();
        $school = school::inNetwork(env('APP_SCHOOLGROUP','0'))->get();
        $data = array(
          'data'=>$user,
          'id'=>$id,
          'branch'=>$branch,
          'schoolgroup'=>$schoolgroup,
          'school'=>$school,
        );
        return view('auth.edituser',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        adminchk();
        MyUserController::validate($request,[
          'name'=>'required',
          'lastname'=>'required',
          'phonenumber'=>'required|numeric',
          'email'=>['required','string','max:255',\Illuminate\Validation\Rule::unique('users','email')->ignore((int) $id)],
          //'password'=>'required'
        ]);
        $user= User::find($id);
        $user->name=$request->name;
        $user->lastname=$request->lastname;
        $user->position=$request->position;
        $user->email=$request->email;
        $user->phonenumber=$request->phonenumber;
        $user->branch=$request->branch;
        $user->schoolgroup=$request->schoolgroup;
        if($request->password!=''){
          $user->password=bcrypt($request->password);
        }
        $user->save();
        Session::flash('massage','แก้ไขข้อมูลผู้ใช้งานสำเร็จ !!');
        return redirect('myuser');
    }

    public function updateuser(Request $request)
    {
        $id = $request->id;

        abort_unless((int) Auth::id() === (int) $id, 403, 'แก้ไขได้เฉพาะบัญชีของตนเอง');

        $user= User::findOrFail($id);
        if($request->password!=""){
           $user->password=bcrypt($request->password);
          $user->save();
          Session::flash('massage','เปลี่ยนพาสเวิร์ดเรียบร้อย!!');
        }else{
           Session::flash('massage','ไม่มีข้อมูลของพาสเวิร์ด!!');
        }
       
        
        return redirect()->back();
        //return redirect('myuser');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        adminchk();
        $user=User::find($id);
        $user->delete();
        Session::flash('massage','User has been deleted!!');
        return redirect('myuser');
    }


    public function areauser()
    {
      areachk();
      $branch = branch::all();
      $schoolgroup = Schoolgroup::all();
      $user = user::where('schoolgroup',Auth::user()->schoolgroup)->paginate(25);
      $school = school::inNetwork(env('APP_SCHOOLGROUP','0'))->get();
      $data  = array(
        'data' => $user,
        'branch'=> $branch,
        'schoolgroup'=>$schoolgroup,
        'school'=>$school,
      );
      return view('auth.adduserarea',$data);
    }
    public function areauseredit($id)
    {
        $schoolgroupId = (int) request()->attributes->get('schoolgroup_id');
        $user = User::where('schoolgroup', $schoolgroupId)->findOrFail($id);
        $branch = branch::all();
        $schoolgroup = Schoolgroup::all();
        $school = school::inNetwork(Auth::user()->schoolgroup)->get();
        $data = array(
          'data'=>$user,
          'id'=>$id,
          'branch'=>$branch,
          'schoolgroup'=>$schoolgroup,
          'school'=>$school,
        );
        return view('auth.edituserarea',$data);
    }
        public function centeruseredit($id)
    {
        abort_unless((int) Auth::id() === (int) $id, 403, 'แก้ไขได้เฉพาะบัญชีของตนเอง');
        $user = User::findOrFail($id);
        $branch = branch::all();
        $schoolgroup = Schoolgroup::all();
        $school = school::inNetwork(Auth::user()->schoolgroup)->get();
        $data = array(
          'data'=>$user,
          'id'=>$id,
          'branch'=>$branch,
          'schoolgroup'=>$schoolgroup,
          'school'=>$school,
        );
        return view('auth.centeredituser',$data);
    }
     public function myuserareaupdate(Request $request)
    {
        $schoolgroupId = (int) $request->attributes->get('schoolgroup_id');
        $request->validate([
            'id' => ['required', 'integer'],
            'name' => ['required'],
            'lastname' => ['required'],
            'email' => ['required', 'email'],
            'phonenumber' => ['required'],
            'position' => ['required', 'integer', 'in:0,5'],
        ]);

        $user = User::where('schoolgroup', $schoolgroupId)->findOrFail($request->id);
        $user->name=$request->name;
        $user->lastname=$request->lastname;
        $user->position=$request->position;
        $user->email=$request->email;
        $user->phonenumber = $request->phonenumber;
        $user->schoolgroup = $schoolgroupId;
        if($request->password != null)
        {
          $user->password=bcrypt($request->password);
        }
       // $user->password=bcrypt($request->password);

        $user->save();
        Session::flash('massage','User was update successfull!!');
        return redirect('area/areauser');
    }
}
