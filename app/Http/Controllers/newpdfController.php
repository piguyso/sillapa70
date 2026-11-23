<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
class newpdfController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }
        public function printtop($id){

        $addschool = addschool::where('school_activity','=',$id)->orderBy('student_score','desc')->paginate(1000);
          

        $activity= activities::find($id);
        $student = addschool::WITH("getstudentname")->where('school_activity','=',$id)->orderBy('id','desc')->get();
        $data = array(
            'addschool'=>$addschool,
            'student'=>$student,
          //  'schoolgroup'=>$schoolgroup,
            'activity'=>$activity,
            'type'=>0,
        );
     

        $pdf = PDF2::loadHTML(network_label_text(view('newpdf.printtop', $data)->render()));
  
       // return $pdf->download('itsolutionstuff.pdf');
       //return view('newpdf.studentregisterform',$data);
       return $pdf->stream();
    }
    //
    public function exportstudent($id){

        $addschool = addschool::where('school_activity','=',$id)->orderBy('student_score','desc')->paginate(1000);
          

        $activity= activities::find($id);
        $student = addschool::WITH("getstudentname")->where('school_activity','=',$id)->orderBy('id','desc')->get();
        $data = array(
            'addschool'=>$addschool,
            'student'=>$student,
          //  'schoolgroup'=>$schoolgroup,
            'activity'=>$activity,
            'type'=>0,
        );
     

        $pdf = PDF2::loadHTML(network_label_text(view('newpdf.studentregisterform', $data)->render()));
  
       // return $pdf->download('itsolutionstuff.pdf');
       //return view('newpdf.studentregisterform',$data);
       return $pdf->stream();
    }

    public function exportteacher($id){

        $addschool = addschool::where('school_activity','=',$id)->orderBy('student_score','desc')->paginate(1000);
          

        $activity= activities::find($id);
        $student = addschool::WITH("getstudentname")->where('school_activity','=',$id)->orderBy('id','desc')->get();
        $data = array(
            'addschool'=>$addschool,
            'student'=>$student,
          //  'schoolgroup'=>$schoolgroup,
            'activity'=>$activity,
            'type'=>1,
        );
     

        $pdf = PDF2::loadHTML(network_label_text(view('newpdf.studentregisterform', $data)->render()));
  
       // return $pdf->download('itsolutionstuff.pdf');
       //return view('newpdf.studentregisterform',$data);
       return $pdf->stream();
    }
    public function exportjow($id){

      $addschool = addschool::where('school_activity','=',$id)->orderBy('student_score','desc')->paginate(1000);
      $jow = jows::where('jow_activity','=',$id)->paginate(1000);

      $activity= activities::find($id);
      $student = addschool::WITH("getstudentname")->where('school_activity','=',$id)->orderBy('id','desc')->get();
      $data = array(
          'addschool'=>$addschool,
          'student'=>$student,
        //  'schoolgroup'=>$schoolgroup,
          'activity'=>$activity,
          'type'=>1,
          'jows'=>$jow,
      );
   
      
      $pdf = PDF2::loadHTML(network_label_text(view('newpdf.jowregisterform', $data)->render()));

     // return $pdf->download('itsolutionstuff.pdf');
     //return view('newpdf.studentregisterform',$data);
     return $pdf->stream();
  }

  public function exportjow2($id){

   // $addschool = addschool::where('school_activity','=',$id)->orderBy('student_score','desc')->paginate(1000);
    $jow = jows::where('jow_branch','=',$id)->where('jow_type','=',1)->paginate(1000);
    $branch = Branch::find($id);
   // $activity= activities::find($id);
   // $student = addschool::WITH("getstudentname")->where('school_activity','=',$id)->orderBy('id','desc')->get();
    $data = array(
      //  'addschool'=>$addschool,
      //  'student'=>$student,
      //  'schoolgroup'=>$schoolgroup,
      //  'activity'=>$activity,
        'type'=>1,
        'jows'=>$jow,
        'branch'=>$branch,
    );
 
    
    $pdf = PDF2::loadHTML(network_label_text(view('newpdf.jow2registerform', $data)->render()));

   // return $pdf->download('itsolutionstuff.pdf');
   //return view('newpdf.studentregisterform',$data);
   return $pdf->stream();
}
///////////////////////////
  public function areaexportjow2($id){

   // $addschool = addschool::where('school_activity','=',$id)->orderBy('student_score','desc')->paginate(1000);
    $jow = jows_areas::where('jow_area','=',$id)->where('jow_type','=',1)->paginate(1000);
    $branch = Branch::find($id);
    $schoolgroup = Schoolgroup::find($id);
   // $activity= activities::find($id);
   // $student = addschool::WITH("getstudentname")->where('school_activity','=',$id)->orderBy('id','desc')->get();
    $data = array(
      //  'addschool'=>$addschool,
      //  'student'=>$student,
      //  'schoolgroup'=>$schoolgroup,
      //  'activity'=>$activity,
        'type'=>1,
        'jows'=>$jow,
        'branch'=>$branch,
        'school_group' =>$schoolgroup,
    );
 
    
    $pdf = PDF2::loadHTML(network_label_text(view('newpdf.areajow2registerform', $data)->render()));

   // return $pdf->download('itsolutionstuff.pdf');
   //return view('newpdf.studentregisterform',$data);
   return $pdf->stream();
}


public function showprintrandomz($id){

  $addschool = addschool::where('school_activity','=',$id)->orderBy('student_score','desc')->paginate(1000);
    

  $activity= activities::find($id);
  $student = addschool::WITH("getstudentname")->where('school_activity','=',$id)->orderBy('id','desc')->get();
  $data = array(
      'addschool'=>$addschool,
      'student'=>$student,
    //  'schoolgroup'=>$schoolgroup,
      'activity'=>$activity,
      'type'=>0,
  );


  $pdf = PDF2::loadHTML(network_label_text(view('newpdf.showprintrandomz', $data)->render()));

 // return $pdf->download('itsolutionstuff.pdf');
 //return view('newpdf.studentregisterform',$data);
 return $pdf->stream();
}

public function showprintpointdoc($id){

  $addschool = addschool::where('school_activity','=',$id)->orderBy('student_score','desc')->paginate(1000);
    
  $jow = jows::where('jow_activity','=',$id)->paginate(1000);
  $activity= activities::find($id);
  $student = addschool::WITH("getstudentname")->where('school_activity','=',$id)->orderBy('id','desc')->get();
  $data = array(
      'addschool'=>$addschool,
      'student'=>$student,
    //  'schoolgroup'=>$schoolgroup,
      'activity'=>$activity,
      'type'=>0,
      'jow' =>$jow,
  );


  $pdf = PDF2::loadHTML(network_label_text(view('newpdf.showprintpointdoc', $data)->render()));

 // return $pdf->download('itsolutionstuff.pdf');
 //return view('newpdf.studentregisterform',$data);
 return $pdf->stream();
}

public function printspeedmath($id){

  $addschool = addschool::where('school_activity','=',$id)->orderBy('student_score','desc')->paginate(1000);
    
  $jow = jows::where('jow_activity','=',$id)->paginate(1000);
  $activity= activities::find($id);
  $student = addschool::WITH("getstudentname")->where('school_activity','=',$id)->orderBy('id','desc')->get();
  $data = array(
      'addschool'=>$addschool,
      'student'=>$student,
    //  'schoolgroup'=>$schoolgroup,
      'activity'=>$activity,
      'type'=>0,
      'jow' =>$jow,
  );


  $pdf = PDF2::loadHTML(network_label_text(view('newpdf.printspeedmath', $data)->render()));

 // return $pdf->download('itsolutionstuff.pdf');
 //return view('newpdf.studentregisterform',$data);
 return $pdf->stream();
}


}
