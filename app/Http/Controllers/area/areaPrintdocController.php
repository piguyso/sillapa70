<?php

namespace App\Http\Controllers\area;
use PDF2; // load DomPdf
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
use App\Jows_areas;
use App\Schoolgroup;
use App\addschools_areas;
use App\addstudents_areas;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class areaPrintdocController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     /** หน้ารวมเอกสารพิมพ์ของเครือข่าย: ใบบันทึกคะแนน/ใบลงทะเบียนนักเรียน ครู กรรมการ ของแต่ละรายการที่มีทีมลงทะเบียน */
     public function printdocs(){
         $user = Auth::user();
         abort_unless($user && in_array((int) $user->position, [5, 10], true) && (int) $user->schoolgroup > 0, 403);

         $rows = addschools_areas::query()
             ->where('school_schoolgroup', $user->schoolgroup)
             ->where('year', active_year())
             ->select('school_activity', DB::raw('COUNT(*) as teams'))
             ->groupBy('school_activity')
             ->get();
         $activities = Activities::with(['getactivitysubject', 'getclass'])
             ->whereIn('id', $rows->pluck('school_activity'))
             ->orderBy('activity_subject')->orderBy('id')->get();
         $teams = $rows->pluck('teams', 'school_activity');

         return view('area.printdocs', [
             'activities' => $activities,
             'teams' => $teams,
             'network' => Schoolgroup::find($user->schoolgroup),
         ]);
     }

     public function printpointdoc($id){
                $addschool = addschools_areas::where('school_activity','=',$id)->where('school_schoolgroup',Auth::user()->schoolgroup)->orderBy('student_score','desc')->paginate(1000);
    
                $jow = Jows_areas::where('jow_activity','=',$id)->where('jow_area',Auth::user()->schoolgroup)->paginate(1000);
                $activity= activities::find($id);
                $student = addschools_areas::WITH("getstudentname")->where('school_schoolgroup',Auth::user()->schoolgroup)->where('school_activity','=',$id)->orderBy('id','desc')->get();
                $data = array(
                    'addschool'=>$addschool,
                    'student'=>$student,
                    //  'schoolgroup'=>$schoolgroup,
                    'activity'=>$activity,
                    'type'=>0,
                    'jow' =>$jow,
                );


                $pdf = PDF2::loadHTML(network_label_text(view('newpdf.areashowprintpointdoc', $data)->render()));

                // return $pdf->download('itsolutionstuff.pdf');
                //return view('newpdf.studentregisterform',$data);
                return $pdf->stream();
     }


    public function studentregisterform($id){

        $addschool = addschools_areas::where('school_activity','=',$id)->where('school_schoolgroup',Auth::user()->schoolgroup)->orderBy('student_score','desc')->paginate(1000);
          

        $activity= activities::find($id);
        $student = addschools_areas::WITH("getstudentname")->where('school_schoolgroup',Auth::user()->schoolgroup)->where('school_activity','=',$id)->orderBy('id','desc')->get();
        $data = array(
            'addschool'=>$addschool,
            'student'=>$student,
          //  'schoolgroup'=>$schoolgroup,
            'activity'=>$activity,
            'type'=>0,
        );
     

        $pdf = PDF2::loadHTML(network_label_text(view('newpdf.areastudentregisterform', $data)->render()));
  
       // return $pdf->download('itsolutionstuff.pdf');
       //return view('newpdf.studentregisterform',$data);
       return $pdf->stream();
    }


    public function teacherregisterform($id){

        $addschool = addschools_areas::where('school_activity','=',$id)->where('school_schoolgroup',Auth::user()->schoolgroup)->orderBy('student_score','desc')->paginate(1000);
          

        $activity= activities::find($id);
        $student = addschools_areas::WITH("getstudentname")->where('school_schoolgroup',Auth::user()->schoolgroup)->where('school_activity','=',$id)->orderBy('id','desc')->get();
        $data = array(
            'addschool'=>$addschool,
            'student'=>$student,
          //  'schoolgroup'=>$schoolgroup,
            'activity'=>$activity,
            'type'=>1,
        );
     

        $pdf = PDF2::loadHTML(network_label_text(view('newpdf.areastudentregisterform', $data)->render()));
  
       // return $pdf->download('itsolutionstuff.pdf');
       //return view('newpdf.studentregisterform',$data);
       return $pdf->stream();
    }


    public function jowregisterform($id){

      $addschool = addschools_areas::where('school_activity','=',$id)->where('school_schoolgroup',Auth::user()->schoolgroup)->orderBy('student_score','desc')->paginate(1000);
      $jow = Jows_areas::where('jow_activity','=',$id)->where('jow_area',Auth::user()->schoolgroup)->paginate(1000);

      $activity= activities::find($id);
      $student = addschools_areas::WITH("getstudentname")->where('school_schoolgroup',Auth::user()->schoolgroup)->where('school_activity','=',$id)->orderBy('id','desc')->get();
      $data = array(
          'addschool'=>$addschool,
          'student'=>$student,
        //  'schoolgroup'=>$schoolgroup,
          'activity'=>$activity,
          'type'=>1,
          'jows'=>$jow,
      );
   
      
      $pdf = PDF2::loadHTML(network_label_text(view('newpdf.areajowregisterform', $data)->render()));

     // return $pdf->download('itsolutionstuff.pdf');
     //return view('newpdf.studentregisterform',$data);
     return $pdf->stream();
  }













    public function index()
    {
        //
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
        //
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
