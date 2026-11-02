<?php
namespace App\Http\Controllers\area;

use App\Http\Controllers\Controller;
use App\systemactives;
use Illuminate\Http\Request;
use App\School;
use App\Schoolgroup;
use App\Addschool;
use App\addschools_areas;
use App\addstudent;
use App\Activities;
use Session;
use Auth;
class areaAddschoolController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        
        $this->middleware('auth');
        $this->middleware('areaschoolmiddleware')->except(['getschool']);

      //  $this->middleware('areamiddleware');
        //adminchk();
    }
    public function index()
    {

    }
    public function getschool($id){ /// ดึงข้อมูลช่ือโรงเรียนจากคอมโบบ๊อคเครือข่าย

        //เชคว่าเป็นเครือข่ายหรือโรงเรียนก่อน
        if(Auth::user()->position == 5){
        $school = School::where('school_group','=',$id)
        ->pluck("school_name","id");
        }elseif(Auth::user()->position == 0){
        $school = School::where('school_group','=',$id)
        ->where('id',Auth::user()->school_id)
        ->pluck("school_name","id");
      /*  $school = School::where('school_group','=',$id)
        ->pluck("school_name","id");*/
        }


      /*  $school = School::where('school_group','=',$id)
        ->pluck("school_name","id");*/

        return json_encode($school);

    }
    public function addaddschool($id) // ดึงข้อมูลของโรงเรียนมาแสดง
    {
        //dd($id);
        if(Auth::user()->position==10){
            $schoolgroup = schoolgroup::all();
            $addschool = addschools_areas::where('school_activity','=',$id)->paginate(25);
  
        }
        
        else{

            $schoolgroup = schoolgroup::where('id','=',Auth::user()->schoolgroup)->get();

            $addschool = addschools_areas::where('school_activity','=',$id)->where('school_schoolgroup','=',$schoolgroup[0]->id)->paginate(15);
        }

        $activity= Activities::find($id);
        $student = addschools_areas::WITH("getstudentname")->where('school_activity','=',$id)->orderBy('id','desc')->get();
        $data = array(
            'addschool'=>$addschool,
            'student'=>$student,
            'schoolgroup'=>$schoolgroup,
            'activity'=>$activity,
        );

        return view('addschool.addarea',$data);
    }


    public function viewaddschool($id)
    {
        $schoolgroup = schoolgroup::all();
        $addschool = addschools_areas::where('school_activity','=',$id)->paginate(15);
        $activity= activities::find($id);
        $student = addschools_areas::WITH("getstudentname")->where('school_activity','=',$id)->orderBy('id','desc')->get();
        $data = array(
            'addschool'=>$addschool,
            'student'=>$student,
            'schoolgroup'=>$schoolgroup,
            'activity'=>$activity,
        );
        return view('addschool.view',$data);
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
            'school_schoolgroup'=>'required',
            'school_name'=>'required',

        ]);

//dd($request);
        $chkschool = addschools_areas::where('school','=',$request->school_name)->where('school_activity','=',$request->school_activity)->count();
        $numschool = activities::find($request->school_activity)->activity_schoolteamnumber;
        $activityteamnumber = activities::find($request->school_activity)->activity_teamnumber;
        //dd($numschool);
        if($chkschool >= $numschool){
            Session::flash('massage','จำนวนทีมต่อโรงเรียนเกินที่กำหนด !!');
            return redirect(url()->previous());
        }
        $chktotelschool = addschools_areas::where('school_activity','=',$request->school_activity)->where('school_schoolgroup','=',$request->school_schoolgroup)->count();
        if($chktotelschool>=$activityteamnumber){
            Session::flash('massage','จำนวนทีมต่อรายการสามารถลงได้แค่ '.$activityteamnumber.' ทีม !!');
         //   return redirect(url()->previous());
        }


        $addschool  = new addschools_areas;
        $addschool->school_schoolgroup = $request->school_schoolgroup;
        $addschool->school_name = $request->school_name;
        $addschool->school = $request->school_name;
        $addschool->school_activity = $request->school_activity;
        
        $addschool->save();
        Session::flash('massage','Success Create School !!');
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
        // อ่าน school_activity จาก DB ก่อนลบ
        $record = \Illuminate\Support\Facades\DB::table('addschools_areas')
            ->where('id', $id)
            ->first();

        // ลบ students ก่อน
        \Illuminate\Support\Facades\DB::table('addstudents_areas')
            ->where('school', $id)
            ->delete();

        // ลบ school
        \Illuminate\Support\Facades\DB::table('addschools_areas')
            ->where('id', $id)
            ->delete();

        Session::flash('massage', 'ลบข้อมูลสำเร็จ');

        // redirect กลับหน้า addaddschool ของ activity นั้นตรงๆ
        $activityId = $record ? $record->school_activity : null;
        if ($activityId) {
            return redirect('area/addaddschool/' . $activityId);
        }
        return redirect()->back();
    }
}
