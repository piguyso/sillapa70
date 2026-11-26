<?php

namespace App\Http\Controllers\area;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session;
use Auth;
use DB;
use App\Activities;
use App\Subjectgroup;
use App\Branch;
use App\Classes;
use App\Addschool;
use App\addschools_areas;
use App\dayofsillapas;
use App\systemactives;
use App\addstudent;
use App\addstudents_areas;
use App\titles;
use App\Services\CompetitionScoreService;
class areaCentersillapaController extends Controller
{
    public function __construct()
    {
      
        $this->middleware('auth');
     $this->middleware('competition.access:network');
     //   $this->middleware('centermiddleware');
     $this->middleware(['areaweb', 'score.network', 'schoolgroup.context']);
        

    
   
    }
    public function index()
    {


      
        $activityinbranch = activities::all()->groupBy('activity_subject');
  
        $activeYear = active_year();
        $activitycount = DB::table('Activities')
        ->select('activity_subject', DB::raw('count(*) as total'))
        ->where('year', $activeYear)
        ->groupBy('activity_subject')
        ->pluck('total','activity_subject');
        $activityactivecount = DB::table('Activities')
        ->select('activity_subject', DB::raw('count(*) as total'))
        ->where('year', $activeYear)
        ->groupBy('activity_subject')
        ->pluck('total','activity_subject');

        $data= array(
            'activityinbranch'=> $activityinbranch,
            'activitycount'=>$activitycount,
            'activityactivecount'=>$activityactivecount,
        );
        
        return view('centersillapa.area.add',$data);
    }

    public function centerviewactivity($id){
             //dd($id);

             $activeYear = active_year();
             $schoolcountactivity = DB::table('addschools')
             ->select('school_activity', DB::raw('count(*) as total'))
             ->where('year', $activeYear)
             ->groupBy('school_activity')
             ->pluck('total','school_activity');
     
            // dd($schoolcountactivity);
     
             $activity = activities::where('activity_subject','=',$id)->where('activity_branch','=',Auth::user()->branch)->paginate(40);
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
            // dd($data);
             return view('centersillapa.area.centerviewactivity',$data);
    }

    public function centerviewactivityschool($id){
        $this->authorizeActivity((int) $id);
        $addschool = addschools_areas::where('school_activity','=',$id)->where('school_schoolgroup',$this->currentSchoolgroupId())->orderBy('student_score','desc')->paginate(40);
          

        $activity= activities::findOrFail($id);
        $activity->activity_status = $addschool->first()->activity_status ?? 0;
        $student = addschools_areas::WITH("getstudentname")->where('school_activity','=',$id)->orderBy('id','desc')->get();
        $data = array(
            'addschool'=>$addschool,
            'student'=>$student,
          //  'schoolgroup'=>$schoolgroup,
            'activity'=>$activity,
        );
        return view('centersillapa.area.centerviewactivityschool',$data);
    }

    public function centereditactiviy($id){
        $activity = $this->authorizeActivity((int) $id);
        //  $subjectgroup = Subjectgroup::find($id);
          $branch = Branch::all();
          $class = Classes::all();
          $dayofsillapa = Dayofsillapas::all();
          $data = array(
            'activity'=>$activity,
        //   'subjectgroup'=>$subjectgroup,
            'branch'=>$branch,
            'class'=>$class,
            'dayofsillapa'=>$dayofsillapa,
            'id'=>$id
          );
         // dd($data);
          return view('centersillapa.edit',$data);
    }
    public function centereditstudent($id){
        $this->authorizeTeam((int) $id);
        $addschool = addschools_areas::where('id','=',$id)->get();
        $addstudent = addstudents_areas::where('school','=',$id)->where('type','=','0')->paginate(40);
        $addteacher = addstudents_areas::where('school','=',$id)->where('type','=','1')->paginate(40);
        $data = array(
            'addschool'=>$addschool,
            'addstudent'=> $addstudent,
            'addteacher'=> $addteacher,
        );
        //dd($data);
       return view('centersillapa.centereditstudent',$data);
    }
    public function centereditstudentname($id){
        $addstudent = addstudents_areas::findOrFail($id);
        $this->authorizeTeam((int) $addstudent->school);
        $addschool = addschools_areas::where('id','=',$addstudent->school)->get();
        $title = titles::all();
        $data = array(
            'addstudent'=>$addstudent,
            'addschool'=>$addschool,
            'title'=>$title,
        );
      //  dd($data);
        return view('centersillapa.centereditstudentname',$data);

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
        $this->authorizeActivity((int) $request->input('id'));
        $this->guardAnnouncementCancel((int) $request->input('id'), (int) $request->input('activity_chk'), $request);
        try {
            app(CompetitionScoreService::class)->process(
                addschools_areas::class,
                (int) $request->input('id'),
                (array) $request->input('activity_score', []),
                (int) $request->input('activity_chk'),
                $this->currentSchoolgroupId(),
                false,
                true,
                \App\Services\TransferReadinessService::representativeCount() + 1,
            );
        } catch (\Illuminate\Validation\ValidationException $e) {
            Session::flash('massage', 'ไม่สามารถบันทึก/ประกาศผลได้: '.collect($e->errors())->flatten()->first());

            return redirect(url()->previous())->withInput();
        }

        Session::flash('massage', 'บันทึกและจัดลำดับผลการแข่งขันเรียบร้อยแล้ว');
        return redirect(url()->previous());

        // Legacy score processing below is unreachable and will be removed after rollout.
      //  dd($request);
              //  $chkactivity_status = activities::find($request->id)->activity_status;
               // dd($chkactivity_status);
             //  if($chkactivity_status=='1'){
             //   Session::flash('massage','รายการแข่งขันนี้มีการประกาศผลไปแล้ว..');
              //  return redirect('/centerviewactivityschool/'.$request->id);
             //  }


                foreach(array_keys($request->activity_score) as $nmkey)
                {
                    if($request->activity_score[$nmkey] > 100 || $request->activity_score[$nmkey] < 0 ){
                         Session::flash('massage','กรอกคะแนนไม่ถูกต้อง..');
                            return redirect(url()->previous());
                    }
                }

               
                if($request->activity_chk=='0'){

/*
                     foreach(array_keys($request->activity_score) as $nmkey)
                            {
                                echo $nmkey."->".$request->activity_score[$nmkey]."rank :".$request->activity_rank1[$nmkey]."<br>";

                                addschools_areas::where('id', '=',$nmkey)->update(['student_score' => $request->activity_score[$nmkey]]);
                              //  addschools_areas::where('id', '=',$nmkey)->update(['activity_rank' => $request->activity_rank1[$nmkey]]);
                                addschools_areas::where('id', '=',$nmkey)->update(['activity_rank' => $request->activity_rank1[$nmkey]]);
                            }


                    Session::flash('massage','บันทึกคะแนนการแข่งขันเรียบร้อยแล้ว..1');
                     return redirect(url()->previous());*/

                    // 1. ดึงคะแนนออกมา และเรียงลำดับจากมากไปน้อย (descending)
                        $scores = $request->activity_score; 
                        arsort($scores); // ฟังก์ชันนี้จะเรียงค่าจากมากไปน้อย โดยที่ยังรักษา Key (ID) ไว้

                        $rank = 1;
                        $prevScore = null;
                        $actualRank = 1;

                        foreach ($scores as $id => $currentScore) {
                            // (Optional) กรณีคะแนนเท่ากัน ให้ลำดับที่เท่ากัน
                            if ($currentScore !== $prevScore) {
                                $actualRank = $rank;
                            }

                            // 2. อัปเดตทั้งคะแนนและลำดับที่ในคำสั่งเดียว (เพื่อลดภาระ Database)
                            addschools_areas::where('id', $id)->update([
                                'student_score' => $currentScore,
                                'activity_rank'  => $actualRank,
                                'activity_status' => 0,
                            ]);

                            $prevScore = $currentScore;
                            $rank++;
                        }

                        Session::flash('massage','บันทึกและจัดลำดับคะแนนเรียบร้อยแล้ว');
                        return redirect(url()->previous());



                }elseif($request->activity_chk=='1'){
                    foreach(array_keys($request->activity_score) as $nmkey)
                            {
                                addschools_areas::where('id', '=',$nmkey)->update(['student_score' => $request->activity_score[$nmkey]]);
                                addschools_areas::where('id', '=',$nmkey)->update(['activity_rank' => $request->activity_rank1[$nmkey]]);
                                addschools_areas::where('id', '=',$nmkey)->update(['activity_status' => '1']);
            
                            } 
                    Session::flash('massage','ประกาศผลการแข่งขันเรียบร้อยแล้ว..');
                     return redirect(url()->previous());

                }elseif($request->activity_chk=='2'){
                     {
                                //echo $nmkey."->".$request->activity_score[$nmkey]."<br>";
                            foreach(array_keys($request->activity_score) as $nmkey){
                                addschools_areas::where('id', '=',$nmkey)->update(['student_score' => '0']);
                                addschools_areas::where('id', '=',$nmkey)->update(['activity_rank' => '0']);
                                addschools_areas::where('id', '=',$nmkey)->update(['activity_status' => '0']);
                            }
                            Session::flash('massage','ยกเลิกผลการแข่งขันเรียบร้อยแล้ว..');
                             return redirect(url()->previous());
                      }
                  }elseif($request->activity_chk=='98'){
                     {
                                //echo $nmkey."->".$request->activity_score[$nmkey]."<br>";
                            foreach(array_keys($request->activity_score) as $nmkey){
                                addschools_areas::where('id', '=',$nmkey)->update(['student_score' => '80']);
                                addschools_areas::where('id', '=',$nmkey)->update(['activity_rank' => '98']);
                                addschools_areas::where('id', '=',$nmkey)->update(['activity_status' => '98']);

                            }
                            Session::flash('massage','ประกาศผลการแข่งขันแบบไม่มีการแข่งขันเรียบร้อยแล้ว..');
                             return redirect(url()->previous());
                      }
                  }elseif($request->activity_chk=='99'){
                     {
                                //echo $nmkey."->".$request->activity_score[$nmkey]."<br>";
                            foreach(array_keys($request->activity_score) as $nmkey){
                                addschools_areas::where('id', '=',$nmkey)->update(['student_score' => '80']);
                                addschools_areas::where('id', '=',$nmkey)->update(['activity_rank' => '99']);
                                addschools_areas::where('id', '=',$nmkey)->update(['activity_status' => '99']);
                            }
                            Session::flash('massage','ประกาศผลการแข่งขันแบบไม่มีการแข่งขันเรียบร้อยแล้ว..');
                             return redirect(url()->previous());
                      }

                }else{
                    Session::flash('massage','ไม่สามารถบันทึกข้อมูลได้กรุณาล้างประวัติของบราวเซอร์ของท่าน..');
                     return redirect(url()->previous());
                }
                    
              //  return redirect('/centerviewactivityschool/'.$request->id);


    }

    public function updatescore(Request $request) //ไม่ได้ใช้แล้ว ไปใช้ข้างบนแทน
    {
        $this->authorizeActivity((int) $request->id);
        abort(410, 'กรุณาบันทึกและประกาศผลผ่านหน้าจัดการแข่งขัน เพื่อให้ระบบคำนวณอันดับใหม่');
       // dd($request);
        $activity = activities::find($request->id);
        $activity->activity_status = $request->activity_status;
        $activity->save();
        Session::flash('massage','ประกาศผลการแข่งขันเรียบร้อยแล้ว..');
        return redirect('/centerviewactivityschool/'.$request->id);
    }
    public function updatestudentname(Request $request, $id)
    {
       // dd($request);
        $this->validate($request,[
            'name'=>'required',
            'lastname'=>'required',
          
        ]);
        $addstudent = addstudents_areas::findOrFail($id);
        $this->authorizeTeam((int) $addstudent->school);
        $addstudent->name = $request->name;
        $addstudent->lastname = $request->lastname;
        $addstudent->title = $request->title;
        $addstudent->save();
        Session::flash('massage','Data update Successfull..');
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
        $this->authorizeActivity((int) $id);
        $this->validate($request,[
            'activity_name'=>'required',
            'activity_branchroom'=>'required',
            'activity_day'=>'required',
            'activity_time'=>'required',
          
        ]);
        $activity = activities::find($id);
       // $activity->activity_name = $request->activity_name;
       // $activity->activity_class = $request->activity_class;
       // $activity->activity_studennumber = $request->activity_studennumber;
       // $activity->activity_teachernumber = $request->activity_teachernumber;
       // $activity->activity_teamnumber = $request->activity_teamnumber;
       // $activity->activity_schoolteamnumber = $request->activity_schoolteamnumber;
       // $activity->activity_branch = $request->activity_branch;
        $activity->activity_branchroom = $request->activity_branchroom;
        $activity->activity_day = $request->activity_day;
        $activity->activity_time = $request->activity_time;
       // $activity->activity_status = $request->activity_status;
        
        $activity->save();
        Session::flash('massage','Data update Successfull..');
        return redirect('centerviewactivity/'.$request->subjectgroupid);
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

    /**
     * ยกเลิกผลที่ประกาศแล้ว (สถานะ 1/98/99): เฉพาะเครือข่ายและผู้ดูแลระบบ
     * และต้องยังไม่ได้ส่งทีมของรายการนี้เข้าระดับเขตพื้นที่
     */
    private function guardAnnouncementCancel(int $activityId, int $action, Request $request): void
    {
        if ($action !== 2) {
            return;
        }
        $groupId = $this->currentSchoolgroupId();
        $announced = addschools_areas::where('school_activity', $activityId)
            ->where('school_schoolgroup', $groupId)
            ->whereIn('activity_status', [1, 98, 99])->exists();
        if (! $announced) {
            return;
        }
        abort_unless(in_array((int) $request->user()->position, [5, 10], true), 403,
            'ยกเลิกผลที่ประกาศแล้วได้เฉพาะเครือข่ายสถานศึกษาหรือผู้ดูแลระบบ');
        $transferred = \App\Addschool::where('school_activity', $activityId)
            ->where('school_schoolgroup', $groupId)->exists();
        if ($transferred) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'activity_chk' => 'ยกเลิกไม่ได้ เพราะส่งทีมของรายการนี้เข้าระดับเขตพื้นที่แล้ว',
            ]);
        }
        \Illuminate\Support\Facades\Log::notice('Network announcement cancelled', [
            'user_id' => $request->user()->id, 'activity_id' => $activityId,
            'schoolgroup_id' => $groupId, 'ip' => $request->ip(),
        ]);
    }

    private function authorizeActivity(int $activityId): Activities
    {
        $activity = Activities::findOrFail($activityId);
        if ((int) Auth::user()->position === 1
            && (int) $activity->activity_branch !== (int) Auth::user()->branch) {
            abort(403, 'ไม่มีสิทธิ์จัดการรายการแข่งขันของสาขานี้');
        }

        return $activity;
    }

    private function authorizeTeam(int $teamId): addschools_areas
    {
        $team = addschools_areas::findOrFail($teamId);
        if ((int) $team->school_schoolgroup !== $this->currentSchoolgroupId()) {
            abort(403, 'ไม่มีสิทธิ์จัดการทีมของเครือข่ายอื่น');
        }
        $this->authorizeActivity((int) $team->school_activity);

        return $team;
    }

    private function currentSchoolgroupId(): int
    {
        return (int) (request()->attributes->get('schoolgroup_id')
            ?: ((int) Auth::user()->position >= 10 ? session('area') : Auth::user()->schoolgroup));
    }
}
