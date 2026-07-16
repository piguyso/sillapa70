<?php

namespace App\Http\Controllers;

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

class shoolPrintCardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
public function index()
{
    if (!Auth::check()) {
        return redirect()->route('login');
    }
    
    $user = Auth::user();

    // 1. ใช้ == เพื่อเปรียบเทียบ (ห้ามใช้ = ตัวเดียว)
    if($user->position == 0) // กรณีโรงเรียน
    {
        $id = $user->school_id;
        $addschool = addschool::where('school', $id)
            ->with([
                'getactivityname.getclass',
                'getactivityname.getactivitybranch',
                'getschoolname',
                'getstudentname.getstudenttitle'
            ])
            ->orderBy('updated_at', 'desc')
            ->get();
            
        return view('schoolprintcard.index', compact('addschool'));
    }

    if($user->position == 5) // กรณีเครือข่าย/กลุ่มโรงเรียน
    {
        // 2. ใช้ค่าจาก User จริงๆ ไม่ Hardcode เลข 2
        $groupId = $user->schoolgroup; 
        
        $addschool = addschool::where('school_schoolgroup', $groupId)
            ->with([
                'getactivityname.getclass',
                'getactivityname.getactivitybranch',
                'getschoolname',
                'getstudentname.getstudenttitle'
            ])
            ->orderBy('updated_at', 'desc')
            ->get();
            
        return view('schoolprintcard.index', compact('addschool'));
    }
    
    // กรณีอื่นๆ ให้ส่งกลับไปหน้าหลัก หรือจัดการตามเหมาะสม
    return redirect('/');
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
        //
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
        //
    }
}
