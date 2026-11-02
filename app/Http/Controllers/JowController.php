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
use App\jows;
class JowController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
      
        $this->middleware('auth');
        $this->middleware('centermiddleware');
        

    
   
    }
    public function index()
    {
       // dd(Auth::user()->position);
        if(Auth::user()->position=='10'){
            $activityinbranch = activities::all()->groupBy('activity_subject');
            $activeYear = active_year();
            $activitycount = DB::table('Activities')
            ->select('activity_subject', DB::raw('count(*) as total'))
            ->where('year', $activeYear)
            ->groupBy('activity_subject')
            ->pluck('total','activity_subject');
       
        }else
        {
            $activityinbranch = activities::where('activity_branch','=',Auth::user()->branch)->get()->groupBy('activity_subject');
            $activeYear = active_year();
            $activitycount = DB::table('Activities')
            ->select('activity_subject', DB::raw('count(*) as total'))
            ->where('activity_branch','=',Auth::user()->branch)
            ->where('year', $activeYear)
            ->groupBy('activity_subject')
            ->pluck('total','activity_subject');
        }
       
       


        $data= array(
            'activityinbranch'=> $activityinbranch,
            'activitycount'=>$activitycount,
        );
        
        return view('jow.add',$data);
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
       if(Auth::user()->position=='10'){
        $activity = activities::where('activity_subject','=',$id)->paginate(20);
       }else{
        $activity = activities::where('activity_subject','=',$id)->where('activity_branch','=',Auth::user()->branch)->paginate(3000);
       }




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
        return view('jow.jowviewactivity',$data);
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
    public function addjow2(){
      

        
        $title = titles::all();



        if(Auth::user()->position=='10'){
            $jowbranch = branch::all();
            $jow = jows::where('jow_type','=','1')->paginate(3000);
           }else{
            $jowbranch = branch::where('id','=',Auth::user()->branch)->get();
            $jow = jows::where('jow_type','=','1')->where('jow_branch','=',Auth::user()->branch)->paginate(3000);
           }
    
        


        $data = array(
           'jowbranch'=>$jowbranch,
            'jow'=>$jow,
            'title'=>$title,
           
        );
      //  dd($data);
        return view('addjow.add2',$data);
    }
     public function editjow2($id)
        {
            adminchk(); // ตรวจสอบสิทธิ์
            $jow = jows::find($id);
            $title = titles::all(); // สำหรับเลือกคำนำหน้า
            $jowbranch = Branch::where('id',auth::user()->branch)->get(); // สำหรับเลือกศูนย์การแข่งขัน


            $data = array(
            'jow'=>$jow,
            'title'=>$title,
            'editid'=>$id,
            'jowbranch'=> $jowbranch ,
            'id' =>$id,
            );
       // dd($data);
            return view('addjow.editjow2',$data);


           // return view('addjow.editjow2', compact('jow', 'id', 'title', 'jowbranch'));
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
        $jow->jow_branch = $request->jow_branch;
        $jow->jow_type = $request->jow_type;
        $jow->jowclass = $request->jowclass;
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
        $jows->jowclass = $request->jowclass;
        $jows->jow_title = $request->jow_title;
        $jows->jow_name = $request->jow_name;
        $jows->jow_lastname = $request->jow_lastname;
        $jows->save();
        Session::flash('massage','Jow has been update..');
      //  return redirect('./addjow/'.$jows->jow_activity);
       return redirect(url()->previous());
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
