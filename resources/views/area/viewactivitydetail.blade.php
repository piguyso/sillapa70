@extends('layouts.apparea')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 font-sarabun text-slate-800">

    <div class="relative bg-gradient-to-r from-amber-800 to-orange-600 rounded-3xl shadow-xl p-8 md:p-12 mb-8 text-white overflow-hidden border-b-8 border-orange-500 text-white">
        <div class="relative z-10 text-white">
            <h2 class="text-lg md:text-xl font-bold opacity-90 italic mb-2 text-white leading-none">
                @guest
                    {{ session('app_name')}} ระดับเครือข่ายสถานศึกษา {{session()->get('schoolgroup')}}
                @else
                    {{ session('app_name')}} ระดับเครือข่ายสถานศึกษา {{session()->get('schoolgroup')}}
                @endguest
                ปีการศึกษา {{ session('app_year') }}
            </h2>
            <h1 class="text-2xl md:text-4xl font-black tracking-tight uppercase italic text-white leading-tight text-white mb-4">
                {{$activity->getactivitysubject->subjectgroup_name ??''}} <br class="md:hidden"> 
                <span class="text-orange-200">→</span> {{$activity->activity_name ??''}}
            </h1>
            <div class="flex flex-wrap gap-4 text-[11px] font-black uppercase italic tracking-widest text-white">
                <span class="bg-white/20 px-3 py-1 rounded-lg backdrop-blur-sm text-white"><i class="ri-user-heart-line"></i> นักเรียน {{$activity->activity_studennumber ??'0'}} คน</span>
                <span class="bg-white/20 px-3 py-1 rounded-lg backdrop-blur-sm text-white"><i class="ri-user-star-line"></i> ครู {{$activity->activity_teachernumber??'0'}} คน</span>
                <span class="bg-white/20 px-3 py-1 rounded-lg backdrop-blur-sm text-white"><i class="ri-team-line"></i> {{$activity->activity_schoolteamnumber??'0'}} ทีม/รร.</span>
            </div>
        </div>
        <i class="ri-medal-line absolute -right-10 -bottom-10 text-[15rem] opacity-10 rotate-12 text-white"></i>
    </div>

    @if(Session::has('massage') || session('status'))
        <div class="mb-8 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 rounded-xl shadow-sm font-bold animate-fade-in italic text-sm flex items-center gap-3">
            <i class="ri-checkbox-circle-fill text-xl"></i> {{ Session::get('massage') ?: session('status') }}
        </div>
    @endif

    <div class="bg-white rounded-3xl shadow-xl shadow-slate-100 border border-slate-100 overflow-hidden text-slate-800">
        <div class="bg-slate-50 px-8 py-5 border-b border-slate-100 flex justify-between items-center text-slate-800">
            <div class="flex items-center gap-4 text-slate-800">
                <a href="../viewactivity/{{$activity->activity_subject ??''}}" class="w-10 h-10 flex items-center justify-center bg-white text-orange-600 rounded-xl border border-slate-200 hover:bg-orange-600 hover:text-white transition-all shadow-sm group">
                    <i class="ri-arrow-left-s-line text-2xl group-hover:-translate-x-1 transition-transform"></i>
                </a>
                <h2 class="text-base font-black text-slate-800 uppercase italic tracking-wide text-slate-800 leading-none">Competition Results</h2>
            </div>
        </div>

        <div class="p-4 md:p-8 text-slate-800">
            <div class="overflow-x-auto text-slate-800">
                <table class="w-full text-left border-separate border-spacing-y-3 text-slate-800">
                    <thead>
                        <tr class="text-slate-400 text-[10px] uppercase tracking-[0.2em] font-black italic text-slate-400">
                            <th class="px-4 py-2 text-center w-12">#</th>
                            <th class="px-4 py-2">สถานศึกษา</th>
                            <th class="px-4 py-2">รายชื่อนักเรียน/ครู</th>
                            <th class="px-4 py-2 text-center">คะแนน</th>
                            <th class="px-4 py-2 text-center">อันดับ</th>
                            <th class="px-4 py-2 text-center">เหรียญ</th>
                            <th class="px-4 py-2 text-right">เกียรติบัตร</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs font-bold italic text-slate-800">
                        @php $x=0; @endphp
                        @forelse($addschool as $l)
                        @php $x++; @endphp
                        <tr class="bg-white hover:bg-orange-50/50 transition-all border border-slate-100 shadow-sm rounded-2xl group text-slate-800">
                            <td class="px-4 py-6 text-center text-slate-300 rounded-l-2xl border-l-4 border-transparent group-hover:border-orange-500 text-slate-800">
                                {{ $x }}
                            </td>
                            <td class="px-4 py-6 text-slate-800">
                                <p class="text-slate-800 font-black uppercase leading-tight text-slate-800">{{$l->getschoolname->school_name ??''}}</p>
                                <span class="text-[9px] text-slate-400 mt-1 inline-block uppercase tracking-tighter">เครือข่าย: {{$l->getschoolgroup->schoolgroup_name??''}}</span>
                            </td>
                            <td class="px-4 py-6 text-slate-800">
                                <div class="grid grid-cols-1 gap-4 text-slate-800">
                                    <div class="text-slate-700 font-medium">
                                        <p class="text-[9px] text-orange-600 font-black uppercase mb-1">Students:</p>
                                        @foreach($student as $s)
                                            @foreach($s->getstudentname as $st)
                                                @if($l->id == $st->school && $st->type == 0)
                                                    <div class="leading-relaxed">• {{$st->getstudenttitle->title}}{{$st->name}} {{$st->lastname}}</div>
                                                @endif
                                            @endforeach
                                        @endforeach
                                    </div>
                                    <div class="text-slate-500 font-medium italic">
                                        <p class="text-[9px] text-slate-400 font-black uppercase mb-1">Teachers:</p>
                                        @foreach($student as $s)
                                            @foreach($s->getstudentname as $st)
                                                @if($l->id == $st->school && $st->type == 1)
                                                    <div class="leading-relaxed opacity-80">- {{$st->getstudenttitle->title}}{{$st->name}} {{$st->lastname}}</div>
                                                @endif
                                            @endforeach
                                        @endforeach
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-6 text-center text-slate-800">
                                <span class="text-lg font-black text-slate-800">
                                    @if($l->activity_status == "1" || $l->activity_status == "99") {{ $l->student_score }} @else - @endif
                                </span>
                            </td>
                            <td class="px-4 py-6 text-center text-slate-800">
                                <div class="w-10 h-10 flex items-center justify-center bg-slate-100 rounded-full mx-auto font-black text-slate-800 text-slate-800 shadow-inner">
                                    @if($l->activity_status == "1" || $l->activity_status == "99") {{ $l->activity_rank }} @else - @endif
                                </div>
                            </td>
                            <td class="px-4 py-6 text-center text-slate-800">
                                @if($l->activity_status == "1" || $l->activity_status == "99")
                                    @php $medal = getmedel($l->student_score ?? ''); @endphp
                                    @if($medal == 'เหรียญทอง')
                                    <span class="px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest shadow-sm bg-yellow-400 text-yellow-900">{{ $medal }}</span>
                                    @elseif($medal == 'เหรียญเงิน')
                                    <span class="px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest shadow-sm bg-slate-300 text-slate-800">{{ $medal }}</span>
                                    @elseif($medal == 'เหรียญทองแดง')
                                    <span class="px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest shadow-sm bg-orange-300 text-orange-900">{{ $medal }}</span>
                                    @endif
                                @else
                                    <span class="text-slate-300 font-normal">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-6 text-right rounded-r-2xl text-slate-800">
                                <div class="flex flex-col gap-1 items-end text-slate-800">
                                    @if($l->activity_status == "1" || $l->activity_status == "99")
                                        <form method="post" target="_blank" action="{{url('area/printPDF')}}">
                                            {!!csrf_field()!!}
                                            <input type="hidden" name="id" value="{{$l->id}}"/>
                                            <input type="hidden" name="student_score" value="{{$l->student_score}}"/>
                                            <input type="hidden" name="x" value="{{$l->activity_rank}}"/>
                                            <button type="submit" class="w-full px-3 py-1.5 bg-slate-100 text-slate-600 rounded-lg hover:bg-slate-800 hover:text-white transition-all text-[9px] font-black uppercase italic shadow-sm">No BG</button>
                                        </form>
                                        <form method="post" target="_blank" action="{{url('area/printPDF2')}}">
                                            {!!csrf_field()!!}
                                            <input type="hidden" name="id" value="{{$l->id}}"/>
                                            <input type="hidden" name="student_score" value="{{$l->student_score}}"/>
                                            <input type="hidden" name="x" value="{{$l->activity_rank}}"/>
                                            <button type="submit" class="w-full px-3 py-1.5 bg-orange-100 text-orange-700 rounded-lg hover:bg-orange-600 hover:text-white transition-all text-[9px] font-black uppercase italic shadow-sm">With BG</button>
                                        </form>
                                    @elseif($l->activity_status == "98")
                                        <span class="text-[9px] text-red-400 italic font-bold">งดมอบเกียรติบัตร</span>
                                    @else
                                        <span class="text-[9px] text-slate-300 italic font-bold">รอประกาศผล</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center py-20 font-black italic text-slate-400 uppercase">No competition data found</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-8">
                {{ $addschool->links() }}
            </div>
        </div>
    </div>
</div>
@endsection