<?php

namespace App\Http\Controllers;

use App\School;
use App\Schoolgroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
    private function rules(): array
    {
        return [
            'school_name'=>'required',
            'school_detail'=>'required',
            'school_group'=>'required|exists:schoolgroups,id',
            'networks'=>'nullable|array',
            'networks.*'=>'integer|exists:schoolgroups,id',
        ];
    }

    public function index()
    {
        $school = school::with(['getschoolgroup','schoolgroups'])->orderBy('school_name')->get();
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
      $this->validate($request, $this->rules());
      $school = new school;
      $school->school_name = $request->school_name;
      $school->school_group = $request->school_group;
      $school->school_detail = $request->school_detail;
      $school->is_expansion = $request->boolean('is_expansion');
      $school->save();
      $school->syncNetworks((array) $request->input('networks', []), (int) $request->school_group);
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
        $school = school::findOrFail($id);
        $schoolgroups = schoolgroup::orderBy('schoolgroup_name')->get();
        $data = array(
          'school'=>$school,
          'schoolgroups'=>$schoolgroups,
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
        adminchk();
        $this->validate($request, $this->rules());
        $school = school::findOrFail($id);
        $school->school_name = $request->school_name;
        $school->school_group = $request->school_group;
        $school->school_detail = $request->school_detail;
        $school->is_expansion = $request->boolean('is_expansion');
        $school->save();
        $school->syncNetworks((array) $request->input('networks', []), (int) $request->school_group);
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
        $school = school::findOrFail($id);
        $used = DB::table('addschools')->where('school_id', $id)->exists()
            || DB::table('addschools_areas')->where('school_id', $id)->exists();
        if ($used) {
            Session::flash('error','ลบโรงเรียนไม่ได้ เพราะมีการลงทะเบียนแข่งขันแล้ว');
            return redirect('school');
        }
        $school->schoolgroups()->detach();
        $school->delete();
        Session::flash('massage','Data has been deleted..');
        return redirect('school');

    }
}
