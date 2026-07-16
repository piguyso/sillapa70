@extends('layouts.apparea')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10 font-sarabun text-slate-800">

    <div class="relative bg-gradient-to-r from-amber-800 to-orange-600 rounded-3xl shadow-xl p-8 md:p-12 mb-8 text-white overflow-hidden border-b-8 border-orange-500">
        <div class="relative z-10 text-white">
            <h2 class="text-lg md:text-xl font-bold opacity-90 italic mb-2 text-white">
                {{ session('app_name')}} ระดับเครือข่ายสถานศึกษา {{Auth::user()->getschoolgroup->schoolgroup_name}}
            </h2>
            <h1 class="text-2xl md:text-4xl font-black tracking-tight uppercase italic text-white leading-none">
                แก้ไขกรรมการดำเนินงาน
            </h1>
        </div>
        <i class="ri-settings-fill absolute -right-10 -bottom-10 text-[10rem] opacity-10 rotate-12 text-white"></i>
    </div>

    @if(Session::has('massage'))
        <div class="mb-8 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 rounded-xl shadow-sm font-bold animate-fade-in italic text-sm flex items-center gap-3">
            <i class="ri-checkbox-circle-fill text-xl"></i> {{ Session::get('massage') }}
        </div>
    @endif

    <div class="bg-white rounded-3xl shadow-xl shadow-slate-100 border border-slate-100 overflow-hidden">
        <div class="bg-slate-50 px-8 py-6 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{url('area/addjow2')}}" class="w-10 h-10 flex items-center justify-center bg-white text-orange-600 rounded-xl border border-slate-200 hover:bg-orange-600 hover:text-white transition-all shadow-sm group">
                    <i class="ri-arrow-left-s-line text-2xl group-hover:-translate-x-1 transition-transform"></i>
                </a>
                <h3 class="text-base font-black text-slate-800 uppercase italic tracking-widest leading-none">Edit Center Staff</h3>
            </div>
            <span class="text-[10px] bg-amber-100 text-amber-700 px-3 py-1 rounded-full font-black uppercase italic tracking-tighter text-slate-800">STAFF ID: #{{$jows->id}}</span>
        </div>

        <div class="p-8 md:p-12 text-slate-800">
            <form class="space-y-8 font-bold italic text-slate-800" method="POST" action="{{ Route('areajow.update',$jows->id) }}">
                {{ csrf_field() }}
                <input type="hidden" name="_method" value="put">
                <input type="hidden" name="school" value="{{$jows->id}}">

                <div class="grid grid-cols-1 md:grid-cols-1 gap-6 text-slate-800">
                    <div class="space-y-2 text-slate-800">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] italic text-slate-800">คำนำหน้า (Prefix)</label>
                        <select id="jow_title" name="jow_title" class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition-all font-black text-slate-700 outline-none text-slate-800">
                            @foreach($title as $l)
                                <option value="{{$l->title_id}}" @if($jows->jow_title==$l->title_id) selected @endif>{{$l->title}}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('jow_title'))
                            <p class="text-red-500 text-[10px] mt-1 font-black italic text-slate-800">* {{ $errors->first('jow_title') }}</p>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-slate-800">
                        <div class="space-y-2 text-slate-800">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] italic text-slate-800">ชื่อ (First Name)</label>
                            <input id="jow_name" type="text" name="jow_name" value="{{$jows->jow_name}}" required
                                   class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition-all font-black text-slate-700 shadow-inner text-slate-800"
                                   placeholder="ระบุชื่อ...">
                            @if ($errors->has('jow_name'))
                                <p class="text-red-500 text-[10px] mt-1 font-black italic text-slate-800">* {{ $errors->first('jow_name') }}</p>
                            @endif
                        </div>

                        <div class="space-y-2 text-slate-800">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] italic text-slate-800">นามสกุล / รายละเอียด (Last Name)</label>
                            <input id="jow_lastname" type="text" name="jow_lastname" value="{{ $jows->jow_lastname }}" required
                                   class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition-all font-black text-slate-700 shadow-inner text-slate-800"
                                   placeholder="ระบุนามสกุล...">
                            @if ($errors->has('jow_lastname'))
                                <p class="text-red-500 text-[10px] mt-1 font-black italic text-slate-800">* {{ $errors->first('jow_lastname') }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-4 pt-6 text-slate-800">
                    <button type="submit" class="flex-1 py-4 bg-orange-600 text-white rounded-2xl font-black text-base hover:bg-orange-700 hover:scale-[1.02] active:scale-95 transition-all shadow-xl shadow-orange-100 uppercase italic tracking-widest text-white">
                        <i class="ri-refresh-line mr-1 text-white"></i> อัปเดตข้อมูลกรรมการดำเนินการ
                    </button>
                    <a href="{{url('area/addjow2')}}" class="inline-flex items-center justify-center px-8 py-4 bg-slate-100 text-slate-500 rounded-2xl font-black hover:bg-slate-200 transition-all uppercase italic text-xs tracking-widest text-slate-500">
                        ยกเลิก
                    </a>
                </div>
            </form>
        </div>

        <div class="px-8 py-6 bg-slate-50 border-t border-slate-100 text-slate-400 italic text-slate-800">
            <p class="text-[10px] font-black uppercase tracking-widest mb-1 text-slate-800">หมายเหตุสำคัญ:</p>
            <p class="text-[11px] font-bold leading-relaxed text-slate-400">
                ข้อมูลกรรมการดำเนินงานศูนย์การแข่งขันจะถูกบันทึกเพื่อใช้ในการสรุปรายชื่อเจ้าหน้าที่ผู้ปฏิบัติงาน และการออกเกียรติบัตรในลำดับถัดไป กรุณาตรวจสอบความถูกต้องของตัวสะกดอีกครั้งครับ
            </p>
        </div>
    </div>
</div>
@endsection