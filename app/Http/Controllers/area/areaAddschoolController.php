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
use App\Http\Controllers\Concerns\AuthorizesNetworkRegistration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
class areaAddschoolController extends Controller
{
    use AuthorizesNetworkRegistration;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        
        $this->middleware('auth');
        $this->middleware('competition.access:network');
        $this->middleware('areaschoolmiddleware')->except(['getschool']);

      //  $this->middleware('areamiddleware');
        //adminchk();
    }
    public function index()
    {

    }
    public function getschool($id){ /// ดึงข้อมูลช่ือโรงเรียนจากคอมโบบ๊อคเครือข่าย

        //เชคว่าเป็นเครือข่ายหรือโรงเรียนก่อน
        $groupId = $this->registrationGroupId();
        $school = School::inNetwork($groupId)
        ->pluck("school_name","id");
        if(Auth::user()->position == 0){
        $school = School::inNetwork($groupId)
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
        $groupId = $this->registrationGroupId();
        $schoolgroup = schoolgroup::whereKey($groupId)->get();
        $addschool = addschools_areas::where('school_activity', $id)
            ->where('school_schoolgroup', $groupId)
            ->when((int) Auth::user()->position === 0, fn ($query) => $query->where('school', Auth::user()->school_id))
            ->paginate(25);

        $activity= Activities::findOrFail($id);
        $student = addschools_areas::WITH("getstudentname")
            ->where('school_activity', $id)
            ->where('school_schoolgroup', $groupId)
            ->when((int) Auth::user()->position === 0, fn ($query) => $query->where('school', Auth::user()->school_id))
            ->orderBy('id','desc')->get();
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
            'school_name'=>'required',
            'school_activity'=>'required|integer',

        ]);

        $school = $this->authorizedRegistrationSchool((int) $request->school_name);
        $activity = Activities::findOrFail((int) $request->school_activity);

//dd($request);
        $chkschool = addschools_areas::where('school','=',$request->school_name)->where('school_activity','=',$request->school_activity)->count();
        $numschool = $activity->activity_schoolteamnumber;
        $activityteamnumber = $activity->activity_teamnumber;
        //dd($numschool);
        if($chkschool >= $numschool){
            Session::flash('massage','จำนวนทีมต่อโรงเรียนเกินที่กำหนด !!');
            return redirect(url()->previous());
        }
        $chktotelschool = addschools_areas::where('school_activity','=',$request->school_activity)->where('school_schoolgroup','=',$this->registrationGroupId())->count();
        if($chktotelschool>=$activityteamnumber){
            Session::flash('massage','จำนวนทีมต่อรายการสามารถลงได้แค่ '.$activityteamnumber.' ทีม !!');
         //   return redirect(url()->previous());
        }


        $addschool  = new addschools_areas;
        $addschool->school_schoolgroup = $this->registrationGroupId();
        $addschool->school_name = $school->id;
        $addschool->school = $school->id;
        $addschool->school_activity = $request->school_activity;
        
        $addschool->save();
        Log::notice('Network registration team created', ['user_id' => Auth::id(), 'ip' => $request->ip(), 'team_id' => $addschool->id, 'school_id' => $school->id, 'schoolgroup_id' => $this->registrationGroupId()]);
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
        $record = $this->authorizedRegistrationTeam((int) $id);

        DB::transaction(function () use ($record): void {
            \App\addstudents_areas::where('school', $record->id)->delete();
            $record->delete();
        });
        Log::notice('Network registration team deleted', ['user_id' => Auth::id(), 'ip' => request()->ip(), 'team_id' => $record->id, 'school_id' => $record->school, 'schoolgroup_id' => $record->school_schoolgroup]);

        Session::flash('massage', 'ลบข้อมูลสำเร็จ');

        // redirect กลับหน้า addaddschool ของ activity นั้นตรงๆ
        $activityId = $record->school_activity;
        if ($activityId) {
            return redirect('area/addaddschool/' . $activityId);
        }
        return redirect()->back();
    }
}
