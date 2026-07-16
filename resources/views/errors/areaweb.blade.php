@extends('layouts.apparea')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 font-sarabun">

    <div class="relative bg-gradient-to-r from-amber-800 to-orange-600 rounded-3xl shadow-xl p-8 md:p-12 mb-8 text-white overflow-hidden border-b-8 border-orange-500">
        <div class="relative z-10">
            <h1 class="text-3xl md:text-5xl font-black tracking-tight uppercase italic text-white leading-none mb-4">
                งานศิลปหัตถกรรมนักเรียน
            </h1>
            <p class="text-orange-100 font-bold italic uppercase tracking-widest text-lg">
                สำนักงานเขตพื้นที่การศึกษาประถมศึกษาชุมพร เขต ๑
            </p>
        </div>
        <i class="ri-error-warning-fill absolute -right-10 -bottom-10 text-[15rem] opacity-10 rotate-12 text-white"></i>
    </div>

    <div class="flex justify-center items-center py-10">
        <div class="w-full max-w-3xl">
            
            @if(Session::has('massage') || session('status'))
                <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 rounded-xl shadow-sm font-bold animate-fade-in italic text-sm flex items-center gap-3">
                    <i class="ri-checkbox-circle-fill text-xl text-emerald-500"></i> {{ Session::get('massage') ?: session('status') }}
                </div>
            @endif

            <div class="bg-white rounded-3xl shadow-2xl shadow-slate-200 border border-slate-100 overflow-hidden text-center p-12 relative">
                <div class="absolute top-0 left-0 w-full h-2 bg-red-500"></div>
                
                <div class="mb-8">
                    <div class="w-24 h-24 bg-red-50 text-red-500 rounded-full flex items-center justify-center mx-auto mb-6 animate-pulse">
                        <i class="ri-lock-2-line text-6xl"></i>
                    </div>
                    <h2 class="text-3xl font-black text-slate-800 uppercase italic tracking-tight mb-2">
                        System Access Restricted
                    </h2>
                    <p class="text-slate-400 font-bold italic uppercase tracking-[0.2em] text-xs">สถานะการเข้าใช้งานระบบ</p>
                </div>

                <div class="bg-slate-50 rounded-2xl p-8 border border-slate-100 mb-8">
                    <p class="text-xl md:text-2xl font-black text-slate-700 leading-relaxed">
                        ⚠️ ขออภัย... ขณะนี้แอดมินเขตพื้นที่ <br class="hidden md:block">
                        <span class="text-red-600">" ปิดใช้งานระบบในส่วนนี้ "</span>
                    </p>
                </div>

                <div class="flex flex-col md:flex-row gap-4 justify-center items-center">
                    <a href="{{ url()->previous() }}" class="px-8 py-3 bg-slate-800 text-white rounded-xl font-black text-xs hover:bg-slate-900 transition-all uppercase italic tracking-widest flex items-center gap-2">
                        <i class="ri-arrow-go-back-line"></i> ย้อนกลับ
                    </a>
                    <a href="{{ url('/') }}" class="px-8 py-3 bg-orange-600 text-white rounded-xl font-black text-xs hover:bg-orange-700 transition-all uppercase italic tracking-widest flex items-center gap-2 shadow-lg shadow-orange-100">
                        <i class="ri-home-4-line"></i> กลับหน้าหลัก
                    </a>
                </div>

                <p class="mt-12 text-slate-300 text-[10px] font-bold italic uppercase tracking-widest">
                    โปรดติดต่อเจ้าหน้าที่แอดมินเขตพื้นที่การศึกษา หากท่านคิดว่านี่คือข้อผิดพลาด
                </p>
            </div>
        </div>
    </div>
</div>
@endsection