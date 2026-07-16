@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 text-slate-800 font-sarabun">
    
    <div class="bg-gradient-to-r from-emerald-700 to-green-600 rounded-3xl shadow-xl p-8 md:p-12 mb-8 text-white relative overflow-hidden text-center md:text-left">
        <div class="relative z-10">
            <h1 class="text-3xl md:text-5xl font-black mb-4 tracking-tight italic uppercase">
                งานศิลปหัตถกรรมนักเรียนครั้งที่ 70
            </h1>
            <p class="text-emerald-50 text-lg md:text-xl opacity-90 font-medium">
                <i class="ri-map-pin-2-fill mr-2"></i>สำนักงานเขตพื้นที่การศึกษาประถมศึกษาชุมพร เขต ๑
            </p>
        </div>
        <i class="ri-brush-fill absolute -right-10 -bottom-10 text-[15rem] opacity-10 rotate-12"></i>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden min-h-[400px] flex items-center justify-center">
        <div class="p-8 text-center max-w-lg">
            
            @if(Session::has('massage') || session('status'))
                <div class="mb-8 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl shadow-sm inline-flex items-center gap-3">
                    <i class="ri-checkbox-circle-fill text-xl"></i>
                    <span class="font-bold">{{ Session::get('massage') ?: session('status') }}</span>
                </div>
            @endif

            <div class="mb-6 relative inline-block">
                <div class="w-24 h-24 bg-red-50 text-red-500 rounded-full flex items-center justify-center mx-auto shadow-inner">
                    <i class="ri-lock-2-line text-5xl"></i>
                </div>
                <div class="absolute -top-1 -right-1 w-8 h-8 bg-red-500 text-white rounded-full border-4 border-white flex items-center justify-center animate-pulse">
                    <i class="ri-close-line font-bold"></i>
                </div>
            </div>

            <h2 class="text-3xl font-black text-gray-800 mb-4 uppercase italic">ปิดระบบบันทึกข้อมูล</h2>
            <p class="text-gray-500 font-medium mb-8 leading-relaxed">
                ขณะนี้ระบบการจัดการข้อมูลได้ถูกปิดการเข้าถึงชั่วคราว <br> 
                เนื่องจากหมดระยะเวลาที่กำหนด หรืออยู่ระหว่างการสรุปผลคะแนน <br>
                ขออภัยในความไม่สะดวก
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ url('/') }}" class="inline-flex items-center justify-center gap-2 px-8 py-3 bg-emerald-600 text-white rounded-2xl font-black hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-100">
                    <i class="ri-home-4-line"></i> กลับหน้าหลัก
                </a>
                <a href="{{ url('/viewall') }}" class="inline-flex items-center justify-center gap-2 px-8 py-3 bg-gray-100 text-gray-600 rounded-2xl font-black hover:bg-gray-200 transition-all">
                    <i class="ri-medal-line"></i> ดูสรุปเหรียญรางวัล
                </a>
            </div>
        </div>
    </div>
</div>
@endsection