<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\addschool;
use App\addstudent;
use App\Activities;
use App\titles;
use Session;
use Illuminate\Support\Facades\Storage;
class AddstudentController extends Controller
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
    public function addaddstudent($id)
    {
       // dd($id);
        $addschool = addschool::where('id','=',$id)->get();
        $addstudent = addstudent::where('school','=',$id)->where('type','=','0')->paginate(20);
        $addteacher = addstudent::where('school','=',$id)->where('type','=','1')->paginate(20);
        $title = titles::all();
        $data = array(
            'title'=>$title,
            'addschool'=>$addschool,
            'addstudent'=> $addstudent,
            'addteacher'=> $addteacher,
        );
        //dd($data);
       return view('addstudent.add',$data);

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
       $chkstudent = addstudent::where('activity','=',$request->activity)->where('school','=',$request->school)->where('type','=','0')->count();
       $chkteacher = addstudent::where('activity','=',$request->activity)->where('school','=',$request->school)->where('type','=','1')->count();

      // dd($chkteacher); ตรวจจำนวนของนักเรียนและครูในเกณฑ์

       $chkactivity = activities::find($request->activity)->activity_studennumber;
       $chkteachernum = activities::find($request->activity)->activity_teachernumber;
       
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
        $addstudent  = new addstudent;
        $addstudent->title = $request->title;
        $addstudent->name = $request->name;
        $addstudent->lastname = $request->lastname;
        $addstudent->activity = $request->activity;
        $addstudent->school = $request->school;
        $addstudent->type = $request->type;
        
        
        $addstudent->save();
        Session::flash('massage','Success Create !!');
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
        $addstudent = addstudent::find($id);
        $addschool = addschool::where('id','=',$addstudent->school)->get();
        $title = titles::all();
        $data = array(
            'addstudent'=>$addstudent,
            'addschool'=>$addschool,
            'title'=>$title,
        );
      //  dd($data);
        return view('addstudent.edit',$data);
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
    // 1. ตรวจสอบข้อมูลเบื้องต้น
    $this->validate($request, [
        'name' => 'required',
        'lastname' => 'required',
    ]);

    // 2. ค้นหาข้อมูลเดิม
    $addstudent = addstudent::find($id);
    $addstudent->name = $request->name;
    $addstudent->lastname = $request->lastname;
    $addstudent->title = $request->title;

    // 3. เช็คว่ามีการส่งรูปภาพ Base64 มาจาก Croppie หรือไม่
    if ($request->image_base64) {
        $data = $request->image_base64;

        // แยกส่วน Header ของ Base64 เพื่อเช็คนามสกุลไฟล์
        if (preg_match('/^data:image\/(\w+);base64,/', $data, $type)) {
            $data = substr($data, strpos($data, ',') + 1);
            $type = strtolower($type[1]); // png, jpg, jpeg

            // ตรวจสอบนามสกุลไฟล์
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
        $fileName = "stu_" . $id . "_" . time() . '.' . $type;
        $year = $addstudent->year ?: active_year();
        Storage::disk('public')->put(storage_year_path('picture', $fileName, $year), $data);

        // ลบรูปเก่าทิ้ง (ถ้ามี) เพื่อประหยัดพื้นที่เซิร์ฟเวอร์
        delete_storage_file('picture', $addstudent->student_image, $year);

        // เก็บชื่อไฟล์ใหม่ลงฐานข้อมูล
        $addstudent->student_image = $fileName;
    }

    // 4. บันทึกข้อมูลทั้งหมด
    $addstudent->save();

    Session::flash('massage', 'Data update Successfull..');
    
    // 5. Redirect กลับไปหน้าเดิม
    return redirect('addaddstudent/' . $request->school);
}

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $addstudent = addstudent::find($id);
        $addstudent->delete();
        Session::flash('massage','Data has been deleted..');
        return redirect(url()->previous());
    }
}
