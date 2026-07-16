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
class AddschoolController extends Controller
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
        //adminchk();
    }
    public function index()
    {

    }
    public function getschool($id){ /// ดึงข้อมูลช่ือโรงเรียนจากคอมโบบ๊อคเครือข่าย
        $school = School::where('school_group','=',$id)->pluck("school_name","id");
        return json_encode($school);

    }
    public function addaddschool($id)
    {
 
        if(Auth::user()->position==10){
            $schoolgroup = schoolgroup::all();
            $addschool = addschool::where('school_activity','=',$id)->paginate(25);
  
        }
        
        else{

            $schoolgroup = schoolgroup::where('id','=',Auth::user()->schoolgroup)->get();

            $addschool = addschool::where('school_activity','=',$id)->where('school_schoolgroup','=',$schoolgroup[0]->id)->paginate(15);
        }

        $activity= activities::find($id);
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
        $schoolgroup = schoolgroup::all();
        $addschool = addschool::where('school_activity','=',$id)->paginate(15);
        $activity= activities::find($id);
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

        ]);

//dd($request);
        $chkschool = addschool::where('school','=',$request->school_name)->where('school_activity','=',$request->school_activity)->count();
        $numschool = activities::find($request->school_activity)->activity_schoolteamnumber;
        $activityteamnumber = activities::find($request->school_activity)->activity_teamnumber;
        //dd($numschool);
        if($chkschool >= $numschool){
            Session::flash('massage','จำนวนทีมต่อโรงเรียนเกินที่กำหนด !!');
            return redirect(url()->previous());
        }
        $chktotelschool = addschool::where('school_activity','=',$request->school_activity)->where('school_schoolgroup','=',$request->school_schoolgroup)->count();
        if($chktotelschool>=$activityteamnumber){
            Session::flash('massage','จำนวนทีมต่อรายการสามารถลงได้แค่ '.$activityteamnumber.' ทีม !!');
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
        $addschool = addschool::find($id);
        $addschool->delete();
        addstudent::where('school',$id)->delete();
        Session::flash('massage','Data has been deleted..');
        return redirect(url()->previous());

    }
}
