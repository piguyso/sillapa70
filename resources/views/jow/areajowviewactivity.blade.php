@extends('layouts.apparea')

@section('content')
<style>
    .detail-row { display: none; @apply bg-slate-50/50; }
    .detail-row.active { display: table-row; }
    .main-row.active-row { @apply bg-orange-50/50 shadow-inner; }
</style>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 font-sarabun text-slate-800">

    <div class="relative bg-gradient-to-r from-amber-800 to-orange-600 rounded-3xl shadow-xl p-8 md:p-12 mb-8 text-white overflow-hidden border-b-8 border-orange-500">
        <div class="relative z-10 text-white">
            <h2 class="text-xl md:text-2xl font-bold opacity-90 italic mb-2 text-white">
                {{ session('app_name')}} ระดับเครือข่ายสถานศึกษา {{Auth::user()->getschoolgroup->schoolgroup_name}}
            </h2>
            <h1 class="text-3xl md:text-5xl font-black tracking-tight uppercase italic text-white leading-none">
                บันทึกรายชื่อกรรมการ
            </h1>
        </div>
        <i class="ri-user-star-line absolute -right-10 -bottom-10 text-[15rem] opacity-10 rotate-12 text-white"></i>
    </div>

    <div class="bg-white rounded-3xl shadow-xl shadow-slate-100 border border-slate-100 overflow-hidden">
        <div class="bg-slate-50 px-8 py-5 border-b border-slate-100 flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-4">
                <a href="{{url('areajow')}}" class="w-10 h-10 flex items-center justify-center bg-white text-orange-600 rounded-xl border border-slate-200 hover:bg-orange-600 hover:text-white transition-all shadow-sm group">
                    <i class="ri-arrow-left-s-line text-2xl group-hover:-translate-x-1 transition-transform"></i>
                </a>
                <div>
                    <h2 class="text-lg font-black text-slate-800 uppercase italic tracking-wide leading-none">{{$subjectgroup->subjectgroup_name}}</h2>
                    <p class="text-[10px] text-slate-400 mt-1 font-bold italic uppercase tracking-widest leading-none">
                        ผู้บันทึก: {{Auth::user()->name.' '.Auth::user()->lastname}}
                    </p>
                </div>
            </div>
            @if (session('status'))
                <div class="text-emerald-600 font-bold animate-pulse text-sm italic">
                    <i class="ri-checkbox-circle-line"></i> {{ session('status') }}
                </div>
            @endif
        </div>

        <div class="p-4 md:p-8">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-separate border-spacing-y-3">
                    <thead>
                        <tr class="text-slate-400 text-[10px] uppercase tracking-[0.2em] font-black italic">
                            <th class="px-6 py-2 text-center w-16">#</th>
                            <th class="px-6 py-2">ชื่อกิจกรรม/ช่วงชั้น</th>
                            <th class="px-6 py-2">รายชื่อกรรมการ</th>
                            <th class="px-6 py-2 text-right">พิมพ์เกียรติบัตร / จัดการ</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm font-bold italic">
                        @php 
                            $x = $activity->currentPage() == 1 ? 0 : $activity->perPage() * ($activity->currentPage() - 1);
                        @endphp
                        @forelse($activity as $l)
                        @php $x++; @endphp
                        <tr class="group bg-white hover:bg-orange-50/30 transition-all border border-slate-100 shadow-sm rounded-2xl cursor-pointer main-row" data-target="detail-{{$l->id}}">
                            <td class="px-6 py-6 text-center text-slate-300 italic rounded-l-2xl border-l-4 border-transparent group-hover:border-orange-500">
                                {{ $x }}.
                            </td>
                            <td class="px-6 py-6">
                                <a href="../addjow/{{$l->id}}" class="text-slate-800 hover:text-orange-600 font-black text-base uppercase tracking-tight transition-colors block leading-tight">
                                    {{$l->activity_name}}
                                </a>
                                <span class="text-[10px] text-slate-400 italic">{{$l->getclass->class_name}}</span>
                            </td>
                            <td class="px-6 py-6">
                                <div class="text-[11px] text-slate-600 leading-relaxed font-medium">
                                    @php $hasJow = false; @endphp
                                    @foreach($jow as $i)
                                        @if($i->jow_activity == $l->id)
                                            <div class="flex items-center gap-1"><i class="ri-checkbox-circle-fill text-orange-400 text-[10px]"></i> {{$i->getjowtitle->title}}{{$i->jow_name}} {{$i->jow_lastname}}</div>
                                            @php $hasJow = true; @endphp
                                        @endif
                                    @endforeach
                                    @if(!$hasJow) <span class="text-slate-300 font-normal italic">- ยังไม่ได้บันทึกกรรมการ -</span> @endif
                                </div>
                            </td>
                            <td class="px-6 py-6 text-right rounded-r-2xl">
                                <div class="flex justify-end gap-2">
                                    <a href="../printjow/{{$l->id}}" target="_blank" class="px-3 py-2 bg-slate-100 text-slate-600 rounded-xl hover:bg-slate-800 hover:text-white transition-all text-[10px] font-black uppercase italic shadow-sm">No BG</a>
                                    <a href="../printjow_showbg/{{$l->id}}" target="_blank" class="px-3 py-2 bg-orange-100 text-orange-700 rounded-xl hover:bg-orange-600 hover:text-white transition-all text-[10px] font-black uppercase italic shadow-sm">With BG</a>
                                    <button class="w-10 h-10 flex items-center justify-center bg-slate-50 text-slate-400 rounded-xl border border-slate-100 hover:bg-orange-500 hover:text-white transition-all">
                                        <i class="ri-information-line text-lg"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <tr class="detail-row" id="detail-{{$l->id}}">
                            <td colspan="4" class="px-10 py-8 border-x-8 border-orange-500 rounded-b-3xl shadow-inner">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                                        <h4 class="text-orange-600 font-black text-xs uppercase italic mb-4 tracking-widest"><i class="ri-file-info-line"></i> รายละเอียดกิจกรรม</h4>
                                        <div class="grid grid-cols-2 gap-y-3 text-xs">
                                            <span class="text-slate-400">กลุ่มสาระฯ:</span><span class="text-slate-800 font-bold">{{$l->getactivitysubject->subjectgroup_name}}</span>
                                            <span class="text-slate-400">ระดับชั้น:</span><span class="text-slate-800 font-bold">{{$l->getclass->class_name}}</span>
                                            <span class="text-slate-400">นักเรียน:</span><span class="text-slate-800 font-bold">{{$l->activity_studennumber}} คน</span>
                                            <span class="text-slate-400">คะแนนเต็ม:</span><span class="text-slate-800 font-bold">{{$l->activity_score}} คะแนน</span>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-center">
                                        <a href="../addjow/{{$l->id}}" class="px-8 py-4 bg-amber-900 text-white rounded-2xl font-black text-sm hover:bg-orange-600 transition-all shadow-xl shadow-orange-100 uppercase italic tracking-widest text-white">
                                            <i class="ri-user-settings-line mr-1 text-white"></i> จัดการรายชื่อกรรมการกิจกรรมนี้
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center py-10 font-bold italic text-slate-400">ไม่พบรายการแข่งขันในกลุ่มสาระนี้</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-8">
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