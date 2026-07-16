@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 text-slate-800">
     
    <div class="bg-gradient-to-r from-primary to-emerald-600 rounded-2xl shadow-lg p-8 mb-8 text-white">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h1 class="text-3xl md:text-4xl font-bold mb-2">จัดการข้อมูลการแข่งขัน</h1>
                <p class="text-green-50 text-lg opacity-90">
                    <i class="ri-book-open-line mr-1"></i> {{$subjectgroup->subjectgroup_name}} 
                    <span class="mx-2 opacity-50">|</span>
                    <i class="ri-user-settings-line mr-1"></i> {{Auth::user()->name.' '.Auth::user()->lastname}}
                </p>
            </div>
            <div class="bg-white/10 backdrop-blur-md p-4 rounded-2xl border border-white/20 flex items-center gap-4">
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center text-2xl">
                    <i class="ri-apps-2-line"></i>
                </div>
                <div>
                    <div class="text-[10px] uppercase tracking-widest opacity-70 font-bold">ระบบบริหารจัดการ</div>
                    <div class="font-bold text-lg">{{session('app_name')}}</div>
                </div>
            </div>
        </div>
    </div>
 @if(Session::has('massage'))
        <div class="mt-8 p-5 bg-blue-600 text-white rounded-2xl shadow-xl shadow-blue-200 flex items-center gap-4 animate-bounce">
            <i class="ri-notification-3-line text-2xl"></i>
            <span class="font-black tracking-wide">{{ Session::get('massage') }}</span>
        </div>
    @endif
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
       
        <div class="bg-gray-50/50 px-8 py-5 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="../centersillapa" class="group inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-white text-primary border border-gray-200 hover:bg-primary hover:text-white transition-all shadow-sm">
                    <i class="ri-arrow-left-s-line text-2xl group-hover:-translate-x-1 transition-transform"></i>
                </a>
               
                <div>
                    <h2 class="text-xl font-bold text-gray-800 leading-none mb-1">รายการกิจกรรมแข่งขัน</h2>
                    <p class="text-xs text-gray-400">เลือกรายการเพื่อแก้ไขผลการแข่งหรือพิมพ์เอกสารสนาม</p>
                </div>
            </div>
        </div>

        <div class="p-8">
            @if (session('status'))
                <div class="mb-8 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 flex items-center gap-3 rounded-xl shadow-sm">
                    <i class="ri-checkbox-circle-fill text-xl"></i>
                    <span class="font-bold">{{ session('status') }}</span>
                </div>
            @endif

            <div class="overflow-x-auto">
                <table class="w-full text-left border-separate border-spacing-y-3" id="table1">
                    <thead>
                        <tr class="text-gray-400 text-[10px] uppercase tracking-[0.2em] font-black">
                            <th class="px-4 py-2 text-center w-16">ลำดับ</th>
                            <th class="px-4 py-2">กิจกรรม / ระดับชั้น</th>
                            <th class="px-4 py-2 text-center">ทีม</th>
                            <th class="px-4 py-2">ข้อมูลสนามแข่ง</th>
                            <th class="px-4 py-2 text-center">สถานะ</th>
                            <th class="px-4 py-2 text-center w-72">การจัดการและพิมพ์เอกสาร</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @php $x = ($activity->currentPage() - 1) * $activity->perPage(); @endphp
                        @forelse($activity as $l)
                        <tr class="group bg-white hover:bg-gray-50/50 transition-all border border-gray-100 shadow-sm rounded-2xl" x-data="{ openModal: false }">
                            <td class="px-4 py-6 text-center text-gray-300 font-black text-lg">
                                @php $x++; echo sprintf("%02d", $x); @endphp
                            </td>
                            <td class="px-4 py-6">
                                <a href="../centerviewactivityschool/{{$l->id}}" class="font-black text-gray-800 hover:text-primary transition-colors block text-base leading-tight mb-2 uppercase">
                                    {{$l->activity_name}}
                                </a>
                                <span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-full text-[10px] font-black tracking-wider uppercase border border-blue-100">
                                    {{$l->getclass->class_name}}
                                </span>
                            </td>
                            <td class="px-4 py-6 text-center">
                                <div class="w-12 h-12 rounded-2xl bg-gray-50 flex flex-col items-center justify-center mx-auto border border-gray-100">
                                    <span class="text-lg font-black text-gray-700 leading-none">{{ isset($schoolcountactivity[$l->id]) ? $schoolcountactivity[$l->id] : 0 }}</span>
                                    <span class="text-[8px] text-gray-400 font-bold uppercase">TEAM</span>
                                </div>
                            </td>
                            <td class="px-4 py-6">
                                <div class="space-y-1 text-xs">
                                    <div class="flex items-center gap-2 text-gray-700 font-bold italic">
                                        <i class="ri-calendar-todo-fill text-primary"></i> {{datethai($l->activity_day)}}
                                    </div>
                                    <div class="flex items-center gap-2 text-gray-400">
                                        <i class="ri-map-pin-2-line"></i> {{$l->activity_branchroom}}
                                    </div>
                                    <div class="flex items-center gap-2 text-gray-400">
                                        <i class="ri-time-line"></i> {{$l->activity_time}} น.
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-6 text-center">
                                @php
                                    $statusText = getactivitystatus($l->activity_status);
                                    $statusColor = $l->activity_status == 1 ? 'bg-emerald-500 shadow-emerald-200' : 'bg-amber-500 shadow-amber-200';
                                @endphp
                                <span class="flex items-center justify-center gap-2 mx-auto">
                                    <span class="w-2 h-2 rounded-full {{ $statusColor }} shadow-[0_0_8px_rgba(0,0,0,0.1)]"></span>
                                    <span class="text-[10px] font-black text-gray-600 uppercase">{{ $statusText }}</span>
                                </span>
                            </td>
                            <td class="px-4 py-6">
                                <div class="grid grid-cols-2 gap-2">
                                    <button @click="openModal = true" class="col-span-1 py-2 bg-gray-100 text-gray-600 rounded-xl hover:bg-gray-800 hover:text-white transition-all text-[10px] font-black flex items-center justify-center gap-1">
                                        <i class="ri-eye-line text-xs"></i> ดูข้อมูล
                                    </button>
                                    <a href="{{'../centereditactiviy/'.$l->id}}" class="col-span-1 py-2 bg-amber-500 text-white rounded-xl hover:shadow-lg hover:shadow-amber-200 transition-all text-[10px] font-black flex items-center justify-center gap-1">
                                        <i class="ri-edit-2-line text-xs"></i> แก้ไขรายการ
                                    </a>
                                    
                                    <div class="col-span-2 grid grid-cols-2 gap-1.5 mt-1 border-t border-gray-100 pt-3">
                                        <a href="{{url('/')}}/showprintpointdoc/{{$l->id}}" target="_blank" class="p-2 bg-white border border-gray-200 text-blue-600 rounded-lg hover:bg-blue-600 hover:text-white transition-all text-[9px] font-bold flex items-center gap-2">
                                            <i class="ri-edit-box-line"></i> ใบบันทึกคะแนน
                                        </a>
                                        <a href="../studentregisterform/{{$l->id}}" target="_blank" class="p-2 bg-white border border-gray-200 text-blue-600 rounded-lg hover:bg-blue-600 hover:text-white transition-all text-[9px] font-bold flex items-center gap-2">
                                            <i class="ri-group-line"></i> ใบรายงานตัว นร.
                                        </a>
                                        <a href="../teacherregisterform/{{$l->id}}" target="_blank" class="p-2 bg-white border border-gray-200 text-indigo-600 rounded-lg hover:bg-indigo-600 hover:text-white transition-all text-[9px] font-bold flex items-center gap-2">
                                            <i class="ri-id-card-line"></i> ใบลงเวลาครู
                                        </a>
                                        <a href="../jowregisterform/{{$l->id}}" target="_blank" class="p-2 bg-white border border-gray-200 text-indigo-600 rounded-lg hover:bg-indigo-600 hover:text-white transition-all text-[9px] font-bold flex items-center gap-2">
                                            <i class="ri-user-shield-line"></i> ใบลงเวลากรรมการ
                                        </a>
                                        <a href="../printtop/{{$l->id}}" target="_blank" class="p-2 bg-white border border-gray-200 text-indigo-600 rounded-lg hover:bg-indigo-600 hover:text-white transition-all text-[9px] font-bold flex items-center gap-2">
                                            <i class="ri-user-shield-line"></i> ใบปะหน้าซอง
                                        </a>
                                        <a href="../printspeedmath/{{$l->id}}" target="_blank" class="p-2 bg-white border border-gray-200 text-indigo-600 rounded-lg hover:bg-indigo-600 hover:text-white transition-all text-[9px] font-bold flex items-center gap-2">
                                            <i class="ri-user-shield-line"></i> (เฉพาะคิดเลขเร็ว)
                                        </a>
                                        <a href="../printjow_showbg/{{$l->id}}" target="_blank" class="col-span-2 p-2 bg-emerald-600 text-white rounded-lg hover:shadow-lg hover:shadow-emerald-200 transition-all text-[9px] font-bold flex items-center justify-center gap-2">
                                            <i class="ri-medal-2-line text-xs"></i> พิมพ์เกียรติบัตรกรรมการ
                                        </a>
                                    </div>
                                </div>

                                <div x-show="openModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 overflow-hidden" style="display: none;">
                                    <div x-show="openModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" @click="openModal = false"></div>
                                    <div x-show="openModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0" class="relative bg-white w-full max-w-lg rounded-3xl shadow-2xl overflow-hidden border-t-8 border-primary">
                                        <div class="px-8 py-6 bg-gray-50/50 border-b border-gray-100 flex justify-between items-center">
                                            <h4 class="font-black text-gray-800 uppercase tracking-widest text-sm italic">Competition Detail</h4>
                                            <button @click="openModal = false" class="w-8 h-8 rounded-full flex items-center justify-center hover:bg-gray-200 transition-colors"><i class="ri-close-line"></i></button>
                                        </div>
                                        <div class="p-8">
                                            <div class="mb-6">
                                                <h3 class="text-2xl font-black text-primary leading-tight uppercase">{{$l->activity_name}}</h3>
                                                <p class="text-xs text-gray-400 font-bold tracking-widest mt-1">CODE: #ACT-{{$l->id}}</p>
                                            </div>
                                            <div class="grid grid-cols-2 gap-4 mb-6">
                                                <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                                                    <div class="text-[9px] font-black text-gray-400 uppercase mb-2">โครงสร้างทีม</div>
                                                    <div class="text-sm font-bold text-gray-800">นักเรียน {{$l->activity_studennumber}} / ครู {{$l->activity_teachernumber}}</div>
                                                </div>
                                                <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                                                    <div class="text-[9px] font-black text-gray-400 uppercase mb-2">เกณฑ์คะแนน</div>
                                                    <div class="text-sm font-bold text-gray-800">{{$l->activity_score}} แต้มเต็ม</div>
                                                </div>
                                            </div>
                                            <div class="space-y-3 bg-blue-50/50 p-6 rounded-2xl border border-blue-100">
                                                <div class="flex items-start gap-4 text-sm font-bold text-blue-900">
                                                    <i class="ri-map-pin-2-fill text-blue-500 text-lg"></i>
                                                    <div>
                                                        <div class="text-[10px] uppercase opacity-50">สถานที่แข่ง</div>
                                                        {{$l->activity_branchroom}}
                                                    </div>
                                                </div>
                                                <div class="flex items-start gap-4 text-sm font-bold text-blue-900">
                                                    <i class="ri-calendar-event-fill text-blue-500 text-lg"></i>
                                                    <div>
                                                        <div class="text-[10px] uppercase opacity-50">กำหนดการ</div>
                                                        {{Datethai($l->activity_day)}} | {{$l->activity_time}} น.
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="px-8 py-5 bg-gray-50 text-right">
                                            <button @click="openModal = false" class="px-8 py-2 bg-white text-gray-600 font-black rounded-xl border border-gray-200 hover:bg-gray-100 transition shadow-sm text-xs uppercase tracking-widest">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-20 text-center">
                                <div class="bg-gray-50 rounded-3xl p-10 border border-dashed border-gray-200 inline-block">
                                    <i class="ri-draft-line text-5xl text-gray-200 block mb-4"></i>
                                    <span class="text-gray-400 font-bold">ไม่พบข้อมูลกิจกรรมในกลุ่มสาระนี้</span>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-12 flex justify-center">
                {{ $activity->links() }}
            </div>
        </div>
    </div>

  
</div>
@endsection