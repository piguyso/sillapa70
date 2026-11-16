<?php

namespace App\Http\Controllers\area;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\addschool;
use App\addschools_areas;
use App\addstudents_areas;
use App\activities_areas;
use App\addstudent;
use App\Activities;
use App\titles;
use Auth;
use Session;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Concerns\AuthorizesNetworkRegistration;
use Illuminate\Support\Facades\Log;
use App\Services\ImageUploadService;
class areaAddstudentController extends Controller
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
     ///   $this->middleware('areamiddleware');
        //adminchk();
    }
    public function index()
    {
        
    }
    public function addaddstudent($id)
    {
        $team = $this->authorizedRegistrationTeam((int) $id);
        $addschool = collect([$team]);
        $addstudent = addstudents_areas::where('school','=',$id)->where('type','=','0')->paginate(20);
        $addteacher = addstudents_areas::where('school','=',$id)->where('type','=','1')->paginate(20);
        $title = titles::all();
        $data = array(
            'title'=>$title,
            'addschool'=>$addschool,
            'addstudent'=> $addstudent,
            'addteacher'=> $addteacher,
        );
      //  dd($addschool[0]->school);
     //   dd($data);
       return view('addstudent.addarea',$data);

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
       $this->validate($request, [
        'name' => 'required',
        'lastname' => 'required',
        'school' => 'required|integer',
        'type' => 'required|integer|in:0,1',
       ]);

       $team = $this->authorizedRegistrationTeam((int) $request->school);
       $activity = Activities::findOrFail((int) $team->school_activity);
       $chkstudent = addstudents_areas::where('activity', $activity->id)->where('school', $team->id)->where('type', 0)->count();
       $chkteacher = addstudents_areas::where('activity', $activity->id)->where('school', $team->id)->where('type', 1)->count();

       if((int) $request->type === 0){
        if($chkstudent >= $activity->activity_studennumber){
            Session::flash('massage','จำนวนของนักเรียนเกินกว่าที่เกณฑ์กำหนด !!');
            return redirect(url()->previous());
            }
       }
       elseif((int) $request->type === 1){
        if($chkteacher >= $activity->activity_teachernumber){
            Session::flash('massage','จำนวนของครูผู้ควบคุมเกินกว่าที่เกณฑ์กำหนด !!');
            return redirect(url()->previous());
        }
       }

        $addstudent  = new addstudents_areas;
        $addstudent->title = $request->title;
        $addstudent->name = $request->name;
        $addstudent->lastname = $request->lastname;
        $addstudent->activity = $activity->id;
        $addstudent->school = $team->id;
        $addstudent->type = $request->type;
        $addstudent->schoolgroup = $team->school_schoolgroup;
        
        $addstudent->save();
        Log::notice('Network participant created', ['user_id' => Auth::id(), 'ip' => $request->ip(), 'person_id' => $addstudent->id, 'team_id' => $team->id, 'school_id' => $team->school]);
        Session::flash('massage','เพิ่มข้อมูลสำเร็จ !!');
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
      //  dd($id);
        $addstudent = $this->authorizedRegistrationPerson((int) $id);
        $addschool = collect([$this->authorizedRegistrationTeam((int) $addstudent->school)]);
        $title = titles::all();

        $data = array(
            'addstudent'=>$addstudent,
            'addschool'=>$addschool,
            'title'=>$title,
        );
       // dd($data);
        return view('addstudent.editarea',$data);
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
                $this->validate($request, [
                    'name' => 'required',
                    'lastname' => 'required',
                ]);

                $addstudent = $this->authorizedRegistrationPerson((int) $id);
                $team = $this->authorizedRegistrationTeam((int) $addstudent->school);
                $before = $addstudent->only(['title', 'name', 'lastname', 'student_image']);
                $addstudent->name = $request->name;
                $addstudent->lastname = $request->lastname;
                $addstudent->title = $request->title;

                // เช็คว่ามีการส่งรูปภาพ Base64 มาหรือไม่
                if ($request->image_base64) {
                    $year = $addstudent->year ?: active_year();
                    $addstudent->student_image = app(ImageUploadService::class)->storeBase64Webp(
                        $request->image_base64, 'picture', (string) $year, 'network_'.$id, $addstudent->student_image, 800
                    );
                }

                $addstudent->save();
                Log::notice('Network participant updated', ['user_id' => Auth::id(), 'ip' => $request->ip(), 'person_id' => $addstudent->id, 'team_id' => $team->id, 'before' => $before, 'after' => $addstudent->only(['title', 'name', 'lastname', 'student_image'])]);

                Session::flash('massage', 'แก้ไขข้อมูลสำเร็จ..');
                return redirect('area/addaddstudent/' . $team->id);
            }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $addstudent = $this->authorizedRegistrationPerson((int) $id);
        $team = $this->authorizedRegistrationTeam((int) $addstudent->school);
        $snapshot = $addstudent->only(['id', 'title', 'name', 'lastname', 'type']);
        $addstudent->delete();
        Log::notice('Network participant deleted', ['user_id' => Auth::id(), 'ip' => request()->ip(), 'team_id' => $team->id, 'person' => $snapshot]);
        Session::flash('massage','ลบข้อมูลสำเร็จ..');
        $redirectUrl = request()->input('redirect_url', url()->previous());
        return redirect($redirectUrl);
    }
}
