<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Auth;
use DB;
use App\Activities;
use App\Subjectgroup;
use App\Branch;
use App\Classes;
use App\Addschool;
use App\dayofsillapas;
use App\systemactives;
use App\addstudent;
use App\titles;
class CentersillapaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
      
        $this->middleware('auth');
        $this->middleware('centermiddleware');
        

    
   
    }
    public function index()
    {

        $centerchk = systemactives::where('systemname','=','center')->get();
       // $scorechk = systemactives::where('systemname','=','score')->get();
       //dd($centerchk->status);
        if($centerchk[0]->status == 1 and Auth::user()->position != 10){
           // Auth::logout();
            return view('home.out');
        }



        if(Auth::user()->position>=10){
            $activityinbranch = activities::all()->groupBy('activity_subject');
            $activitycount = DB::table('Activities')
                ->select('activity_subject', DB::raw('count(*) as total'))
                
                ->groupBy('activity_subject')
                ->pluck('total','activity_subject');
            $activityactivecount = DB::table('Activities')
                ->select('activity_subject', DB::raw('count(*) as total'))
                ->where('activity_status','=',1)
                ->groupBy('activity_subject')
                ->pluck('total','activity_subject');
       
        }else
        {
            $activityinbranch = activities::where('activity_branch','=',Auth::user()->branch)->get()->groupBy('activity_subject');
                 $activitycount = DB::table('Activities')
                ->select('activity_subject', DB::raw('count(*) as total'))
                ->where('activity_branch','=',Auth::user()->branch)
                ->groupBy('activity_subject')
                ->pluck('total','activity_subject');

            $activityactivecount = DB::table('Activities')
                ->select('activity_subject', DB::raw('count(*) as total'))
                ->where('activity_branch','=',Auth::user()->branch)->where('activity_status','=',1)
                ->groupBy('activity_subject')
                ->pluck('total','activity_subject');
        }
       
   

        

        $data= array(
            'activityinbranch'=> $activityinbranch,
            'activitycount'=>$activitycount,
            'activityactivecount'=>$activityactivecount,
        );
        
        return view('centersillapa.add',$data);
    }

    public function centerviewactivity($id){
             //dd($id);

             $schoolcountactivity = DB::table('addschools')
             ->select('school_activity', DB::raw('count(*) as total'))
             ->groupBy('school_activity')
             ->pluck('total','school_activity');
     
            // dd($schoolcountactivity);
     
             if(Auth::user()->position >= 10){
                $activity = activities::where('activity_subject','=',$id)->paginate(1000);
             
             }else{
                $activity = activities::where('activity_subject','=',$id)->where('activity_branch','=',Auth::user()->branch)->paginate(1000);
              
             }
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
             return view('centersillapa.centerviewactivity',$data);
    }
    public function centerviewactivityschool($id){
        $addschool = addschool::where('school_activity','=',$id)->orderBy('student_score','desc')->paginate(40);
          

        $activity= activities::find($id);
        $student = addschool::WITH("getstudentname")->where('school_activity','=',$id)->orderBy('id','desc')->get();
        $data = array(
            'addschool'=>$addschool,
            'student'=>$student,
          //  'schoolgroup'=>$schoolgroup,
            'activity'=>$activity,
        );
        return view('centersillapa.centerviewactivityschool',$data);
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
               
                if($request->activity_chk=='0'){
                     foreach(array_keys($request->activity_score) as $nmkey)
                    {
                        //echo $nmkey."->".$request->activity_score[$nmkey]."<br>";

                        addschool::where('id', '=',$nmkey)->update(['student_score' => $request->activity_score[$nmkey]]);
                        addschool::where('id', '=',$nmkey)->update(['activity_rank' => $request->activity_rank1[$nmkey]]);


                    }
                    Session::flash('massage','บันทึกคะแนนการแข่งขันเรียบร้อยแล้ว..');
                }
                elseif($request->activity_chk=='1')
                {
                    foreach(array_keys($request->activity_score) as $nmkey)
                    {
                        //อับเดทข้อมูลคะแนนกรณี แก้ไขคะแนน
                    addschool::where('id', '=',$nmkey)->update(['student_score' => $request->activity_score[$nmkey]]);
                    addschool::where('id', '=',$nmkey)->update(['activity_rank' => $request->activity_rank1[$nmkey]]);
        
                        addschool::where('id', '=',$nmkey)->update(['activity_status' => '1']);
    
                    }

                    $activity = activities::find($request->id);
                    $activity->activity_status = '1';
                    $activity->save();
                    Session::flash('massage','ประกาศผลการแข่งขันเรียบร้อยแล้ว..');
                }
                elseif($request->activity_chk=='2')
                {
                    foreach(array_keys($request->activity_score) as $nmkey)
                    {
                        //อับเดทข้อมูลคะแนนกรณี แก้ไขคะแนน
                    addschool::where('id', '=',$nmkey)->update(['student_score' => 0]);
                    addschool::where('id', '=',$nmkey)->update(['activity_rank' => 0]);
        
                        addschool::where('id', '=',$nmkey)->update(['activity_status' => '0']);
    
                    }

                    $activity = activities::find($request->id);
                    $activity->activity_status = '0';
                    $activity->save();
                    Session::flash('massage','ยกเลิกผลการแข่งขันแล้ว..');
                }
                else
                {
                    Session::flash('massage','ไม่สามารถบันทึกข้อมูลได้กรุณาล้างประวัติของบราวเซอร์ของท่าน..');
                }
                
                return redirect('/centerviewactivityschool/'.$request->id);

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
    // 1. Validation ข้อมูลพื้นฐาน
    $this->validate($request, [
        'name' => 'required',
        'lastname' => 'required',
    ]);

    $addstudent = addstudent::find($id);
    $addstudent->name = $request->name;
    $addstudent->lastname = $request->lastname;
    $addstudent->title = $request->title;

    // 2. ตรวจสอบว่ามีการส่งรูปภาพใหม่ (Base64) มาหรือไม่
    if ($request->image_base64) {
        $data = $request->image_base64;

        // แยกส่วน Header ของ Base64 เพื่อหานามสกุลไฟล์
        if (preg_match('/^data:image\/(\w+);base64,/', $data, $type)) {
            $data = substr($data, strpos($data, ',') + 1);
            $type = strtolower($type[1]); // png, jpg, jpeg

            // ตรวจสอบนามสกุลไฟล์ที่อนุญาต
            if (!in_array($type, ['jpg', 'jpeg', 'gif', 'png'])) {
                throw new \Exception('invalid image type');
            }

            $data = str_replace(' ', '+', $data);
            $data = base64_decode($data);

            if ($data === false) {
                throw new \Exception('base64_decode failed');
            }
        } else {
            throw new \Exception('did not match data URI with image data');
        }

        // ตั้งชื่อไฟล์ใหม่ (ใช้ ID + Timestamp เพื่อป้องกันชื่อซ้ำ)
        $fileName = "profile_center_" . $id . "_" . time() . '.' . $type;
        $path = public_path('picture/' . $fileName);

        // ตรวจสอบ/สร้างโฟลเดอร์ public/picture
        if (!file_exists(public_path('picture'))) {
            mkdir(public_path('picture'), 0777, true);
        }

        // บันทึกไฟล์รูปภาพจริงลงใน Server
        file_put_contents($path, $data);

        // ลบรูปภาพเก่าทิ้ง (ถ้ามี) เพื่อประหยัดพื้นที่
        if ($addstudent->student_image && file_exists(public_path('picture/' . $addstudent->student_image))) {
            @unlink(public_path('picture/' . $addstudent->student_image));
        }

        // อัปเดตชื่อไฟล์รูปภาพลงในฐานข้อมูล
        $addstudent->student_image = $fileName;
    }

    // 3. บันทึกข้อมูลลงฐานข้อมูล
    $addstudent->save();

    Session::flash('massage', 'Data update Successfull..');
    
    // 4. กลับไปยังหน้าที่แล้ว
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
      //  dd($request);
        $this->validate($request,[
            //'activity_name'=>'required',
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
