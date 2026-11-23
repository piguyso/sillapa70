@extends('layouts.apparea')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 font-sarabun text-slate-800">

    <div class="relative bg-gradient-to-r from-amber-800 to-orange-600 rounded-3xl shadow-xl p-8 md:p-12 mb-8 text-white relative overflow-hidden border-b-8 border-orange-500">
        <div class="relative z-10">
            <h1 class="text-2xl md:text-4xl font-black mb-4 tracking-tight italic uppercase">
                {{ session('app_name')}}
            </h1>
            <p class="text-orange-100 text-lg md:text-xl opacity-90 font-medium italic">
                <i class="ri-book-open-line mr-2"></i>{{Auth::user()->getschoolgroup->schoolgroup_name}} {{ 'ปีการศึกษา '. session('app_year') }}
            </p>
            <div class="mt-4 inline-block bg-white/20 backdrop-blur-md px-4 py-1 rounded-full text-sm font-bold border border-white/30 italic">
                รายการแข่งขันตามสาระการเรียนรู้
            </div>
        </div>
        <i class="ri-microscope-line absolute -right-10 -bottom-10 text-[15rem] opacity-10 rotate-12 text-white"></i>
    </div>

    @if (session('status') || Session::has('massage'))
        <div class="mb-8 p-4 bg-orange-50 border-l-4 border-orange-500 text-orange-700 flex items-center gap-3 rounded-xl shadow-sm font-bold animate-fade-in text-sm italic">
            <i class="ri-checkbox-circle-fill text-xl"></i> {{ session('status') ?: Session::get('massage') }}
        </div>
    @endif

    @php
        $pt = $progressTotal;
        $pending = max(0, $pt['registered'] - $pt['announced']);
        $pct = $pt['registered'] > 0 ? round($pt['announced'] * 100 / $pt['registered']) : 0;
    @endphp
    @if(($orphanTeams ?? 0) > 0)
        <div class="mb-4 p-4 bg-amber-50 border-l-4 border-amber-400 text-amber-800 rounded-xl text-sm font-bold">
            <i class="ri-error-warning-line"></i> พบ {{ number_format($orphanTeams) }} ทีมที่อ้างอิงรายการแข่งขันซึ่งไม่มีอยู่ในงานนี้ (ข้อมูลตกค้าง) จึงไม่นับรวมในสรุปด้านล่าง
        </div>
    @endif
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 text-center">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">รายการที่มีทีมลงทะเบียน</p>
            <p class="text-3xl font-black text-slate-800">{{ number_format($pt['registered']) }}</p>
            <p class="text-[10px] text-slate-400">{{ number_format($pt['teams']) }} ทีม</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 text-center">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">ประกาศผลแล้ว</p>
            <p class="text-3xl font-black text-emerald-600">{{ number_format($pt['announced']) }} <span class="text-sm">รายการ</span></p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 text-center">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">ยังไม่ประกาศผล</p>
            <p class="text-3xl font-black text-red-500">{{ number_format($pending) }} <span class="text-sm">รายการ</span></p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 text-center">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">ความคืบหน้า</p>
            <p class="text-3xl font-black text-slate-800">{{ $pct }}%</p>
            <div class="h-2 bg-slate-100 rounded-full mt-2 overflow-hidden"><div class="h-2 bg-emerald-500 rounded-full" style="width: {{ $pct }}%"></div></div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2">
            <div class="bg-white rounded-3xl shadow-xl shadow-slate-100 border border-slate-100 overflow-hidden">
                <div class="bg-slate-50 px-8 py-5 border-b border-slate-100 flex justify-between items-center">
                    <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2 uppercase tracking-wide italic leading-none">
                        <i class="ri-list-settings-line text-orange-600"></i> Subject Groups List
                    </h2>
                </div>
                
                <div class="p-4 md:p-8">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-separate border-spacing-y-3">
                            <thead>
                                <tr class="text-slate-400 text-[10px] uppercase tracking-[0.2em] font-black">
                                    <th class="px-6 py-2 text-center w-16 italic">#</th>
                                    <th class="px-6 py-2 italic">สาระการเรียนรู้</th>
                                    <th class="px-6 py-2 italic">รายละเอียด</th>
                                    <th class="px-6 py-2 text-center italic">จำนวนรายการ</th>
                                    <th class="px-6 py-2 text-center italic">ลงทะเบียน</th>
                                    <th class="px-6 py-2 text-center italic">ประกาศผลแล้ว</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm font-bold italic">
                                @php $index = 0; @endphp
                                @forelse($subjectgroup as $l)
                                @php $index++; @endphp
                                <tr class="group bg-white hover:bg-orange-50/50 transition-all border border-slate-100 shadow-sm rounded-2xl">
                                    <td class="px-6 py-6 text-center text-slate-300 italic rounded-l-2xl border-l-4 border-transparent group-hover:border-orange-500">
                                        {{ $index }}.
                                    </td>
                                    <td class="px-6 py-6">
                                        <a href="{{url('area/areasubjectgroup/activity/'.$l->id)}}" class="font-black text-slate-800 text-base leading-tight hover:text-orange-600 transition-colors uppercase italic tracking-tight">
                                            {{$l->subjectgroup_name}}
                                        </a>
                                    </td>
                                    <td class="px-6 py-6 text-slate-500 font-medium">
                                        {{$l->subjectgroup_detail}}
                                    </td>
                                    <td class="px-6 py-6 text-center">
                                        <span class="px-3 py-1 bg-orange-100 text-orange-700 rounded-lg text-xs font-black shadow-inner">
                                            @if(isset($activitycount[$l->id]))
                                                {{ $activitycount[$l->id] }} รายการ
                                            @else
                                                0 รายการ
                                            @endif
                                        </span>
                                    </td>
                                    @php $p = $progress[$l->id] ?? ['registered' => 0, 'announced' => 0, 'teams' => 0]; @endphp
                                    <td class="px-6 py-6 text-center">
                                        <span class="text-slate-700">{{ $p['registered'] }} รายการ</span>
                                        <div class="text-[10px] text-slate-400">{{ $p['teams'] }} ทีม</div>
                                    </td>
                                    <td class="px-6 py-6 text-center rounded-r-2xl">
                                        @if($p['registered'] === 0)
                                            <span class="text-slate-300">-</span>
                                        @elseif($p['announced'] >= $p['registered'])
                                            <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-lg text-xs font-black">ครบ {{ $p['announced'] }}/{{ $p['registered'] }}</span>
                                        @else
                                            <span class="px-3 py-1 bg-red-50 text-red-600 rounded-lg text-xs font-black">{{ $p['announced'] }}/{{ $p['registered'] }} (ค้าง {{ $p['registered'] - $p['announced'] }})</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-10 text-center text-slate-400 italic font-bold">
                                        ไม่พบข้อมูลรายการแข่งขัน
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-8">
                        {{ $subjectgroup->links() }}
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-1">
            @if(adminchk())
            <div class="bg-white rounded-3xl shadow-xl shadow-slate-100 border border-slate-100 overflow-hidden sticky top-10">
                <div class="bg-amber-900 px-8 py-6 text-white flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                        <i class="ri-add-box-line text-xl text-white"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-black leading-none text-white uppercase italic">Add Subject</h3>
                        <p class="text-orange-200 text-[10px] mt-1 font-bold italic uppercase opacity-80 text-white leading-none">เพิ่มรายวิชาการแข่งขัน</p>
                    </div>
                </div>

                <div class="p-8">
                    <form method="POST" action="{{ Route('subjectgroup.store') }}" class="space-y-6 font-bold italic">
                        {{ csrf_field() }}

                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">ชื่อสาระการเรียนรู้</label>
                            <input type="text" name="subjectgroup_name" value="{{ old('subjectgroup_name') }}" required
                                   class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition-all font-bold text-slate-700 shadow-inner"
                                   placeholder="ระบุชื่อสาระ...">
                            @if ($errors->has('subjectgroup_name'))
                                <p class="text-red-500 text-[10px] mt-1 leading-none italic font-black">* {{ $errors->first('subjectgroup_name') }}</p>
                            @endif
                        </div>

                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">รายละเอียด</label>
                            <input type="text" name="subjectgroup_detail" value="{{ old('subjectgroup_detail') }}"
                                   class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition-all font-bold text-slate-700 shadow-inner"
                                   placeholder="คำอธิบายเพิ่มเติม...">
                            @if ($errors->has('subjectgroup_detail'))
                                <p class="text-red-500 text-[10px] mt-1 leading-none italic font-black">* {{ $errors->first('subjectgroup_detail') }}</p>
                            @endif
                        </div>

                        <button type="submit" class="w-full py-4 bg-orange-600 text-white rounded-2xl font-black text-sm hover:bg-orange-700 hover:scale-[1.02] active:scale-95 transition-all shadow-xl shadow-orange-100 uppercase italic tracking-widest text-white">
                            <i class="ri-save-3-line mr-1 text-white"></i> บันทึกข้อมูลใหม่
                        </button>
                    </form>
                </div>
            </div>
            @else
            <div class="bg-orange-50 rounded-3xl p-8 border border-orange-100 border-dashed text-center italic">
                <i class="ri-lock-2-line text-4xl text-orange-300 mb-4 block"></i>
                <h4 class="font-black text-orange-800 uppercase tracking-tight">Access Restricted</h4>
                <p class="text-xs text-orange-600 mt-2 leading-relaxed">เฉพาะผู้ดูแลระบบเท่านั้นที่สามารถเพิ่มรายวิชาการแข่งขันในเครือข่ายนี้ได้</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection