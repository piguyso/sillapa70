@extends('layouts.apparea')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 font-sarabun text-slate-800">

    <div class="relative bg-gradient-to-r from-amber-800 to-orange-600 rounded-3xl shadow-xl p-8 md:p-12 mb-8 text-white overflow-hidden border-b-8 border-orange-500">
        <div class="relative z-10 text-white">
            <h2 class="text-xl md:text-2xl font-bold opacity-90 italic mb-2 text-white">
                {{ session('app_name')}} ระดับเครือข่ายสถานศึกษา {{Auth::user()->getschoolgroup->schoolgroup_name}}
            </h2>
            <h1 class="text-3xl md:text-5xl font-black tracking-tight uppercase italic text-white leading-none">
                บันทึกผลการแข่งขัน
            </h1>
        </div>
        <i class="ri-edit-box-line absolute -right-10 -bottom-10 text-[15rem] opacity-10 rotate-12 text-white"></i>
    </div>



    <div class="bg-white rounded-3xl shadow-xl shadow-slate-100 border border-slate-100 overflow-hidden">
        <div class="bg-slate-50 px-8 py-6 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{url('area/areasubjectgroup/activity/'.$activity->activity_subject)}}" class="w-10 h-10 flex items-center justify-center bg-white text-orange-600 rounded-xl border border-slate-200 hover:bg-orange-600 hover:text-white transition-all shadow-sm group">
                    <i class="ri-arrow-left-s-line text-2xl group-hover:-translate-x-1 transition-transform"></i>
                </a>
                <div>
                    <h2 class="text-lg font-black text-slate-800 uppercase italic tracking-wide leading-none">
                        {{$activity->activity_name}}
                    </h2>
                    <p class="text-[10px] text-slate-400 mt-1 font-bold italic uppercase tracking-widest">
                        {{$activity->getactivitysubject->subjectgroup_name}} | นักเรียน {{$activity->activity_studennumber}} | ครู {{$activity->activity_teachernumber}}
                    </p>
                </div>
            </div>
        </div>

        <div class="p-6 md:p-8">
            <form id="saveform" method="POST" action="{{ Route('areacentersillapa.store') }}">
                {{ csrf_field() }}
                <input type="hidden" name="id" value="{{$activity->id}}"/>
                <input type="hidden" id="activity_chk" name="activity_chk" value=""/>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-separate border-spacing-y-2">
                        <thead>
                            <tr class="text-slate-400 text-[10px] uppercase tracking-[0.2em] font-black italic">
                                <th class="px-4 py-2 text-center">#</th>
                                <th class="px-4 py-2">โรงเรียน/เครือข่าย</th>
                                <th class="px-4 py-2">รายชื่อนักเรียน</th>
                                <th class="px-4 py-2">รายชื่อครู</th>
                                <th class="px-4 py-2 text-center w-28">คะแนน</th>
                                <th class="px-4 py-2 text-center w-20">อันดับ</th>
                                <th class="px-4 py-2 text-center">ผลรางวัล</th>
                            </tr>
                        </thead>
                        <tbody class="text-xs font-bold italic">
                            @php $x=0; @endphp
                            @forelse($addschool as $l)
                            @php $x++; @endphp
                            <tr class="bg-white hover:bg-slate-50 transition-all border border-slate-100 shadow-sm rounded-2xl group">
                                <td class="px-4 py-5 text-center text-slate-300 rounded-l-2xl border-l-4 border-transparent group-hover:border-orange-500">
                                    {{$x}}
                                </td>
                                <td class="px-4 py-5">
                                    <p class="text-[10px] text-slate-400 uppercase tracking-tighter">เครือข่าย {{$l->getschoolgroup->schoolgroup_name}}</p>
                                    <a href="{{url('area/addaddstudent/'.$l->id)}}" class="text-slate-800 hover:text-orange-600 font-black italic uppercase transition-colors">
                                        {{$l->getschoolname->school_name}}
                                    </a>
                                </td>
                                <td class="px-4 py-5 text-slate-500 leading-relaxed font-medium">
                                    @foreach($student as $s)
                                        @foreach($s->getstudentname as $st)
                                            @if($l->id == $st->school && $st->type == 0)
                                                <div class="flex items-center gap-1"><i class="ri-user-smile-line text-orange-300 text-[10px]"></i> {{$st->getstudenttitle->title}}{{$st->name}} {{$st->lastname}}</div>
                                            @endif
                                        @endforeach
                                    @endforeach
                                </td>
                                <td class="px-4 py-5 text-slate-400 leading-relaxed font-medium">
                                    @foreach($student as $s)
                                        @foreach($s->getstudentname as $st)
                                            @if($l->id == $st->school && $st->type == 1)
                                                <div class="flex items-center gap-1"><i class="ri-presentation-line text-slate-300 text-[10px]"></i> {{$st->getstudenttitle->title}}{{$st->name}} {{$st->lastname}}</div>
                                            @endif
                                        @endforeach
                                    @endforeach
                                </td>
                                <td class="px-4 py-5">
                                    <input type="number" step="any" onfocus="this.select();" 
                                           class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 text-center font-black text-orange-700 shadow-inner" 
                                           name="activity_score[{{$l->id}}]" 
                                           value="{{$l->student_score}}" 
                                           @if($activity->activity_status == 1) readonly @endif>
                                </td>
                                <td class="px-4 py-5">
                                    <input type="text" class="w-full px-3 py-2 bg-slate-100 border-none rounded-xl text-center font-black text-slate-400" 
                                           name="activity_rank1[{{$l->id}}]" 
                                           value="{{$l->activity_rank}}" readonly>
                                </td>
                                <td class="px-4 py-5 text-center rounded-r-2xl">
                                    <div class="flex flex-col items-center">
                                        @php getmedel($l->student_score); @endphp
                                        @if($l->activity_status == "98")
                                            <span class="text-[9px] bg-red-100 text-red-700 px-2 py-0.5 rounded-full uppercase italic">No Certificate</span>
                                        @elseif($l->activity_status == "99")
                                            <span class="text-[9px] bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full uppercase italic">With Certificate</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="7" class="text-center py-10 font-bold italic text-slate-400">ไม่พบข้อมูลโรงเรียนที่เข้าร่วม</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-8 p-6 bg-slate-50 rounded-3xl border border-slate-100">
                    <p class="text-[11px] text-red-500 font-black italic mb-6 flex items-start gap-2">
                        <i class="ri-error-warning-line text-lg"></i>
                        คำเตือน: หากมีการแก้ไขคะแนน จะต้อง "บันทึกคะแนน" ก่อนทุกครั้งเพื่อจัดลำดับผลการแข่งขัน (Ranking) ใหม่ก่อนที่จะกดประกาศผล
                    </p>

                    <div class="flex flex-wrap gap-4">
                        @php $status = $activity->activity_status; @endphp
                        
                        @if($status == 0)
                            <button type="button" class="px-8 py-4 bg-orange-600 text-white rounded-2xl font-black text-sm hover:bg-orange-700 hover:scale-[1.02] active:scale-95 transition-all shadow-xl shadow-orange-100 uppercase italic tracking-widest text-white" onClick="submitWithConfirm('0');">
                                <i class="ri-save-3-line mr-1 text-white"></i> บันทึกคะแนน
                            </button>
                            
                            <button type="button" class="px-8 py-4 bg-emerald-600 text-white rounded-2xl font-black text-sm hover:bg-emerald-700 hover:scale-[1.02] active:scale-95 transition-all shadow-xl shadow-emerald-100 uppercase italic tracking-widest text-white" onClick="submitWithConfirm('1');">
                                <i class="ri-megaphone-line mr-1 text-white"></i> ประกาศผลการแข่งขัน
                            </button>

                            @if($addschool->count() <= 3)
                                <button type="button" class="px-8 py-4 bg-slate-800 text-white rounded-2xl font-black text-[10px] hover:bg-slate-900 transition-all uppercase italic tracking-widest text-white" onClick="submitWithConfirm('98');">
                                    ไม่มีการแข่งขัน (ไม่มอบเกียรติบัตร)
                                </button>
                                <button type="button" class="px-8 py-4 bg-slate-700 text-white rounded-2xl font-black text-[10px] hover:bg-slate-900 transition-all uppercase italic tracking-widest text-white" onClick="submitWithConfirm('99');">
                                    ไม่มีการแข่งขัน (มอบเกียรติบัตร)
                                </button>
                                <button type="button" class="px-8 py-4 bg-red-600 text-white rounded-2xl font-black text-sm hover:bg-red-700 hover:scale-[1.02] active:scale-95 transition-all shadow-xl shadow-red-100 uppercase italic tracking-widest text-white" onClick="submitWithConfirm('2');">
                                    <i class="ri-close-circle-line mr-1 text-white"></i> ยกเลิกผลการแข่งขัน
                                </button>
                            @endif

                            @elseif(in_array($status, [1, 98, 99, 2]))
                                <div class="flex flex-wrap items-center gap-4 w-full">
                                    @if($status == 1)
                                        <div class="px-6 py-3.5 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-2xl font-black text-sm uppercase italic tracking-wider flex items-center gap-2">
                                            <i class="ri-checkbox-circle-fill text-lg text-emerald-500 animate-pulse"></i> ประกาศผลการแข่งขันเรียบร้อยแล้ว
                                        </div>
                                    @elseif($status == 98)
                                        <div class="px-6 py-3.5 bg-red-50 text-red-700 border border-red-200 rounded-2xl font-black text-sm uppercase italic tracking-wider flex items-center gap-2">
                                            <i class="ri-close-circle-fill text-lg text-red-500"></i> ประกาศผลแล้ว (ไม่มีการแข่งขัน - ไม่มอบเกียรติบัตร)
                                        </div>
                                    @elseif($status == 99)
                                        <div class="px-6 py-3.5 bg-amber-50 text-amber-700 border border-amber-200 rounded-2xl font-black text-sm uppercase italic tracking-wider flex items-center gap-2">
                                            <i class="ri-award-fill text-lg text-amber-500"></i> ประกาศผลแล้ว (ไม่มีการแข่งขัน - มอบเกียรติบัตร)
                                        </div>
                                    @elseif($status == 2)
                                        <div class="px-6 py-3.5 bg-slate-100 text-slate-700 border border-slate-200 rounded-2xl font-black text-sm uppercase italic tracking-wider flex items-center gap-2">
                                            <i class="ri-spam-2-fill text-lg text-slate-500"></i> ยกเลิกผลการแข่งขันแล้ว
                                        </div>
                                    @endif
                                </div>
                            @endif
                    </div>
                </div>
            </form>
            <div class="mt-6 font-bold italic">{{ $addschool->links() }}</div>
        </div>
    </div>
</div>

<script>
    function submitWithConfirm(status) {
        let title = '';
        let message = '';
        if (status === '0') {
            title = 'ยืนยันการบันทึกคะแนน';
            message = 'คุณต้องการยืนยันการบันทึกคะแนนใช่หรือไม่?';
        } else if (status === '1') {
            title = 'ยืนยันการประกาศผล';
            message = 'คุณต้องการยืนยันการประกาศผลการแข่งขันใช่หรือไม่?';
        } else if (status === '98') {
            title = 'ประกาศผลแบบไม่มีการแข่งขัน';
            message = 'ยืนยันประกาศผลแบบไม่มีการแข่งขัน (ไม่มอบเกียรติบัตร) ใช่หรือไม่?';
        } else if (status === '99') {
            title = 'ประกาศผลแบบไม่มีการแข่งขัน';
            message = 'ยืนยันประกาศผลแบบไม่มีการแข่งขัน (มอบเกียรติบัตร) ใช่หรือไม่?';
        } else if (status === '2') {
            title = 'ยืนยันการยกเลิกผลการแข่งขัน';
            message = '⚠️ ยืนยันยกเลิกผลการแข่งขัน? การกระทำนี้ไม่สามารถย้อนกลับได้!';
        }
        
        showCustomConfirm(title, message, function() {
            document.getElementById('activity_chk').value = status;
            document.getElementById('saveform').submit();
        });
    }
</script>
@endsection