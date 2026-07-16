<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Subjectgroup;
use App\Activities;
use App\Branch;
use Session;
use DB;
use Auth;
use App\systemactives;
class SubjectgroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('areamiddleware');
    }
    public function index()
    {
        //$centerchk = systemactives::where('systemname','=','center')->get();
         $scorechk = systemactives::where('systemname','=','area')->get();
        //dd($centerchk->status);
         if($scorechk[0]->status == 1 and Auth::user()->position != '10' ){
            // Auth::logout();
             return view('home.out');
         }




        $subjectgroup = subjectgroup::where('id','like','%')->orderby('in_dex','asc')->paginate(20);
      //  $activitycount = Activities::groupBy('activity_subject')->get();
        $activitycount = DB::table('Activities')
    ->select('activity_subject', DB::raw('count(*) as total'))
    ->groupBy('activity_subject')
    ->pluck('total','activity_subject');
  

        $data  = array(
           'subjectgroup'=>$subjectgroup,
           'activitycount'=>$activitycount,
        );

       // dd($data);
        return view('subjectgroup.add',$data);
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
            'subjectgroup_name'=>'required',
            'subjectgroup_detail'=>'required',

        ]);
        $subjectgroup  = new subjectgroup;
        $subjectgroup->subjectgroup_name = $request->subjectgroup_name;
        $subjectgroup->subjectgroup_detail = $request->subjectgroup_detail;
        $subjectgroup->save();
        Session::flash('massage','Success Create User !!');
        return redirect('/subjectgroup');
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
        $branch = Branch::all();
        $activity_subject = Activities::where('activity_subject','=',$id)->get();
        $subjectgroup = subjectgroup::find($id);
        $data = array(
            'subjectgroup'=>$subjectgroup,
            'branch'=>$branch,
            'activity_subject'=>$activity_subject,
        );
       // dd($data);
        return view('subjectgroup.edit',$data);
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
        //dd($request);
        $this->validate($request,[
            'subjectgroup_name'=>'required',
            'subjectgroup_detail'=>'required',
          
        ]);
        $subjectgroup = subjectgroup::find($id);
        $subjectgroup->subjectgroup_name = $request->subjectgroup_name;
        $subjectgroup->subjectgroup_detail = $request->subjectgroup_detail;
        $subjectgroup->save();

        $activities = Activities::where('activity_subject','=',$id)->get();
        foreach ($activities as $activity) {
            $activity->activity_branch= $request->branch;
            $activity->save();
        }

        Session::flash('massage','Data update Successfull..');
        return redirect('subjectgroup');
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
        $subjectgroup=subjectgroup::find($id);
        $subjectgroup->delete();
   
        activities::where('activity_subject',$id)->delete();

        Session::flash('massage','subjectgroup has been deleted!!');
        return redirect('subjectgroup');
    }
}
