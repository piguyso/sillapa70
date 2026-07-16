@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 text-slate-800">

    <div class="bg-gradient-to-r from-primary to-emerald-600 rounded-2xl shadow-lg p-8 mb-8 text-white">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="../jow" class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center hover:bg-white/40 transition-all border border-white/30 shadow-sm">
                    <i class="ri-arrow-left-line text-2xl text-white"></i>
                </a>
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold leading-tight uppercase tracking-tight">บันทึกรายชื่อกรรมการ</h1>
                    <p class="text-green-50 text-lg opacity-90 font-medium italic">
                        {{$subjectgroup->subjectgroup_name}} | ผู้ดูแล: {{Auth::user()->name.' '.Auth::user()->lastname}}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-gray-50/50 px-8 py-5 border-b border-gray-100 flex items-center justify-between">
            <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                <i class="ri-user-star-line text-primary"></i> รายชื่อกรรมการตัดสินผลการแข่งขัน
            </h2>
        </div>

        <div class="p-8">
            @if (session('status'))
                <div class="mb-8 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 flex items-center gap-3 rounded-xl shadow-sm">
                    <i class="ri-checkbox-circle-fill text-xl"></i> 
                    <span class="font-bold">{{ session('status') }}</span>
                </div>
            @endif

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse" id="table1">
                    <thead>
                        <tr class="text-gray-400 text-[10px] uppercase tracking-[0.2em] font-black border-b border-gray-100">
                            <th class="px-6 py-4 text-center w-16">#</th>
                            <th class="px-6 py-4">ชื่อกิจกรรมแข่งขัน</th>
                            <th class="px-6 py-4 text-center">ช่วงชั้น</th>
                            <th class="px-6 py-4">รายชื่อกรรมการ</th>
                            <th class="px-6 py-4 text-center">สถานะ</th>
                            <th class="px-6 py-4 text-center w-72">พิมพ์เกียรติบัตร / ข้อมูล</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 text-sm">
                        @php 
                            $x = ($activity->currentPage() - 1) * $activity->perPage();
                        @endphp
                        
                        @forelse($activity as $l)
                        <tr class="hover:bg-green-50/30 transition-colors group" x-data="{ openModal: false }">
                            <td class="px-6 py-6 text-center text-gray-300 font-black text-lg leading-none">
                                @php $x++; echo sprintf("%02d", $x); @endphp
                            </td>
                            <td class="px-6 py-6">
                                <a href="../addjow/{{$l->id}}" class="font-black text-gray-800 hover:text-primary transition-colors block text-base leading-tight uppercase">
                                    {{$l->activity_name}}
                                </a>
                            </td>
                            <td class="px-6 py-6 text-center">
                                <span class="bg-blue-50 text-blue-600 px-3 py-1 rounded-full text-[10px] font-black tracking-wider uppercase border border-blue-100">
                                    {{$l->getclass->class_name}}
                                </span>
                            </td>
                            <td class="px-6 py-6 leading-relaxed">
                                <div class="space-y-1">
                                    @php $hasJow = false; @endphp
                                    @foreach($jow as $i)
                                        @if($i->jow_activity == $l->id)
                                            <div class="flex items-center gap-2 text-gray-600 font-bold italic">
                                                <i class="ri-medal-line text-[10px] text-primary"></i>
                                                {{$i->getjowtitle->title}}{{$i->jow_name}} {{$i->jow_lastname}}
                                            </div>
                                            @php $hasJow = true; @endphp
                                        @endif
                                    @endforeach
                                    @if(!$hasJow)
                                        <span class="text-gray-300 italic text-xs">ยังไม่มีข้อมูลกรรมการ</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-6 text-center">
                                @php
                                    $statusText = getactivitystatus($l->activity_status);
                                    $statusColor = $l->activity_status == 1 ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700';
                                @endphp
                                <span class="{{ $statusColor }} px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest">
                                    {{ $statusText }}
                                </span>
                            </td>
                            <td class="px-6 py-6">
                                <div class="flex flex-col gap-2">
                                    <div class="grid grid-cols-2 gap-2">
                                        <a href="../printjow/{{$l->id}}" target="_blank" class="bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white px-2 py-2 rounded-xl transition text-[9px] font-black border border-blue-100 text-center uppercase tracking-tighter">
                                            <i class="ri-printer-line"></i> ไม่มีพื้นหลัง
                                        </a>
                                        <a href="../printjow_showbg/{{$l->id}}" target="_blank" class="bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white px-2 py-2 rounded-xl transition text-[9px] font-black border border-emerald-100 text-center uppercase tracking-tighter">
                                            <i class="ri-landscape-line"></i> มีพื้นหลัง
                                        </a>
                                    </div>
                                    <button @click="openModal = true" class="w-full bg-gray-50 text-gray-400 hover:bg-gray-800 hover:text-white py-2 rounded-xl transition text-[10px] font-black flex items-center justify-center gap-1 border border-gray-100 uppercase tracking-widest">
                                        <i class="ri-information-line"></i> รายละเอียดสนาม
                                    </button>
                                </div>

                                <div x-show="openModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 overflow-hidden" style="display: none;">
                                    <div x-show="openModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" @click="openModal = false"></div>
                                    <div x-show="openModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="relative bg-white w-full max-w-lg rounded-3xl shadow-2xl overflow-hidden border-t-8 border-primary text-slate-800">
                                        <div class="px-8 py-6 bg-gray-50/50 border-b flex justify-between items-center">
                                            <h4 class="font-black text-gray-800 uppercase tracking-widest text-sm italic">Activity Info</h4>
                                            <button @click="openModal = false" class="w-8 h-8 rounded-full flex items-center justify-center hover:bg-gray-200 transition-colors"><i class="ri-close-line"></i></button>
                                        </div>
                                        <div class="p-8">
                                            <h3 class="text-2xl font-black text-primary leading-tight uppercase mb-6">{{$l->activity_name}}</h3>
                                            <div class="space-y-4 text-sm font-bold">
                                                <div class="flex justify-between border-b border-gray-100 pb-2">
                                                    <span class="text-gray-400 uppercase text-[10px] tracking-widest">ศูนย์การแข่งขัน</span>
                                                    <span class="text-gray-800">{{$l->getactivitybranch->branch_name}}</span>
                                                </div>
                                                <div class="flex justify-between border-b border-gray-100 pb-2">
                                                    <span class="text-gray-400 uppercase text-[10px] tracking-widest">ระดับชั้น</span>
                                                    <span class="text-gray-800">{{$l->getclass->class_name}}</span>
                                                </div>
                                                <div class="flex justify-between border-b border-gray-100 pb-2 text-primary font-black">
                                                    <span class="text-gray-400 uppercase text-[10px] tracking-widest font-bold">สถานที่แข่ง</span>
                                                    <span>{{$l->activity_branchroom}}</span>
                                                </div>
                                                <div class="flex justify-between border-b border-gray-100 pb-2">
                                                    <span class="text-gray-400 uppercase text-[10px] tracking-widest">วัน/เวลา</span>
                                                    <span class="text-gray-800">{{Datethai($l->activity_day)}} | {{$l->activity_time}} น.</span>
                                                </div>
                                            </div>
                                            <div class="grid grid-cols-2 gap-4 mt-8">
                                                <div class="p-4 bg-blue-50 rounded-2xl border border-blue-100 text-center">
                                                    <div class="text-[9px] font-black text-blue-400 uppercase mb-1">นักเรียน (คน)</div>
                                                    <div class="text-2xl font-black text-blue-700 leading-none">{{$l->activity_studennumber}}</div>
                                                </div>
                                                <div class="p-4 bg-emerald-50 rounded-2xl border border-emerald-100 text-center">
                                                    <div class="text-[9px] font-black text-emerald-400 uppercase mb-1">เกณฑ์คะแนน</div>
                                                    <div class="text-2xl font-black text-emerald-700 leading-none">{{$l->activity_score}}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="px-8 py-6 bg-gray-50 text-right">
                                            <button @click="openModal = false" class="px-10 py-3 bg-white border border-gray-200 text-gray-500 font-black rounded-2xl hover:bg-gray-100 transition shadow-sm text-xs uppercase tracking-widest">Close</button>
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
                                    <span class="text-gray-400 font-bold">ไม่พบข้อมูลกิจกรรมแข่งขัน</span>
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

    @if(Session::has('massage'))
        <div class="mt-8 p-5 bg-primary text-white rounded-2xl shadow-xl shadow-emerald-100 flex items-center gap-4 animate-bounce">
            <i class="ri-notification-3-line text-2xl"></i>
            <span class="font-black tracking-wide">{{ Session::get('massage') }}</span>
        </div>
    @endif
</div>
@endsection