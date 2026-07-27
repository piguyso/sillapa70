<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use carbon\carbon;
use Auth;
use PDF;
use App\addschool;
use App\addstudent;
use App\activities;
use App\jows;
use App\Systemactives;
use App\Years;
use App\Configs;
class pdfController2 extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }
    //---------------------------- ปริ้นเกียรติบัตร รายกิจกรรม
public function printPDF(request $request){

    // dd($request);
     $id = $request->id;
     $score = $request->student_score;
     $inx = $request->x;
     $addschool = addschool::where('id','=',$id)->get();

      $score = $addschool[0]->student_score;
  $inx = $addschool[0]->activity_rank;
     //$addstudent = addstudent::where('school','=',$id)->where('type','=','0')->paginate(20);
    // $addteacher = addstudent::where('school','=',$id)->where('type','=','1')->paginate(20);
     $addstudent = addstudent::where('school','=',$id)->paginate(65);
     $cerfiticatechk = Systemactives::where('systemname','=','certificate')->paginate(10);
     $year = Years::find(1)->get();
    // dd($addschool[0]->getactivityname->activity_name);
     //dd(count($addstudent));
   $pic = Configs::all();
    //dd($cerfiticatechk);
     //////////////////////////////////////////////////////////////////////////////////
     $filename="cerf.pdf";
     $pdf = new PDF();
     $pdf::AddFont('fonts/THSarabun.ttf','','fonts/thsarabunpsk.php');
     $pdf::SetFont('fonts/THSarabun.ttf','',18);
     $pdf::SetTitle('ไฟล์');
   //  $pdf::SetLineWidth(0);
       
             if($score >=80){
                $xscore = "เหรียญทอง";
             }elseif($score >= 70){
                $xscore = "เหรียญเงิน";
               }elseif($score >= 60){
                 $xscore = "เหรียญทองแดง";
              }elseif($score == 0){
                 $xscore = '';
             }else{
                $xscore ='เข้าร่วม';
             }
   
             if($inx ==1){
               $xinx = "ชนะเลิศ";
             }elseif($inx == 2){
               $xinx = "รองชนะเลิศอันดับ ๑";
             }elseif($inx == 3){
               $xinx = "รองชนะเลิศอันดับ ๒";
             }else{
               $xinx = '';
             }
   //-------- 
   $pdf::SetMargins(0,0,0,0,true);
   $pdf::SetAutoPageBreak(TRUE, 0);
   for($i=0;$i< count($addstudent);$i++) {
               $pdf::AddPage('L','A4');
             
              // ใช้พังก์ชั่น str_replace เปลี่ยน \ เป็น / เพื่อให้ Library อ่านง่ายขึ้น
$imagePath = storage_file_path('upload', $pic[0]->app_certificate, $pic[0]->app_year ?? active_year());

if (file_exists($imagePath)) {
    // ระบุนามสกุลไฟล์ให้ชัดเจนในพารามิเตอร์ที่ 7 (เช่น 'PNG' หรือ 'JPG')
    $pdf::Image($imagePath, 0, 0, 300, 210, '', '', '', false, 300, '', false, false, 0, false, false, false);
} else {
    dd("หาไฟล์ไม่พบที่: " . $imagePath);
} 
               if($addstudent[$i]->type == 1){
                 if($xscore=='เข้าร่วม'){
                 $teach ='ครูผู้สอนนักเรียน'.$xinx.''.$xscore;
                 $student =''; 
                 }else{
                 $teach ='ครูผู้สอนนักเรียนได้รับรางวัลระดับ'.$xscore.' '.$xinx;
                 $student ='';
                 }
              }else{
                 if($xscore=='เข้าร่วม'){
                 $teach ='';
                 $student ='ได้'.$xinx.''.$xscore;
                 }else{
                 $teach ='';
                 $student ='ได้รับรางวัลระดับ'.$xscore.' '.$xinx;
                 }
               }
              
              $pdf::AddFont('fonts/THSarabun.ttf','','fonts/thsarabunpsk.php');
               $pdf::AddFont('fonts/PSLMalinee.ttf','','fonts/pslmalinee.php');
              $pdf::SetFont('fonts/THSarabun.ttf','',10);   
               $pdf::Cell(0, 0,'', 0, 1, 'R', 0, '', 1);
               $pdf::SetFont('fonts/THSarabun.ttf','',20);
               
               $pdf::Cell(0, 0,'เลขที่ -๐๑-'.thainumDigit($addschool[0]->school_activity.$addschool[0]->school_name.$addschool[0]->id).'/'.thainumDigit($year[0]->years).'      ', 0, 1, 'R', 0, '', 1);
               $pdf::SetFont('fonts/PSLMalinee.ttf','',28);
               $pdf::Cell(0, 0,'', 0, 1, 'C', 0, '', 1);
               $pdf::Cell(0, 0,'', 0, 1, 'C', 0, '', 1);
               $pdf::Cell(0, 0,'', 0, 1, 'C', 0, '', 1);
               $pdf::Cell(0, 0,'', 0, 1, 'C', 0, '', 1);
             //  $pdf::SetFont('fonts/THSarabun.ttf','',20);
               $pdf::Cell(0, 0,'', 0, 1, 'C', 0, '', 1);
             //  $pdf::SetFont('fonts/THSarabun.ttf','B',28);
               $pdf::SetTextColor(0,0,255);
             //  $pdf::Cell(0, 0,studenttitle($addstudent[$i]->title).' '.$addstudent[$i]->name.' '.$addstudent[$i]->lastname, 0, 1, 'C', 0, '', 1);
               $pdf::Cell(0, 0,$addstudent[$i]->getstudenttitle->title.''.$addstudent[$i]->name.' '.$addstudent[$i]->lastname, 0, 1, 'C', 0, '', 1);
               $pdf::SetTextColor(0,0,0);
               $pdf::SetFont('fonts/THSarabun.ttf','',24);
               $pdf::Cell(0, 0,$addschool[0]->getschoolname->school_name, 0, 1, 'C', 0, '', 1);
               $pdf::Cell(0, 0,''.$teach.' '.$student, 0, 1, 'C', 0, '', 1);
               $pdf::SetTextColor(0,0,0);
   
               $activity_name = str_replace('[เดี่ยว]','',$addschool[0]->getactivityname->activity_name);
               $activity_name = str_replace('[ทีม 2 คน]','',$activity_name);
               $activity_name = str_replace('[ทีม 3 คน]','',$activity_name);
               $activity_name = str_replace('[ทีม 4 คน]','',$activity_name);
               $activity_name = str_replace('[ทีม 5 คน]','',$activity_name);
               $activity_name = str_replace('[ทีม 6 คน]','',$activity_name);
               $activity_name = str_replace('[ทีม 9 คน]','',$activity_name);
               $activity_name = str_replace('[ทีม 10 คน]','',$activity_name);
               $activity_name = str_replace('[ทีม 12 คน]','',$activity_name);
               $activity_name = str_replace('[ทีม 15 คน]','',$activity_name);
               $activity_name = str_replace('[ทีม 16 คน]','',$activity_name);
               $activity_name = str_replace('[ทีม 20 คน]','',$activity_name);
               $activity_name = str_replace('[ทีม 40 คน]','',$activity_name);
               
               $activity_name = thainumDigit($activity_name);
             //  $activity_name = str_replace('2','๒',$activity_name);
             //  $activity_name = str_replace('3','๓',$activity_name);
             //  $activity_name = str_replace('4','๔',$activity_name);
             //  $activity_name = str_replace('5','๕',$activity_name);
             //  $activity_name = str_replace('6','๖',$activity_name);
            //  $activity_name = str_replace('7','๗',$activity_name);
             //  $activity_name = str_replace('8','๘',$activity_name);
             //  $activity_name = str_replace('9','๙',$activity_name);
            //   $activity_name = str_replace('0','๐',$activity_name);
   
               $pdf::Cell(0, 0,$activity_name." ".$addschool[0]->getactivityname->getclass->class_name, 0, 1, 'C', 0, '', 1);
               $pdf::SetTextColor(0,0,0);
               $pdf::endPage();
   
   }
   
   
     $pdf::Output($filename);
   
   }
   


////////////
//---------------------------- ปริ้นเกียรติบัตร รายกิจกรรม
public function printstudent($id){


  
   $score = 80; //คะแนน
   $inx = 2; //
 
   //$addstudent = addstudent::where('school','=',$id)->where('type','=','0')->paginate(20);
  // $addteacher = addstudent::where('school','=',$id)->where('type','=','1')->paginate(20);
   $addstudent = addstudent::where('activity','=',$id)->paginate(65);
   $addschool = addschool::where('id','=',$addstudent[0]->school)->get();
   $cerfiticatechk = Systemactives::where('systemname','=','certificate')->paginate(10);
   $year = Years::find(1)->get();
  // dd($addschool[0]->getactivityname->activity_name);
   //dd(count($addstudent));
   $pic = Configs::all();
  //dd($cerfiticatechk);
   //////////////////////////////////////////////////////////////////////////////////
   $filename="cerf.pdf";
   $pdf = new PDF();
   $pdf::AddFont('fonts/THSarabun.ttf','','fonts/thsarabunpsk.php');
   $pdf::SetFont('fonts/THSarabun.ttf','',18);
   $pdf::SetTitle('ไฟล์');
 //  $pdf::SetLineWidth(0);
     
           if($score >=80){
              $xscore = "เหรียญทอง";
           }elseif($score >= 70){
              $xscore = "เหรียญเงิน";
             }elseif($score >= 60){
               $xscore = "เหรียญทองแดง";
            }elseif($score == 0){
               $xscore = '';
           }else{
              $xscore ='เข้าร่วม';
           }
 
           if($inx ==1){
             $xinx = "ชนะเลิศ";
           }elseif($inx == 2){
             $xinx = "รองชนะเลิศอันดับ ๑";
           }elseif($inx == 3){
             $xinx = "รองชนะเลิศอันดับ ๒";
           }else{
             $xinx = '';
           }
 //-------- 
 $pdf::SetMargins(0,0,0,0,true);
 $pdf::SetAutoPageBreak(TRUE, 0);

              for($i=0;$i< count($addstudent);$i++) {
                $pdf::AddPage('L','A4');
                          
               // ใช้พังก์ชั่น str_replace เปลี่ยน \ เป็น / เพื่อให้ Library อ่านง่ายขึ้น
$imagePath = storage_file_path('upload', $pic[0]->app_certificate, $pic[0]->app_year ?? active_year());

if (file_exists($imagePath)) {
    // ระบุนามสกุลไฟล์ให้ชัดเจนในพารามิเตอร์ที่ 7 (เช่น 'PNG' หรือ 'JPG')
    $pdf::Image($imagePath, 0, 0, 300, 210, '', '', '', false, 300, '', false, false, 0, false, false, false);
} else {
    dd("หาไฟล์ไม่พบที่: " . $imagePath);
} 
                if($addstudent[$i]->type == 1){
                  if($xscore=='เข้าร่วม'){
                  $teach ='ครูผู้สอนนักเรียน'.$xinx.''.$xscore;
                  $student =''; 
                  }else{
                  $teach ='ครูผู้สอนนักเรียนได้รับรางวัล'.$xinx.' '.$xscore;
                  $student ='';
                  }
              }else{
                  if($xscore=='เข้าร่วม'){
                  $teach ='';
                  $student ='ได้'.$xinx.''.$xscore;
                  }else{
                  $teach ='';
                  $student ='ได้รับรางวัล '.$xinx.' '.$xscore;
                  }
                }
              
              $pdf::AddFont('fonts/THSarabun.ttf','','fonts/thsarabunpsk.php');
                $pdf::AddFont('fonts/PSLMalinee.ttf','','fonts/pslmalinee.php');
              $pdf::SetFont('fonts/THSarabun.ttf','',10);   
                $pdf::Cell(0, 0,'', 0, 1, 'R', 0, '', 1);
                $pdf::SetFont('fonts/THSarabun.ttf','',20);
                
                $pdf::Cell(0, 0,'เลขที่ -๐๑-'.thainumDigit($addschool[0]->school_activity.$addschool[0]->school_name.$addschool[0]->id).'/'.thainumDigit($year[0]->years).'      ', 0, 1, 'R', 0, '', 1);
                $pdf::SetFont('fonts/PSLMalinee.ttf','',28);
                $pdf::Cell(0, 0,'', 0, 1, 'C', 0, '', 1);
                $pdf::Cell(0, 0,'', 0, 1, 'C', 0, '', 1);
                $pdf::Cell(0, 0,'', 0, 1, 'C', 0, '', 1);
                $pdf::Cell(0, 0,'', 0, 1, 'C', 0, '', 1);
              //  $pdf::SetFont('fonts/THSarabun.ttf','',20);
                $pdf::Cell(0, 0,'', 0, 1, 'C', 0, '', 1);
              //  $pdf::SetFont('fonts/THSarabun.ttf','B',28);
                $pdf::SetTextColor(0,0,255);
              //  $pdf::Cell(0, 0,studenttitle($addstudent[$i]->title).' '.$addstudent[$i]->name.' '.$addstudent[$i]->lastname, 0, 1, 'C', 0, '', 1);
                $pdf::Cell(0, 0,$addstudent[$i]->getstudenttitle->title.''.$addstudent[$i]->name.' '.$addstudent[$i]->lastname, 0, 1, 'C', 0, '', 1);
                $pdf::SetTextColor(0,0,0);
                $pdf::SetFont('fonts/THSarabun.ttf','',24);
                $pdf::Cell(0, 0,$addschool[0]->getschoolname->school_name, 0, 1, 'C', 0, '', 1);
                $pdf::Cell(0, 0,''.$teach.' '.$student, 0, 1, 'C', 0, '', 1);
                $pdf::SetTextColor(0,0,0);

                $activity_name = str_replace('[เดี่ยว]','',$addschool[0]->getactivityname->activity_name);
                $activity_name = str_replace('[ทีม 2 คน]','',$activity_name);
                $activity_name = str_replace('[ทีม 3 คน]','',$activity_name);
                $activity_name = str_replace('[ทีม 4 คน]','',$activity_name);
                $activity_name = str_replace('[ทีม 5 คน]','',$activity_name);
                $activity_name = str_replace('[ทีม 6 คน]','',$activity_name);
                $activity_name = str_replace('[ทีม 9 คน]','',$activity_name);
                $activity_name = str_replace('[ทีม 10 คน]','',$activity_name);
                $activity_name = str_replace('[ทีม 12 คน]','',$activity_name);
                $activity_name = str_replace('[ทีม 15 คน]','',$activity_name);
                $activity_name = str_replace('[ทีม 16 คน]','',$activity_name);
                $activity_name = str_replace('[ทีม 20 คน]','',$activity_name);
                $activity_name = str_replace('[ทีม 40 คน]','',$activity_name);
                
                $activity_name = thainumDigit($activity_name);
              //  $activity_name = str_replace('2','๒',$activity_name);
              //  $activity_name = str_replace('3','๓',$activity_name);
              //  $activity_name = str_replace('4','๔',$activity_name);
              //  $activity_name = str_replace('5','๕',$activity_name);
              //  $activity_name = str_replace('6','๖',$activity_name);
              //  $activity_name = str_replace('7','๗',$activity_name);
              //  $activity_name = str_replace('8','๘',$activity_name);
              //  $activity_name = str_replace('9','๙',$activity_name);
              //   $activity_name = str_replace('0','๐',$activity_name);

                $pdf::Cell(0, 0,$activity_name." ".$addschool[0]->getactivityname->getclass->class_name, 0, 1, 'C', 0, '', 1);
                $pdf::SetTextColor(0,0,0);
                $pdf::endPage();
              
              }
              
              
                $pdf::Output($filename);
              
              }
              





}
