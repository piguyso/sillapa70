@extends('layouts.apparea')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 font-sarabun text-slate-800">

    <div class="relative bg-gradient-to-r from-amber-800 to-orange-600 rounded-3xl shadow-xl p-8 md:p-12 mb-8 text-white overflow-hidden border-b-8 border-orange-500">
        <div class="relative z-10 text-white">
            <h2 class="text-xl md:text-2xl font-bold opacity-90 italic mb-2 text-white">
                {{ session('app_name')}} ระดับเครือข่ายสถานศึกษา {{Auth::user()->getschoolgroup->schoolgroup_name}}
            </h2>
            <h1 class="text-3xl md:text-5xl font-black tracking-tight uppercase italic text-white leading-none">
                บันทึกรายชื่อกรรมการ
            </h1>
            <p class="mt-4 text-orange-100 font-bold italic uppercase tracking-widest text-sm">
                <i class="ri-shield-user-line mr-1"></i> เลือกกลุ่มสาระเพื่อจัดการรายชื่อกรรมการตามรายการแข่งขัน
            </p>
        </div>
        <i class="ri-user-star-line absolute -right-10 -bottom-10 text-[15rem] opacity-10 rotate-12 text-white"></i>
    </div>

    <div class="bg-white rounded-3xl shadow-xl shadow-slate-100 border border-slate-100 overflow-hidden">
        <div class="bg-slate-50 px-8 py-5 border-b border-slate-100">
            <h2 class="text-lg font-black text-slate-800 flex items-center gap-2 uppercase italic tracking-wide">
                <i class="ri-folder-user-line text-orange-600"></i> รายการแข่งขันตามสาระการเรียนรู้
            </h2>
        </div>

        <div class="p-4 md:p-8">
            @if (session('status') || Session::has('massage'))
                <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 rounded-xl shadow-sm font-bold italic text-sm animate-fade-in">
                    <i class="ri-checkbox-circle-fill mr-2"></i> {{ session('status') ?: Session::get('massage') }}
                </div>
            @endif

            <div class="overflow-x-auto">
                <table class="w-full text-left border-separate border-spacing-y-3">
                    <thead>
                        <tr class="text-slate-400 text-[10px] uppercase tracking-[0.2em] font-black italic">
                            <th class="px-6 py-2 text-center w-16">#</th>
                            <th class="px-6 py-2">สาระการเรียนรู้</th>
                            <th class="px-6 py-2">รายละเอียด</th>
                            <th class="px-6 py-2 text-center">จำนวนรายการ</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm font-bold italic">
                        @php $x=1; @endphp
                        @forelse($activityinbranch as $l)
                        <tr class="group bg-white hover:bg-orange-50/50 transition-all border border-slate-100 shadow-sm rounded-2xl">
                            <td class="px-6 py-6 text-center text-slate-300 italic rounded-l-2xl border-l-4 border-transparent group-hover:border-orange-500">
                                {{ $x++ }}.
                            </td>
                            <td class="px-6 py-6">
                                <a href="area/jowviewactivity/{{$l[0]->activity_subject}}" class="font-black text-slate-800 text-base leading-tight hover:text-orange-600 transition-colors uppercase italic tracking-tight">
                                    {{$l[0]->getactivitysubject->subjectgroup_name}}
                                </a>
                                <p class="text-[9px] text-slate-400 mt-1 uppercase tracking-widest leading-none">Click to manage judges</p>
                            </td>
                            <td class="px-6 py-6 text-slate-500 font-medium">
                                {{$l[0]->getactivitysubject->subjectgroup_detail}}
                            </td>
                            <td class="px-6 py-6 text-center rounded-r-2xl">
                                <span class="px-4 py-1.5 bg-amber-900 text-white rounded-xl text-xs font-black shadow-lg shadow-orange-100 text-white leading-none">
                                    @if(isset($activitycount[$l[0]->activity_subject]))
                                        {{ $activitycount[$l[0]->activity_subject] }} รายการ
                                    @else
                                        0 รายการ
                                    @endif
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-slate-400 italic font-bold">
                                - ไม่พบข้อมูลกลุ่มสาระการเรียนรู้ -
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6 italic">
        <div class="p-6 bg-orange-50 rounded-3xl border border-orange-100 flex items-start gap-4 shadow-sm">
            <i class="ri-information-line text-2xl text-orange-600"></i>
            <div>
                <h4 class="font-black text-orange-800 uppercase text-xs mb-1">การจัดการกรรมการ</h4>
                <p class="text-xs text-orange-700 leading-relaxed font-bold">
                    โปรดเลือกกลุ่มสาระฯ เพื่อเข้าไปเพิ่มรายชื่อกรรมการแยกตามรายการแข่งขันย่อย ข้อมูลนี้จะใช้สำหรับพิมพ์ใบลงเวลาและเกียรติบัตรกรรมการ
                </p>
            </div>
        </div>
    </div>
</div>
@endsection