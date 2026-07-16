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
      $school = school::where('school_group','=', env('APP_SCHOOLGROUP','0'))->get();
      $data  = array(
        'data' => $user,
        'branch'=> $branch,
        'schoolgroup'=>$schoolgroup,
        'school'=>$school,
      );
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
      $user = user::where('id','like','%')->paginate(25);
      $data  = array(
        'data' => $user,
      );
      return view('auth.adduser2',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
     // dd($request);
      adminchk();
      $this->validate($request,[
          'name'=>'required',
          'lastname'=>'required',
          'phonenumber'=>'required|numeric',
          'email'=>'required',
          'password'=>'required',
          'position' => 'required',
      ]);
      $user = new User;
      $user->name = $request->name;
      $user->lastname = $request->lastname;
      $user->phonenumber = $request->phonenumber;
      $user->position = $request ->position;
      $user->email = $request->email;
      $user->branch = $request->branch;
      $user->schoolgroup = $request->schoolgroup;
      $user->password = bcrypt($request->password);
      $user->save();
      Session::flash('massage','สร้างผู้ใช้ใหม่สำเร็จ..');
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
        $school = school::where('school_group','=', env('APP_SCHOOLGROUP','0'))->get();
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
          'email'=>'required',
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

        $user= User::find($id);
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
      $school = school::where('school_group','=', env('APP_SCHOOLGROUP','0'))->get();
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
        $user = User::find($id);
        $branch = branch::all();
        $schoolgroup = Schoolgroup::all();
        $school = school::where('school_group','=', Auth::user()->schoolgroup)->get();
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
        if(auth::user()->id != $id){
          return("error!!.not permit");
        }
        $user = User::find($id);
        $branch = branch::all();
        $schoolgroup = Schoolgroup::all();
        $school = school::where('school_group','=', Auth::user()->schoolgroup)->get();
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
     // dd($request);
        $user= User::find($request->id);
        $user->name=$request->name;
        $user->lastname=$request->lastname;
        $user->position=$request->position;
        $user->email=$request->email;
        $user->phonenumber = $request->phonenumber;
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
