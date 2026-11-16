<?php

namespace App\Http\Controllers\area;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\dayofsillapas;
use App\Configs;
use App\Systemactives;
use App\Schoolgroup;
use Auth;
use App\CompetitionAccessRule;
class areaConfigController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Schoolgroup::findOrFail(Auth::user()->schoolgroup);
        $config = Configs::where('schoolgroup', Auth::user()->schoolgroup)
                         ->where('event_id', active_event_id())
                         ->get();
        if ($config->isEmpty()) {
            abort(404, 'ไม่พบการตั้งค่าเครือข่ายสำหรับครั้งการแข่งขันนี้');
        }
        $dayofsillapa = dayofsillapas::where('schoolgroup', Auth::user()->schoolgroup)->get();
        $networkRule = CompetitionAccessRule::where('event_id', active_event_id())
            ->where('competition_level', 'network')->where('subject_type', 'school')
            ->where('scope_type', 'network')->where('scope_id', Auth::user()->schoolgroup)->first();
        $masterFreeze = CompetitionAccessRule::where('event_id', active_event_id())
            ->where('competition_level', 'network')->where('subject_type', 'master')
            ->where('scope_type', 'all')->where('scope_id', 0)->where('is_closed', true)->exists();
        $systemactive = collect([(object) [
            'id' => Auth::user()->schoolgroup,
            'systemname' => 'การแก้ไขข้อมูลของโรงเรียนในเครือข่าย',
            'detail' => $masterFreeze ? 'แอดมินหลัก Freeze ระบบแล้ว' : 'เปิดหรือปิดให้โรงเรียนในเครือข่ายแก้ไขข้อมูล',
            'status' => $masterFreeze || optional($networkRule)->is_closed ? 1 : 0,
            'master_freeze' => $masterFreeze,
        ]]);
        $data = Array(
            'config' => $config,
            'systemactive' => $systemactive,
            'dayofsillapa' => $dayofsillapa,
        );

        return view('home.configarea',$data);
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
