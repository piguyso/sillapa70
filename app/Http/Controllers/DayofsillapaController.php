<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use App\dayofsillapas;
use Auth;
class DayofsillapaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
        //adminchk();
    }
    public function index()
    {
        adminchk();
        $dayofsillapa = dayofsillapas::where('id','like','%')->paginate(20);

        $data = array(
            'dayofsillapa'=>$dayofsillapa,
           
        );
        //dd($data);
       return view('dayofsillapa.add',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
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
            'dayofsillapa'=>'required',
           // 'school_detail'=>'required',
          
        ]);
        $dayofsillapa = new dayofsillapas;
        $dayofsillapa->dayofsillapa = $request->dayofsillapa;
        $dayofsillapa->schoolgroup = Auth::user()->schoolgroup;
      //  $school->school_group = $request->school_group;
      //  $school->school_detail = $request->school_detail;
   
        $dayofsillapa->save();
        Session::flash('massage','Success Create !!');
        return redirect()->back();
       // return redirect('/dayofsillapa');
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
  
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // ค้นหาข้อมูลวันแข่งขันตาม ID
        $dayofsillapa = dayofsillapas::find($id);

        // ถ้าไม่พบข้อมูลให้ส่งกลับไปหน้าหลักพร้อมแจ้งเตือน
        if (!$dayofsillapa) {
            return redirect()->route('dayofsillapa.index')->with('massage', 'ไม่พบข้อมูลที่ต้องการแก้ไข');
        }

        // ส่งข้อมูลไปยังหน้า View (resources/views/dayofsillapa/edit.blade.php)
        return view('dayofsillapa.edit', compact('dayofsillapa'));
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
        $request->validate([
            'dayofsillapa' => 'required|date',
        ], [
            'dayofsillapa.required' => 'กรุณาเลือกวันที่แข่งขัน',
            'dayofsillapa.date' => 'รูปแบบวันที่ไม่ถูกต้อง',
        ]);

        // 2. ค้นหาและอัปเดตข้อมูล
        $day = dayofsillapas::find($id);
        
        if ($day) {
            $day->dayofsillapa = $request->dayofsillapa;
            $day->save();

            // 3. ส่งกลับไปยังหน้าหลักพร้อมข้อความสำเร็จ
            return redirect()->route('dayofsillapa.index')->with('massage', 'อัปเดตข้อมูลวันแข่งขันเรียบร้อยแล้ว');
        }

        return redirect()->route('dayofsillapa.index')->with('massage', 'เกิดข้อผิดพลาด: ไม่พบข้อมูล');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $dayofsillapa = dayofsillapas::find($id);
        $dayofsillapa->delete();
        Session::flash('massage','Data has been deleted..');
               return redirect()->back();
    }
}
