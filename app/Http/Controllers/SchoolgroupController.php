<?php

namespace App\Http\Controllers;

use App\Schoolgroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;
class SchoolgroupController extends Controller
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
        $schoolgroup = Schoolgroup::withCount('schools')->orderBy('id')->paginate(100);
        $data = array(
            'data'=>$schoolgroup,
        );
        return view('schoolgroup.add',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
      $this->validate($request,[
          'schoolgroup_name'=>'required',
          'schoolgroup_detail'=>'required',
          'group_type'=>'required|in:district,cluster',
        
      ]);
      $schoolgroup = new Schoolgroup;
      $schoolgroup->schoolgroup_name = $request->schoolgroup_name;
      $schoolgroup->schoolgroup_detail = $request->schoolgroup_detail;
      $schoolgroup->group_type = $request->group_type;
 
      $schoolgroup->save();
      Session::flash('massage','Success Create !!');
      return redirect('/schoolgroup');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Schoolgroup  $schoolgroup
     * @return \Illuminate\Http\Response
     */
    public function show(Schoolgroup $schoolgroup)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Schoolgroup  $schoolgroup
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        adminchk();
        $schoolgroup = schoolgroup::find($id);
        $data = array(
          'schoolgroup'=>$schoolgroup,
          'id'=>$id
        );
        return view('schoolgroup.editschoolgroup',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Schoolgroup  $schoolgroup
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $this->validate($request,[
            'schoolgroup_name'=>'required',
            'schoolgroup_detail'=>'required',
            'group_type'=>'required|in:district,cluster',
          
        ]);
        $schoolgroup = Schoolgroup::findOrFail($id);
        $schoolgroup->schoolgroup_name = $request->schoolgroup_name;
        $schoolgroup->schoolgroup_detail = $request->schoolgroup_detail;
      $schoolgroup->group_type = $request->group_type;
        $schoolgroup->save();
        Session::flash('massage','Data update Successfull..');
        return redirect('schoolgroup');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Schoolgroup  $schoolgroup
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $schoolgroup = Schoolgroup::findOrFail($id);
        $inUse = DB::table('school_schoolgroup')->where('schoolgroup_id', $id)->exists()
            || DB::table('schools')->where('school_group', $id)->exists()
            || DB::table('users')->where('schoolgroup', $id)->exists()
            || DB::table('addschools')->where('school_schoolgroup', $id)->exists()
            || DB::table('addschools_areas')->where('school_schoolgroup', $id)->exists();
        if ($inUse) {
            Session::flash('error','ลบเครือข่ายไม่ได้ เพราะยังมีโรงเรียน ผู้ใช้ หรือการลงทะเบียนอยู่ในเครือข่ายนี้');
            return redirect('schoolgroup');
        }
        $schoolgroup->delete();
        Session::flash('massage','Data has been deleted..');
        return redirect('schoolgroup');

    }
}
