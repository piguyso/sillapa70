<?php

namespace App\Http\Controllers;

use App\School;
use App\Schoolgroup;
use Illuminate\Http\Request;
use Session;
class SchoolController extends Controller
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
        $school = school::where('id','like','%')->paginate(25);
        $schoolgroup = schoolgroup::all();
        $data = array(
            'data'=>$school,
            'schoolgroup'=>$schoolgroup,
        );
        return view('school.add',$data);
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
          'school_name'=>'required',
          'school_detail'=>'required',
        
      ]);
      $school = new school;
      $school->school_name = $request->school_name;
      $school->school_group = $request->school_group;
      $school->school_detail = $request->school_detail;
 
      $school->save();
      Session::flash('massage','Success Create !!');
      return redirect('/school');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\school  $school
     * @return \Illuminate\Http\Response
     */
    public function show(school $school)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\school  $school
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        adminchk();
        $school = school::find($id);
        $data = array(
          'school'=>$school,
          'id'=>$id
        );
        return view('school.editschool',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\school  $school
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $this->validate($request,[
            'school_name'=>'required',
            'school_detail'=>'required',
          
        ]);
        $school = school::find($id);
        $school->school_name = $request->school_name;
        $school->school_detail = $request->school_detail;
        $school->save();
        Session::flash('massage','Data update Successfull..');
        return redirect('school');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\school  $school
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $school = school::find($id);
        $school->delete();
        Session::flash('massage','Data has been deleted..');
        return redirect('school');

    }
}
