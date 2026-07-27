<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Auth;
use DB;
use App\Activities;
use App\Subjectgroup;
use App\Branch;
use App\Classes;
use App\Addschool;
use App\dayofsillapas;
use App\systemactives;
use App\addstudent;
use App\titles;
use App\Schoolgroup;
use App\jows;
use App\Jows_areas;
class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth')->except([
            'index', 'show', 'jowviewactivity', 'reportviewjow', 
            'areareport', 'areareportviewjow', 'areajowviewactivity'
        ]);
    }
    public function areareport($id){
        
        $activityinbranch = activities::all()->groupBy('activity_subject');
        $subjectgroup = subjectgroup::where('id','like','%')->orderby('in_dex','asc')->paginate(1000);
        $branch = branch::all();
        $area = Schoolgroup::where('id',$id)->get();
       
        $activeYear = active_year();
        $activitycount = DB::table('Activities')
        ->select('activity_subject', DB::raw('count(*) as total'))
        ->where('year', $activeYear)
        ->groupBy('activity_subject')
        ->pluck('total','activity_subject');

        $data= array(
            'activityinbranch'=> $activityinbranch,
            'activitycount'=>$activitycount,
            'subjectgroup'=>$subjectgroup,
            'branch'=>$branch,
            'id'=>$id,
            'area' => $area,

        );
        
        return view('report.areareport',$data);

    }

        public function areareportviewjow($id){
        $jow = jows_areas::where('jow_area','=',$id)
        ->where('jow_type',1)
        ->paginate(1000);
       // $jow = jows::where('jow_branch','like',"%")->paginate(1000);
        $data = array(
    
            'jow'=>$jow,
            'branch'=>$id,
            'area'=>$id,
        );
      // dd($data);
        return view('report.areareportjow',$data);
    }

    public function areajowviewactivity($id){
        //dd($id);

        $activeYear = active_year();
        $schoolcountactivity = DB::table('addschools')
        ->select('school_activity', DB::raw('count(*) as total'))
        ->where('year', $activeYear)
        ->groupBy('school_activity')
        ->pluck('total','school_activity');

       // dd($schoolcountactivity);

        $activity = activities::where('activity_subject','=',$id)->paginate(1000);
        $subjectgroup = Subjectgroup::find($id);
        $branch = Branch::all();
        $class = Classes::all();
        $dayofsillapa = Dayofsillapas::all();
        $jow = Jows_areas::where('jow_activity','like','%')
        ->where('jow_type',0)
        ->where('jow_area',session('area'))
        ->get();
        $data = array(
            'id'=>$id,
            'activity'=>$activity,
            'subjectgroup'=>$subjectgroup,
            'branch'=>$branch,
            'class'=>$class,
            'dayofsillapa'=>$dayofsillapa,
            'schoolcountactivity'=>$schoolcountactivity,
            'jow'=>$jow,
        );
      //  dd($data);
        return view('report.areareportviewactivity',$data);
}




    public function index()
    {
       
            $activityinbranch = activities::all()->groupBy('activity_subject');
            $subjectgroup = subjectgroup::where('id','like','%')->orderby('in_dex','asc')->paginate(1000);
        $branch = branch::all();
       
        $activeYear = active_year();
        $activitycount = DB::table('Activities')
        ->select('activity_subject', DB::raw('count(*) as total'))
        ->where('year', $activeYear)
        ->groupBy('activity_subject')
        ->pluck('total','activity_subject');

        $data= array(
            'activityinbranch'=> $activityinbranch,
            'activitycount'=>$activitycount,
            'subjectgroup'=>$subjectgroup,
            'branch'=>$branch,
        );
        
        return view('report.index',$data);
    }
    public function reportviewjow($id){
        $jow = jows::where('jow_branch','=',$id)->paginate(1000);
       // $jow = jows::where('jow_branch','like',"%")->paginate(1000);
        $data = array(
    
            'jow'=>$jow,
            'branch'=>$id,
        );
      // dd($data);
        return view('report.reportjow',$data);
    }

    public function jowviewactivity($id){
        //dd($id);

        $activeYear = active_year();
        $schoolcountactivity = DB::table('addschools')
        ->select('school_activity', DB::raw('count(*) as total'))
        ->where('year', $activeYear)
        ->groupBy('school_activity')
        ->pluck('total','school_activity');

       // dd($schoolcountactivity);

        $activity = activities::where('activity_subject','=',$id)->paginate(1000);
        $subjectgroup = Subjectgroup::find($id);
        $branch = Branch::all();
        $class = Classes::all();
        $dayofsillapa = Dayofsillapas::all();
        $jow = jows::where('jow_activity','like','%')->get();
        $data = array(
            'id'=>$id,
            'activity'=>$activity,
            'subjectgroup'=>$subjectgroup,
            'branch'=>$branch,
            'class'=>$class,
            'dayofsillapa'=>$dayofsillapa,
            'schoolcountactivity'=>$schoolcountactivity,
            'jow'=>$jow,
        );
      //  dd($data);
        return view('report.reportviewactivity',$data);
}
    public function addjow($id){
        $activity = activities::find($id);
        $jow = jows::where('jow_activity','=',$id)->get();
        $title = titles::all();
        $data = array(
            'activity'=>$activity,
            'jow'=>$jow,
            'title'=>$title,
            'id'=>$id,
        );
      //  dd($data);
        return view('addjow.add',$data);
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
        $this->validate($request,[
            'title'=>'required',
            'name'=>'required',
            'lastname'=>'required',

        ]);
        $jow  = new jows;
        $jow->jow_title = $request->title;
        $jow->jow_name = $request->name;
        $jow->jow_lastname = $request->lastname;
        $jow->jow_activity = $request->activity;
        $jow->save();
        Session::flash('massage','Success Create User !!');
        return redirect(url()->previous());
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
        $jows = jows::find($id);
        $title = titles::all();
        $data = array(
            'jows'=>$jows,
            'title'=>$title,
            'editid'=>$id,
        );
       // dd($data);
        return view('addjow.edit',$data);
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
       // dd($request);
        $jows= jows::find($id);
        $jows->jow_title = $request->jow_title;
        $jows->jow_name = $request->jow_name;
        $jows->jow_lastname = $request->jow_lastname;
        $jows->save();
        Session::flash('massage','Jow has been update..');
        return redirect('./addjow/'.$jows->jow_activity);
      // return redirect(url()->previous());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $jow = jows::find($id);
        $jow->delete();
        Session::flash('massage','jow has been deleted!!');
        return redirect(url()->previous(2));
    }
}
