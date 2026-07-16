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

class shoolPrintCardAreaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
{
    if (!Auth::check()) {
        return redirect()->route('login'); // ถ้ายัง ให้ไปหน้า login
    }
    $id = Auth::user()->school_id;

    $addschool = addschools_areas::where('school', $id)
        ->with([
            'getactivityname.getclass',    // ดึงกิจกรรม -> ระดับชั้น
            'getactivityname.getactivitybranch', // ดึงกิจกรรม -> ศูนย์/สาขา (Branch)
            'getschoolname',               // ดึงชื่อโรงเรียน
            'getstudentname.getstudenttitle'
        ])
        ->orderBy('updated_at', 'desc')
        ->get();

    $data = ['addschool' => $addschool];
    return view('schoolprintcard.indexarea', $data);
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
