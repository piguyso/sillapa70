@extends('layouts.apparea')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 font-sarabun text-slate-800">

    <div class="relative bg-gradient-to-r from-amber-800 to-orange-600 rounded-3xl shadow-xl p-8 md:p-12 mb-8 text-white overflow-hidden border-b-8 border-orange-500">
        <div class="relative z-10 text-white">
            <h2 class="text-xl md:text-2xl font-bold opacity-90 italic mb-2 text-white leading-none">
                {{ session('app_name')}} ระดับเครือข่ายสถานศึกษา {{session()->get('schoolgroup')}}
            </h2>
            <h1 class="text-3xl md:text-5xl font-black tracking-tight uppercase italic text-white leading-none text-white">
                เครือข่ายสถานศึกษา {{$schoolname[0]->getschoolgroup->schoolgroup_name ?? ''}}
            </h1>
            <p class="mt-4 text-orange-100 font-bold italic uppercase tracking-widest text-sm text-white">
                <i class="ri-community-line mr-1 text-white"></i> รายชื่อโรงเรียนสมาชิกและสรุปจำนวนทีมเข้าแข่งขัน
            </p>
        </div>
        <i class="ri-hotel-line absolute -right-10 -bottom-10 text-[15rem] opacity-10 rotate-12 text-white"></i>
    </div>

    @if (session('status') || Session::has('massage'))
        <div class="mb-8 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 rounded-xl shadow-sm font-bold animate-fade-in italic text-sm flex items-center gap-3">
            <i class="ri-checkbox-circle-fill text-xl text-emerald-500"></i> {{ session('status') ?: Session::get('massage') }}
        </div>
    @endif

    <div class="bg-white rounded-3xl shadow-xl shadow-slate-100 border border-slate-100 overflow-hidden text-slate-800">
        <div class="bg-slate-50 px-8 py-5 border-b border-slate-100 flex justify-between items-center text-slate-800">
            <div class="flex items-center gap-4 text-slate-800">
                <a href="{{url('area/'.session('area'))}}" class="w-10 h-10 flex items-center justify-center bg-white text-orange-600 rounded-xl border border-slate-200 hover:bg-orange-600 hover:text-white transition-all shadow-sm group">
                    <i class="ri-arrow-left-s-line text-2xl group-hover:-translate-x-1 transition-transform"></i>
                </a>
                <h2 class="text-base font-black text-slate-800 uppercase italic tracking-wide text-slate-800 leading-none">School Directory</h2>
            </div>
            <div class="hidden md:block text-[10px] font-black text-slate-400 uppercase italic tracking-widest text-slate-800">ปีการศึกษา {{ session('app_year') }}</div>
        </div>

        <div class="p-4 md:p-8 text-slate-800">
            <div class="overflow-x-auto text-slate-800">
                <table class="w-full text-left border-separate border-spacing-y-3 text-slate-800">
                    <thead>
                        <tr class="text-slate-400 text-[10px] uppercase tracking-[0.2em] font-black italic text-slate-400">
                            <th class="px-6 py-2 text-center w-16">#</th>
                            <th class="px-6 py-2">รายชื่อสถานศึกษา</th>
                            <th class="px-6 py-2 text-center">จำนวนทีมที่ลงทะเบียน</th>
                            <th class="px-6 py-2 text-right">ข้อมูลสรุปผล</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm font-bold italic text-slate-800">
                        @php 
                            $x = $schoolname->currentPage() == 1 ? 0 : $schoolname->perPage() * ($schoolname->currentPage() - 1);
                        @endphp
                        @forelse($schoolname as $l)
                        @php $x++; @endphp
                        <tr class="group bg-white hover:bg-orange-50/50 transition-all border border-slate-100 shadow-sm rounded-2xl text-slate-800">
                            <td class="px-6 py-6 text-center text-slate-300 italic rounded-l-2xl border-l-4 border-transparent group-hover:border-orange-500 text-slate-800">
                                {{ $x }}.
                            </td>
                            <td class="px-6 py-6 text-slate-800">
                                <a href="../showschooldetail/{{$l->id}}/{{$l->school_group}}" class="text-lg font-black text-slate-800 uppercase hover:text-orange-600 transition-colors tracking-tight text-slate-800">
                                    {{$l->school_name}}
                                </a>
                                <p class="text-[10px] text-slate-400 mt-1 uppercase tracking-widest leading-none text-slate-800">Chumphon Primary Educational Area Office</p>
                            </td>
                            <td class="px-6 py-6 text-center text-slate-800">
                                <div class="inline-flex items-center gap-2 px-4 py-2 bg-slate-100 text-slate-600 rounded-xl shadow-inner text-slate-600">
                                    <i class="ri-team-line text-orange-600"></i>
                                    <span class="text-base font-black">
                                        {{ isset($schoolcount[$l->id]) ? $schoolcount[$l->id] : 0 }}
                                    </span>
                                    <span class="text-[10px] font-bold uppercase">Teams</span>
                                </div>
                            </td>
                            <td class="px-6 py-6 text-right rounded-r-2xl text-slate-800">
                                <a href="../showschooldetail/{{$l->id}}/{{$l->school_group}}" class="inline-flex items-center gap-2 px-6 py-3 bg-amber-900 text-white rounded-xl font-black text-[10px] hover:bg-orange-600 transition-all shadow-lg shadow-orange-100 uppercase italic tracking-widest text-white">
                                    <i class="ri-bar-chart-box-line text-white"></i> View Medal Tally
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center py-20 font-black italic text-slate-300 text-slate-800">ไม่พบข้อมูลโรงเรียนในระบบ</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-8 text-slate-800">
                {{ $schoolname->links() }}
            </div>
        </div>
    </div>
</div>
@endsection