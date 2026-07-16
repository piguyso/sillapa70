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
        <i class="ri-history-line absolute -right-10 -bottom-10 text-[15rem] opacity-10 rotate-12 text-white"></i>
    </div>

    <div class="flex justify-center items-center py-10">
        <div class="w-full max-w-3xl">
            
            @if(Session::has('massage') || session('status'))
                <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 rounded-xl shadow-sm font-bold animate-fade-in italic text-sm flex items-center gap-3">
                    <i class="ri-checkbox-circle-fill text-xl text-emerald-500"></i> {{ Session::get('massage') ?: session('status') }}
                </div>
            @endif

            <div class="bg-white rounded-3xl shadow-2xl shadow-slate-200 border border-slate-100 overflow-hidden text-center p-12 relative">
                <div class="absolute top-0 left-0 w-full h-2 bg-amber-900"></div>
                
                <div class="mb-8">
                    <div class="w-24 h-24 bg-amber-50 text-amber-800 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="ri-hourglass-2-fill text-6xl animate-spin-slow"></i>
                    </div>
                    <h2 class="text-3xl font-black text-slate-800 uppercase italic tracking-tight mb-2">
                        System Access Closed
                    </h2>
                    <p class="text-slate-400 font-bold italic uppercase tracking-[0.2em] text-[10px]">สถานะการเข้าใช้งานระดับสถานศึกษา</p>
                </div>

                <div class="bg-orange-50 rounded-2xl p-8 border border-orange-100 mb-8">
                    <p class="text-xl md:text-2xl font-black text-orange-900 leading-relaxed italic">
                        " ระบบการแข่งขันปิดการใช้งาน <br class="hidden md:block"> ของโรงเรียนเรียบร้อยแล้ว "
                    </p>
                </div>

                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                    <a href="{{ url()->previous() }}" class="w-full sm:w-auto px-10 py-4 bg-slate-800 text-white rounded-2xl font-black text-xs hover:bg-slate-900 transition-all uppercase italic tracking-widest flex items-center justify-center gap-2 text-white">
                        <i class="ri-arrow-left-up-line"></i> กลับไปก่อนหน้า
                    </a>
                    <a href="{{ url('/') }}" class="w-full sm:w-auto px-10 py-4 bg-orange-600 text-white rounded-2xl font-black text-xs hover:bg-orange-700 transition-all uppercase italic tracking-widest flex items-center justify-center gap-2 shadow-xl shadow-orange-100 text-white">
                        <i class="ri-home-7-line"></i> หน้าหลักระบบ
                    </a>
                </div>

                <div class="mt-12 pt-8 border-t border-slate-50">
                    <div class="flex flex-col items-center gap-2">
                        <span class="px-4 py-1 bg-slate-100 text-slate-500 rounded-full text-[10px] font-black uppercase italic tracking-tighter">
                            Reason: ปิดตามกำหนดการหรือแอดมินปิดใช้งาน
                        </span>
                        <p class="text-slate-300 text-[10px] font-bold italic uppercase tracking-widest mt-2">
                            หากมีข้อสงสัย โปรดติดต่อฝ่ายเทคโนโลยีสารสนเทศของเครือข่ายสถานศึกษา
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes spin-slow {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    .animate-spin-slow {
        animation: spin-slow 8s linear infinite;
    }
</style>
@endsection