@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/material_green.css">

<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-10 font-sarabun text-slate-800">
    
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ url('dayofsillapa') }}" 
           class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-white text-emerald-600 border border-gray-200 hover:bg-emerald-600 hover:text-white transition-all shadow-sm group">
            <i class="ri-arrow-left-s-line text-2xl group-hover:-translate-x-1 transition-transform"></i>
        </a>
        <div>
            <h1 class="text-2xl font-black text-gray-800 leading-none mb-1 uppercase italic text-emerald-700">Edit Date</h1>
            <p class="text-sm text-gray-400 font-bold tracking-widest uppercase italic text-slate-400">แก้ไขข้อมูลวันแข่งขัน</p>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-xl shadow-slate-100 border border-gray-100 overflow-hidden">
        <div class="bg-emerald-600 px-8 py-6 text-white flex items-center gap-3">
            <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                <i class="ri-calendar-check-line text-2xl text-white"></i>
            </div>
            <div>
                <h3 class="text-lg font-black leading-none text-white uppercase italic">Update Competition Date</h3>
                <p class="text-emerald-100 text-[10px] mt-1 font-bold italic uppercase opacity-80 text-white">ID Reference: #{{ $dayofsillapa->id }}</p>
            </div>
        </div>

        <div class="p-8 md:p-12">
            @if(Session::has('massage'))
                <div class="mb-8 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 flex items-center gap-3 rounded-xl shadow-sm animate-fade-in font-bold text-sm italic">
                    <i class="ri-checkbox-circle-fill text-xl"></i> {{ Session::get('massage') }}
                </div>
            @endif

            <form method="POST" action="{{ Route('dayofsillapa.update', $dayofsillapa->id) }}" class="space-y-8 font-bold">
                {{ csrf_field() }}
                <input type="hidden" name="_method" value="PATCH">

                <div class="space-y-3">
                    <label for="dayofsillapa" class="block text-xs font-black text-slate-500 uppercase tracking-widest italic">แก้ไขวันที่แข่งขัน</label>
                    <div class="relative group">
                        <input id="datepicker" type="text" 
                               class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-black text-lg text-slate-700 shadow-inner cursor-pointer" 
                               name="dayofsillapa" 
                               value="{{ $dayofsillapa->dayofsillapa }}" 
                               placeholder="เลือกวันที่ใหม่..." required readonly>
                        <i class="ri-calendar-2-line absolute right-5 top-1/2 -translate-y-1/2 text-emerald-500 text-xl group-hover:scale-110 transition-transform"></i>
                    </div>
                    
                    @if ($errors->has('dayofsillapa'))
                        <p class="text-red-500 text-xs italic mt-2 font-black"> 
                            <i class="ri-error-warning-line"></i> {{ $errors->first('dayofsillapa') }}
                        </p>
                    @endif
                </div>

                <div class="flex flex-col sm:flex-row gap-4 pt-4">
                    <button type="submit" class="flex-1 py-4 bg-emerald-600 text-white rounded-2xl font-black text-base hover:bg-emerald-700 hover:scale-[1.02] active:scale-95 transition-all shadow-xl shadow-emerald-100 uppercase italic tracking-widest text-white">
                        <i class="ri-refresh-line mr-1 text-white"></i> Update Date
                    </button>
                    <a href="{{ url('dayofsillapa') }}" class="inline-flex items-center justify-center px-8 py-4 bg-slate-100 text-slate-500 rounded-2xl font-black hover:bg-slate-200 transition-all uppercase italic text-xs tracking-widest">
                        Cancel
                    </a>
                </div>
            </form>
        </div>

        <div class="px-8 py-6 bg-slate-50 border-t border-slate-100">
            <div class="flex items-start gap-3 text-slate-400 italic">
                <i class="ri-information-line text-lg text-emerald-500"></i>
                <p class="text-[11px] font-bold leading-relaxed">
                    การแก้ไขวันที่แข่งขันจะมีผลกับกิจกรรมทั้งหมดที่ผูกอยู่กับวันนี้ โปรดตรวจสอบตารางเวลาของศูนย์การแข่งขันก่อนทำการกดยืนยัน
                </p>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/th.js"></script>

<script>
    // ติดตั้ง Datepicker (Flatpickr) สำหรับหน้า Edit
    flatpickr("#datepicker", {
        dateFormat: "Y-m-d",
        locale: "th",
        allowInput: false,
        disableMobile: "true",
        altInput: true,
        altFormat: "j F Y",
        defaultDate: "{{ $dayofsillapa->dayofsillapa }}", // กำหนดค่าตั้งต้นเป็นค่าปัจจุบันจาก DB
    });
</script>
@endsection