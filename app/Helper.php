<?php

//namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Project;
use App\Workgroup;
use App\Year;
//use Session;
use App\Budgetofschools;
use App\Projectprocesses;
use App\Configyear;

$header1='งานศิลปหัตถกรรมนักเรียนครั้งที่ ๗๐';
$header2='สำนักงานเขตพื้นที่การศึกษาประถมศึกษาชุมพร เขต ๑';
function thainumDigit($num){
  return str_replace(array( '0' , '1' , '2' , '3' , '4' , '5' , '6' ,'7' , '8' , '9' ),
  array( "๐" , "๑" , "๒" , "๓" , "๔" , "๕" , "๖" , "๗" , "๘" , "๙" ),
  $num);
}
	 function getbudjetleft($id)
	{
		$project = project::find($id);
		$budgetleft = Projectprocesses::where('projectId','=',$id)->where('status','=',0)->get();
		$data = $project->projectBudget;
		foreach($budgetleft as $i)
		{
			if($i->processtype == 0)
			{
				$data = $data-$i->budget;
			}
			else
			{
				$data = $data+$i->budget;
			}
			
			
		}
		
	
		return $data;
	}






function letitemid($id)
{
	return str_pad($id,3,"0",STR_PAD_LEFT);
	
}
function letitemid2($id)
{
	return str_pad($id,2,"0",STR_PAD_LEFT);
	
}
function leavecheck($type1,$type2,$day)
{
    if($type1 == $type2)
    {
      return($day);
    }
    else {
      return(0);
    }
}
function inc($year)
{
  return $year-1;
}
function ThaiBahtConversion($amount_number)
{
    $amount_number = number_format($amount_number, 2, ".","");
    //echo "<br/>amount = " . $amount_number . "<br/>";
    $pt = strpos($amount_number , ".");
    $number = $fraction = "";
    if ($pt === false)
        $number = $amount_number;
    else
    {
        $number = substr($amount_number, 0, $pt);
        $fraction = substr($amount_number, $pt + 1);
    }

    //list($number, $fraction) = explode(".", $number);
    $ret = "";
    $baht = ReadNumber ($number);
    if ($baht != "")
        $ret .= $baht . "บาท";

    $satang = ReadNumber($fraction);
    if ($satang != "")
        $ret .=  $satang . "สตางค์";
    else
        $ret .= "ถ้วน";
    //return iconv("UTF-8", "TIS-620", $ret);
    return $ret;
}

function ReadNumber($number)
{
    $position_call = array("แสน", "หมื่น", "พัน", "ร้อย", "สิบ", "");
    $number_call = array("", "หนึ่ง", "สอง", "สาม", "สี่", "ห้า", "หก", "เจ็ด", "แปด", "เก้า");
    $number = $number + 0;
    $ret = "";
    if ($number == 0) return $ret;
    if ($number > 1000000)
    {
        $ret .= ReadNumber(intval($number / 1000000)) . "ล้าน";
        $number = intval(fmod($number, 1000000));
    }

    $divider = 100000;
    $pos = 0;
    while($number > 0)
    {
        $d = intval($number / $divider);
        $ret .= (($divider == 10) && ($d == 2)) ? "ยี่" :
            ((($divider == 10) && ($d == 1)) ? "" :
            ((($divider == 1) && ($d == 1) && ($ret != "")) ? "เอ็ด" : $number_call[$d]));
        $ret .= ($d ? $position_call[$pos] : "");
        $number = $number % $divider;
        $divider = $divider / 10;
        $pos++;
    }
    return $ret;
}
function type($type){
  switch($type){
    case '1':
      return('ซื้อ');
      break;
      case '2':
      return('จ้าง');
      break;
  }
}
function how2getitem($type){
  switch($type){
    case '1':
      return('จัดซื้อ');
      break;
      case '2':
      return('จัดจ้าง');
      break;
	  case '3':
      return('บริจาค');
      break;
	  
  }
}
function leaveType($type){
  switch($type){
    case '1':
      return('ลาป่วย');
      break;
      case '2':
      return('ลากิจ');
      break;
      case '3':
      return('ลาคลอดบุตร');
      break;
  }
}
function type2($type){
  switch($type){
    case '1':
      return('ผู้ขาย');
      break;
      case '2':
      return('ผู้รับจ้าง');
      break;
  }
}
function type3($type){
  switch($type){
    case '1':
      return('พัสดุ');
      break;
      case '2':
      return('งานจ้าง');
      break;
  }
}
function type4($type){
  switch($type){
    case '1':
      return('ขายพัสดุ');
      break;
      case '2':
      return('รับจ้าง');
      break;
  }
}

function type5($type){
  switch($type){
    case '1':
      return('ใบสั่งซื้อ');
      break;
      case '2':
      return('ใบสั่งจ้าง');
      break;
  }
}
function documenttype($type){
  switch($type){
    case '1':
      return('คำสั่ง');
      break;
      case '2':
      return('หนังสือราชการ');
      break;
	  case '3':
      return('บันทึกข้อความ');
      break;
  }
}
function purchaseStatus($status){
  switch($status){
    case '0':
      return('ยังไม่ได้ตรวจรับ');
      break;
      case '1':
      return('ตรวจรับแล้ว');
      break;
  }

}
function purchasetype2($type){
  switch($type){
    case '1':
      return('วิธีเฉพาะเจาะจง');
      break;
      case '2':
      return('วิธีคัดเลือก');
      break;
      case '3':
      return('วิธีประกาศเชิญชวน');
      break;
      case '4':
      return('วิธี E-bidding');
      break;
  }
}

function partnerType($type){
  switch($type){
    case '1':
      return('บริษัท/ห้างหุ้นส่วน');
      break;
      case '2':
      return('ร้าน/บุคคลธรรมดา');
      break;
  }
}

function itemtype($type){
  switch($type){
    case '1':
      return('วัสดุ');
      break;
      case '2':
      return('ครุภัณฑ์');
      break;
      case '3':
      return('อาคารถาวร');
      break;
      case '4':
      return('อาคารชั่วคราว');
      break;
      case '5':
      return('สิ่งก่อสร้าง');
      break;
      case '6':
      return('งานจ้าง');
      break;
  }
}
function studenttitle($title){
  switch($title){
    case '1';
    return('เด็กชาย');
    break;
    case '2';
    return('เด็กหญิง');
    break;
    case '3';
    return('นาย');
    break;
    case '4';
    return('นางสาว');
    break;
    case '5';
    return('นาง');
    break;
    case '6';
    return('สิบเอก');
    break;
    case '7';
    return('ว่าที่ร้อยตรี');
    break;
    case '8';
    return('ว่าที่ร้อยตรีหญิง');
    break;
    case '9';
    return('Mr.');
    break;
    case '10';
    return('Mrs.');
    break;
    case '11';
    return('Miss');
    break;
    case '12';
    return('จ.ส.ต.หญิง');
    break;
    case '13';
    return('ด.ต.');
    break;
  }
}
function getmedel($score){
  $url=url('/imgs/');
  if($score >= 80){
    
    
   // echo "<span class='glyphicon glyphicon-certificate' style='color:gold'></span> ทอง";
   echo "<img src='".$url."/gold.png' width='35px'>";
  }elseif($score >=70){
   // echo "<span class='glyphicon glyphicon-certificate' style='color:gray'></span> เงิน";
    echo "<img src='".$url."/silver.png' width='35px'>";
  }elseif($score >=60){
   // echo "<span class='glyphicon glyphicon-certificate' style='color:gray'></span> ทองแดง";
    echo "<img src='".$url."/bronze.png' width='35px'>";
  }
  elseif($score > 0){
    echo " เข้าร่วม";
  }
  else{
    echo "-";
  }

}

function getactive($activity_status){
  switch($activity_status){
    case '';
    echo('');
    break;
    case '0';
    echo('');
    break;
    case '1';
    echo('<font color="red">ประกาศผลแล้ว</font>');
    break;
  }
  
}
function getmx($score){
  if($score >= 80){
    echo "เหรียญทอง";
  }elseif($score >=70){
    echo "เหรียญเงิน";
  }elseif($score >=60){
    echo "เหรียญทองแดง";
  }
  elseif($score > 0){
    echo "เข้าร่วม";
  }
  else{
    echo "-";
  }
}
function getactivitystatus($x){
  if($x==1){
    echo "<span class='glyphicon glyphicon-ok' style='color:brown'> ประกาศผลแล้ว</span>";
  }else{
    echo "- ";
  }
}
function position($position){
  switch($position){
	     case '1':
    return('จนท.บันทึกคะแนน');
    break;
    case '2':
    return('หัวหน้าศูนย์การแข่งขัน');
    break;
    case '3':
    return('เจ้าหน้าที่เขตฯ');
    break;
    case '4':
    return('ผู้บริหาร');
    break;
    case '5':
    return('เครือข่ายสถานศึกษา');
    break;
    case '7':
    return('เจ้าหน้าที่พัสดุ');
    break;
    case '8':
    return('หัวหน้าเจ้าหน้าที่พัสดุ');
    break;
    case '9':
    return('ผู้บริหาร');
    break;
    case '10':
    return('ผู้ดูแลระบบ');
    break;
  }
}
function committee($position){
  switch($position){
    case '5':
    return('กรรมการตรวจรับ');
    break;
    case '6':
    return('ประธานกรรมการตรวจรับ');
    break;
  }
}

function taxtype($type){
  switch($type){
    case '1':
    return('ภาษีเงินได้บุคคลธรรมดา');
    break;
    case '2':
    return('ภาษีเงินได้นิติบุคคล');
    break;
  }
}
function documentid($documentid,$type,$year){
  switch($type){
    case '1':
    return($documentid.'/'.$year);
    break;
    case '2':
    return('ศธ 04041.143/'.$documentid);
    break;
	case '3':
    return($documentid.'/'.$year);
    break;
  }
}
function DateThai($strDate)
{
  $strYear = date("Y",strtotime($strDate))+543-2500;
  $strMonth= date("n",strtotime($strDate));
  $strDay= date("j",strtotime($strDate));
  $strHour= date("H",strtotime($strDate));
  $strMinute= date("i",strtotime($strDate));
  $strSeconds= date("s",strtotime($strDate));
  $strMonthCut = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
  $strMonthThai=$strMonthCut[$strMonth];
  //return "$strDay $strMonthThai $strYear, $strHour:$strMinute";
    return "$strDay $strMonthThai $strYear";
}
function DateThai2($strDate)
{
  $strYear = date("Y",strtotime($strDate))+543-2500;
  $strMonth= date("n",strtotime($strDate));
  $strDay= date("j",strtotime($strDate));
  $strHour= date("H",strtotime($strDate));
  $strMinute= date("i",strtotime($strDate));
  $strSeconds= date("s",strtotime($strDate));
  $strMonthCut = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
  $strMonthThai=$strMonthCut[$strMonth];
  //return "$strDay $strMonthThai $strYear, $strHour:$strMinute";
    return "$strDay $strMonthThai $strYear";
}

function DateThai3($strDate)
{
  $strYear = date("Y",strtotime($strDate))+543-2500;
  $strMonth= date("n",strtotime($strDate));
  $strDay= date("j",strtotime($strDate));
  $strHour= date("H",strtotime($strDate));
  $strMinute= date("i",strtotime($strDate));
  $strSeconds= date("s",strtotime($strDate));
  $strMonthCut = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
  $strMonthThai=$strMonthCut[$strMonth];
  //return "$strDay $strMonthThai $strYear, $strHour:$strMinute";
    return "$strYear";
}

function DateThai4($strDate)
{
  $strYear = date("Y",strtotime($strDate))+543;
  $strMonth= date("n",strtotime($strDate));
  $strDay= date("j",strtotime($strDate));
  $strHour= date("H",strtotime($strDate));
  $strMinute= date("i",strtotime($strDate));
  $strSeconds= date("s",strtotime($strDate));
  $strMonthCut = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
  $strMonthThai=$strMonthCut[$strMonth];
  //return "$strDay $strMonthThai $strYear, $strHour:$strMinute";
    return "$strYear";
}
function DateThai5($strDate)
{
  $strYear = date("y",strtotime($strDate))+543;
  $strMonth= date("n",strtotime($strDate));
  $strDay= date("j",strtotime($strDate));
  $strHour= date("H",strtotime($strDate));
  $strMinute= date("i",strtotime($strDate));
  $strSeconds= date("s",strtotime($strDate));
  $strMonthCut = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
  $strMonthThai=$strMonthCut[$strMonth];
  //return "$strDay $strMonthThai $strYear, $strHour:$strMinute";
    return "$strYear";
}
function DateThai6($strDate)
{
  $strYear = date("Y",strtotime($strDate))+543;
  $strMonth= date("n",strtotime($strDate));
  $strDay= date("j",strtotime($strDate));
  $strHour= date("H",strtotime($strDate));
  $strMinute= date("i",strtotime($strDate));
  $strSeconds= date("s",strtotime($strDate));
  $strMonthCut = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
  $strMonthThai=$strMonthCut[$strMonth];
  return "$strDay $strMonthThai $strYear, $strHour:$strMinute";
   // return "$strYear";
}
function adminchk()
{
  if(Auth::user()->position==10)
  {
    return true;
  }
  else {
    return false;
  }
}
function areachk()
{
  if(Auth::user()->position==45)
  {
    return true;
  }
  else {
    return false;
  }
}
function officerchk()
{
  if(session('position')==10 || session('position')==8 || session('position')==7)
  {
    return true;
  }
  else {
    return false;
  }
}
function userchk()
{
  if(Auth::user()->position!=7)
  {
 abort(404);
  }
}
function bosschk()
{
  if(Auth::user()->position!=9)
  {
 abort(404);
  }
}
function lawerchk()
{
  if(Auth::user()->position!=8)
  {
 abort(404);
  }
}
function documentownerchk($user)
{
	if($user == Auth::user()->name." ".Auth::user()->lastname)
	{
		return true;
	}
	else
	{
		return false;
	}
}

// ฟังก์ัชั่นคำนวนค่าเสื่ฟังก์ัชั่นคำนวนค่าเสื่อมราคา
function pricechk1($year1,$year2)
{
  if($year1!=$year2)
  {
    return ("คำนวน 1 ปี");
  }
  else {
    return("เลิกคำนวน");
  }
}
function pricechk2($itemtotel,$timeoflife,$year1,$year2)
{
  if($year1!=$year2)
  {
    if($year1 == $year2-1)
    {
      return ($itemtotel/$timeoflife-1);
    }
    else{
      return ($itemtotel/$timeoflife);
    }

  }
  else {
    return("-");
  }
}
function pricechk3($int1,$int2)
{

  return $int1+$int2;

}

if (!function_exists('active_year')) {
    function active_year() {
        return session('year') ?: config('app.year') ?: env('APP_YEAR', '2570');
    }
}

if (!function_exists('storage_year_path')) {
    function storage_year_path($subDir = '', $fileName = '', $year = null) {
        $year = $year ?: active_year();
        $parts = [trim($year, '/')];

        if (!empty($subDir)) {
            $parts[] = trim($subDir, '/');
        }
        if (!empty($fileName)) {
            $parts[] = basename($fileName);
        }

        return implode('/', array_filter($parts));
    }
}

if (!function_exists('storage_file_exists')) {
    function storage_file_exists($subDir, $fileName, $year = null) {
        if (empty($fileName)) {
            return false;
        }

        $path = storage_year_path($subDir, $fileName, $year);
        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
            return true;
        }

        return file_exists(public_path(trim($subDir, '/') . '/' . basename($fileName)));
    }
}

if (!function_exists('storage_file_path')) {
    function storage_file_path($subDir, $fileName, $year = null) {
        $path = storage_year_path($subDir, $fileName, $year);
        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
            return str_replace('\\', '/', \Illuminate\Support\Facades\Storage::disk('public')->path($path));
        }

        return str_replace('\\', '/', public_path(trim($subDir, '/') . '/' . basename($fileName)));
    }
}

if (!function_exists('get_secure_file_url')) {
    function get_secure_file_url($subDir, $fileName, $year = null) {
        if (empty($fileName)) {
            return null;
        }

        return route('secure.download', [
            'year' => $year ?: active_year(),
            'type' => trim($subDir, '/'),
            'file' => basename($fileName),
        ]);
    }
}

if (!function_exists('delete_storage_file')) {
    function delete_storage_file($subDir, $fileName, $year = null) {
        if (empty($fileName)) {
            return;
        }

        $path = storage_year_path($subDir, $fileName, $year);
        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($path);
        }

        $legacyPath = public_path(trim($subDir, '/') . '/' . basename($fileName));
        if (file_exists($legacyPath)) {
            @unlink($legacyPath);
        }
    }
}

if (!function_exists('visitor_count')) {
    function visitor_count() {
        try {
            $baseOffset = 1578;
            return \Illuminate\Support\Facades\DB::table('visitor_logs')->count() + $baseOffset;
        } catch (\Exception $e) {
            return 1578;
        }
    }
}

if (!function_exists('online_count')) {
    function online_count() {
        try {
            return \Illuminate\Support\Facades\DB::table('visitor_logs')
                ->where('last_activity', '>=', now()->subMinutes(5))
                ->count();
        } catch (\Exception $e) {
            return 1;
        }
    }
}
