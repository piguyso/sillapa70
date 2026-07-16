<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User; // เรียกใช้ model User
use App\Purchase;
use App\Subjectgroup;
use App\Schoolgroup;
use App\addschool;
use App\addstudent;
use App\Activities;
use App\Branch;
use App\Dayofsillapas;
use App\Classes;
use App\School;
use App\Schoolranks;
use App\Jows;
use Session;
use Auth;
use DB;
use App\Notices;
use App\Systemactives;
use App\Configs;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
      //  $this->middleware('auth');
      $config = Configs::all();
      Session::put('app_name',$config[0]->app_name);
      Session::put('app_group',$config[0]->app_group);
      Session::put('app_area',$config[0]->app_area);
      Session::put('app_year',$config[0]->app_year);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $config = Configs::all();
      $subjectgroup = subjectgroup::where('id','like','%')->orderby('in_dex','asc')->paginate(1000);
      $schoolgroup = schoolgroup::where('id','like','%')->paginate(1000);
      $notice_1 = Notices::where('notice_type','=',10)->where('schoolgroup','=',0)->orderBy('updated_at','desc')->paginate(1000);
      $notice_2 = Notices::where('notice_type','=',1)->orderBy('updated_at','desc')->paginate(1000);
      $Branch = Branch::where('id','like','%')->paginate(1000);

        $activitycount = DB::table('Activities')
    ->select('activity_subject', DB::raw('count(*) as total'))
    ->groupBy('activity_subject')
    ->pluck('total','activity_subject');

      $activityfinish = DB::table('Activities')
    ->select('activity_subject', DB::raw('count(*) as total'))
    ->where('activity_status','=','1')
    ->groupBy('activity_subject')
    ->pluck('total','activity_subject');
   
    $schoolgroupcount  = DB::table('schools')
    ->select('school_group', DB::raw('count(*) as total'))
    ->groupBy('school_group')
    ->pluck('total','school_group');

    $branchactivitycount = DB::table('Activities')
    ->select('activity_branch', DB::raw('count(*) as total'))
    ->groupBy('activity_branch')
    ->pluck('total','activity_branch');

    $schoolgroupactivitycount = DB::table('addschools')
    ->select('school_schoolgroup', DB::raw('count(*) as total'))
    ->groupBy('school_schoolgroup')
    ->pluck('total','school_schoolgroup');

   $selectschool = school::where('id','like','%')->pluck('school_name','id');
   //$selectschool = db::
   $schoolrank = Schoolranks::where('id','like','%')->orderBy('schoolpoint')->paginate(10);

$dbrank = DB::select("
    SELECT 
        school_name,
        COUNT(CASE WHEN student_score >= 80 THEN 1 END) as Gold,
        COUNT(CASE WHEN student_score < 80 AND student_score >= 70 THEN 1 END) as Silver,
        COUNT(CASE WHEN student_score < 70 AND student_score > 0 THEN 1 END) as Bronce
    FROM addschools
    WHERE activity_status = 1
    GROUP BY school_name
    ORDER BY Gold DESC, Silver DESC, Bronce DESC, MIN(activity_rank) ASC 
    LIMIT 10
");

  //dd($selectschool);
   $firstactivity = activities::where('id','like','%')->where('activity_status','=','1')->orderBy('updated_at','desc')->paginate(15);
    
   $countstudent = addstudent::where('type','=','0')->get()->count();
   $countteacher = addstudent::where('type','=','1')->get()->count();
   $countjow = jows::where('jow_activity','!=',null)->get()->count();
   $countjow2 = jows::where('jow_type','=','1')->get()->count();


   // นับจำนวนทีมในแต่ละกิจกรรม (Key เป็น ID กิจกรรม, Value เป็นจำนวนทีม)
$teamCount = DB::table('addschools')
    ->select('school_activity', DB::raw('count(*) as total'))
    // ->where('activity_status', 1) // เอาคอมเมนต์ออกถ้าต้องการนับเฉพาะทีมที่ยืนยันแล้ว
    ->groupBy('school_activity')
    ->pluck('total', 'school_activity');

  // นับจำนวนทีม แยกตาม "หมวดวิชา" (activity_subject)
$subjectTeamCount = DB::table('addschools')
    ->join('activities', 'addschools.school_activity', '=', 'activities.id') // เชื่อมตารางโดยใช้ ID กิจกรรม
    ->select('activities.activity_subject', DB::raw('count(*) as total'))
    ->groupBy('activities.activity_subject') // จัดกลุ่มตามชื่อหมวด
    // ->where('addschools.activity_status', 1) // (Optional) เปิดบรรทัดนี้ถ้านับเฉพาะทีมที่ยืนยันแล้ว
    ->pluck('total', 'activities.activity_subject');
  //  $countstudentwithactivity = activities::where('id','like',"%")
  //dd($countjow);

$studentBySubject = DB::table('addstudents')
    ->join('addschools', 'addstudents.school', '=', 'addschools.id') // เชื่อมไปหาทีม/กิจกรรม
    ->join('activities', 'addschools.school_activity', '=', 'activities.id') // เชื่อมไปหากลุ่มสาระ
    ->select('activities.activity_subject', DB::raw('count(addstudents.id) as total'))
    ->where('addstudents.type', '=', '0') // นับเฉพาะนักเรียน (ไม่นับครู)
    // ->where('addschools.activity_status', 1) // (Optional) ถ้านับเฉพาะนักเรียนในทีมที่ยืนยันแล้ว
    ->groupBy('activities.activity_subject')
    ->pluck('total', 'activities.activity_subject');
// นับจำนวนนักเรียน แยกตาม "สาขา/แขนง" (activity_branch)
// นับจำนวนนักเรียน แยกตาม Branch ID
// 2. นักเรียน (ไม่ซ้ำชื่อ-นามสกุล)
$studentByBranch = DB::table('addstudents')
    ->join('addschools', 'addstudents.school', '=', 'addschools.id') 
    ->join('activities', 'addschools.school_activity', '=', 'activities.id') 
    ->select('activities.activity_branch', 
        DB::raw('count(DISTINCT CONCAT(addstudents.name, addstudents.lastname)) as total'))
    ->where('addstudents.type', '=', '0') 
    ->groupBy('activities.activity_branch')
    ->pluck('total', 'activities.activity_branch');

// 3. ครูผู้ควบคุม (ไม่ซ้ำชื่อ-นามสกุล)
$teacherByBranch = DB::table('addstudents')
    ->join('addschools', 'addstudents.school', '=', 'addschools.id') 
    ->join('activities', 'addschools.school_activity', '=', 'activities.id') 
    ->select('activities.activity_branch', 
        DB::raw('count(DISTINCT CONCAT(addstudents.name, addstudents.lastname)) as total'))
    ->where('addstudents.type', '=', '1')
    ->groupBy('activities.activity_branch')
    ->pluck('total', 'activities.activity_branch');

// 4. นับจำนวนกรรมการตัดสิน แยกตาม Branch ID (จากตาราง jows)
$judgeCountByBranch = DB::table('jows')
    ->join('activities', 'jows.jow_activity', '=', 'activities.id')
    ->select('activities.activity_branch', DB::raw('count(jows.id) as total'))
    ->where('jows.jow_type', '=', '0') // สมมติ 0 คือกรรมการตัดสิน
    ->groupBy('activities.activity_branch')
    ->pluck('total', 'activities.activity_branch');

// 5. นับจำนวนกรรมการดำเนินงาน แยกตาม Branch ID (จากตาราง jows)
$staffCountByBranch = DB::table('jows')
    ->select('jow_branch', DB::raw('count(DISTINCT CONCAT(IFNULL(jow_name,""), IFNULL(jow_lastname,""))) as total'))
    ->where('jow_type', '=', '1')
    ->whereNotNull('jow_branch') // มั่นใจว่ามีรหัสศูนย์
    ->groupBy('jow_branch')
    ->pluck('total', 'jow_branch');

  //  dd($staffCountByBranch);
// --- จบส่วนที่เพิ่มใหม่ ---

// นับจำนวนกิจกรรมที่มีการลงทะเบียน แยกตามเครือข่าย (School Group)
$activityBySchoolGroup = DB::table('addschools')
    ->join('activities', 'addschools.school_activity', '=', 'activities.id')
    ->select('addschools.school_schoolgroup', DB::raw('count(DISTINCT activities.id) as total'))
    ->groupBy('addschools.school_schoolgroup')
    ->pluck('total', 'addschools.school_schoolgroup');


        $data  = array(
           'subjectgroup'=>$subjectgroup,
           'schoolgroup'=>$schoolgroup,
           'activitycount'=>$activitycount,
           'teamCount' => $teamCount, // <--- เพิ่มบรรทัดนี้
           'subjectTeamCount'=>$subjectTeamCount,
           'activityfinish'=>$activityfinish,
           'schoolgroupcount'=>$schoolgroupcount,
           'schoolgroupactivitycount'=>$schoolgroupactivitycount,
           'selectschool'=>$selectschool,
           'schoolrank'=>$schoolrank,
           'dbrank' =>$dbrank,
           'firstactivity'=> $firstactivity,
           'notice_1'=> $notice_1,
           'notice_2'=> $notice_2,
           'Branch'=>$Branch,
           'branchactivitycount'=>$branchactivitycount,
           'countstudent'=>$countstudent,
           'countteacher'=>$countteacher,
           'countjow'=>$countjow,
           'countjow2'=>$countjow2,
           'config'=>$config,
           'studentBySubject' => $studentBySubject,
           'branchactivitycount' => $branchactivitycount, 
           'studentByBranch' => $studentByBranch,
           'teacherByBranch' => $teacherByBranch,
            'judgeCountByBranch' => $judgeCountByBranch,
           'staffCountByBranch' => $staffCountByBranch,
           'activityBySchoolGroup' => $activityBySchoolGroup, // เพิ่มบรรทัดนี้
        );

 //dd($data);

           return view('home.home',$data);
    }

//////////
public function index2()
    {
      $subjectgroup = subjectgroup::where('id','like','%')->orderby('in_dex','asc')->paginate(1000);
      $schoolgroup = schoolgroup::where('id','like','%')->paginate(1000);
      $notice_1 = Notices::where('notice_type','=',10)->orderBy('updated_at','desc')->paginate(1000);
      $notice_2 = Notices::where('notice_type','=',1)->orderBy('updated_at','desc')->paginate(1000);
      $Branch = Branch::where('id','like','%')->paginate(1000);

        $activitycount = DB::table('Activities')
    ->select('activity_subject', DB::raw('count(*) as total'))
    ->groupBy('activity_subject')
    ->pluck('total','activity_subject');

      $activityfinish = DB::table('Activities')
    ->select('activity_subject', DB::raw('count(*) as total'))
    ->where('activity_status','=','1')
    ->groupBy('activity_subject')
    ->pluck('total','activity_subject');
   
    $schoolgroupcount  = DB::table('schools')
    ->select('school_group', DB::raw('count(*) as total'))
    ->groupBy('school_group')
    ->pluck('total','school_group');

    $branchactivitycount = DB::table('Activities')
    ->select('activity_branch', DB::raw('count(*) as total'))
    ->groupBy('activity_branch')
    ->pluck('total','activity_branch');

    $schoolgroupactivitycount = DB::table('addschools')
    ->select('school_schoolgroup', DB::raw('count(*) as total'))
    ->groupBy('school_schoolgroup')
    ->pluck('total','school_schoolgroup');

   $selectschool = school::where('id','like','%')->pluck('school_name','id');
   //$selectschool = db::
   $schoolrank = Schoolranks::where('id','like','%')->orderBy('schoolpoint')->paginate(10);

   $dbrank = DB::select("SELECT a.school_name , 
    (select count(activity_rank) from addschools b where b.school_name = a.school_name and b.student_score >= 80) as Gold,
    (select count(activity_rank) from addschools c where c.school_name = a.school_name and c.student_score < 80 and c.student_score >= 70) as Silver,
    (select count(activity_rank) from addschools d where d.school_name = a.school_name  and d.student_score < 70 and d.student_score > 0) as Bronce
    FROM addschools a 
    where activity_status = 1
    group by school_name
    order by Gold desc,Silver desc, Bronce desc,activity_rank asc limit 10");

  //dd($selectschool);
   $firstactivity = activities::where('id','like','%')->where('activity_status','=','1')->orderBy('updated_at','desc')->paginate(15);
    
   $countstudent = addstudent::where('type','=','0')->get()->count();
   $countteacher = addstudent::where('type','=','1')->get()->count();
   $countjow = jows::where('jow_activity','!=',null)->get()->count();
   $countjow2 = jows::where('jow_type','=','1')->get()->count();
  //  $countstudentwithactivity = activities::where('id','like',"%")
  //dd($countjow);

  /*$activityGroupCounts = DB::table('activity_group')
  ->join('activity', 'activity.group', '=', 'activity_group.id')
  ->join('addschool', 'addschool.activity', '=', 'activity.id')
  ->select('activity_group.id as group_id', 'activity_group.name as group_name', DB::raw('COUNT(addschool.id) as total'))
  ->groupBy('activity_group.id', 'activity_group.name')
  ->get(); */




        $data  = array(
           'subjectgroup'=>$subjectgroup,
           'schoolgroup'=>$schoolgroup,
           'activitycount'=>$activitycount,
           'activityfinish'=>$activityfinish,
           'schoolgroupcount'=>$schoolgroupcount,
           'schoolgroupactivitycount'=>$schoolgroupactivitycount,
           'selectschool'=>$selectschool,
           'schoolrank'=>$schoolrank,
           'dbrank' =>$dbrank,
           'firstactivity'=> $firstactivity,
           'notice_1'=> $notice_1,
           'notice_2'=> $notice_2,
           'Branch'=>$Branch,
           'branchactivitycount'=>$branchactivitycount,
           'countstudent'=>$countstudent,
           'countteacher'=>$countteacher,
           'countjow'=>$countjow,
           'countjow2'=>$countjow2,
        );

 //dd($data);

           return view('home.home2',$data);
    }

        /////////////////////////////////////////////
    public function showall_print() {
              // ใช้ with() เพื่อโหลดความสัมพันธ์มาล่วงหน้า ลดภาระ Database
              $student = addstudent::with([
                  'getaddschool.getschoolname', 
                  'getstudentactivity.getactivitysubject', 
                  'getstudentactivity.getclass', 
                  'getstudenttitle'
              ])
              ->orderBy('id', 'desc')
              ->get(); // ถ้าอยากให้ DataTable ค้นหาได้ทุกตัว ต้องใช้ get() แต่ต้องระวังถ้าข้อมูลเยอะมากจริงๆ

              $data = array(
                  'student' => $student,
              );

              return view('home.showall_print', $data);
          }
                  
         ///////////////////////////////////////////
            public function showall_print2() {
              // ใช้ with() เพื่อโหลดความสัมพันธ์มาล่วงหน้า ลดภาระ Database
              $student = addstudent::with([
                  'getaddschool.getschoolname', 
                  'getstudentactivity.getactivitysubject', 
                  'getstudentactivity.getclass', 
                  'getstudenttitle'
              ])
              ->orderBy('id', 'desc')
              ->get(); // ถ้าอยากให้ DataTable ค้นหาได้ทุกตัว ต้องใช้ get() แต่ต้องระวังถ้าข้อมูลเยอะมากจริงๆ

              $data = array(
                  'student' => $student,
              );

              return view('home.showall_print', $data);
          }

             ///////////////////////////////////////////
             public function showall_print3(){
              $student = addstudent::all();
              $addschool2 = addschool::all();
              $data = Array(
                'addschool2'=>$addschool2,
                'student'=>$student,
              );
                   return view('home.showall_print3',$data);
                 }
                 ///////////////////////////////////////////
    /////////////////////////////////////////////
    public function showall1(){
     // $addschool = addschool::where('school','like','%')->orderBy('updated_at','desc')->paginate(20);

     // $student = addschool::where('school','like','%')->get();
      $student = addschool::all();
      $addschool2 = addschool::Where('activity_status','=','1')->Where('activity_rank','<=','3')->orderBy('school_activity','desc')->paginate(50);
  
     // $student2 = addschool::where('school','=',$id)->get();
   
      $data = Array(
       // 'addschool'=>$addschool,
        'addschool2'=>$addschool2,
       // 'schoolgroup'=>$schoolgroup,
        'student'=>$student,
      );
     // dd($data);
      return view('home.showall1',$data);
    }
    ///////////////////////////////////////////
    public function viewall(){

      $subjectgroup = subjectgroup::where('id','like','%')->orderby('in_dex','asc')->paginate(1000);
      $schoolgroup = schoolgroup::where('id','like','%')->paginate(1000);
     
        $activitycount = DB::table('Activities')
    ->select('activity_subject', DB::raw('count(*) as total'))
    ->groupBy('activity_subject')
    ->pluck('total','activity_subject');

      $activityfinish = DB::table('Activities')
    ->select('activity_subject', DB::raw('count(*) as total'))
    ->where('activity_status','=','1')
    ->groupBy('activity_subject')
    ->pluck('total','activity_subject');
   
    $schoolgroupcount  = DB::table('schools')
    ->select('school_group', DB::raw('count(*) as total'))
    ->groupBy('school_group')
    ->pluck('total','school_group');

    $schoolgroupactivitycount = DB::table('addschools')
    ->select('school_schoolgroup', DB::raw('count(*) as total'))
    ->groupBy('school_schoolgroup')
    ->pluck('total','school_schoolgroup');

   $selectschool = school::where('id','like','%')->pluck('school_name','id');
   //$selectschool = db::
   $schoolrank = Schoolranks::where('id','like','%')->orderBy('schoolpoint')->paginate(2000);

   $dbrank = DB::select("
    SELECT 
        school_name,
        COUNT(CASE WHEN student_score >= 80 THEN 1 END) as Gold,
        COUNT(CASE WHEN student_score < 80 AND student_score >= 70 THEN 1 END) as Silver,
        COUNT(CASE WHEN student_score < 70 AND student_score > 0 THEN 1 END) as Bronce
    FROM addschools
    WHERE activity_status = 1
    GROUP BY school_name
    ORDER BY Gold DESC, Silver DESC, Bronce DESC, MIN(activity_rank) ASC 
    LIMIT 1000
");

 // dd($dbrank);
   
    
        $data  = array(
           'subjectgroup'=>$subjectgroup,
           'schoolgroup'=>$schoolgroup,
           'activitycount'=>$activitycount,
           'activityfinish'=>$activityfinish,
           'schoolgroupcount'=>$schoolgroupcount,
           'schoolgroupactivitycount'=>$schoolgroupactivitycount,
           'selectschool'=>$selectschool,
           'schoolrank'=>$schoolrank,
           'dbrank' =>$dbrank,
        );



      return view('home.viewall',$data);
    }
    ///////////////////////////////////////////
public function viewactivity($id){
$schoolcountactivity = DB::table('addschools')
->select('school_activity', DB::raw('count(*) as total'))
->groupBy('school_activity')
->pluck('total','school_activity');
      $activity = activities::where('activity_subject','=',$id)->orderBy('updated_at','desc')->paginate(1000);
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
      return view('home.viewactivity',$data);
    }

    //////////////
    ///////////////////////////////////////////
    public function showall_print4(){


      $duplicates = Addschool::select('school_activity', DB::raw('COUNT(*) as count'))
      ->groupBy('school_activity')
      ->having('count', '>', 3)
      ->get();


      $data = Array(
        'schoolcountactivity'=>$duplicates,
      );
      //dd($data);
      return view('home.showall_print4',$data);


          }
      
          //////////////
              ///////////////////////////////////////////
    public function showall_print8(){


      $duplicates = Addschool::select('school_activity', DB::raw('COUNT(*) as count'))
      ->groupBy('school_activity')
      ->having('count', '<=', 3)
      ->get();


      $data = Array(
        'schoolcountactivity'=>$duplicates,
      );
      //dd($data);
      return view('home.showall_print8',$data);


          }
      
          //////////////
                        ///////////////////////////////////////////
    public function printrandomz(){


      $duplicates = Addschool::select('school_activity', DB::raw('COUNT(*) as count'))
      ->groupBy('school_activity')
      ->having('count', '<=', 3)
      ->get();


      $data = Array(
        'schoolcountactivity'=>$duplicates,
      );
    //  dd($data);
      return view('centersillapa.printrandomz',$data);


          }
      
          //////////////
              ///////////////////////////////////////////
                                      ///////////////////////////////////////////
public function printpointdoc() {
    $duplicates = Addschool::select('school_activity', DB::raw('COUNT(*) as count'))
       /// ->where()
        ->groupBy('school_activity')
        // เปลี่ยนจาก 'count' เป็น DB::raw('COUNT(*)') เพื่อให้ MySQL เข้าใจตอนทำ Pagination
        ->having(DB::raw('COUNT(*)'), '>', 0) 
        ->get();
       // ->paginate(15); // กำหนดจำนวนรายการต่อหน้าที่ต้องการ

    $data = [
        'schoolcountactivity' => $duplicates,
    ];

    return view('centersillapa.printpointdoc', $data);
}
      
          //////////////
              ///////////////////////////////////////////
    public function showall_print5(){

// ค้นหา activity_name ที่ซ้ำกัน 3 ครั้ง
$activityNames = Addschool::select('school_activity')
    ->groupBy('school_activity')
    ->havingRaw('COUNT(*) <= 3') // เงื่อนไขนับเฉพาะที่น้อยกว่า 4
    ->pluck('school_activity');

   // dd($activityNames);

  $totalStudents = Addschool::withCount(['getstudentname as male_students_count' => function ($query) {
    $query->where('type', '0'); // กรองเฉพาะนักเรียน
  }])
  ->whereIn('school_activity', $activityNames) // กรองเฉพาะ activity_name ที่ซ้ำกัน 3
  ->get()
  ->sum('male_students_count');

  $totalTeacher = Addschool::withCount(['getstudentname as male_students_count' => function ($query) {
    $query->where('type', '1'); // กรองเฉพา teacher
  }])
  ->whereIn('school_activity', $activityNames) // กรองเฉพาะ activity_name ที่ซ้ำกัน 3
  ->get()
  ->sum('male_students_count');


  $activityCounts = DB::table('addschools')
  ->select(
      'school_activity',
      DB::raw('CASE WHEN COUNT(*) > 3 THEN 3 ELSE COUNT(*) END as total')
  )
  ->groupBy('school_activity')
  ->get();

  $studentCounts = DB::table('addstudents')
    ->select('activity', DB::raw('COUNT(*) as total'))
    ->groupBy('activity')
    ->get();

   // dd($studentCounts);

  $totalJow1 = Jows::where('jow_type','=',0)->count();
  $totalJow2 = Jows::where('jow_type','=',1)->count();

    $totalactivitystudent = activities::all()->pluck('activity_studennumber', 'id')->toArray();;
 
    $totalactivityteacher = activities::all()->pluck('activity_teachernumber', 'id')->toArray();;
 




      $data = Array(
        'totalStudents'=>$totalStudents,
        'totalTeachers'=>$totalTeacher,
        'totalJows1'=>$totalJow1,
        'totalJows2'=>$totalJow2,
        'activitycount' => $activityCounts,
        'totalactivitystudent'=> $totalactivitystudent ,
        'totalactivityteacher'=>$totalactivityteacher,
      );
     
  //  dd($data);

      return view('home.showall_print5',$data);


         }
        ///////////////////////////////////////////
        public function showall_print6(){


          $duplicates = Addschool::select('school_activity', DB::raw('COUNT(*) as count'))
          ->groupBy('school_activity')
          ->having('count', '>', 0)
          ->get();
    
    
          $data = Array(
            'schoolcountactivity'=>$duplicates,
          );
          //dd($data);
          return view('home.showall_print6',$data);
    
    
              }
          
              //////////////
              ///////////////////////////////////////////
              public function showall_print7(){
                $schoolcountactivity = DB::table('addschools')
                ->select('school_activity', DB::raw('count(*) as total'))
                ->groupBy('school_activity')
                ->pluck('total','school_activity');
                     // $activity = activities::all();
                     $activity = activities::whereDoesntHave('addschools')->get();
                      $subjectgroup = Subjectgroup::all();
                      $branch = Branch::all();
                      $class = Classes::all();
                      $dayofsillapa = Dayofsillapas::all();
                      $data = array(
          
                          'activity'=>$activity,
                          'subjectgroup'=>$subjectgroup,
                          'branch'=>$branch,
                          'class'=>$class,
                          'dayofsillapa'=>$dayofsillapa,
                          'schoolcountactivity'=>$schoolcountactivity,
                      );


            //    dd($data);
                return view('home.showall_print7',$data);
          
          
                    }
                
                    //////////////
          //////////////
    public function viewbranch($id){

   //   $countstudent = 
      $schoolcountactivity = DB::table('addschools')
      ->select('school_activity', DB::raw('count(*) as total'))
      ->groupBy('school_activity')
      ->pluck('total','school_activity');
            $activity = activities::where('activity_branch','=',$id)->orderBy('updated_at','desc')->paginate(1000);
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
            return view('home.viewbranch',$data);
          }
      
          //////////////

  public function viewactivitydetail($id){

         

         //     $schoolgroup = schoolgroup::where('id','=',Auth::user()->schoolgroup)->get();

              $addschool = addschool::where('school_activity','=',$id)->orderBy('student_score','desc')->paginate(1000);
          

          $activity= activities::find($id);
          $student = addschool::WITH("getstudentname")->where('school_activity','=',$id)->orderBy('id','desc')->get();
          $data = array(
              'addschool'=>$addschool,
              'student'=>$student,
            //  'schoolgroup'=>$schoolgroup,
              'activity'=>$activity,
          );
          return view('home.viewactivitydetail',$data);
  }


  /////////////////////////////////////

  public function showschool($id){
      $school = school::where('school_group','=',$id)->paginate(1000);
      $schoolcount = DB::table('addschools')
      ->select('school_name', DB::raw('count(*) as total'))
      ->groupBy('school_name')
      ->pluck('total','school_name');
      $data = Array(
        'schoolname'=>$school,
        'schoolcount'=>$schoolcount,
      );
      return view('home.viewschool',$data);
  }
    //////////////////////////////////
      /////////////////////////////////////
  public function showschooldetail($id,$schoolgroup){
 
    $addschool = addschool::where('school','=',$id)->orderBy('updated_at','desc')->paginate(1000);

    $student = addschool::where('school','=',$id)->get();

    $addschool2 = addschool::where('school','=',$id)->Where('activity_status','=','1')->Where('activity_rank','=','1')->paginate(1000);
    $cerfiticatechk = Systemactives::where('systemname','=','certificate')->paginate(10);
   // $student2 = addschool::where('school','=',$id)->get();
   $dbrank = DB::select("SELECT a.school_name , 
   (select count(activity_rank) from addschools b where b.school_name = a.school_name and b.student_score >= 80) as Gold,
   (select count(activity_rank) from addschools c where c.school_name = a.school_name and c.student_score < 80 and c.student_score >= 70) as Silver,
   (select count(activity_rank) from addschools d where d.school_name = a.school_name  and d.student_score < 70 and d.student_score > 0) as Bronce
   FROM addschools a 
   where activity_status = 1
   and school = $id
   group by school_name
   order by Gold desc,Silver desc, Bronce desc,activity_rank asc limit 10");

  $dbrank = DB::select("
    SELECT 
        school_name,
        COUNT(CASE WHEN student_score >= 80 THEN 1 END) as Gold,
        COUNT(CASE WHEN student_score < 80 AND student_score >= 70 THEN 1 END) as Silver,
        COUNT(CASE WHEN student_score < 70 AND student_score > 0 THEN 1 END) as Bronce
    FROM addschools
    WHERE activity_status = 1 and school = $id
    GROUP BY school_name
    ORDER BY Gold DESC, Silver DESC, Bronce DESC, MIN(activity_rank) ASC 
    LIMIT 10
");
    $data = Array(
      'addschool'=>$addschool,
      'addschool2'=>$addschool2,
      'schoolgroup'=>$schoolgroup,
      'student'=>$student,
      'cerfiticatechk'=>$cerfiticatechk,
      'dbrank'=>$dbrank,
    );
 //dd($data);
    return view('home.viewschooldetail',$data);
}
  //////////////////////////////////
    public function adduser()
    {
        $user = user::where('id','like','%')->paginate(1000);
        $data  = array(
          'data' => $user,
        );
        return view('adduser',$data);
    }

    /////////////////////////////////
    public function adduser_prc(Request $data)
    {
     ///dd($data);
      // return User::create([
      //     'name' => $data['name'],
      //     'lastname'=>$data['lastname'],
      //     'phonenumber'=>$data['phonenumber'],
      //     'position'=>$data['position'],
      //     'email' => $data['email'],
      //     'password' => bcrypt($data['password']),
      // ]);
      $this->validate($data,[
          'name'=>'required',
          'lastname'=>'required',
          'phonenumber'=>'required|numeric',
          'email'=>'required',
          'password'=>'required'
      ]);
      $user = new User;
      $user->name = $data->name;
      $user->lastname = $data->lastname;
      $user->phonenumber = $data->phonenumber;
      $user->position = $data ->position;
      $user->email = $data->email;
      $user->password = bcrypt($data->password);
      $user->save();
      Session::flash('massage','Success Create User !!');
      return redirect('/adduser');


    }
}
