@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 text-slate-800">
    
    <div class="bg-gradient-to-r from-primary to-emerald-600 rounded-2xl shadow-lg p-8 mb-8 text-white">
        <h1 class="text-3xl md:text-4xl font-bold mb-2 uppercase tracking-tight">
            <i class="ri-government-line mr-2"></i>รายการแข่งขันตามสาระการเรียนรู้
        </h1>
        <p class="text-green-50 text-lg opacity-90 font-medium italic">สำหรับคณะกรรมการตัดสินผลการแข่งขัน</p>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        
        <div class="bg-gray-50/50 px-8 py-5 border-b border-gray-100 flex items-center justify-between">
            <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                <i class="ri-stack-line text-primary"></i>
                กลุ่มสาระการเรียนรู้ที่เปิดตัดสินผล
            </h2>
        </div>

        <div class="p-8">
            @if (session('status'))
                <div class="mb-8 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 flex items-center gap-3 rounded-r-xl shadow-sm">
                    <i class="ri-information-fill text-xl"></i>
                    <span class="font-bold">{{ session('status') }}</span>
                </div>
            @endif

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-gray-400 text-[10px] uppercase tracking-[0.2em] font-black border-b border-gray-100">
                            <th class="px-6 py-4 text-center w-20">ลำดับ</th>
                            <th class="px-6 py-4">กลุ่มสาระการเรียนรู้</th>
                            <th class="px-6 py-4 text-center">จำนวนรายการแข่งขัน</th>
                            <th class="px-4 py-4 font-bold text-center w-32">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @php $x = 1; @endphp
                        @forelse($activityinbranch as $l)
                        <tr class="hover:bg-green-50/30 transition-colors group">
                            <td class="px-6 py-6 text-center text-gray-300 font-black text-lg">
                                {{ sprintf("%02d", $x++) }}
                            </td>
                            <td class="px-6 py-6">
                                <a href="jowviewactivity/{{$l[0]->activity_subject}}" class="font-black text-gray-800 hover:text-secondary transition-colors block text-lg leading-tight uppercase tracking-tight">
                                    {{$l[0]->getactivitysubject->subjectgroup_name}}
                                </a>
                            </td>
                            <td class="px-6 py-6 text-center">
                                <span class="inline-flex items-center gap-2 bg-white border border-gray-200 px-4 py-1.5 rounded-full text-sm font-bold text-gray-600 shadow-sm">
                                    <i class="ri-list-settings-line text-primary"></i>
                                    {{ isset($activitycount[$l[0]->activity_subject]) ? $activitycount[$l[0]->activity_subject] : 0 }} รายการ
                                </span>
                            </td>
                            <td class="px-6 py-6 text-center">
                                <a href="jowviewactivity/{{$l[0]->activity_subject}}" class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all shadow-sm">
                                    <i class="ri-information-line text-xl"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-20 text-center">
                                <div class="bg-gray-50 rounded-3xl p-10 border border-dashed border-gray-200 inline-block">
                                    <i class="ri-clipboard-line text-5xl text-gray-200 block mb-4"></i>
                                    <span class="text-gray-400 font-bold italic tracking-wide">ไม่พบข้อมูลกลุ่มสาระการเรียนรู้ในระบบ</span>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if(Session::has('massage'))
        <div class="mt-8 p-5 bg-primary text-white rounded-2xl shadow-xl shadow-emerald-100 flex items-center gap-4 animate-in fade-in slide-in-from-bottom-4 duration-500">
            <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                <i class="ri-notification-3-line text-xl"></i>
            </div>
            <span class="font-black tracking-wide">{{ Session::get('massage') }}</span>
        </div>
    @endif
</div>
@endsection