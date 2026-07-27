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
class areaAddstudentController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
     ///   $this->middleware('areamiddleware');
        //adminchk();
    }
    public function index()
    {
        
    }
    public function addaddstudent($id)
    {
        
        $addschool = addschools_areas::where('id','=',$id)->get();
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
        if(Auth::user()->position == 0 && Auth::user()->schoolgroup == $addschool[0]->school ){
            return ("xxx");
        }

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
       // dd($request); นับจำนวนของนักเรียนและครูในฐานข้อมุล
       $chkstudent = addstudents_areas::where('activity','=',$request->activity)->where('school','=',$request->school)->where('type','=','0')->count();
       $chkteacher = addstudents_areas::where('activity','=',$request->activity)->where('school','=',$request->school)->where('type','=','1')->count();

      // dd($chkteacher); ตรวจจำนวนของนักเรียนและครูในเกณฑ์

       $chkactivity = Activities::find($request->activity)->activity_studennumber;
       $chkteachernum = Activities::find($request->activity)->activity_teachernumber;
       
       //dd($chkactivity);
       if($request->type==0){
        if($chkstudent >= $chkactivity){
            Session::flash('massage','จำนวนของนักเรียนเกินกว่าที่เกณฑ์กำหนด !!');
            return redirect(url()->previous());
            }
       }
       elseif($request->type==1){
        if($chkteacher >= $chkteachernum){
            Session::flash('massage','จำนวนของครูผู้ควบคุมเกินกว่าที่เกณฑ์กำหนด !!');
            return redirect(url()->previous());
        }
       }
   
        




       $this->validate($request,[
        'name'=>'required',
        'lastname'=>'required',
       ]);
        $addstudent  = new addstudents_areas;
        $addstudent->title = $request->title;
        $addstudent->name = $request->name;
        $addstudent->lastname = $request->lastname;
        $addstudent->activity = $request->activity;
        $addstudent->school = $request->school;
        $addstudent->type = $request->type;
        $addstudent->schoolgroup = Auth::user()->schoolgroup;
        
        $addstudent->save();
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
        $addstudent = addstudents_areas::find($id);
        $addschool = addschools_areas::where('id','=',$addstudent->school)->get();
        $title = titles::all();

        //return $addschool[0]->school.Auth::user()->school_id;

     /*   if($addschool[0]->school != Auth::user()->school_id ){
            return("error access denied..");
        }elseif($addschool[0]->school_schoolgroup != Auth::user()->schoolgroup){
             return("error access denied..");
        }*/
        if(Auth::user()->position == 5 && $addschool[0]->school_schoolgroup != Auth::user()->schoolgroup){
            return("error access denied..");
        }
        if(Auth::user()->position == 0 && $addschool[0]->school != Auth::user()->school_id){
             return("error access denied..");
        }
            
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

                $addstudent = addstudents_areas::find($id);
                $addstudent->name = $request->name;
                $addstudent->lastname = $request->lastname;
                $addstudent->title = $request->title;

                // เช็คว่ามีการส่งรูปภาพ Base64 มาหรือไม่
                if ($request->image_base64) {
                    $data = $request->image_base64;

                    // แยกส่วน Header ของ Base64 ออกมา
                    // Format: data:image/png;base64,iVBORw0KGgoAAAANSUhEU...
                    if (preg_match('/^data:image\/(\w+);base64,/', $data, $type)) {
                        $data = substr($data, strpos($data, ',') + 1);
                        $type = strtolower($type[1]); // png, jpg, jpeg

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

                    // ตั้งชื่อไฟล์ใหม่: ใช้ ID + เวลา เพื่อป้องกันชื่อซ้ำ
                    $fileName = "profile_" . $id . "_" . time() . '.' . $type;
                    $year = $addstudent->year ?: active_year();
                    Storage::disk('public')->put(storage_year_path('picture', $fileName, $year), $data);

                    // ลบรูปเก่าทิ้ง (ถ้ามี) เพื่อประหยัดพื้นที่
                    delete_storage_file('picture', $addstudent->student_image, $year);

                    // เก็บชื่อไฟล์ใหม่ลงฐานข้อมูล
                    $addstudent->student_image = $fileName;
                }

                $addstudent->save();

                Session::flash('massage', 'แก้ไขข้อมูลสำเร็จ..');
                return redirect('area/addaddstudent/' . $request->school);
            }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $addstudent = addstudents_areas::find($id);
        $addstudent->delete();
        Session::flash('massage','ลบข้อมูลสำเร็จ..');
        return redirect(url()->previous());
    }
}
