<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\School;
use App\Schoolgroup;
use App\Addschool;
use App\addstudent;
use App\Activities;
use Session;
use Auth;
use App\Http\Controllers\Concerns\AuthorizesDistrictBranch;
class AddschoolController extends Controller
{
    use AuthorizesDistrictBranch;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('competition.access:district');
        // ทีมระดับเขตพื้นที่มาจากการโอนข้อมูลระดับเครือข่ายเป็นหลัก
        // การลงทะเบียนเพิ่ม/ลบ ทำได้เฉพาะศูนย์การแข่งขัน (1, เฉพาะสาขาตนเอง) และผู้ดูแลระบบ (10)
        $this->middleware('score.district');
        //adminchk();
    }
    public function index()
    {

    }
    public function getschool($id){ /// ดึงข้อมูลช่ือโรงเรียนจากคอมโบบ๊อคเครือข่าย
        $school = School::inNetwork($id)->pluck("school_name","id");
        return json_encode($school);

    }
    public function addaddschool($id)
    {
        // ศูนย์การแข่งขันลงทะเบียนเพิ่มได้ทุกเครือข่าย แต่เฉพาะกิจกรรมในสาขาของตนเอง
        $activity = $this->authorizeDistrictActivity($id);
        $schoolgroup = schoolgroup::all();
        $addschool = addschool::where('school_activity','=',$id)->paginate(25);

        $student = addschool::WITH("getstudentname")->where('school_activity','=',$id)->orderBy('id','desc')->get();
        $data = array(
            'addschool'=>$addschool,
            'student'=>$student,
            'schoolgroup'=>$schoolgroup,
            'activity'=>$activity,
        );
        return view('addschool.add',$data);
    }


    public function viewaddschool($id)
    {
        $activity = $this->authorizeDistrictActivity($id);
        $schoolgroup = schoolgroup::all();
        $addschool = addschool::where('school_activity','=',$id)->paginate(15);
        $student = addschool::WITH("getstudentname")->where('school_activity','=',$id)->orderBy('id','desc')->get();
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
            'school_activity'=>'required|integer',
        ]);
        $activity = $this->authorizeDistrictActivity($request->school_activity);

        // โรงเรียนต้องสังกัดเครือข่ายที่ส่งมาจริง (ไม่เชื่อค่าจากฟอร์มอย่างเดียว)
        if (! School::whereKey((int) $request->school_name)->inNetwork((int) $request->school_schoolgroup)->exists()) {
            Session::flash('massage','โรงเรียนที่เลือกไม่ได้สังกัดเครือข่ายนี้ !!');
            return redirect(url()->previous());
        }

        $chkschool = addschool::where('school','=',$request->school_name)->where('school_activity','=',$request->school_activity)->count();
        $numschool = $activity->activity_schoolteamnumber;
        //dd($numschool);
        if($chkschool >= $numschool){
            Session::flash('massage','จำนวนทีมต่อโรงเรียนเกินที่กำหนด !!');
            return redirect(url()->previous());
        }
        $chktotelschool = addschool::where('school_activity','=',$request->school_activity)->where('school_schoolgroup','=',$request->school_schoolgroup)->count();
        $representatives = \App\Services\TransferReadinessService::representativeCount();
        if($chktotelschool>=$representatives){
            Session::flash('massage','เครือข่ายนี้ส่งตัวแทนเข้าระดับเขตได้แค่ '.$representatives.' ทีมต่อรายการ !!');
            return redirect(url()->previous());
        }


        $addschool  = new addschool;
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
        $addschool = $this->authorizeDistrictTeam($id);
        $addschool->delete();
        addstudent::where('school',$id)->delete();
        Session::flash('massage','Data has been deleted..');
        return redirect(url()->previous());

    }
}
