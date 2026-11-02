<?php

namespace App\Http\Controllers\area;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User; // เรียกใช้ model User
use App\Purchase;
use App\Subjectgroup;
use App\Schoolgroup;
use App\addschool;
use App\addstudent;
use App\addschools_areas;
use App\addstudents_areas;
use App\Activities;
use App\activities_areas;
use App\Branch;
use App\Dayofsillapas;
use App\Classes;
use App\School;
use App\Schoolranks;
use App\Jows;
use App\Jows_areas;
use Session;
use Auth;
use DB;
use App\Notices;
use App\Systemactives;
use App\Configs;



class AreaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


   public function __construct()
    {
       // $this->middleware('auth');
       // $this->middleware('areamiddleware');
      //$this->middleware('areaschoolmiddleware');
      
  


    }



    public function index($id)
    {
        $activeYear = active_year();
        session()->put('area', $id);
        

       


        //id = schoolgroup
      $subjectgroup = subjectgroup::where('id','like','%')->orderby('in_dex','asc')->paginate(1000);
      $schoolgroup = schoolgroup::where('id','=',$id)->paginate(1000);
      $notice_1 = Notices::where('notice_type','=',10)->where('schoolgroup','=',$id)->orderBy('updated_at','desc')->paginate(1000);
      $notice_2 = Notices::where('notice_type','=',5)->where('schoolgroup','=',$id)->orderBy('updated_at','desc')->paginate(1000);
      $Branch = Branch::where('id','like','%')->paginate(1000);

      session()->put('schoolgroup',$schoolgroup[0]->schoolgroup_name);
      
        $activitycount = DB::table('Activities')
    ->select('activity_subject', DB::raw('count(*) as total'))
    ->where('year', $activeYear)
    ->groupBy('activity_subject')
    ->pluck('total','activity_subject');

$rows = DB::table('activities')
    ->join('addschools_areas', 'activities.id', '=', 'addschools_areas.school_activity')
    ->whereIn('addschools_areas.activity_status', [1, 98, 99])
    ->where('addschools_areas.school_schoolgroup', $id)
    ->where('addschools_areas.year', $activeYear)
    ->groupBy('activities.activity_subject')
    ->select(
        'activities.activity_subject',
        DB::raw('COUNT(DISTINCT activities.id) as total')
    )
    ->get();

if ($rows->isEmpty()) {
    $rows = DB::table('activities')
        ->join('addschools_areas', 'activities.id', '=', 'addschools_areas.school_activity')
        ->whereIn('addschools_areas.activity_status', [1, 98, 99])
        ->where('addschools_areas.school_schoolgroup', $id)
        ->groupBy('activities.activity_subject')
        ->select(
            'activities.activity_subject',
            DB::raw('COUNT(DISTINCT activities.id) as total')
        )
        ->get();
}

$activityfinish = $rows->pluck('total', 'activity_subject')->toArray();

    ////////////////////////
    $schoolgroupcount  = DB::table('schools')
    ->select('school_group', DB::raw('count(*) as total'))
    ->groupBy('school_group')
    ->pluck('total','school_group');

    $branchactivitycount = DB::table('Activities')
    ->select('activity_branch', DB::raw('count(*) as total'))
    ->where('year', $activeYear)
    ->groupBy('activity_branch')
    ->pluck('total','activity_branch');

    $schoolgroupactivitycount = DB::table('addschools_areas')
    ->select('school_schoolgroup', DB::raw('count(*) as total'))
    ->where('year', $activeYear)
    ->groupBy('school_schoolgroup')
    ->pluck('total','school_schoolgroup');

   $selectschool = school::where('id','like','%')->pluck('school_name','id');
   //$selectschool = db::
   $schoolrank = Schoolranks::where('id','like','%')->orderBy('schoolpoint')->paginate(10);

   $dbrank = DB::select("SELECT a.school_name , 
    (select count(activity_rank) from addschools_areas b where b.school_name = a.school_name and b.student_score >= 80 and b.year = ?) as Gold,
    (select count(activity_rank) from addschools_areas c where c.school_name = a.school_name and c.student_score < 80 and c.student_score >= 70 and c.year = ?) as Silver,
    (select count(activity_rank) from addschools_areas d where d.school_name = a.school_name  and d.student_score < 70 and d.student_score > 0 and d.year = ?) as Bronce
    FROM addschools_areas a 
    where activity_status IN (1, 98, 99) and school_schoolgroup = ? and a.year = ?
    group by school_name
    order by Gold desc,Silver desc, Bronce desc,activity_rank asc limit 10", [$activeYear, $activeYear, $activeYear, $id, $activeYear]);

  // ผลการแข่งขันล่าสุด
   $firstactivity = addschools_areas::whereIn('activity_status', [1, 98, 99])
    ->where('school_schoolgroup',session('area'))
    ->orderBy('updated_at','desc')
    ->get()
    ->unique('school_activity')
    ->values()
    ->take(15);
   // นับจำนวนทีมในแต่ละกิจกรรม (Key เป็น ID กิจกรรม, Value เป็นจำนวนทีม)
$teamCount = DB::table('addschools')
    ->select('school_activity', DB::raw('count(*) as total'))
    ->where('year', $activeYear)
    ->groupBy('school_activity')
    ->pluck('total', 'school_activity');

  // นับจำนวนทีม แยกตาม "หมวดวิชา" (activity_subject)
$subjectTeamCount = DB::table('addschools_areas')
    ->join('activities', 'addschools_areas.school_activity', '=', 'activities.id') // เชื่อมตารางโดยใช้ ID กิจกรรม
    ->select('activities.activity_subject', DB::raw('count(*) as total'))
    ->where('addschools_areas.school_schoolgroup', session('area')) 
    ->where('addschools_areas.year', $activeYear)
    ->groupBy('activities.activity_subject') // จัดกลุ่มตามชื่อหมวด
    ->pluck('total', 'activities.activity_subject');
   //dd($firstactivity);
    // จำนวนบุคคล
   $countstudent = addstudents_areas::where('type','=','0')->where('schoolgroup',$id)->get()->count();
   $countteacher = addstudents_areas::where('type','=','1')->where('schoolgroup',$id)->get()->count();
   $countjow = Jows_areas::where('jow_activity','!=',null)->where('jow_area',$id)->get()->count();
   $countjow2 = Jows_areas::where('jow_type','=','1')->where('jow_area',$id)->get()->count();
  //  $countstudentwithactivity = activities::where('id','like',"%")
  //dd($countjow);
        $data  = array(
           'subjectgroup'=>$subjectgroup,
           'schoolgroup'=>$schoolgroup,
           'activitycount'=>$activitycount,
           'activityfinish'=>$activityfinish,
           'teamCount' => $teamCount, // <--- เพิ่มบรรทัดนี้
           'subjectTeamCount'=>$subjectTeamCount,
           'schoolgroupcount'=>$schoolgroupcount,
           'schoolgroupactivitycount'=>$schoolgroupactivitycount,
           'selectschool'=>$selectschool,
           'schoolrank'=>$schoolrank,
           'dbrank' =>$dbrank,
           'firstactivity'=> $firstactivity,
           'notice_1'=> $notice_1,
           'notice_2'=> $notice_2,
           'Branch'=>$Branch,
           'countstudent'=>$countstudent,
           'countteacher'=>$countteacher,
           'countjow'=>$countjow,
           'countjow2'=>$countjow2,
         );
         return view('area.home',$data);
    }

    public function showschooldetail($id,$schoolgroup){
        $activeYear = active_year();
  
        $addschool = addschools_areas::where('school','=',$id)->orderBy('updated_at','desc')->paginate(1000);
        $student = addschools_areas::where('school','=',$id)->get();
        $addschool2 = addschools_areas::where('school','=',$id)->Where('activity_status','=','1')->Where('activity_rank','=','1')->paginate(1000);
        $cerfiticatechk = Systemactives::where('systemname','=','certificate')->paginate(10);

        $dbrank = DB::select("SELECT a.school_name , 
        (select count(activity_rank) from addschools_areas b where b.school_name = a.school_name and b.student_score >= 80 and b.year = ?) as Gold,
        (select count(activity_rank) from addschools_areas c where c.school_name = a.school_name and c.student_score < 80 and c.student_score >= 70 and c.year = ?) as Silver,
        (select count(activity_rank) from addschools_areas d where d.school_name = a.school_name  and d.student_score < 70 and d.student_score > 0 and d.year = ?) as Bronce
        FROM addschools_areas a 
        where activity_status = 1
        and school = ? and a.year = ?
        group by school_name
        order by Gold desc,Silver desc, Bronce desc,activity_rank asc limit 10", [$activeYear, $activeYear, $activeYear, $id, $activeYear]);

        $data = Array(
            'addschool'=>$addschool,
            'addschool2'=>$addschool2,
            'schoolgroup'=>$schoolgroup,
            'student'=>$student,
            'cerfiticatechk'=>$cerfiticatechk,
            'dbrank'=>$dbrank,
        );
        return view('area.viewschooldetail',$data);
    }


public function viewactivity($id){
$activeYear = active_year();
$schoolcountactivity = DB::table('addschools_areas')
->select('school_activity', DB::raw('count(*) as total'))
->where('school_schoolgroup',session('area'))
->where('year', $activeYear)
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
    //  dd($data);
      return view('area.viewactivity',$data);
    }


  public function viewactivitydetail($id){

         

         //     $schoolgroup = schoolgroup::where('id','=',Auth::user()->schoolgroup)->get();

              $addschool = addschools_areas::where('school_activity','=',$id)
              ->where('school_schoolgroup',session('area'))
              ->orderBy('student_score','desc')->paginate(1000);
          

          $activity= activities::find($id);
          $student = addschools_areas::WITH("getstudentname")->where('school_activity','=',$id)
          ->where('school_schoolgroup',session('area'))
          ->orderBy('id','desc')->get();
          $data = array(
              'addschool'=>$addschool,
              'student'=>$student,
            //  'schoolgroup'=>$schoolgroup,
              'activity'=>$activity,
          );
          return view('area.viewactivitydetail',$data);
  }


  public function areaviewactivitydetail($area,$id){

         

         //     $schoolgroup = schoolgroup::where('id','=',Auth::user()->schoolgroup)->get();

              $addschool = addschools_areas::where('school_activity','=',$id)
              ->where('school_schoolgroup',$area)
              ->orderBy('student_score','desc')->paginate(1000);
          

          $activity= activities::find($id);
          $student = addschools_areas::WITH("getstudentname")->where('school_activity','=',$id)
          ->where('school_schoolgroup',$area)
          ->orderBy('id','desc')->get();
          $data = array(
              'addschool'=>$addschool,
              'student'=>$student,
            //  'schoolgroup'=>$schoolgroup,
              'activity'=>$activity,
          );
          return view('area.areaviewactivitydetail',$data);
  }

  public function showschool($id){
      $activeYear = active_year();
      $school = school::where('school_group','=',$id)->paginate(1000);
      $schoolcount = DB::table('addschools_areas')
      ->select('school_name', DB::raw('count(*) as total'))
        ->where('school_schoolgroup',session('area'))
        ->where('year', $activeYear)
      ->groupBy('school_name')
      ->pluck('total','school_name');
      $data = Array(
        'schoolname'=>$school,
        'schoolcount'=>$schoolcount,
      );
      return view('area.viewschool',$data);
  }




 /////////////////////////////////////////////
        public function showall_print($id){
      $student = addstudents_areas::all();
      $student = addstudents_areas::where('schoolgroup',$id)->get();
    //  $addschool2 = addschool::Where('activity_status','=','1')->Where('activity_rank','<=','3')->orderBy('school_activity','desc')->paginate(50);
      $data = Array(
      //  'addschool2'=>$addschool2,
        'student'=>$student,
      );

     // dd($data);
           return view('area.showall_print',$data);
         }
         
         ///////////////////////////////////////////
         public function showall_print2($id){
            $student = addstudents_areas::where('schoolgroup',$id)->get();
        //  $addschool2 = addschool::orderBy('school_activity','desc')->paginate(50);
          $data = Array(
         //   'addschool2'=>$addschool2,
            'student'=>$student,
          );
               return view('area.showall_print2',$data);
             }



             
             ///////////////////////////////////////////
             public function showall_print3($id){
              $student = addstudents_areas::where('schoolgroup',$id)->get();
              $addschool2 = addstudents_areas::where('schoolgroup',$id)->get();
              $data = Array(
                'addschool2'=>$addschool2,
                'student'=>$student,
              );
                   return view('area.showall_print3',$data);
                 }


                 ///////////////////////////////////////////

    public function showall_print4($id){


      $duplicates = addschools_areas::select('school_activity', DB::raw('COUNT(*) as count'))
      ->where('school_schoolgroup',$id)
      ->groupBy('school_activity')
      ->having('count', '>', 3)
      ->get();


      $data = Array(
        'schoolcountactivity'=>$duplicates,
        'area'=>$id,
      );
      //dd($data);
      return view('area.showall_print4',$data);


          }


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


  $activeYear = active_year();
  $activityCounts = DB::table('addschools')
  ->select(
      'school_activity',
      DB::raw('CASE WHEN COUNT(*) > 3 THEN 3 ELSE COUNT(*) END as total')
  )
  ->where('year', $activeYear)
  ->groupBy('school_activity')
  ->get();

  $studentCounts = DB::table('addstudents')
    ->select('activity', DB::raw('COUNT(*) as total'))
    ->where('year', $activeYear)
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

      return view('area.showall_print5',$data);


         }
        ///////////////////////////////////////////
        public function showall_print6($id){


          $duplicates = addschools_areas::select('school_activity', DB::raw('COUNT(*) as count'))
          ->where('school_schoolgroup',$id)
          ->groupBy('school_activity')
          ->having('count', '>', 0)
          ->get();
    
    
          $data = Array(
            'schoolcountactivity'=>$duplicates,
            'area'=>$id,
          );
          //dd($data);
          return view('area.showall_print6',$data);
    
    
              }
          
              //////////////
              ///////////////////////////////////////////
              public function showall_print7($id){
                $activeYear = active_year();
                $schoolcountactivity = DB::table('addschools_areas')
                ->select('school_activity', DB::raw('count(*) as total'))
                ->where('year', $activeYear)
                ->groupBy('school_activity')
                ->pluck('total','school_activity');
                     // $activity = activities::all();
                     $activity = activities::whereDoesntHave('addschools_areas')->get();
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
                          'area'=>$id,
                      );


            //    dd($data);
                return view('area.showall_print7',$data);
          
          
                    }
                
    public function showall_print8($id){


      $duplicates = addschools_areas::select('school_activity', DB::raw('COUNT(*) as count'))
      ->where('school_schoolgroup',$id)
      ->groupBy('school_activity')
      ->having('count', '<=', 3)
      ->get();


      $data = Array(
        'schoolcountactivity'=>$duplicates,
        'area'=>$id,
      );
      //dd($data);
      return view('area.showall_print8',$data);


          }

                    //////////////

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
