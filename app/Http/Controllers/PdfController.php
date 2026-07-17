<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use carbon\carbon;
use Auth;
use PDF;
use Session;
use App\addschool;
use App\addstudent;
use App\addschools_areas;
use App\addstudents_areas;
use App\Jows_areas;
use App\activities;
use App\jows;
use App\Systemactives;
use App\Years;
use App\Configs;
//https://www.rubydoc.info/gems/rfpdf/1.17.4/TCPDF:Cell
class PdfController extends Controller
{
    /**
     * Display a listing of the resource.
     *  TCPEF นะ
     * @return \Illuminate\Http\Response
     */
     public function __construct()
     {
         // $this->middleware('auth');
     }

public function areaprinttest($id){
        $pic = Configs::where('schoolgroup',Session('area'))->get();
      // dd($pic[0]->app_certificate);
       $id = 1;
       $score = 80;
       $inx = 2;
    
       $cerfiticatechk = 1;

      //dd($cerfiticatechk);
       //////////////////////////////////////////////////////////////////////////////////
       $filename="cerf.pdf";
       $pdf = new PDF();
       $pdf::AddFont('fonts/THSarabun.ttf','','fonts/thsarabunpsk.php');
       $pdf::SetFont('fonts/THSarabun.ttf','',18);
       $pdf::SetTitle('ไฟล์');

     //-------- 
     $pdf::SetMargins(0,0,0,0,true);
     $pdf::SetAutoPageBreak(TRUE, 0);
 
                 $pdf::AddPage('L','A4');
                // ใช้พังก์ชั่น str_replace เปลี่ยน \ เป็น / เพื่อให้ Library อ่านง่ายขึ้น
$imagePath = str_replace('\\', '/', public_path('upload/' . $pic[0]->app_certificate));

if (file_exists($imagePath)) {
    // ระบุนามสกุลไฟล์ให้ชัดเจนในพารามิเตอร์ที่ 7 (เช่น 'PNG' หรือ 'JPG')
    $pdf::Image($imagePath, 0, 0, 300, 210, '', '', '', false, 300, '', false, false, 0, false, false, false);
} else {
    dd("หาไฟล์ไม่พบที่: " . $imagePath);
} 
            

                 
// ใช้พังก์ชั่น str_replace เปลี่ยน \ เป็น / เพื่อให้ Library อ่านง่ายขึ้น
$imagePath = str_replace('\\', '/', public_path('upload/' . $pic[0]->app_certificate));

if (file_exists($imagePath)) {
    // ระบุนามสกุลไฟล์ให้ชัดเจนในพารามิเตอร์ที่ 7 (เช่น 'PNG' หรือ 'JPG')
    $pdf::Image($imagePath, 0, 0, 300, 210, '', '', '', false, 300, '', false, false, 0, false, false, false);
} else {
    dd("หาไฟล์ไม่พบที่: " . $imagePath);
}


                
                $pdf::AddFont('fonts/THSarabun.ttf','','fonts/thsarabunpsk.php');
                 $pdf::AddFont('fonts/PSLMalinee.ttf','','fonts/pslmalinee.php');
                $pdf::SetFont('fonts/THSarabun.ttf','',10);   
                 $pdf::Cell(0, 0,'', 0, 1, 'R', 0, '', 1);
                 $pdf::SetFont('fonts/THSarabun.ttf','',20);
                 
                 $pdf::Cell(0, 0,'เลขที่ ๐๔๕๖๘-๐๑-๒๕๖๘    ', 0, 1, 'R', 0, '', 1);
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
                 $pdf::Cell(0, 0,"นายกิตติพงษ์ น้อยจีน", 0, 1, 'C', 0, '', 1);
                 $pdf::SetTextColor(0,0,0);
                 $pdf::SetFont('fonts/THSarabun.ttf','',24);
                 $pdf::Cell(0, 0,"รองผู้อำนวยการโรงเรียนอนุบาลชุมพร", 0, 1, 'C', 0, '', 1);
                 $pdf::Cell(0, 0,'ได้เป็นวิทยากรในการฝึกอบรมเชิงปฏิบัติการ', 0, 1, 'C', 0, '', 1);
                 $pdf::SetTextColor(0,0,0);
     
           
     
                 $pdf::Cell(0, 0,"เครือข่ายสถานศึกษาและเจ้าหน้าที่บันทึกคะแนนเพื่อใช้งานโปรแกรม", 0, 1, 'C', 0, '', 1);
                 $pdf::SetTextColor(0,0,0);
                 $pdf::endPage();
     
   
     
     
       $pdf::Output($filename);
     
     }










     public function printtest(){
      $pic = Configs::all();
     //  dd($pic[0]->app_certificate);
       $id = 1;
       $score = 80;
       $inx = 2;
    
       $cerfiticatechk = 1;

      //dd($cerfiticatechk);
       //////////////////////////////////////////////////////////////////////////////////
       $filename="cerf.pdf";
       $pdf = new PDF();
       $pdf::AddFont('fonts/THSarabun.ttf','','fonts/thsarabunpsk.php');
       $pdf::SetFont('fonts/THSarabun.ttf','',18);
       $pdf::SetTitle('ไฟล์');

     //-------- 
     $pdf::SetMargins(0,0,0,0,true);
     $pdf::SetAutoPageBreak(TRUE, 0);
 
                 $pdf::AddPage('L','A4');


// ใช้พังก์ชั่น str_replace เปลี่ยน \ เป็น / เพื่อให้ Library อ่านง่ายขึ้น
$imagePath = str_replace('\\', '/', public_path('upload/' . $pic[0]->app_certificate));

if (file_exists($imagePath)) {
    // ระบุนามสกุลไฟล์ให้ชัดเจนในพารามิเตอร์ที่ 7 (เช่น 'PNG' หรือ 'JPG')
    $pdf::Image($imagePath, 0, 0, 300, 210, '', '', '', false, 300, '', false, false, 0, false, false, false);
} else {
    dd("หาไฟล์ไม่พบที่: " . $imagePath);
}





               //  $pdf::Image(public_path('upload/' . $pic[0]->app_certificate), 0, 0, 300, 210, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false); 
            
                
                $pdf::AddFont('fonts/THSarabun.ttf','','fonts/thsarabunpsk.php');
                 $pdf::AddFont('fonts/PSLMalinee.ttf','','fonts/pslmalinee.php');
                $pdf::SetFont('fonts/THSarabun.ttf','',10);   
                 $pdf::Cell(0, 0,'', 0, 1, 'R', 0, '', 1);
                 $pdf::SetFont('fonts/THSarabun.ttf','',20);
                 
                 $pdf::Cell(0, 0,'เลขที่ ๐๔๕๖๘-๐๑-๒๕๖๘    ', 0, 1, 'R', 0, '', 1);
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
                 $pdf::Cell(0, 0,"นายกิตติพงษ์ น้อยจีน", 0, 1, 'C', 0, '', 1);
                 $pdf::SetTextColor(0,0,0);
                 $pdf::SetFont('fonts/THSarabun.ttf','',24);
                 $pdf::Cell(0, 0,"รองผู้อำนวยการโรงเรียนอนุบาลชุมพร", 0, 1, 'C', 0, '', 1);
                 $pdf::Cell(0, 0,'ได้เป็นวิทยากรในการฝึกอบรมเชิงปฏิบัติการ', 0, 1, 'C', 0, '', 1);
                 $pdf::SetTextColor(0,0,0);
     
           
     
                 $pdf::Cell(0, 0,"เครือข่ายสถานศึกษาและเจ้าหน้าที่บันทึกคะแนนเพื่อใช้งานโปรแกรม", 0, 1, 'C', 0, '', 1);
                 $pdf::SetTextColor(0,0,0);
                 $pdf::endPage();
     
   
     
     
       $pdf::Output($filename);
     
     }
     
 // ---- ปริ้นเกียรติบัตร ระดับเครือข่าย ของนักเรียนน 
      
//---------------------------- ปริ้นเกียรติบัตร รายกิจกรรม
public function areaprintPDF(request $request){
  //dd($request);
 // $pic = Configs::all();
  $pic = Configs::where('schoolgroup',Session('area'))->get();
 // dd($request);
  $id = $request->id;
  $score = $request->student_score;
  $inx = $request->x;
  $addschool = addschools_areas::where('id','=',$id)->get();
  //$addstudent = addstudent::where('school','=',$id)->where('type','=','0')->paginate(20);
 // $addteacher = addstudent::where('school','=',$id)->where('type','=','1')->paginate(20);
  $addstudent = addstudents_areas::where('school','=',$id)->paginate(65);
  $cerfiticatechk = Systemactives::where('systemname','=','certificate')->paginate(10);
  $year = Years::find(1)->get();
 // dd($addschool[0]->getactivityname->activity_name);
  //dd(count($addstudent));

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
            if($cerfiticatechk[0]->status==0){
            //  $pdf::Image('imgs/bg.JPG', 0, 0, 300, 210, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false); 
              }  if($addstudent[$i]->type == 1){
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
            
            $pdf::Cell(0, 0,'เลขที่ ๐๑-'.thainumDigit($addschool[0]->school_activity.$addschool[0]->school_name.$addschool[0]->id).'/'.thainumDigit($year[0]->years).'      ', 0, 1, 'R', 0, '', 1);
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

            $pdf::Cell(0, 0,$activity_name, 0, 1, 'C', 0, '', 1);
            $pdf::SetTextColor(0,0,0);
            $pdf::endPage();

}


  $pdf::Output($filename);

}
 

public function areaprintPDF2(request $request){

    // dd($request);
  $pic = Configs::where('schoolgroup',Session('area'))->get();
 // dd($request);
  $id = $request->id;
  $score = $request->student_score;
  $inx = $request->x;
  $addschool = addschools_areas::where('id','=',$id)->get();
  //$addstudent = addstudent::where('school','=',$id)->where('type','=','0')->paginate(20);
 // $addteacher = addstudent::where('school','=',$id)->where('type','=','1')->paginate(20);
  $addstudent = addstudents_areas::where('school','=',$id)->paginate(65);
     $cerfiticatechk = Systemactives::where('systemname','=','certificate')->paginate(10);
     $year = Years::find(1)->get();
    // dd($addschool[0]->getactivityname->activity_name);
     //dd(count($addstudent));
   //$pic = Configs::all();
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
$imagePath = str_replace('\\', '/', public_path('upload/' . $pic[0]->app_certificate));

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
               
               $pdf::Cell(0, 0,'เลขที่ ๐๑-'.thainumDigit($addschool[0]->school_activity.$addschool[0]->school_name.$addschool[0]->id).'/'.thainumDigit($year[0]->years).'      ', 0, 1, 'R', 0, '', 1);
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
   

// พิมพ์เกียริบัตร กรรมการตัดสิน ระดับเครือข่าย


public function areaprintjowcerf($id){
  $addschool = addschools_areas::where('id','=',$id)->get();
  //$addstudent = addstudent::where('school','=',$id)->where('type','=','0')->paginate(20);
 // $addteacher = addstudent::where('school','=',$id)->where('type','=','1')->paginate(20);
  $addstudent = addstudents_areas::where('school','=',$id)->paginate(65);
  $pic = Configs::all();
 // dd($addschool[0]->getactivityname->activity_name);
  //dd(count($addstudent));
  $cerfiticatechk = Systemactives::where('systemname','=','certificate')->paginate(10);
 // dd($cerfiticatechk[0]->status);
 $activity = activities::find($id);
 $jows = Jows_areas::where('jow_activity','=',$id)
 ->where('jow_area',session('area'))
 ->get();
 $year = Years::find(1)->get();

  //////////////////////////////////////////////////////////////////////////////////
  $filename="cerf.pdf";
  $pdf = new PDF();
  $pdf::AddFont('fonts/THSarabun.ttf','','fonts/thsarabunpsk.php');
  $pdf::SetFont('fonts/THSarabun.ttf','',18);
  $pdf::SetTitle('ไฟล์');
//  $pdf::SetLineWidth(0);

//-------- 
$pdf::SetMargins(0,0,0,0,true);
$pdf::SetAutoPageBreak(TRUE, 0);

for($i=0;$i< count($jows);$i++) {
  $pdf::AddPage('L','A4');
 // if($cerfiticatechk[0]->status==0){
 // $pdf::Image('imgs/bg.JPG', 0, 0, 300, 210, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false); 
 // }
  $pdf::AddFont('fonts/THSarabun.ttf','','fonts/thsarabunpsk.php');
  $pdf::AddFont('fonts/PSLMalinee.ttf','','fonts/pslmalinee.php');
            $pdf::SetFont('fonts/THSarabun.ttf','',10);   
            $pdf::Cell(0, 0,'', 0, 1, 'R', 0, '', 1);
            $pdf::SetFont('fonts/THSarabun.ttf','',20);
            
            $pdf::Cell(0, 0,'เลขที่ ๐๒-'.thainumDigit($activity->id.$activity->activity_class.$activity->activity_subject).'/'.thainumDigit($year[0]->years).'      ', 0, 1, 'R', 0, '', 1);
            $pdf::SetFont('fonts/THSarabun.ttf','',158);
            $pdf::Cell(0, 0,'', 0, 1, 'C', 0, '', 1);
            
            $pdf::SetFont('fonts/PSLMalinee.ttf','',36);
            $pdf::SetTextColor(0,0,255);
            $pdf::Cell(0, 0,$jows[$i]->getjowtitle->title.$jows[$i]->jow_name.' '.$jows[$i]->jow_lastname, 0, 1, 'C', 0, '', 1);
            $pdf::SetFont('fonts/THSarabun.ttf','',24);
            $pdf::SetTextColor(0,0,0);
            $pdf::Cell(0, 0,'เป็นกรรมการตัดสิน', 0, 1, 'C', 0, '', 1);

            $activity_name = str_replace('[เดี่ยว]','',$activity->activity_name);
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


            $pdf::Cell(0, 0,$activity_name, 0, 1, 'C', 0, '', 1);



      }
            $pdf::endPage();
        $pdf::Output($filename);
       }


//////////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////////


public function areaprintjowcerf_showbg($id){
  $addschool = addschool::where('id','=',$id)->get();
  //$addstudent = addstudent::where('school','=',$id)->where('type','=','0')->paginate(20);
 // $addteacher = addstudent::where('school','=',$id)->where('type','=','1')->paginate(20);
  $addstudent = addstudent::where('school','=',$id)->paginate(65);
  $pic = Configs::where('schoolgroup',Session('area'))->get();
 // dd($addschool[0]->getactivityname->activity_name);
  //dd(count($addstudent));
  $cerfiticatechk = Systemactives::where('systemname','=','certificate')->paginate(10);
 // dd($cerfiticatechk[0]->status);
 $activity = activities::find($id);
 $jows = Jows_areas::where('jow_activity','=',$id)
 ->where('jow_area',session('area'))
 ->get();
 $year = Years::find(1)->get();

  //////////////////////////////////////////////////////////////////////////////////
  $filename="cerf.pdf";
  $pdf = new PDF();
  $pdf::AddFont('fonts/THSarabun.ttf','','fonts/thsarabunpsk.php');
  $pdf::SetFont('fonts/THSarabun.ttf','',18);
  $pdf::SetTitle('ไฟล์');
//  $pdf::SetLineWidth(0);

//-------- 
$pdf::SetMargins(0,0,0,0,true);
$pdf::SetAutoPageBreak(TRUE, 0);

for($i=0;$i< count($jows);$i++) {
  $pdf::AddPage('L','A4');

 // ใช้พังก์ชั่น str_replace เปลี่ยน \ เป็น / เพื่อให้ Library อ่านง่ายขึ้น
$imagePath = str_replace('\\', '/', public_path('upload/' . $pic[0]->app_certificate));

if (file_exists($imagePath)) {
    // ระบุนามสกุลไฟล์ให้ชัดเจนในพารามิเตอร์ที่ 7 (เช่น 'PNG' หรือ 'JPG')
    $pdf::Image($imagePath, 0, 0, 300, 210, '', '', '', false, 300, '', false, false, 0, false, false, false);
} else {
    dd("หาไฟล์ไม่พบที่: " . $imagePath);
} 
            
  $pdf::AddFont('fonts/THSarabun.ttf','','fonts/thsarabunpsk.php');
  $pdf::AddFont('fonts/PSLMalinee.ttf','','fonts/pslmalinee.php');
            $pdf::SetFont('fonts/THSarabun.ttf','',10);   
            $pdf::Cell(0, 0,'', 0, 1, 'R', 0, '', 1);
            $pdf::SetFont('fonts/THSarabun.ttf','',20);
            
            $pdf::Cell(0, 0,'เลขที่ ๐๒-'.thainumDigit($activity->id.$activity->activity_class.$activity->activity_subject).'/'.thainumDigit($year[0]->years).'      ', 0, 1, 'R', 0, '', 1);
            $pdf::SetFont('fonts/THSarabun.ttf','',140);
            $pdf::Cell(0, 0,'', 0, 1, 'C', 0, '', 1);
            
            $pdf::SetFont('fonts/PSLMalinee.ttf','',36);
            $pdf::SetTextColor(0,0,255);
            $pdf::Cell(0, 0,$jows[$i]->getjowtitle->title.$jows[$i]->jow_name.' '.$jows[$i]->jow_lastname, 0, 1, 'C', 0, '', 1);
            $pdf::SetFont('fonts/THSarabun.ttf','',24);
            $pdf::SetTextColor(0,0,0);
            $pdf::Cell(0, 0,'เป็นกรรมการตัดสิน', 0, 1, 'C', 0, '', 1);

            $activity_name = str_replace('[เดี่ยว]','',$activity->activity_name);
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


            $pdf::Cell(0, 0,$activity_name." ".$activity->getclass->class_name, 0, 1, 'C', 0, '', 1);



      }
            $pdf::endPage();
        $pdf::Output($filename);
       }




public function areaprintreportjowcerf($id){
 // $addschool = addschool::where('id','=',$id)->get();
  //$addstudent = addstudent::where('school','=',$id)->where('type','=','0')->paginate(20);
 // $addteacher = addstudent::where('school','=',$id)->where('type','=','1')->paginate(20);
 // $addstudent = addstudent::where('school','=',$id)->paginate(65);

 // dd($addschool[0]->getactivityname->activity_name);
  //dd(count($addstudent));

 //$activity = activities::find($id);
 $jows = Jows_areas::where('id','=',$id)->get();
 $year = years::find(1)->get();
  //////////////////////////////////////////////////////////////////////////////////
  $filename="cerf.pdf";
  $pdf = new PDF();
  $pdf::AddFont('fonts/THSarabun.ttf','','fonts/thsarabunpsk.php');

  $pdf::SetFont('fonts/THSarabun.ttf','',18);
  $pdf::SetTitle('ไฟล์');
//  $pdf::SetLineWidth(0);
$cerfiticatechk = Systemactives::where('systemname','=','certificate')->paginate(10);
//-------- 
$pdf::SetMargins(0,0,0,0,true);
$pdf::SetAutoPageBreak(TRUE, 0);

for($i=0;$i< count($jows);$i++) {
  $pdf::AddPage('L','A4');
 
    $pdf::AddFont('fonts/THSarabun.ttf','','fonts/thsarabunpsk.php');
  $pdf::AddFont('fonts/PSLMalinee.ttf','','fonts/pslmalinee.php');
            $pdf::SetFont('fonts/THSarabun.ttf','',10);   
            $pdf::Cell(0, 0,'', 0, 1, 'R', 0, '', 1);
            $pdf::SetFont('fonts/THSarabun.ttf','',20);
            
            $pdf::Cell(0, 0,'เลขที่ ๐๓-'.thainumDigit($jows[$i]->id).'-'.thainumDigit(session('area')).'/'.thainumDigit($year[0]->years).'      ', 0, 1, 'R', 0, '', 1);
            $pdf::SetFont('fonts/THSarabun.ttf','',150);
            $pdf::Cell(0, 0,'', 0, 1, 'C', 0, '', 1);
            
            $pdf::SetFont('fonts/PSLMalinee.ttf','',36);
            $pdf::SetTextColor(0,0,255);
            $pdf::Cell(0, 0,$jows[$i]->getjowtitle->title.$jows[$i]->jow_name.' '.$jows[$i]->jow_lastname, 0, 1, 'C', 0, '', 1);
            $pdf::SetFont('fonts/THSarabun.ttf','',24);
            $pdf::SetTextColor(0,0,0);
            $pdf::Cell(0, 0,'เป็นกรรมการดำเนินงาน', 0, 1, 'C', 0, '', 1);
            $pdf::Cell(0, 0,$jows[$i]->getjowbranch->branch_name??'', 0, 1, 'C', 0, '', 1);

       
        //    $activity_name = thainumDigit($activity_name);


         //   $pdf::Cell(0, 0,$activity_name, 0, 1, 'C', 0, '', 1);



      }
            $pdf::endPage();
        $pdf::Output($filename);
       }

       ////////////////////////////////


       public function areaprintreportjowcerf_showbg($id){
        // $addschool = addschool::where('id','=',$id)->get();
         //$addstudent = addstudent::where('school','=',$id)->where('type','=','0')->paginate(20);
        // $addteacher = addstudent::where('school','=',$id)->where('type','=','1')->paginate(20);
        // $addstudent = addstudent::where('school','=',$id)->paginate(65);
       $pic = Configs::where('schoolgroup',Session('area'))->get();
        // dd($addschool[0]->getactivityname->activity_name);
         //dd(count($addstudent));
       
        //$activity = activities::find($id);
        $jows = Jows_areas::where('id','=',$id)->get();
        $year = years::find(1)->get();
         //////////////////////////////////////////////////////////////////////////////////
         $filename="cerf.pdf";
         $pdf = new PDF();
         $pdf::AddFont('fonts/THSarabun.ttf','','fonts/thsarabunpsk.php');
       
         $pdf::SetFont('fonts/THSarabun.ttf','',18);
         $pdf::SetTitle('ไฟล์');
       //  $pdf::SetLineWidth(0);
       $cerfiticatechk = Systemactives::where('systemname','=','certificate')->paginate(10);
       //-------- 
       $pdf::SetMargins(0,0,0,0,true);
       $pdf::SetAutoPageBreak(TRUE, 0);
       
       for($i=0;$i< count($jows);$i++) {
         $pdf::AddPage('L','A4');
        // if($cerfiticatechk[0]->status==0){
        //   $pdf::Image('imgs/bg.JPG', 0, 0, 300, 210, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false); 
         //  }
        // ใช้พังก์ชั่น str_replace เปลี่ยน \ เป็น / เพื่อให้ Library อ่านง่ายขึ้น
$imagePath = str_replace('\\', '/', public_path('upload/' . $pic[0]->app_certificate));

if (file_exists($imagePath)) {
    // ระบุนามสกุลไฟล์ให้ชัดเจนในพารามิเตอร์ที่ 7 (เช่น 'PNG' หรือ 'JPG')
    $pdf::Image($imagePath, 0, 0, 300, 210, '', '', '', false, 300, '', false, false, 0, false, false, false);
} else {
    dd("หาไฟล์ไม่พบที่: " . $imagePath);
} 
      
           $pdf::AddFont('fonts/THSarabun.ttf','','fonts/thsarabunpsk.php');
         $pdf::AddFont('fonts/PSLMalinee.ttf','','fonts/pslmalinee.php');
                   $pdf::SetFont('fonts/THSarabun.ttf','',10);   
                   $pdf::Cell(0, 0,'', 0, 1, 'R', 0, '', 1);
                   $pdf::SetFont('fonts/THSarabun.ttf','',20);
                   
                     $pdf::Cell(0, 0,'เลขที่ ๐๓-'.thainumDigit($jows[$i]->id).'-'.thainumDigit(session('area')).'/'.thainumDigit($year[0]->years).'      ', 0, 1, 'R', 0, '', 1);
                   $pdf::SetFont('fonts/THSarabun.ttf','',145);
                   $pdf::Cell(0, 0,'', 0, 1, 'C', 0, '', 1);
                   
                   $pdf::SetFont('fonts/PSLMalinee.ttf','',36);
                   $pdf::SetTextColor(0,0,255);
                   $pdf::Cell(0, 0,$jows[$i]->getjowtitle->title.$jows[$i]->jow_name.' '.$jows[$i]->jow_lastname, 0, 1, 'C', 0, '', 1);
                   $pdf::SetFont('fonts/THSarabun.ttf','',24);
                   $pdf::SetTextColor(0,0,0);
                   $pdf::Cell(0, 0,'เป็นกรรมการดำเนินงาน', 0, 1, 'C', 0, '', 1);
                   $pdf::Cell(0, 0,$jows[$i]->getjowbranch->branch_name??'', 0, 1, 'C', 0, '', 1);
       
              
               //    $activity_name = thainumDigit($activity_name);
       
       
                //   $pdf::Cell(0, 0,$activity_name, 0, 1, 'C', 0, '', 1);
       
       
       
             }
                   $pdf::endPage();
               $pdf::Output($filename);
              }
       
              ////////////////////////////////




   
public function areaprintjow2doccerf($id){
  // $addschool = addschool::where('id','=',$id)->get();
   //$addstudent = addstudent::where('school','=',$id)->where('type','=','0')->paginate(20);
  // $addteacher = addstudent::where('school','=',$id)->where('type','=','1')->paginate(20);
  // $addstudent = addstudent::where('school','=',$id)->paginate(65);
 
  // dd($addschool[0]->getactivityname->activity_name);
   //dd(count($addstudent));
 $year = years::find(1)->get();
  //$activity = activities::find($id);
  $jows = Jows_areas::where('jow_area','=',$id)->get();
   //////////////////////////////////////////////////////////////////////////////////

   $filename="jww.pdf";
  // dd($filename);
   $pdf = new PDF();
   $pdf::AddFont('fonts/THSarabun.ttf','','fonts/thsarabunpsk.php');
 
   $pdf::SetFont('fonts/THSarabun.ttf','',18);
   $pdf::SetTitle('ไฟล์');
 //  $pdf::SetLineWidth(0);
 $cerfiticatechk = Systemactives::where('systemname','=','certificate')->paginate(10);
 //-------- 
 $pdf::SetMargins(0,0,0,0,true);
 $pdf::SetAutoPageBreak(TRUE, 0);
 
 for($i=0;$i< count($jows);$i++) {
   $pdf::AddPage('L','A4');

     $pdf::AddFont('fonts/THSarabun.ttf','','fonts/thsarabunpsk.php');
   $pdf::AddFont('fonts/PSLMalinee.ttf','','fonts/pslmalinee.php');
             $pdf::SetFont('fonts/THSarabun.ttf','',10);   
             $pdf::Cell(0, 0,'', 0, 1, 'R', 0, '', 1);
             $pdf::SetFont('fonts/THSarabun.ttf','',20);
             
             $pdf::Cell(0, 0,'เลขที่ ๐๓-'.thainumDigit(session('area')).'-'.thainumDigit($i).'/'.thainumDigit($year[0]->years).'      ', 0, 1, 'R', 0, '', 1);
             $pdf::SetFont('fonts/THSarabun.ttf','',150);
             $pdf::Cell(0, 0,'', 0, 1, 'C', 0, '', 1);
             
             $pdf::SetFont('fonts/PSLMalinee.ttf','',36);
             $pdf::SetTextColor(0,0,255);
             $pdf::Cell(0, 0,$jows[$i]->getjowtitle->title.$jows[$i]->jow_name.' '.$jows[$i]->jow_lastname, 0, 1, 'C', 0, '', 1);
             $pdf::SetFont('fonts/THSarabun.ttf','',24);
             $pdf::SetTextColor(0,0,0);
             $pdf::Cell(0, 0,'เป็นกรรมการดำเนินงาน', 0, 1, 'C', 0, '', 1);
             $pdf::Cell(0, 0,$jows[$i]->getjowbranch->branch_name ??'', 0, 1, 'C', 0, '', 1);
 
        
         //    $activity_name = thainumDigit($activity_name);
 
 
          //   $pdf::Cell(0, 0,$activity_name, 0, 1, 'C', 0, '', 1);
 
 
 
       }
             $pdf::endPage();
         $pdf::Output($filename);
        }
 
        ////////////////////////////////

  
        public function areaprintjow2doccerf_showbg($id){
          // $addschool = addschool::where('id','=',$id)->get();
           //$addstudent = addstudent::where('school','=',$id)->where('type','=','0')->paginate(20);
          // $addteacher = addstudent::where('school','=',$id)->where('type','=','1')->paginate(20);
          // $addstudent = addstudent::where('school','=',$id)->paginate(65);
        $pic = Configs::where('schoolgroup',$id)->get();
          // dd($addschool[0]->getactivityname->activity_name);
           //dd(count($addstudent));
         $year = years::find(1)->get();
          //$activity = activities::find($id);
          $jows = Jows_areas::where('jow_area','=',$id)->get();
           //////////////////////////////////////////////////////////////////////////////////
        
           $filename="jww.pdf";
          // dd($filename);
           $pdf = new PDF();
           $pdf::AddFont('fonts/THSarabun.ttf','','fonts/thsarabunpsk.php');
         
           $pdf::SetFont('fonts/THSarabun.ttf','',18);
           $pdf::SetTitle('ไฟล์');
         //  $pdf::SetLineWidth(0);
         $cerfiticatechk = Systemactives::where('systemname','=','certificate')->paginate(10);
         //-------- 
         $pdf::SetMargins(0,0,0,0,true);
         $pdf::SetAutoPageBreak(TRUE, 0);
         
         for($i=0;$i< count($jows);$i++) {
           $pdf::AddPage('L','A4');
         //  if($cerfiticatechk[0]->status==0){
         // ใช้พังก์ชั่น str_replace เปลี่ยน \ เป็น / เพื่อให้ Library อ่านง่ายขึ้น
$imagePath = str_replace('\\', '/', public_path('upload/' . $pic[0]->app_certificate));

if (file_exists($imagePath)) {
    // ระบุนามสกุลไฟล์ให้ชัดเจนในพารามิเตอร์ที่ 7 (เช่น 'PNG' หรือ 'JPG')
    $pdf::Image($imagePath, 0, 0, 300, 210, '', '', '', false, 300, '', false, false, 0, false, false, false);
} else {
    dd("หาไฟล์ไม่พบที่: " . $imagePath);
} 
          //   }
             $pdf::AddFont('fonts/THSarabun.ttf','','fonts/thsarabunpsk.php');
           $pdf::AddFont('fonts/PSLMalinee.ttf','','fonts/pslmalinee.php');
                     $pdf::SetFont('fonts/THSarabun.ttf','',10);   
                     $pdf::Cell(0, 0,'', 0, 1, 'R', 0, '', 1);
                     $pdf::SetFont('fonts/THSarabun.ttf','',20);
                     
                          $pdf::Cell(0, 0,'เลขที่ ๐๓-'.thainumDigit(session('area')).'-'.thainumDigit($i).'/'.thainumDigit($year[0]->years).'      ', 0, 1, 'R', 0, '', 1);
                     $pdf::SetFont('fonts/THSarabun.ttf','',145);
                     $pdf::Cell(0, 0,'', 0, 1, 'C', 0, '', 1);
                     
                     $pdf::SetFont('fonts/PSLMalinee.ttf','',36);
                     $pdf::SetTextColor(0,0,255);
                     $pdf::Cell(0, 0,$jows[$i]->getjowtitle->title.$jows[$i]->jow_name.' '.$jows[$i]->jow_lastname, 0, 1, 'C', 0, '', 1);
                     $pdf::SetFont('fonts/THSarabun.ttf','',24);
                     $pdf::SetTextColor(0,0,0);
                     $pdf::Cell(0, 0,'เป็นกรรมการดำเนินงาน', 0, 1, 'C', 0, '', 1);
                     $pdf::Cell(0, 0,$jows[$i]->getjowbranch->branch_name??'', 0, 1, 'C', 0, '', 1);
         
                
                 //    $activity_name = thainumDigit($activity_name);
         
         
                  //   $pdf::Cell(0, 0,$activity_name, 0, 1, 'C', 0, '', 1);
         
         
         
               }
                     $pdf::endPage();
                 $pdf::Output($filename);
                }
         
                ////////////////////////////////













//---------------------------- ปริ้นเกียรติบัตร รายกิจกรรม
public function printPDF(request $request){
  $pic = Configs::all();
  //dd($request);






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
            if($cerfiticatechk[0]->status==0){
            //  $pdf::Image('imgs/bg.JPG', 0, 0, 300, 210, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false); 
              }  if($addstudent[$i]->type == 1){
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
            
            $pdf::Cell(0, 0,'เลขที่ ๐๑-'.thainumDigit($addschool[0]->school_activity.$addschool[0]->school_name.$addschool[0]->id).'/'.thainumDigit($year[0]->years).'      ', 0, 1, 'R', 0, '', 1);
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

            $pdf::Cell(0, 0,$activity_name, 0, 1, 'C', 0, '', 1);
            $pdf::SetTextColor(0,0,0);
            $pdf::endPage();

}


  $pdf::Output($filename);

}





/////////////////////////////////////////////////////
public function printactivitycerf($id,$score,$inx){
  $addschool = addschool::where('id','=',$id)->get();
  //$addstudent = addstudent::where('school','=',$id)->where('type','=','0')->paginate(20);
 // $addteacher = addstudent::where('school','=',$id)->where('type','=','1')->paginate(20);
  $addstudent = addstudent::where('school','=',$id)->paginate(55);
  $pic = Configs::all();
 // dd($addschool[0]->getactivityname->activity_name);
  //dd(count($addstudent));
  $cerfiticatechk = Systemactives::where('systemname','=','certificate')->paginate(10);
 
  //////////////////////////////////////////////////////////////////////////////////
  $filename="cerf.pdf";
  $pdf = new PDF();
  $pdf::AddFont('fonts/THSarabun.ttf','','fonts/thsarabunpsk.php');
  $pdf::SetFont('fonts/THSarabun.ttf','',18);
  $pdf::SetTitle('ไฟล์');
  $year = Years::find(1)->get();
//  $pdf::SetLineWidth(0);
    
          if($score >=80){
             $xscore = "เหรียญทอง";
          }elseif($score >= 70){
             $xscore = "เหรียญเงิน";
           }elseif($score == 0){
              $xscore = ' ';
          }else{
             $xscore =' ';
          }

          if($inx ==1){
            $xinx = "ชนะเลิศ";
          }elseif($inx == 2){
            $xinx = "รองชนะเลิศอันดับ ๑";
          }elseif($inx == 3){
            $xinx = "รองชนะเลิศอันดับ ๒";
          }else{
            $xinx = ' ';
          }
//-------- หน้าขอซื้อ
$pdf::SetMargins(0,0,0,0,true);
$pdf::SetAutoPageBreak(TRUE, 0);
for($i=0;$i< count($addstudent);$i++) {
            $pdf::AddPage('L','A4');
            if($cerfiticatechk[0]->status==0){
              $pdf::Image('imgs/bg.JPG', 0, 0, 300, 210, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false); 
              } 
              if($addstudent[$i]->type == 1){
              $teach ='ครูผู้สอนนักเรียนได้รับรางวัล'.$xinx.' '.$xscore;
              $student ='';
           }else{
              $teach ='';
              $student ='ได้รับรางวัล '.$xinx.' '.$xscore;
            }
           
            $pdf::AddFont('fonts/THSarabun.ttf','','fonts/thsarabunpsk.php');
            $pdf::AddFont('fonts/PSLMalinee.ttf','','fonts/pslmalinee.php');
            $pdf::SetFont('fonts/THSarabun.ttf','',10);   
            $pdf::Cell(0, 0,'', 0, 1, 'R', 0, '', 1);
            $pdf::SetFont('fonts/THSarabun.ttf','',20);
            
            $pdf::Cell(0, 0,'เลขที่ ๐๑-'.thainumDigit($addschool[0]->school_activity.$addschool[0]->school_name.$addschool[0]->id).'/'.thainumDigit($year[0]->years).'      ', 0, 1, 'R', 0, '', 1);
            $pdf::SetFont('fonts/THSarabun.ttf','',32);
            $pdf::Cell(0, 0,'', 0, 1, 'C', 0, '', 1);
            $pdf::Cell(0, 0,'', 0, 1, 'C', 0, '', 1);
            $pdf::Cell(0, 0,'', 0, 1, 'C', 0, '', 1);
            $pdf::Cell(0, 0,'', 0, 1, 'C', 0, '', 1);
            $pdf::SetFont('fonts/THSarabun.ttf','',20);
            $pdf::Cell(0, 0,'', 0, 1, 'C', 0, '', 1);
            $pdf::SetFont('fonts/PSLMalinee.ttf','',36);
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

            $pdf::Cell(0, 0,$activity_name, 0, 1, 'C', 0, '', 1);
            $pdf::SetTextColor(0,0,0);
            $pdf::endPage();

}


  $pdf::Output($filename);

}

//////////////////////////////////////////////////////////////


public function printjowcerf($id){
  $addschool = addschool::where('id','=',$id)->get();
  //$addstudent = addstudent::where('school','=',$id)->where('type','=','0')->paginate(20);
 // $addteacher = addstudent::where('school','=',$id)->where('type','=','1')->paginate(20);
  $addstudent = addstudent::where('school','=',$id)->paginate(65);
  $pic = Configs::all();
 // dd($addschool[0]->getactivityname->activity_name);
  //dd(count($addstudent));
  $cerfiticatechk = Systemactives::where('systemname','=','certificate')->paginate(10);
 // dd($cerfiticatechk[0]->status);
 $activity = activities::find($id);
 $jows = jows::where('jow_activity','=',$id)->get();
 $year = Years::find(1)->get();

  //////////////////////////////////////////////////////////////////////////////////
  $filename="cerf.pdf";
  $pdf = new PDF();
  $pdf::AddFont('fonts/THSarabun.ttf','','fonts/thsarabunpsk.php');
  $pdf::SetFont('fonts/THSarabun.ttf','',18);
  $pdf::SetTitle('ไฟล์');
//  $pdf::SetLineWidth(0);

//-------- 
$pdf::SetMargins(0,0,0,0,true);
$pdf::SetAutoPageBreak(TRUE, 0);

for($i=0;$i< count($jows);$i++) {
  $pdf::AddPage('L','A4');
 // if($cerfiticatechk[0]->status==0){
 // $pdf::Image('imgs/bg.JPG', 0, 0, 300, 210, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false); 
 // }
  $pdf::AddFont('fonts/THSarabun.ttf','','fonts/thsarabunpsk.php');
  $pdf::AddFont('fonts/PSLMalinee.ttf','','fonts/pslmalinee.php');
            $pdf::SetFont('fonts/THSarabun.ttf','',10);   
            $pdf::Cell(0, 0,'', 0, 1, 'R', 0, '', 1);
            $pdf::SetFont('fonts/THSarabun.ttf','',20);
            
            $pdf::Cell(0, 0,'เลขที่ ๐๒-'.thainumDigit($activity->id.$activity->activity_class.$activity->activity_subject).'/'.thainumDigit($year[0]->years).'      ', 0, 1, 'R', 0, '', 1);
            $pdf::SetFont('fonts/THSarabun.ttf','',158);
            $pdf::Cell(0, 0,'', 0, 1, 'C', 0, '', 1);
            
            $pdf::SetFont('fonts/PSLMalinee.ttf','',36);
            $pdf::SetTextColor(0,0,255);
            $pdf::Cell(0, 0,$jows[$i]->getjowtitle->title.$jows[$i]->jow_name.' '.$jows[$i]->jow_lastname, 0, 1, 'C', 0, '', 1);
            $pdf::SetFont('fonts/THSarabun.ttf','',24);
            $pdf::SetTextColor(0,0,0);
            $pdf::Cell(0, 0,'เป็นกรรมการตัดสิน', 0, 1, 'C', 0, '', 1);

            $activity_name = str_replace('[เดี่ยว]','',$activity->activity_name);
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


            $pdf::Cell(0, 0,$activity_name, 0, 1, 'C', 0, '', 1);



      }
            $pdf::endPage();
        $pdf::Output($filename);
       }


//////////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////////


public function printjowcerf_showbg($id){
  $addschool = addschool::where('id','=',$id)->get();
  //$addstudent = addstudent::where('school','=',$id)->where('type','=','0')->paginate(20);
 // $addteacher = addstudent::where('school','=',$id)->where('type','=','1')->paginate(20);
  $addstudent = addstudent::where('school','=',$id)->paginate(65);
 $pic = Configs::all();
 // dd($addschool[0]->getactivityname->activity_name);
  //dd(count($addstudent));
  $cerfiticatechk = Systemactives::where('systemname','=','certificate')->paginate(10);
 // dd($cerfiticatechk[0]->status);
 $activity = activities::find($id);
 $jows = jows::where('jow_activity','=',$id)->get();
 $year = Years::find(1)->get();

  //////////////////////////////////////////////////////////////////////////////////
  $filename="cerf.pdf";
  $pdf = new PDF();
  $pdf::AddFont('fonts/THSarabun.ttf','','fonts/thsarabunpsk.php');
  $pdf::SetFont('fonts/THSarabun.ttf','',18);
  $pdf::SetTitle('ไฟล์');
//  $pdf::SetLineWidth(0);

//-------- 
$pdf::SetMargins(0,0,0,0,true);
$pdf::SetAutoPageBreak(TRUE, 0);

for($i=0;$i< count($jows);$i++) {
  $pdf::AddPage('L','A4');

 // ใช้พังก์ชั่น str_replace เปลี่ยน \ เป็น / เพื่อให้ Library อ่านง่ายขึ้น
$imagePath = str_replace('\\', '/', public_path('upload/' . $pic[0]->app_certificate));

if (file_exists($imagePath)) {
    // ระบุนามสกุลไฟล์ให้ชัดเจนในพารามิเตอร์ที่ 7 (เช่น 'PNG' หรือ 'JPG')
    $pdf::Image($imagePath, 0, 0, 300, 210, '', '', '', false, 300, '', false, false, 0, false, false, false);
} else {
    dd("หาไฟล์ไม่พบที่: " . $imagePath);
} 
            
  $pdf::AddFont('fonts/THSarabun.ttf','','fonts/thsarabunpsk.php');
  $pdf::AddFont('fonts/PSLMalinee.ttf','','fonts/pslmalinee.php');
            $pdf::SetFont('fonts/THSarabun.ttf','',10);   
            $pdf::Cell(0, 0,'', 0, 1, 'R', 0, '', 1);
            $pdf::SetFont('fonts/THSarabun.ttf','',20);
            
            $pdf::Cell(0, 0,'เลขที่ ๐๒-'.thainumDigit($activity->id.$activity->activity_class.$activity->activity_subject).'/'.thainumDigit($year[0]->years).'      ', 0, 1, 'R', 0, '', 1);
            $pdf::SetFont('fonts/THSarabun.ttf','',140);
            $pdf::Cell(0, 0,'', 0, 1, 'C', 0, '', 1);
            
            $pdf::SetFont('fonts/PSLMalinee.ttf','',36);
            $pdf::SetTextColor(0,0,255);
            $pdf::Cell(0, 0,$jows[$i]->getjowtitle->title.$jows[$i]->jow_name.' '.$jows[$i]->jow_lastname, 0, 1, 'C', 0, '', 1);
            $pdf::SetFont('fonts/THSarabun.ttf','',24);
            $pdf::SetTextColor(0,0,0);
            $pdf::Cell(0, 0,'เป็นกรรมการตัดสิน', 0, 1, 'C', 0, '', 1);

            $activity_name = str_replace('[เดี่ยว]','',$activity->activity_name);
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


            $pdf::Cell(0, 0,$activity_name." ".$activity->getclass->class_name, 0, 1, 'C', 0, '', 1);



      }
            $pdf::endPage();
        $pdf::Output($filename);
       }


//////////////////////////////////////////////////////////////
public function printjowdoccerf($id){
  $addschool = addschool::where('id','=',$id)->get();
  //$addstudent = addstudent::where('school','=',$id)->where('type','=','0')->paginate(20);
 // $addteacher = addstudent::where('school','=',$id)->where('type','=','1')->paginate(20);
  $addstudent = addstudent::where('school','=',$id)->paginate(65);

 // dd($addschool[0]->getactivityname->activity_name);
  //dd(count($addstudent));
  $cerfiticatechk = Systemactives::where('systemname','=','certificate')->paginate(10);
 $activity = activities::find($id);
 $jows = jows::where('jow_activity','=',$id)->get();
 $years = years::find(1)->get();
  //////////////////////////////////////////////////////////////////////////////////
  $filename="cerf.pdf";
  $pdf = new PDF();
  $pdf::AddFont('fonts/THSarabun.ttf','','fonts/thsarabunpsk.php');
  $pdf::SetFont('fonts/THSarabun.ttf','',18);
  $pdf::SetTitle('ไฟล์');
//  $pdf::SetLineWidth(0);

//-------- 
$pdf::SetMargins(0,0,0,0,true);
$pdf::SetAutoPageBreak(TRUE, 0);


  $pdf::AddPage('L','A4');
  //$pdf::Image('imgs/bg.JPG', 0, 0, 300, 210, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false); 
  $pdf::AddFont('fonts/THSarabun.ttf','','fonts/thsarabunpsk.php');
            $pdf::SetFont('fonts/THSarabun.ttf','',10);   
            $pdf::Cell(0, 0,'', 0, 1, 'R', 0, '', 1);
            $pdf::SetFont('fonts/THSarabun.ttf','',20);
            
            $pdf::Cell(0, 0,'เลขที่ ๐๒-'.thainumDigit($activity->id.$activity->activity_class.$activity->activity_subject).'/'.thainumDigit($years[0]->years).'      ', 0, 1, 'R', 0, '', 1);
            $pdf::SetFont('fonts/THSarabun.ttf','',158);
            $pdf::Cell(0, 0,'', 0, 1, 'C', 0, '', 1);
            
            $pdf::SetFont('fonts/THSarabun.ttf','',28);
            $pdf::SetTextColor(0,0,255);
           // $pdf::Cell(0, 0,$jows[$i]->getjowtitle->title.$jows[$i]->jow_name.' '.$jows[$i]->jow_lastname, 0, 1, 'C', 0, '', 1);
            $pdf::SetFont('fonts/THSarabun.ttf','',24);
            $pdf::SetTextColor(0,0,0);
            $pdf::Cell(0, 0,'เป็นกรรมการตัดสิน', 0, 1, 'C', 0, '', 1);

            $activity_name = str_replace('[เดี่ยว]','',$activity->activity_name);
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


            $pdf::Cell(0, 0,$activity_name, 0, 1, 'C', 0, '', 1);



      
            $pdf::endPage();
        $pdf::Output($filename);
       }

//////////////////////////////////////////////////////////////




//////////////////////////////////////////////////////////////













//////////////////////////////////////////////////////////////







public function printreportjowcerf($id){
 // $addschool = addschool::where('id','=',$id)->get();
  //$addstudent = addstudent::where('school','=',$id)->where('type','=','0')->paginate(20);
 // $addteacher = addstudent::where('school','=',$id)->where('type','=','1')->paginate(20);
 // $addstudent = addstudent::where('school','=',$id)->paginate(65);

 // dd($addschool[0]->getactivityname->activity_name);
  //dd(count($addstudent));

 //$activity = activities::find($id);
 $jows = jows::where('id','=',$id)->get();
 $year = years::find(1)->get();
  //////////////////////////////////////////////////////////////////////////////////
  $filename="cerf.pdf";
  $pdf = new PDF();
  $pdf::AddFont('fonts/THSarabun.ttf','','fonts/thsarabunpsk.php');

  $pdf::SetFont('fonts/THSarabun.ttf','',18);
  $pdf::SetTitle('ไฟล์');
//  $pdf::SetLineWidth(0);
$cerfiticatechk = Systemactives::where('systemname','=','certificate')->paginate(10);
//-------- 
$pdf::SetMargins(0,0,0,0,true);
$pdf::SetAutoPageBreak(TRUE, 0);

for($i=0;$i< count($jows);$i++) {
  $pdf::AddPage('L','A4');
 
    $pdf::AddFont('fonts/THSarabun.ttf','','fonts/thsarabunpsk.php');
  $pdf::AddFont('fonts/PSLMalinee.ttf','','fonts/pslmalinee.php');
            $pdf::SetFont('fonts/THSarabun.ttf','',10);   
            $pdf::Cell(0, 0,'', 0, 1, 'R', 0, '', 1);
            $pdf::SetFont('fonts/THSarabun.ttf','',20);
            
            $pdf::Cell(0, 0,'เลขที่ ๐๓-'.thainumDigit($jows[$i]->id).'/'.thainumDigit($year[0]->years).'      ', 0, 1, 'R', 0, '', 1);
            $pdf::SetFont('fonts/THSarabun.ttf','',150);
            $pdf::Cell(0, 0,'', 0, 1, 'C', 0, '', 1);
            
            $pdf::SetFont('fonts/PSLMalinee.ttf','',36);
            $pdf::SetTextColor(0,0,255);
            $pdf::Cell(0, 0,$jows[$i]->getjowtitle->title.$jows[$i]->jow_name.' '.$jows[$i]->jow_lastname, 0, 1, 'C', 0, '', 1);
            $pdf::SetFont('fonts/THSarabun.ttf','',24);
            $pdf::SetTextColor(0,0,0);
            $pdf::Cell(0, 0,'เป็นกรรมการดำเนินงาน', 0, 1, 'C', 0, '', 1);
            $pdf::Cell(0, 0,$jows[$i]->getjowbranch->branch_name, 0, 1, 'C', 0, '', 1);

       
        //    $activity_name = thainumDigit($activity_name);


         //   $pdf::Cell(0, 0,$activity_name, 0, 1, 'C', 0, '', 1);



      }
            $pdf::endPage();
        $pdf::Output($filename);
       }

       ////////////////////////////////


       public function printreportjowcerf_showbg($id){
        // $addschool = addschool::where('id','=',$id)->get();
         //$addstudent = addstudent::where('school','=',$id)->where('type','=','0')->paginate(20);
        // $addteacher = addstudent::where('school','=',$id)->where('type','=','1')->paginate(20);
        // $addstudent = addstudent::where('school','=',$id)->paginate(65);
       $pic = Configs::all();
        // dd($addschool[0]->getactivityname->activity_name);
         //dd(count($addstudent));
       
        //$activity = activities::find($id);
        $jows = jows::where('id','=',$id)->get();
        $year = years::find(1)->get();
         //////////////////////////////////////////////////////////////////////////////////
         $filename="cerf.pdf";
         $pdf = new PDF();
         $pdf::AddFont('fonts/THSarabun.ttf','','fonts/thsarabunpsk.php');
       
         $pdf::SetFont('fonts/THSarabun.ttf','',18);
         $pdf::SetTitle('ไฟล์');
       //  $pdf::SetLineWidth(0);
       $cerfiticatechk = Systemactives::where('systemname','=','certificate')->paginate(10);
       //-------- 
       $pdf::SetMargins(0,0,0,0,true);
       $pdf::SetAutoPageBreak(TRUE, 0);
       
       for($i=0;$i< count($jows);$i++) {
         $pdf::AddPage('L','A4');
        // if($cerfiticatechk[0]->status==0){
        //   $pdf::Image('imgs/bg.JPG', 0, 0, 300, 210, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false); 
         //  }
        // ใช้พังก์ชั่น str_replace เปลี่ยน \ เป็น / เพื่อให้ Library อ่านง่ายขึ้น
$imagePath = str_replace('\\', '/', public_path('upload/' . $pic[0]->app_certificate));

if (file_exists($imagePath)) {
    // ระบุนามสกุลไฟล์ให้ชัดเจนในพารามิเตอร์ที่ 7 (เช่น 'PNG' หรือ 'JPG')
    $pdf::Image($imagePath, 0, 0, 300, 210, '', '', '', false, 300, '', false, false, 0, false, false, false);
} else {
    dd("หาไฟล์ไม่พบที่: " . $imagePath);
} 
      
           $pdf::AddFont('fonts/THSarabun.ttf','','fonts/thsarabunpsk.php');
         $pdf::AddFont('fonts/PSLMalinee.ttf','','fonts/pslmalinee.php');
                   $pdf::SetFont('fonts/THSarabun.ttf','',10);   
                   $pdf::Cell(0, 0,'', 0, 1, 'R', 0, '', 1);
                   $pdf::SetFont('fonts/THSarabun.ttf','',20);
                   
                   $pdf::Cell(0, 0,'เลขที่ ๐๓-'.thainumDigit($jows[$i]->id).'/'.thainumDigit($year[0]->years).'      ', 0, 1, 'R', 0, '', 1);
                   $pdf::SetFont('fonts/THSarabun.ttf','',145);
                   $pdf::Cell(0, 0,'', 0, 1, 'C', 0, '', 1);
                   
                   $pdf::SetFont('fonts/PSLMalinee.ttf','',36);
                   $pdf::SetTextColor(0,0,255);
                   $pdf::Cell(0, 0,$jows[$i]->getjowtitle->title.$jows[$i]->jow_name.' '.$jows[$i]->jow_lastname, 0, 1, 'C', 0, '', 1);
                   $pdf::SetFont('fonts/THSarabun.ttf','',24);
                   $pdf::SetTextColor(0,0,0);
                   $pdf::Cell(0, 0,'เป็นกรรมการดำเนินงาน', 0, 1, 'C', 0, '', 1);
                   $pdf::Cell(0, 0,$jows[$i]->getjowbranch->branch_name, 0, 1, 'C', 0, '', 1);
       
              
               //    $activity_name = thainumDigit($activity_name);
       
       
                //   $pdf::Cell(0, 0,$activity_name, 0, 1, 'C', 0, '', 1);
       
       
       
             }
                   $pdf::endPage();
               $pdf::Output($filename);
              }
       
              ////////////////////////////////
   public function printcenteractivity2($id){
    $addschool = addschool::where('school_activity','=',$id)->orderBy('student_score','desc')->paginate(20);
    $activity= activities::find($id);
    $student = addschool::WITH("getstudentname")->where('school_activity','=',$id)->orderBy('id','desc')->get();
    $cerfiticatechk = Systemactives::where('systemname','=','certificate')->paginate(10);
 
    $data = array(
      'addschool'=>$addschool,
      'student'=>$student,
    //  'schoolgroup'=>$schoolgroup,
      'activity'=>$activity,
  );

   //dd($data);
    
            $filename="register.pdf";
            $pdf = new PDF();
            $pdf::AddFont('fonts/THSarabun.ttf','','fonts/thsarabunpsk.php');
            $pdf::SetFont('fonts/THSarabun.ttf','',20);
            $pdf::SetTitle('-');
            $pdf::SetMargins(5, 34, 10,true);
            $pdf::setPrintHeader(true);
            $pdf::setPrintFooter(true);
              ///////
              session(['activity_name' =>$activity->activity_name]);
              session(['subject_name'=>$activity->getactivitysubject->subjectgroup_name]);
            PDF::setHeaderCallback(function($pdf) {
              //  dd($purchaseId);
              //  $ped= new PDF;
                $pdf->SetY(+5);
                $pdf->AddFont('fonts/THSarabun.ttf','','fonts/thsarabunpsk.php');
                $pdf->SetFont('fonts/THSarabun.ttf','',18);
                $pdf->Cell(0, 15,(session('app_name') ?: 'งานศิลปหัตถกรรมนักเรียนครั้งที่ 71') . ' ปีการศึกษา ' . thainumDigit(active_year()) . ' ' . (session('app_group') ?: 'ระดับเขตพื้นที่การศึกษา'), 0, 1, 'C', 0, '', 0, false, 'M', 'M');
                $pdf->SetFont('fonts/THSarabun.ttf','',16);
                $pdf->Cell(0, 15,'กลุ่มสาระการเรียนรู้ '.session('subject_name').' กิจกรรม '.session('activity_name'), 0, 1, 'C', 0, '', 0, false, 'M', 'M');
                $pdf->SetFont('fonts/THSarabun.ttf','',16);
                $pdf->Cell(0, 12,'ใบลงทะเบียนผู้เข้าร่วมการแข่งขัน (นักเรียน)', 0, 1, 'C', 0, '', 0, false, 'M', 'M');
                $pdf->Cell(10, 8, 'ที่', 1, 0, 'C', 0, '', 1);
                $pdf->Cell(50, 8, 'โรงเรียน', 1, 0, 'C', 0, '', 1);
                $pdf->Cell(70, 8, 'ชื่อ-สกุล', 1, 0, 'C', 0, '', 1);
                $pdf->Cell(25, 8, 'เวลามา', 1, 0, 'C', 0, '', 1);
                $pdf->Cell(35, 8, 'ลายมือชื่อ', 1, 0, 'C', 0, '', 1);
                $pdf->Cell(25, 8, 'เวลากลับ', 1, 0, 'C', 0, '', 1);
                $pdf->Cell(35, 8, 'ลายมือชื่อ', 1, 0, 'C', 0, '', 1);
              $pdf->Cell(30, 8, 'หมายเหต', 1, 0, 'C', 0, '', 1);
               // $pdf->Cell(30, 10, 'งบประมาณ', 1, 0, 'C', 0, '', 1);
               // $pdf->Cell(20, 10, 'วันที่ส่งมอบ', 1, 0, 'C', 0, '', 1);
               // $pdf->Cell(30, 10, 'เลข egp', 1, 0, 'C', 0, '', 1);
        });
        PDF::setFooterCallback(function($pdf) {
                $pdf->AddFont('fonts/THSarabun.ttf','','fonts/thsarabunpsk.php');
                $pdf->SetFont('fonts/THSarabun.ttf','',14);
                $pdf->SetY(-18);
                $pdf->Cell(85, 0, '', 0, 0, 'C', 0, '', 1);
                $pdf->Cell(85, 0,'', 0, 0, 'C', 0, '', 1);
                $pdf->Cell(85, 0,'('.session('DepartmentBoss').')', 0, 1, 'C', 0, '', 1);
                $pdf->Cell(85, 0, '', 0, 0, 'C', 0, '', 1);
                $pdf->Cell(85, 0,'', 0, 0, 'C', 0, '', 1);
                $pdf->Cell(85, 0,'กรรมการรับรายงานตัว', 0, 1, 'C', 0, '', 1);

        });


        $pdf::SetFont('fonts/THSarabun.ttf','',16);
        $pdf::AddPage('L'); // เพิ่มหน้ารายการพัสดุ
        $pdf::Image('imgs/obec.JPG', 10, 0, 18, 25, 'JPG', '', '', false, 200, '', false, false, 0, false, false, false); 
        //$pdf::SetY(-5);
        for($i=0;$i< count($addschool);$i++) {
          $pdf::Ln();
          $pdf::Cell(10, 8, $i+1, 'L,B,R', 0, 'C', 0, '', 1);
          $pdf::Cell(50, 8, $addschool[$i]->getschoolname->school_name, 'L,B,R', 0, 'C', 0, '', 1);

          for($std=0;$std < count($student);$std++){
            $pdf::Cell(50, 8,'ll', 'L,B,R', 1, 'C', 0, '', 1);
          }




        }

        $pdf::Output($filename);
          //  $pdf::Cell(0, 0,'เอกสารรับรายงานตัว', 0, 1, 'C', 0, '', 1);
         //   $pdf::SetFont('fonts/THSarabun.ttf','',16); 
          //  $pdf::Cell(0, 0,$activity->activity_name, 0, 1, 'C', 0, '', 1);
          //  $pdf::Cell(0, 0,'งานศิลปหัตถกรรมนักเรียนครั้งที่ 70 สำนักงานเขตพื้นที่การศึกษาประถมศึกษาชุมพร เขต 1', 0, 1, 'C', 0, '', 1);

         //   $pdf::endPage();
         //   $pdf::Output($filename);
   }


   ////////////////////////////////

   public function printcenteractivity($id){
    $addschool = addschool::where('school_activity','=',$id)->orderBy('student_score','desc')->paginate(20);
    $activity= activities::find($id);
    $student = addschool::WITH("getstudentname")->where('school_activity','=',$id)->orderBy('id','desc')->get();
    $data = array(
      'addschool'=>$addschool,
      'student'=>$student,
    //  'schoolgroup'=>$schoolgroup,
      'activity'=>$activity,
  );

   //dd($data);
    
            $filename="register.pdf";
            $pdf = new PDF();
          //  $pdf::AddFont('fonts/THSarabun.ttf','','fonts/thsarabunpsk.php');
          //  $pdf::SetFont('fonts/THSarabun.ttf','',20);
            $pdf::SetTitle('-');
            $pdf::SetMargins(5, 34, 10,true);
            $pdf::setPrintHeader(true);
            $pdf::setPrintFooter(true);
              ///////
              session(['activity_name' =>$activity->activity_name]);
              session(['subject_name'=>$activity->getactivitysubject->subjectgroup_name]);
            PDF::setHeaderCallback(function($pdf) {
              //  dd($purchaseId);
              //  $ped= new PDF;
                $pdf->SetY(+5);
                $pdf->AddFont('fonts/THSarabun.ttf','','fonts/thsarabunpsk.php');
                $pdf->SetFont('fonts/THSarabun.ttf','',18);
                $pdf->Cell(0, 15,(session('app_name') ?: 'งานศิลปหัตถกรรมนักเรียนครั้งที่ 71') . ' ปีการศึกษา ' . thainumDigit(active_year()) . ' ' . (session('app_group') ?: 'ระดับเขตพื้นที่การศึกษา'), 0, 1, 'C', 0, '', 0, false, 'M', 'M');
                $pdf->SetFont('fonts/THSarabun.ttf','',16);
                $pdf->Cell(0, 15,'กลุ่มสาระการเรียนรู้ '.session('subject_name').' กิจกรรม '.session('activity_name'), 0, 1, 'C', 0, '', 0, false, 'M', 'M');
                $pdf->SetFont('fonts/THSarabun.ttf','',16);
                $pdf->Cell(0, 12,'ใบลงทะเบียนผู้เข้าร่วมการแข่งขัน (นักเรียน)', 0, 1, 'C', 0, '', 0, false, 'M', 'M');
                $pdf->Cell(10, 8, 'ที่', 1, 0, 'C', 0, '', 1);
                $pdf->Cell(50, 8, 'โรงเรียน', 1, 0, 'C', 0, '', 1);
                $pdf->Cell(70, 8, 'ชื่อ-สกุล', 1, 0, 'C', 0, '', 1);
                $pdf->Cell(25, 8, 'เวลามา', 1, 0, 'C', 0, '', 1);
                $pdf->Cell(35, 8, 'ลายมือชื่อ', 1, 0, 'C', 0, '', 1);
                $pdf->Cell(25, 8, 'เวลากลับ', 1, 0, 'C', 0, '', 1);
                $pdf->Cell(35, 8, 'ลายมือชื่อ', 1, 0, 'C', 0, '', 1);
              $pdf->Cell(30, 8, 'หมายเหต', 1, 0, 'C', 0, '', 1);
               // $pdf->Cell(30, 10, 'งบประมาณ', 1, 0, 'C', 0, '', 1);
               // $pdf->Cell(20, 10, 'วันที่ส่งมอบ', 1, 0, 'C', 0, '', 1);
               // $pdf->Cell(30, 10, 'เลข egp', 1, 0, 'C', 0, '', 1);
        });
        PDF::setFooterCallback(function($pdf) {
                $pdf->AddFont('fonts/THSarabun.ttf','','fonts/thsarabunpsk.php');
                $pdf->SetFont('fonts/THSarabun.ttf','',14);
                $pdf->SetY(-18);
                $pdf->Cell(85, 0, '', 0, 0, 'C', 0, '', 1);
                $pdf->Cell(85, 0,'', 0, 0, 'C', 0, '', 1);
                $pdf->Cell(85, 0,'('.session('DepartmentBoss').')', 0, 1, 'C', 0, '', 1);
                $pdf->Cell(85, 0, '', 0, 0, 'C', 0, '', 1);
                $pdf->Cell(85, 0,'', 0, 0, 'C', 0, '', 1);
                $pdf->Cell(85, 0,'กรรมการรับรายงานตัว', 0, 1, 'C', 0, '', 1);

        });


        
   
        $pdf::AddPage('L'); // เพิ่มหน้ารายการพัสดุ
        $pdf::SetFont('THSarabunPSK', '', 10);
        $pdf::Image('imgs/obec.JPG', 10, 0, 18, 25, 'JPG', '', '', false, 200, '', false, false, 0, false, false, false); 
      

        $pdf::lastPage();
        $pdf::Output('example_049.pdf', 'I');
     
   }




   /// print jow2

   
public function printjow2doccerf($id){
  // $addschool = addschool::where('id','=',$id)->get();
   //$addstudent = addstudent::where('school','=',$id)->where('type','=','0')->paginate(20);
  // $addteacher = addstudent::where('school','=',$id)->where('type','=','1')->paginate(20);
  // $addstudent = addstudent::where('school','=',$id)->paginate(65);
 
  // dd($addschool[0]->getactivityname->activity_name);
   //dd(count($addstudent));
 $year = years::find(1)->get();
  //$activity = activities::find($id);
  $jows = jows::where('jow_branch','=',$id)->get();
   //////////////////////////////////////////////////////////////////////////////////

   $filename="jww".$jows[0]->getjowbranch->branch_name.".pdf";
  // dd($filename);
   $pdf = new PDF();
   $pdf::AddFont('fonts/THSarabun.ttf','','fonts/thsarabunpsk.php');
 
   $pdf::SetFont('fonts/THSarabun.ttf','',18);
   $pdf::SetTitle('ไฟล์');
 //  $pdf::SetLineWidth(0);
 $cerfiticatechk = Systemactives::where('systemname','=','certificate')->paginate(10);
 //-------- 
 $pdf::SetMargins(0,0,0,0,true);
 $pdf::SetAutoPageBreak(TRUE, 0);
 
 for($i=0;$i< count($jows);$i++) {
   $pdf::AddPage('L','A4');

     $pdf::AddFont('fonts/THSarabun.ttf','','fonts/thsarabunpsk.php');
   $pdf::AddFont('fonts/PSLMalinee.ttf','','fonts/pslmalinee.php');
             $pdf::SetFont('fonts/THSarabun.ttf','',10);   
             $pdf::Cell(0, 0,'', 0, 1, 'R', 0, '', 1);
             $pdf::SetFont('fonts/THSarabun.ttf','',20);
             
             $pdf::Cell(0, 0,'เลขที่ ๐๓-'.thainumDigit($jows[$i]->getjowbranch->id)."-".thainumDigit($jows[$i]->id).'/'.thainumDigit($year[0]->years).'      ', 0, 1, 'R', 0, '', 1);
             $pdf::SetFont('fonts/THSarabun.ttf','',150);
             $pdf::Cell(0, 0,'', 0, 1, 'C', 0, '', 1);
             
             $pdf::SetFont('fonts/PSLMalinee.ttf','',36);
             $pdf::SetTextColor(0,0,255);
             $pdf::Cell(0, 0,$jows[$i]->getjowtitle->title.$jows[$i]->jow_name.' '.$jows[$i]->jow_lastname, 0, 1, 'C', 0, '', 1);
             $pdf::SetFont('fonts/THSarabun.ttf','',24);
             $pdf::SetTextColor(0,0,0);
             $pdf::Cell(0, 0,'เป็นกรรมการดำเนินงาน', 0, 1, 'C', 0, '', 1);
             $pdf::Cell(0, 0,$jows[$i]->getjowbranch->branch_name, 0, 1, 'C', 0, '', 1);
 
        
         //    $activity_name = thainumDigit($activity_name);
 
 
          //   $pdf::Cell(0, 0,$activity_name, 0, 1, 'C', 0, '', 1);
 
 
 
       }
             $pdf::endPage();
         $pdf::Output($filename);
        }
 
        ////////////////////////////////

  
        public function printjow2doccerf_showbg($id){
          // $addschool = addschool::where('id','=',$id)->get();
           //$addstudent = addstudent::where('school','=',$id)->where('type','=','0')->paginate(20);
          // $addteacher = addstudent::where('school','=',$id)->where('type','=','1')->paginate(20);
          // $addstudent = addstudent::where('school','=',$id)->paginate(65);
         $pic = Configs::all();
          // dd($addschool[0]->getactivityname->activity_name);
           //dd(count($addstudent));
         $year = years::find(1)->get();
          //$activity = activities::find($id);
          $jows = jows::where('jow_branch','=',$id)->get();
           //////////////////////////////////////////////////////////////////////////////////
        
           $filename="jww".$jows[0]->getjowbranch->branch_name.".pdf";
          // dd($filename);
           $pdf = new PDF();
           $pdf::AddFont('fonts/THSarabun.ttf','','fonts/thsarabunpsk.php');
         
           $pdf::SetFont('fonts/THSarabun.ttf','',18);
           $pdf::SetTitle('ไฟล์');
         //  $pdf::SetLineWidth(0);
         $cerfiticatechk = Systemactives::where('systemname','=','certificate')->paginate(10);
         //-------- 
         $pdf::SetMargins(0,0,0,0,true);
         $pdf::SetAutoPageBreak(TRUE, 0);
         
         for($i=0;$i< count($jows);$i++) {
           $pdf::AddPage('L','A4');
         //  if($cerfiticatechk[0]->status==0){
         // ใช้พังก์ชั่น str_replace เปลี่ยน \ เป็น / เพื่อให้ Library อ่านง่ายขึ้น
$imagePath = str_replace('\\', '/', public_path('upload/' . $pic[0]->app_certificate));

if (file_exists($imagePath)) {
    // ระบุนามสกุลไฟล์ให้ชัดเจนในพารามิเตอร์ที่ 7 (เช่น 'PNG' หรือ 'JPG')
    $pdf::Image($imagePath, 0, 0, 300, 210, '', '', '', false, 300, '', false, false, 0, false, false, false);
} else {
    dd("หาไฟล์ไม่พบที่: " . $imagePath);
} 
          //   }
             $pdf::AddFont('fonts/THSarabun.ttf','','fonts/thsarabunpsk.php');
           $pdf::AddFont('fonts/PSLMalinee.ttf','','fonts/pslmalinee.php');
                     $pdf::SetFont('fonts/THSarabun.ttf','',10);   
                     $pdf::Cell(0, 0,'', 0, 1, 'R', 0, '', 1);
                     $pdf::SetFont('fonts/THSarabun.ttf','',20);
                     
                     $pdf::Cell(0, 0,'เลขที่ ๐๓-'.thainumDigit($jows[$i]->getjowbranch->id)."-".thainumDigit($jows[$i]->id).'/'.thainumDigit($year[0]->years).'      ', 0, 1, 'R', 0, '', 1);
                     $pdf::SetFont('fonts/THSarabun.ttf','',145);
                     $pdf::Cell(0, 0,'', 0, 1, 'C', 0, '', 1);
                     
                     $pdf::SetFont('fonts/PSLMalinee.ttf','',36);
                     $pdf::SetTextColor(0,0,255);
                     $pdf::Cell(0, 0,$jows[$i]->getjowtitle->title.$jows[$i]->jow_name.' '.$jows[$i]->jow_lastname, 0, 1, 'C', 0, '', 1);
                     $pdf::SetFont('fonts/THSarabun.ttf','',24);
                     $pdf::SetTextColor(0,0,0);
                     $pdf::Cell(0, 0,'เป็นกรรมการดำเนินงาน', 0, 1, 'C', 0, '', 1);
                     $pdf::Cell(0, 0,$jows[$i]->getjowbranch->branch_name, 0, 1, 'C', 0, '', 1);
         
                
                 //    $activity_name = thainumDigit($activity_name);
         
         
                  //   $pdf::Cell(0, 0,$activity_name, 0, 1, 'C', 0, '', 1);
         
         
         
               }
                     $pdf::endPage();
                 $pdf::Output($filename);
                }
         
                ////////////////////////////////

}
