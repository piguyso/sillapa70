@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/material_green.css">

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10 font-sarabun text-slate-800">
    
    <div class="bg-gradient-to-r from-slate-800 to-emerald-900 rounded-3xl shadow-xl p-8 mb-8 text-white relative overflow-hidden border-b-8 border-emerald-500">
        <div class="relative z-10 flex items-center gap-4">
            <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-md">
                <i class="ri-calendar-event-line text-2xl text-white"></i>
            </div>
            <div>
                <h1 class="text-2xl font-black italic uppercase tracking-tight text-white leading-none">Competition Dates</h1>
                <p class="text-emerald-100 text-xs mt-1 font-bold italic uppercase opacity-80 text-white">จัดการข้อมูลวันแข่งขันศิลปหัตถกรรม</p>
            </div>
        </div>
        <i class="ri-time-line absolute -right-10 -bottom-10 text-[10rem] opacity-10 rotate-12 text-white"></i>
    </div>



    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-1">
            <div class="bg-white rounded-3xl shadow-xl shadow-slate-100 border border-gray-100 overflow-hidden sticky top-10">
                <div class="bg-emerald-600 px-6 py-4 text-white">
                    <h3 class="font-black italic uppercase tracking-widest text-xs text-white">เพิ่มวันแข่งขันใหม่</h3>
                </div>
                <div class="p-6">
                    <form method="POST" action="{{ Route('dayofsillapa.store') }}" class="space-y-4 font-bold">
                        {{ csrf_field() }}
                        <div class="space-y-2">
                            <label for="dayofsillapa" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] italic">เลือกวันที่</label>
                            <div class="relative">
                                <input id="datepicker" type="text" 
                                       class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-bold text-slate-700 shadow-inner" 
                                       name="dayofsillapa" value="{{ old('dayofsillapa') }}" 
                                       placeholder="คลิกเพื่อเลือกวันที่..." required readonly>
                                <i class="ri-calendar-2-line absolute right-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            </div>
                            @if ($errors->has('dayofsillapa'))
                                <p class="text-red-500 text-[10px] italic mt-1 font-black"> {{ $errors->first('dayofsillapa') }}</p>
                            @endif
                        </div>

                        <button type="submit" class="w-full py-4 bg-emerald-600 text-white rounded-2xl font-black text-sm hover:bg-emerald-700 hover:scale-[1.02] active:scale-95 transition-all shadow-xl shadow-emerald-100 uppercase italic tracking-widest text-white">
                            <i class="ri-save-3-line mr-1 text-white"></i> บันทึกข้อมูล
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="bg-white rounded-3xl shadow-xl shadow-slate-100 border border-gray-100 overflow-hidden">
                <div class="bg-slate-50 px-8 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h2 class="text-xs font-black text-slate-500 uppercase tracking-widest italic flex items-center gap-2">
                        <i class="ri-list-ordered text-emerald-600"></i> รายการวันแข่งขันทั้งหมด
                    </h2>
                </div>

                <div class="p-6">
                    <table class="w-full text-left border-separate border-spacing-y-2">
                        <thead>
                            <tr class="text-slate-400 text-[10px] uppercase tracking-[0.2em] font-black italic">
                                <th class="px-6 py-2 text-center w-16">ID</th>
                                <th class="px-6 py-2">วันที่ (ระบบ)</th>
                                <th class="px-6 py-2">วันแข่งขัน (ไทย)</th>
                                <th class="px-6 py-2 text-center">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm font-bold">
                            @forelse($dayofsillapa as $l)
                            <tr class="group bg-white hover:bg-emerald-50/50 transition-all border border-gray-100 shadow-sm rounded-2xl">
                                <td class="px-6 py-4 text-center text-slate-300 italic rounded-l-2xl">{{$l->id}}</td>
                                <td class="px-6 py-4 text-slate-400 font-mono text-xs">{{$l->dayofsillapa}}</td>
                                <td class="px-6 py-4 text-emerald-800 italic font-black uppercase tracking-tight">
                                    {{datethai($l->dayofsillapa)}}
                                </td>
                                <td class="px-6 py-4 text-center rounded-r-2xl">
                                    <div class="flex justify-center gap-2">
                                        @if(officerchk())
                                            <a href="{{'dayofsillapa/'.$l->id.'/edit?action=edit'}}" 
                                               class="w-9 h-9 flex items-center justify-center bg-blue-50 text-blue-600 rounded-xl hover:bg-blue-600 hover:text-white transition-all shadow-sm">
                                                <i class="ri-edit-2-line"></i>
                                            </a>
                                            <button onclick="DelConfirm({{$l->id}})" 
                                                    class="w-9 h-9 flex items-center justify-center bg-red-50 text-red-600 rounded-xl hover:bg-red-600 hover:text-white transition-all shadow-sm">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                            <form id="delform{{$l->id}}" method="post" action="{{ route('dayofsillapa.destroy',$l->id)}}" class="hidden">
                                                <input type="hidden" name="_method" value="DELETE"/> {!!csrf_field()!!}
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center py-10 text-slate-400 italic font-black">ไม่พบข้อมูลวันแข่งขัน</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-6">{{ $dayofsillapa->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/th.js"></script>

<script>
    // ติดตั้ง Datepicker (Flatpickr)
    flatpickr("#datepicker", {
        dateFormat: "Y-m-d",
        locale: "th", // รองรับภาษาไทย
        allowInput: false,
        disableMobile: "true", // ป้องกันคีย์บอร์ดมือถือเด้งทับ
        altInput: true,
        altFormat: "j F Y", // แสดงผลแบบ "18 กุมภาพันธ์ 2026"
    });

    function DelConfirm(id) {
        showCustomConfirm(
            'ยืนยันการลบวันแข่งขัน',
            'คุณต้องการลบวันแข่งขันนี้ใช่หรือไม่? กิจกรรมที่ผูกกับวันนี้อาจได้รับผลกระทบ การกระทำนี้ไม่สามารถย้อนกลับได้',
            function() {
                document.getElementById('delform' + id).submit();
            }
        );
    }
</script>
@endsection