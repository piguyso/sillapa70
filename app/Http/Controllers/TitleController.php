<?php

namespace App\Http\Controllers;
use App\titles;
use Illuminate\Http\Request;
use DB;
use Session;
class TitleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = titles::where('id','like','%')->orderBy('id','desc')->paginate(10);
        $data = array(
            'title'=>$title,
        );
        return view('title.add',$data);
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
            'title'=>'required',
          
          
        ]);
        $title_index = DB::table('titles')->latest('id')->first();
        //dd($title_index->id);
        $title = new titles;
        $title->title_id = $title_index->id+1;
        $title->title = $request->title;
        $title->save();
        Session::flash('massage','Success Create !!');
      return redirect('/title');

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
        // 1. ดึงข้อมูลคำนำหน้าชื่อตาม ID
        $title = titles::find($id);

        // 2. ตรวจสอบว่าพบข้อมูลหรือไม่ (ป้องกันกรณีพิมพ์ URL เข้ามามั่วๆ)
        if (!$title) {
            return redirect('title')->with('massage', 'ไม่พบข้อมูลที่ต้องการแก้ไข');
        }

        // 3. ส่งข้อมูลไปยัง View (สมมติว่าไฟล์อยู่ใน resources/views/title/edit.blade.php)
        return view('title.edit', compact('title'));
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
            // 1. ตรวจสอบความถูกต้องของข้อมูล (Validation)
            $this->validate($request, [
                'title' => 'required|max:255', // บังคับกรอก และห้ามยาวเกิน 255 ตัวอักษร
            ], [
                'title.required' => 'กรุณากรอกชื่อคำนำหน้าชื่อ',
            ]);

            // 2. ค้นหาข้อมูลเดิมจาก ID
            $title = \App\titles::find($id); // ปรับ Path Model ตามที่คุณใช้จริง เช่น App\Models\Title

            if (!$title) {
                return redirect('title')->with('massage', 'ไม่พบข้อมูลที่ต้องการแก้ไข');
            }

            // 3. ปรับปรุงข้อมูล
            $title->title = $request->title;
            
            // 4. บันทึก
            $title->save();

            // 5. ส่งกลับไปยังหน้าหลักพร้อมข้อความแจ้งเตือนสีเขียวๆ ที่เราทำไว้
            return redirect('title')->with('massage', 'อัปเดตข้อมูลคำนำหน้าเรียบร้อยแล้ว');
        }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        adminchk();
        $title=titles::find($id);
        $title->delete();
        Session::flash('massage','title has been deleted!!');
        return redirect('title');
    }
    
}
