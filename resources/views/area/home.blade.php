@extends('layouts.apparea')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 font-sarabun">

    <div class="relative bg-gradient-to-r from-orange-500 to-amber-900 rounded-3xl shadow-xl mb-8 overflow-hidden border-b-8 border-orange-600">
        <div class="relative z-10 px-6 py-12 sm:px-12 sm:py-16 text-center text-white">
            <h1 class="text-2xl sm:text-4xl font-black tracking-tight mb-2 italic uppercase text-white">
                {{ session('app_name')}}
            </h1>
            <h2 class="text-xl sm:text-2xl font-bold text-orange-100 italic mb-4">
                ระดับเครือข่ายสถานศึกษา {{session()->get('schoolgroup')}} {{ 'ปีการศึกษา '. session('app_year') }}
            </h2>
            <div class="flex justify-center">
                <div class="text-xs sm:text-sm border border-white/30 rounded-full px-4 py-1 flex items-center bg-white/10 backdrop-blur-sm">
                    <span class="w-2 h-2 bg-yellow-400 rounded-full mr-2 animate-pulse"></span>
                    {{ session('app_area') ?: 'สพป.ชุมพร เขต 1' }}
                </div>
            </div>
        </div>
        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-64 h-64 rounded-full bg-white opacity-10 text-white"></div>
        <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-64 h-64 rounded-full bg-orange-400 opacity-20 text-white"></div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-8">
        <div class="bg-orange-500 px-6 py-4 font-black text-white flex items-center gap-2 italic uppercase tracking-wider text-white">
            <i class="ri-notification-3-line text-white"></i> ประกาศจากศูนย์การแข่งขัน
        </div>
        <div class="p-6 space-y-4 font-bold">
            @forelse($notice_2 as $b)
                <div class="flex items-center gap-3 border-b border-slate-50 pb-3 last:border-0">
                    <span class="bg-orange-100 text-orange-700 text-[10px] px-2 py-1 rounded-lg uppercase tracking-tighter italic shadow-sm">
                        {{$b->notice_owner}}
                    </span>
                    <a href="notice_detail/{{$b->id}}" class="text-slate-700 hover:text-orange-600 transition-colors italic">
                        <span class="text-slate-400 text-xs mr-1">[{{Datethai6($b->created_at)}}]</span> {{$b->notice_text}}
                    </a>
                    @php
                        $to_time = strtotime(now());
                        $from_time = strtotime($b->created_at);
                        if(round(abs($to_time - $from_time) / 60,2)<= 180) {
                            echo '<span class="bg-red-500 text-white text-[9px] px-1.5 py-0.5 rounded-full animate-pulse shadow-sm italic">NEW</span>';
                        }
                    @endphp
                </div>
            @empty
                <p class="text-slate-400 text-center py-4 italic">- ไม่พบข้อมูลประกาศ -</p>
            @endforelse
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-8">
        <div class="bg-amber-900 px-6 py-4 font-black text-white flex items-center gap-2 italic uppercase tracking-wider text-white">
            <i class="ri-trophy-line text-white"></i> ผลการแข่งขันล่าสุด
        </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4 font-bold">
            @forelse($firstactivity as $b)
                <div class="flex items-center gap-2 text-sm p-3 bg-slate-50 rounded-xl hover:bg-orange-50 transition-all border border-transparent hover:border-orange-100">
                    <i class="ri-checkbox-circle-line text-orange-500"></i>
                    <a href="viewactivitydetail/{{$b->school_activity}}" class="text-slate-700 hover:text-orange-700 truncate">
                        {{$b->getactivityname->activity_name}} <span class="text-slate-400 text-xs">({{$b->getactivityname->getclass->class_name}})</span>
                    </a>
                    @php
                        $to_time = strtotime(now());
                        $from_time = strtotime($b->updated_at);
                        if(round(abs($to_time - $from_time) / 60,2)<= 10){
                            echo '<span class="bg-red-500 text-white text-[9px] px-1.5 py-0.5 rounded-full animate-pulse shadow-sm italic">New</span>';
                        }
                    @endphp
                </div>
            @empty
                <p class="col-span-2 text-slate-400 text-center py-4 italic">- ยังไม่มีการประกาศผล -</p>
            @endforelse
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden mb-8">
        <div class="bg-orange-600 px-8 py-5 text-white flex justify-between items-center text-white">
            <h3 class="font-black italic uppercase tracking-widest text-white"><i class="ri-medal-line text-white"></i> สรุปเหรียญการแข่งขันรายโรงเรียน TOP 10</h3>
            <a href="./viewall" class="text-[10px] bg-white/20 hover:bg-white/40 px-3 py-1 rounded-lg text-white transition backdrop-blur-sm uppercase italic">View All</a>
        </div>
        <div class="overflow-x-auto p-4">
            <table class="w-full text-left border-separate border-spacing-y-2">
                <thead>
                    <tr class="text-slate-400 text-[10px] uppercase tracking-[0.2em] font-black italic">
                        <th class="px-6 py-2 text-center w-16">อันดับ</th>
                        <th class="px-6 py-2">โรงเรียน</th>
                        <th class="px-6 py-2 text-center text-amber-500">ทอง</th>
                        <th class="px-6 py-2 text-center text-slate-400">เงิน</th>
                        <th class="px-6 py-2 text-center text-amber-700">ทองแดง</th>
                        <th class="px-6 py-2 text-center">รวม</th>
                    </tr>
                </thead>
                <tbody class="text-sm font-bold italic">
                    @php $num1 = 1; @endphp
                    @forelse($dbrank as $l)
                        <tr class="bg-white hover:bg-slate-50 transition-all border border-slate-100 shadow-sm rounded-2xl group">
                            <td class="px-6 py-4 text-center text-slate-400 border-l-4 border-transparent group-hover:border-orange-500 rounded-l-2xl">{{$num1}}</td>
                            <td class="px-6 py-4 text-slate-800 uppercase tracking-tight">
                                <a href="showschooldetail/{{$l->school_name}}/0" class="hover:text-orange-600 transition-colors">
                                    {{$selectschool[$l->school_name] ?? ''}}
                                </a>
                            </td>
                            <td class="px-6 py-4 text-center text-amber-500 bg-amber-50/30">{{$l->Gold}}</td>
                            <td class="px-6 py-4 text-center text-slate-400">{{$l->Silver}}</td>
                            <td class="px-6 py-4 text-center text-amber-700">{{$l->Bronce}}</td>
                            <td class="px-6 py-4 text-center text-orange-800 rounded-r-2xl bg-orange-50/30">{{$l->Gold+$l->Silver+$l->Bronce}}</td>
                        </tr>
                        @php $num1++; @endphp
                    @empty
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden mb-8 italic text-slate-800">
        <div class="bg-slate-800 px-8 py-4 font-black text-white uppercase italic tracking-widest text-xs text-white">
            <i class="ri-bar-chart-2-line text-white"></i> สถิติการแข่งขัน งานศิลปหัตถกรรมนักเรียน
        </div>
        <div class="p-8 grid grid-cols-2 lg:grid-cols-4 gap-4 font-black italic uppercase">
            <div class="p-6 bg-orange-50 border border-orange-100 rounded-3xl text-center shadow-sm">
                <p class="text-[10px] text-orange-600 mb-1">นักเรียน</p>
                <p class="text-4xl text-orange-700">{{number_format($countstudent)}}</p>
            </div>
            <div class="p-6 bg-slate-50 border border-slate-200 rounded-3xl text-center shadow-sm text-slate-800">
                <p class="text-[10px] text-slate-500 mb-1">ครู</p>
                <p class="text-4xl text-amber-900">{{number_format($countteacher)}}</p>
            </div>
            <div class="p-6 bg-orange-50 border border-orange-100 rounded-3xl text-center shadow-sm">
                <p class="text-[10px] text-orange-600 mb-1">กรรมการตัดสิน</p>
                <p class="text-4xl text-orange-700">{{number_format($countjow)}}</p>
            </div>
            <div class="p-6 bg-slate-50 border border-slate-200 rounded-3xl text-center shadow-sm text-slate-800">
                <p class="text-[10px] text-slate-500 mb-1">กรรมการดำเนินงาน</p>
                <p class="text-4xl text-amber-900">{{number_format($countjow2)}}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8 font-bold italic text-slate-800">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden text-slate-800">
            <div class="bg-orange-700 px-6 py-3 text-white text-xs uppercase text-white">ผลการแข่งขันตามกลุ่มสาระ</div>
            <div class="overflow-x-auto text-slate-800">
                <table class="w-full text-xs text-slate-800">
                    <tbody class="divide-y divide-slate-50 text-slate-600 text-slate-800">
                        @foreach($subjectgroup as $l)
                        <tr class="hover:bg-orange-50/30 text-slate-800">
                            <td class="px-6 py-3 text-slate-800"><a href="./viewactivity/{{$l->id}}" class="hover:text-orange-600">{{$l->subjectgroup_name}}</a></td>
                            <td class="px-6 py-3 text-right text-slate-800">
                                <span class="bg-slate-100 px-2 py-0.5 rounded italic shadow-inner text-slate-800">
                                    {{$activitycount[$l->id] ?? 0}} / {{ $activityfinish[$l->id] ?? 0 }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden text-slate-800">
            <div class="bg-amber-900 px-6 py-3 text-white text-xs uppercase text-white">จำนวนโรงเรียนในเครือข่าย</div>
            <div class="overflow-x-auto text-slate-800">
                <table class="w-full text-xs text-slate-800">
                    <tbody class="divide-y divide-slate-50 text-slate-600 text-slate-800">
                        @foreach($schoolgroup as $l)
                        <tr class="hover:bg-amber-50/30 text-slate-800">
                            <td class="px-6 py-3 text-slate-800"><a href="showschool/{{$l->id}}" class="hover:text-amber-700">เครือข่าย{{$l->schoolgroup_name}}</a></td>
                            <td class="px-6 py-3 text-right text-amber-900 font-black italic text-slate-800">{{$schoolgroupcount[$l->id] ?? 0}} โรงเรียน</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div> @endsection