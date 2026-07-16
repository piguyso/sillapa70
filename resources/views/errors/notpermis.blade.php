@extends('layouts.apparea')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 font-sarabun text-slate-800">

    <div class="relative bg-gradient-to-r from-amber-800 to-orange-600 rounded-3xl shadow-xl p-8 md:p-12 mb-8 text-white overflow-hidden border-b-8 border-orange-500">
        <div class="relative z-10 text-white">
            <h1 class="text-3xl md:text-5xl font-black tracking-tight uppercase italic text-white leading-none mb-4">
                งานศิลปหัตถกรรมนักเรียน
            </h1>
            <p class="text-orange-100 font-bold italic uppercase tracking-widest text-lg text-white">
                สำนักงานเขตพื้นที่การศึกษาประถมศึกษาชุมพร เขต ๑
            </p>
        </div>
        <i class="ri-shield-user-line absolute -right-10 -bottom-10 text-[15rem] opacity-10 rotate-12 text-white"></i>
    </div>

    <div class="flex justify-center items-center py-10">
        <div class="w-full max-w-2xl">
            
            @if(Session::has('massage') || session('status'))
                <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 rounded-xl shadow-sm font-bold animate-fade-in italic text-sm flex items-center gap-3">
                    <i class="ri-checkbox-circle-fill text-xl text-emerald-500"></i> {{ Session::get('massage') ?: session('status') }}
                </div>
            @endif

            <div class="bg-white rounded-3xl shadow-2xl shadow-slate-200 border border-slate-100 overflow-hidden text-center p-12 relative">
                <div class="absolute top-0 left-0 w-full h-2 bg-orange-500"></div>
                
                <div class="mb-8">
                    <div class="w-24 h-24 bg-orange-50 text-orange-600 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="ri-door-lock-line text-6xl"></i>
                    </div>
                    <h2 class="text-3xl font-black text-slate-800 uppercase italic tracking-tight mb-2">
                        Permission Denied
                    </h2>
                    <p class="text-slate-400 font-bold italic uppercase tracking-[0.2em] text-[10px]">ตรวจสอบสิทธิ์การเข้าถึงข้อมูล</p>
                </div>

                <div class="bg-slate-50 rounded-2xl p-8 border border-slate-100 mb-8">
                    <p class="text-xl md:text-2xl font-black text-slate-700 leading-relaxed italic">
                        " คุณไม่มีสิทธิ์เข้าใช้งานในส่วนนี้ "
                    </p>
                    <p class="mt-2 text-slate-400 text-sm font-medium">โปรดตรวจสอบบัญชีผู้ใช้งานหรือระดับการเข้าถึงของท่าน</p>
                </div>

                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                    <a href="{{ url()->previous() }}" class="w-full sm:w-auto px-10 py-4 bg-slate-800 text-white rounded-2xl font-black text-xs hover:bg-slate-900 transition-all uppercase italic tracking-widest flex items-center justify-center gap-2 text-white">
                        <i class="ri-arrow-left-line"></i> Go Back
                    </a>
                    <a href="{{ url('/') }}" class="w-full sm:w-auto px-10 py-4 bg-orange-600 text-white rounded-2xl font-black text-xs hover:bg-orange-700 transition-all uppercase italic tracking-widest flex items-center justify-center gap-2 shadow-xl shadow-orange-100 text-white">
                        <i class="ri-home-line"></i> Dashboard
                    </a>
                </div>

                <div class="mt-12 pt-8 border-t border-slate-50">
                    <p class="text-slate-300 text-[10px] font-bold italic uppercase tracking-widest leading-relaxed">
                        หากท่านต้องการขอสิทธิ์เข้าถึงเพิ่มเติม <br>
                        กรุณาติดต่อผู้ดูแลระบบเครือข่าย หรือ แอดมินเขตพื้นที่การศึกษา
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection