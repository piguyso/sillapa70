<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Activities;
use App\Subjectgroup;
use App\Branch;
use App\Classes;
use App\Addschool;
use App\dayofsillapas;
use Session;
use DB;
class ActivityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('areamiddleware');
    }
    public function index()
    {
        //dd($id);
        $activity = activities::where('id','like','%')->paginate(4000);
         $activity = activities::all();
        $data = array(
            'activity'=>$activity,
        );
        return view('activity.add',$data);
    }
    public function addactivity($id)
    {
        //dd($id);

        $activeYear = active_year();
        $schoolcountactivity = DB::table('addschools')
        ->select('school_activity', DB::raw('count(*) as total'))
        ->where('year', $activeYear)
        ->groupBy('school_activity')
        ->pluck('total','school_activity');

       // dd($schoolcountactivity);

        $activity = activities::where('activity_subject','=',$id)->paginate(40);
        $subjectgroup = Subjectgroup::find($id);
        $branch = Branch::all();
        $class = Classes::all();
        $dayofsillapa = Dayofsillapas::all();
        $data = array(
            'id'=>$id,
            'activity'=>$activity,
            'subjectgroup'=>$subjectgroup,
            'branch'=>$branch,
            'class'=>$class,
            'dayofsillapa'=>$dayofsillapa,
            'schoolcountactivity'=>$schoolcountactivity,
        );
       // dd($subjectgroup->id);
        return view('activity.addactivity',$data);
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
      //  dd($request->id);
        adminchk();
      $this->validate($request,[
          'activity_name'=>'required',
          'activity_branchroom'=>'required',
          'activity_day'=>'required',
          'activity_time'=>'required', 
      ]);

      $activity = new Activities;
      $activity->activity_name = $request->activity_name;
      $activity->activity_subject = $request->activity_subject;
      $activity->activity_class = $request->activity_class;
      $activity->activity_studennumber = $request->activity_studennumber;
      $activity->activity_teachernumber = $request->activity_teachernumber;
      $activity->activity_teamnumber = $request->activity_teamnumber;
      $activity->activity_schoolteamnumber = $request->activity_schoolteamnumber;
      $activity->activity_score = $request->activity_score;
      $activity->activity_branch = $request->activity_branch;
      $activity->activity_branchroom = $request->activity_branchroom;
      $activity->activity_day = $request->activity_day;
      $activity->activity_time = $request->activity_time;
      $activity->activity_timestart = $request->activity_timestart;
    
 
      $activity->save();
      Session::flash('massage','Success Create !!');
      return redirect('/addactivity/'.$request->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\activity  $activity
     * @return \Illuminate\Http\Response
     */
    public function show(activity $activity)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\activity  $activity
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        adminchk();
        $activity = activities::find($id);
      //  $subjectgroup = Subjectgroup::find($id);
        $branch = Branch::all();
        $class = Classes::all();
        $dayofsillapa = Dayofsillapas::all();
        $data = array(
          'activity'=>$activity,
      //   'subjectgroup'=>$subjectgroup,
          'branch'=>$branch,
          'class'=>$class,
          'dayofsillapa'=>$dayofsillapa,
          'id'=>$id
        );
       // dd($data);
        return view('activity.edit',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\activity  $activity
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
      // dd($request);
        $this->validate($request,[
          //  'activity_name'=>'required',
         //   'activity_branchroom'=>'required',
         //   'activity_day'=>'required',
          //  'activity_time'=>'required',
          
        ]);
        $activity = activities::find($id);
        $activity->activity_name = $request->activity_name;
        $activity->activity_class = $request->activity_class;
        $activity->activity_studennumber = $request->activity_studennumber;
        $activity->activity_teachernumber = $request->activity_teachernumber;
        $activity->activity_teamnumber = $request->activity_teamnumber;
        $activity->activity_schoolteamnumber = $request->activity_schoolteamnumber;
        $activity->activity_branch = $request->activity_branch;
        $activity->activity_branchroom = $request->activity_branchroom;
        $activity->activity_day = $request->activity_day;
        $activity->activity_time = $request->activity_time;
        $activity->activity_status = $request->activity_status;
        
        if($request->activity_status==0){
            $addschool = addschool::where('school_activity','=',$id)->get();
            foreach($addschool as $nmkey)
            {
            //  echo $nmkey."->".$addschool->id[$nmkey]."<br>";
                echo "->".$nmkey->id."<br>";
                addschool::where('id', '=',$nmkey->id)->update(['activity_status' => '0']);
                addschool::where('id', '=',$nmkey->id)->update(['activity_rank' => '0']);
                addschool::where('id', '=',$nmkey->id)->update(['student_score' => '0']);
            }
        }
 
        $activity->save();
        Session::flash('massage','Data 2 update Successfull..');
        return redirect('addactivity/'.$request->subjectgroupid);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\activity  $activity
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $activity = activities::find($id);
        $activity->delete();

        addschool::where('school_activity',$id)->delete();

        Session::flash('massage','Data has been deleted..');
        return redirect(url()->previous());

    }
}
