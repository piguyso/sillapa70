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
use App\titles;
class areaCentersillapaController extends Controller
{
    public function __construct()
    {
      
        $this->middleware('auth');
     //   $this->middleware('centermiddleware');
     $this->middleware('areaweb');
        

    
   
    }
    public function index()
    {


      
        $activityinbranch = activities::all()->groupBy('activity_subject');
  
        $activitycount = DB::table('Activities')
        ->select('activity_subject', DB::raw('count(*) as total'))
        
        ->groupBy('activity_subject')
        ->pluck('total','activity_subject');
        $activityactivecount = DB::table('Activities')
        ->select('activity_subject', DB::raw('count(*) as total'))
        //->where('activity_branch','=',Auth::user()->branch)->where('activity_status','=',1)
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

             $schoolcountactivity = DB::table('addschools')
             ->select('school_activity', DB::raw('count(*) as total'))
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
        $addschool = addschools_areas::where('school_activity','=',$id)->where('school_schoolgroup',Auth::user()->schoolgroup)->orderBy('student_score','desc')->paginate(40);
          

        $activity= activities::find($id);
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
        $activity = activities::find($id);
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
        $addschool = addschool::where('id','=',$id)->get();
        $addstudent = addstudent::where('school','=',$id)->where('type','=','0')->paginate(40);
        $addteacher = addstudent::where('school','=',$id)->where('type','=','1')->paginate(40);
        $data = array(
            'addschool'=>$addschool,
            'addstudent'=> $addstudent,
            'addteacher'=> $addteacher,
        );
        //dd($data);
       return view('centersillapa.centereditstudent',$data);
    }
    public function centereditstudentname($id){
        $addstudent = addstudent::find($id);
        $addschool = addschool::where('id','=',$addstudent->school)->get();
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
        $addstudent = addstudent::find($id);
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
}
