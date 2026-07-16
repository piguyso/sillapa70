@extends('layouts.apparea')

@section('content')
<style>
    /* สไตล์สำหรับแถวรายละเอียดที่ซ่อนอยู่ */
    .detail-row { display: none; @apply bg-slate-50/50; }
    .detail-row.active { display: table-row; }
    .main-row.active-row { @apply bg-orange-50/50 shadow-inner; }
</style>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 font-sarabun text-slate-800">

    <div class="relative bg-gradient-to-r from-amber-800 to-orange-600 rounded-3xl shadow-xl p-8 md:p-12 mb-8 text-white overflow-hidden border-b-8 border-orange-500 text-white">
        <div class="relative z-10 text-white">
            <h2 class="text-xl md:text-2xl font-bold opacity-90 italic mb-2 text-white leading-none">
                {{ session('app_name')}} ระดับเครือข่ายสถานศึกษา {{session()->get('schoolgroup')}}
            </h2>
            <h1 class="text-3xl md:text-5xl font-black tracking-tight uppercase italic text-white leading-none text-white mb-2">
                {{$subjectgroup->subjectgroup_name}}
            </h1>
            <p class="text-orange-100 font-bold italic uppercase tracking-widest text-sm text-white">
                <i class="ri-search-eye-line mr-1 text-white"></i> ตรวจสอบผลการแข่งขันรายกิจกรรม
            </p>
        </div>
        <i class="ri-trophy-fill absolute -right-10 -bottom-10 text-[15rem] opacity-10 rotate-12 text-white"></i>
    </div>

    <div class="bg-white rounded-3xl shadow-xl shadow-slate-100 border border-slate-100 overflow-hidden text-slate-800">
        <div class="bg-slate-50 px-8 py-5 border-b border-slate-100 flex flex-col md:flex-row justify-between items-center gap-4 text-slate-800">
            <div class="flex items-center gap-4 text-slate-800">
                <a href="../{{session('area')}}" class="w-10 h-10 flex items-center justify-center bg-white text-orange-600 rounded-xl border border-slate-200 hover:bg-orange-600 hover:text-white transition-all shadow-sm group">
                    <i class="ri-arrow-left-s-line text-2xl group-hover:-translate-x-1 transition-transform"></i>
                </a>
                <h2 class="text-base font-black text-slate-800 uppercase italic tracking-wide text-slate-800 leading-none">Activity Result List</h2>
            </div>
            <div class="text-[10px] font-black text-slate-400 uppercase italic tracking-widest text-slate-800 italic">คลิกที่แถวเพื่อดูเกณฑ์คะแนน</div>
        </div>

        <div class="p-4 md:p-8 text-slate-800">
            @if (session('status') || Session::has('massage'))
                <div class="mb-8 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 rounded-xl shadow-sm font-bold animate-fade-in italic text-sm flex items-center gap-3">
                    <i class="ri-checkbox-circle-fill text-xl text-emerald-500 text-white"></i> {{ session('status') ?: Session::get('massage') }}
                </div>
            @endif

            <div class="overflow-x-auto text-slate-800">
                <table class="w-full text-left border-separate border-spacing-y-3 text-slate-800">
                    <thead>
                        <tr class="text-slate-400 text-[10px] uppercase tracking-[0.2em] font-black italic text-slate-400">
                            <th class="px-6 py-2 text-center w-16">#</th>
                            <th class="px-6 py-2">ชื่อกิจกรรม</th>
                            <th class="px-6 py-2">ช่วงชั้น</th>
                            <th class="px-6 py-2 text-center">จำนวนทีม</th>
                            <th class="px-6 py-2 text-center">สถานะ</th>
                            <th class="px-6 py-2 text-right">ข้อมูล</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm font-bold italic text-slate-800">
                        @php 
                            $x = $activity->currentPage() == 1 ? 0 : $activity->perPage() * ($activity->currentPage() - 1);
                        @endphp
                        @forelse($activity as $l)
                        @php $x++; @endphp
                        
                        <tr class="group bg-white hover:bg-orange-50/30 transition-all border border-slate-100 shadow-sm rounded-2xl cursor-pointer main-row text-slate-800" data-target="detail-{{$l->id}}">
                            <td class="px-6 py-6 text-center text-slate-300 italic rounded-l-2xl border-l-4 border-transparent group-hover:border-orange-500 text-slate-800">
                                {{ $x }}.
                            </td>
                            <td class="px-6 py-6 text-slate-800">
                                <a href="../viewactivitydetail/{{$l->id}}" class="font-black text-slate-800 text-base leading-tight group-hover:text-orange-600 transition-colors uppercase italic tracking-tight text-slate-800">
                                    {{$l->activity_name}}
                                </a>
                                <p class="text-[9px] text-slate-400 mt-1 uppercase tracking-widest leading-none text-slate-800">Click to view results</p>
                            </td>
                            <td class="px-6 py-6 text-slate-500 italic text-slate-500">{{$l->getclass->class_name}}</td>
                            <td class="px-6 py-6 text-center text-slate-800">
                                <span class="px-3 py-1 bg-slate-100 text-slate-600 rounded-lg text-xs font-black shadow-inner text-slate-600">
                                    {{ isset($schoolcountactivity[$l->id]) ? $schoolcountactivity[$l->id] : 0 }} ทีม
                                </span>
                            </td>
                            <td class="px-6 py-6 text-center text-slate-800">
                                @php $status = $l->getactivitystatus->activity_status ?? '0'; @endphp
                                <span class="px-3 py-1 rounded-lg text-[10px] font-black uppercase italic shadow-sm
                                    @if($status == '1' || $status == '99') bg-emerald-100 text-emerald-600 
                                    @else bg-slate-100 text-slate-400 @endif">
                                    {{ getactivitystatus($status) }}
                                </span>
                            </td>
                            <td class="px-6 py-6 text-right rounded-r-2xl text-slate-800">
                                <button class="w-10 h-10 flex items-center justify-center bg-slate-50 text-slate-400 rounded-xl border border-slate-100 group-hover:bg-orange-500 group-hover:text-white transition-all text-white">
                                    <i class="ri-information-line text-lg text-white"></i>
                                </button>
                            </td>
                        </tr>

                        <tr class="detail-row text-slate-800" id="detail-{{$l->id}}">
                            <td colspan="6" class="px-10 py-8 border-x-8 border-orange-500 rounded-b-3xl shadow-inner text-slate-800">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-slate-800">
                                    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm text-slate-800">
                                        <h4 class="text-[10px] font-black text-orange-600 uppercase italic mb-4 tracking-widest leading-none">เกณฑ์การตัดสินและรายละเอียด</h4>
                                        <div class="space-y-3 text-xs font-bold text-slate-700">
                                            <div class="flex justify-between border-b border-slate-50 pb-2 italic text-slate-800"><span>กลุ่มสาระฯ:</span><span class="text-slate-900">{{$l->getactivitysubject->subjectgroup_name}}</span></div>
                                            <div class="flex justify-between border-b border-slate-50 pb-2 italic text-slate-800"><span>ระดับชั้น:</span><span class="text-slate-900">{{$l->getclass->class_name}}</span></div>
                                            <div class="flex justify-between border-b border-slate-50 pb-2 italic text-slate-800"><span>นักเรียน:</span><span class="text-slate-900">{{$l->activity_studennumber}} คน/ทีม</span></div>
                                            <div class="flex justify-between border-b border-slate-50 pb-2 italic text-slate-800"><span>ครูผู้ควบคุม:</span><span class="text-slate-900">{{$l->activity_teachernumber}} คน/ทีม</span></div>
                                        </div>
                                    </div>
                                    <div class="flex flex-col justify-center gap-4 text-slate-800">
                                        <div class="bg-orange-50 p-6 rounded-2xl border border-orange-100 text-slate-800">
                                            <p class="text-[10px] font-black text-orange-800 uppercase italic mb-1 text-slate-800 leading-none">คะแนนตัดสิน</p>
                                            <p class="text-2xl font-black text-orange-600 text-slate-800">{{$l->activity_score}} คะแนน</p>
                                        </div>
                                        <a href="../viewactivitydetail/{{$l->id}}" class="w-full py-4 bg-amber-900 text-white rounded-2xl font-black text-sm hover:bg-orange-600 transition-all shadow-xl shadow-orange-100 uppercase italic tracking-widest text-center text-white">
                                            <i class="ri-search-eye-line mr-1 text-white"></i> ดูผลประกาศคะแนนและอันดับ
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center py-20 font-black italic text-slate-300 text-slate-800">ไม่พบกิจกรรมในกลุ่มสาระนี้</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-8 text-slate-800">
                {{ $activity->links() }}
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    $(document).ready(function() {
        $('.main-row').on('click', function(e) {
            if ($(e.target).closest('a, button').length) return;
            const targetId = $(this).data('target');
            const detailRow = $('#' + targetId);
            $(this).toggleClass('active-row');
            detailRow.toggleClass('active');
            $('.detail-row').not(detailRow).removeClass('active');
            $('.main-row').not($(this)).removeClass('active-row');
        });
    });
</script>
@endsection