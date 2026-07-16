@extends('layouts.apparea')

@section('content')
<style>
    /* สไตล์สำหรับ Modal */
    #guidelineModal.hidden { display: none; }
    #modalBody::-webkit-scrollbar { width: 6px; }
    #modalBody::-webkit-scrollbar-track { background: #f1f1f1; }
    #modalBody::-webkit-scrollbar-thumb { background: #ea580c; border-radius: 10px; }
</style>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 font-sarabun text-slate-800">

    <div class="relative bg-gradient-to-r from-amber-800 to-orange-600 rounded-3xl shadow-xl p-8 md:p-12 mb-8 text-white overflow-hidden border-b-8 border-orange-500">
        <div class="relative z-10 text-white">
            <h2 class="text-xl md:text-2xl font-bold opacity-90 italic mb-2 text-white leading-none">
                {{ session('app_name')}} ระดับเครือข่ายสถานศึกษา {{Auth::user()->getschoolgroup->schoolgroup_name}}
            </h2>
            <h1 class="text-3xl md:text-5xl font-black tracking-tight uppercase italic text-white leading-none text-white">
                {{ $activity[0]->getactivitysubject->subjectgroup_name ?? 'รายการแข่งขัน' }}
            </h1>
        </div>
        <i class="ri-trophy-line absolute -right-10 -bottom-10 text-[15rem] opacity-10 rotate-12 text-white"></i>
    </div>

    <div class="bg-white rounded-3xl shadow-xl shadow-slate-100 border border-slate-100 overflow-hidden mb-8">
        <div class="bg-slate-50 px-8 py-5 border-b border-slate-100 flex flex-col md:flex-row justify-between items-center gap-4 text-slate-800">
            <div class="flex items-center gap-4 text-slate-800">
                <a href="{{url('area/areasubjectgroup/'.session('area'))}}" class="w-10 h-10 flex items-center justify-center bg-white text-orange-600 rounded-xl border border-slate-200 hover:bg-orange-600 hover:text-white transition-all shadow-sm group">
                    <i class="ri-arrow-left-s-line text-2xl group-hover:-translate-x-1 transition-transform"></i>
                </a>
                <h2 class="text-lg font-black text-slate-800 uppercase italic tracking-wide text-slate-800 leading-none">Activity List</h2>
            </div>
            <div class="text-[10px] font-black text-slate-400 uppercase italic tracking-widest text-slate-800">คลิกที่ชื่อเพื่อดูรายชื่อผู้สมัคร | คลิกที่แว่นขยายเพื่อดูเกณฑ์</div>
        </div>

        <div class="p-4 md:p-8 text-slate-800">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-separate border-spacing-y-3">
                    <thead>
                        <tr class="text-slate-400 text-[10px] uppercase tracking-[0.2em] font-black italic">
                            <th class="px-6 py-2 text-center w-16">#</th>
                            <th class="px-6 py-2">กิจกรรมการแข่งขัน</th>
                            <th class="px-6 py-2">ช่วงชั้น</th>
                            <th class="px-6 py-2 text-center">ลงทะเบียน</th>
                            <th class="px-6 py-2 text-right">จัดการผล</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm font-bold italic text-slate-800">
                        @php 
                            $x = $activity->currentPage() == 1 ? 0 : $activity->perPage() * ($activity->currentPage() - 1);
                        @endphp
                        @forelse($activity as $l)
                        @php $x++; @endphp
                        <tr class="group bg-white hover:bg-orange-50/30 transition-all border border-slate-100 shadow-sm rounded-2xl">
                            <td class="px-6 py-6 text-center text-slate-300 italic rounded-l-2xl border-l-4 border-transparent group-hover:border-orange-500">
                                {{ $x }}.
                            </td>
                            <td class="px-6 py-6">
                                <a href="{{url('/area/addaddschool/'.$l->id)}}" class="block group-hover:text-orange-600 transition-colors">
                                    <div class="font-black text-base leading-tight uppercase italic tracking-tight text-slate-800 group-hover:text-orange-600">
                                        {{$l->activity_name}}
                                    </div>
                                    <div class="text-[9px] text-slate-400 mt-1 uppercase italic tracking-widest">
                                        <i class="ri-user-search-line"></i> View Participants / รายชื่อผู้สมัคร
                                    </div>
                                </a>
                            </td>
                            <td class="px-6 py-6 text-slate-500 italic">{{$l->getclass->class_name ??''}}</td>
                            <td class="px-6 py-6 text-center">
                                <span class="px-4 py-1.5 bg-slate-100 text-slate-600 rounded-xl text-[11px] font-black shadow-inner">
                                    <i class="ri-team-line"></i> {{ $schoolcountactivity[$l->id] ?? 0 }} ทีม
                                </span>
                            </td>
                            <td class="px-6 py-6 text-right rounded-r-2xl">
                                <div class="flex justify-end gap-2">
                                    @if(Auth::user()->position == 5 || Auth::user()->position == 1)
                                        @if(isset($schoolcountactivity[$l->id]))
                                            <a href="{{url('area/centerviewactivityschool/'.$l->id)}}" class="px-4 py-2 bg-orange-600 text-white rounded-xl hover:bg-amber-900 transition-all text-[10px] font-black uppercase shadow-lg shadow-orange-100 flex items-center gap-2">
                                                <i class="ri-shield-flash-line"></i> บันทึกผล
                                            </a>
                                        @endif
                                    @endif
                                    
                                    <button type="button" 
                                        class="btn-open-modal w-10 h-10 flex items-center justify-center bg-slate-800 text-white rounded-xl hover:bg-orange-600 transition-all shadow-md"
                                        data-name="{{$l->activity_name}}"
                                        data-class="{{$l->getclass->class_name ?? '-'}}"
                                        data-students="{{$l->activity_studennumber}}"
                                        data-teachers="{{$l->activity_teachernumber}}"
                                        data-quota="{{$l->activity_schoolteamnumber}}"
                                        data-score="{{$l->activity_score}}"
                                        data-status="{{ getactivitystatus($l->getactivitystatus->activity_status ?? '0') }}"
                                        data-print="{{url('/area/printpointdoc/'.$l->id)}}"
                                        data-reg="{{url('/area/studentregisterform/'.$l->id)}}">
                                        <i class="ri-search-eye-line text-white"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center py-20 font-black italic text-slate-300">ไม่พบรายการแข่งขัน</td></tr>
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

<div id="guidelineModal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeGuidelineModal()"></div>
        <div class="relative bg-white rounded-3xl shadow-2xl max-w-2xl w-full border-b-8 border-orange-500 transition-all transform">
            <div class="bg-slate-50 px-8 py-6 border-b border-slate-100 flex justify-between items-center rounded-t-3xl">
                <h3 class="text-xl font-bold text-slate-800 italic uppercase"><i class="ri-information-line text-orange-600"></i> รายละเอียดเกณฑ์</h3>
                <button onclick="closeGuidelineModal()" class="text-slate-400 hover:text-orange-600 transition-colors"><i class="ri-close-line text-3xl"></i></button>
            </div>
            <div class="px-8 py-8" id="modalBodyContent">
                </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    $(document).ready(function() {
        const isAdmin = {{ (Auth::user()->position == 5 || Auth::user()->position == 1) ? 'true' : 'false' }};

        $('.btn-open-modal').on('click', function() {
            const data = $(this).data();
            
            let content = `
                <div class="space-y-6 font-sarabun text-slate-800 italic">
                    <div class="border-l-4 border-orange-500 pl-4 mb-6">
                        <p class="text-[10px] text-slate-400 uppercase font-black tracking-widest">รายการที่เลือก</p>
                        <h4 class="text-xl font-black text-slate-800">${data.name}</h4>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100 shadow-sm">
                            <p class="text-[10px] font-black text-orange-600 uppercase mb-4 tracking-widest">เกณฑ์เบื้องต้น</p>
                            <div class="space-y-3 text-xs font-bold text-slate-700">
                                <div class="flex justify-between border-b border-slate-100 pb-2"><span>ระดับชั้น:</span><span class="text-slate-900">${data.class}</span></div>
                                <div class="flex justify-between border-b border-slate-100 pb-2"><span>นักเรียน:</span><span class="text-slate-900">${data.students} คน/ทีม</span></div>
                                <div class="flex justify-between border-b border-slate-100 pb-2"><span>ครูควบคุม:</span><span class="text-slate-900">${data.teachers} คน/ทีม</span></div>
                            </div>
                        </div>
                        <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100 shadow-sm">
                            <p class="text-[10px] font-black text-orange-600 uppercase mb-4 tracking-widest">การตั้งค่าระบบ</p>
                            <div class="space-y-3 text-xs font-bold text-slate-700">
                                <div class="flex justify-between border-b border-slate-100 pb-2"><span>โควตาทีม:</span><span class="text-slate-900">${data.quota} ทีม/รร.</span></div>
                                <div class="flex justify-between border-b border-slate-100 pb-2"><span>คะแนนเต็ม:</span><span class="text-slate-900">${data.score} คะแนน</span></div>
                                <div class="flex justify-between border-b border-slate-100 pb-2"><span>สถานะ:</span><span class="text-emerald-600 font-black">${data.status}</span></div>
                            </div>
                        </div>
                    </div>`;

            if(isAdmin) {
                content += `
                    <div class="mt-6 flex flex-col gap-3">
                        <p class="text-[10px] font-black text-slate-400 uppercase italic px-2">เมนูเอกสารการแข่งขัน</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <a href="${data.print}" target="_blank" class="flex items-center justify-between px-6 py-4 bg-amber-900 text-white rounded-2xl hover:bg-orange-600 transition-all shadow-lg">
                                <span class="text-[11px] font-black italic uppercase tracking-widest">Score Sheet</span>
                                <i class="ri-printer-line text-xl"></i>
                            </a>
                            <a href="${data.reg}" target="_blank" class="flex items-center justify-between px-6 py-4 bg-slate-100 text-slate-700 rounded-2xl hover:bg-slate-200 transition-all shadow-sm">
                                <span class="text-[11px] font-black italic uppercase tracking-widest">Register Form</span>
                                <i class="ri-user-received-2-line text-xl text-orange-600"></i>
                            </a>
                        </div>
                    </div>`;
            }

            content += `</div>`;
            
            $('#modalBodyContent').html(content);
            $('#guidelineModal').removeClass('hidden');
            $('body').addClass('overflow-hidden');
        });
    });

    function closeGuidelineModal() {
        $('#guidelineModal').addClass('hidden');
        $('body').removeClass('overflow-hidden');
    }

    $(document).keydown(function(e) {
        if (e.keyCode === 27) closeGuidelineModal();
    });
</script>
@endsection