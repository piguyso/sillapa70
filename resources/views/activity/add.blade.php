@extends('layouts.app')

@section('content')
<style>
    #loading-overlay { @apply fixed inset-0 z-[9999] flex items-center justify-center bg-white/80 backdrop-blur-sm transition-opacity duration-500; }
    .spinner { @apply w-16 h-16 border-4 border-emerald-200 border-t-emerald-600 rounded-full animate-spin; }
    .child-container { @apply p-6 bg-slate-50 border-x-4 border-emerald-500 rounded-b-2xl shadow-inner animate-fade-in text-left; }
    
    /* ตกแต่ง Search Box ของ DataTable */
    .dataTables_filter input {
        @apply ml-2 px-4 py-2 border border-gray-200 rounded-xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none transition-all font-bold text-sm;
    }
    .dataTables_info, .dataTables_paginate {
        @apply text-xs font-bold text-slate-400 uppercase italic;
    }
</style>

<div id="loading-overlay">
    <div class="text-center">
        <div class="spinner mb-4"></div>
        <p class="text-emerald-700 font-bold animate-pulse text-lg italic uppercase">Fetching Activities...</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 font-sarabun text-slate-800">
    <div class="bg-gradient-to-r from-slate-800 to-emerald-900 rounded-3xl shadow-xl p-8 md:p-12 mb-8 text-white relative overflow-hidden border-b-8 border-emerald-500 text-white">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h1 class="text-3xl md:text-5xl font-black mb-4 tracking-tight italic uppercase text-white">Activity Management</h1>
                <p class="text-emerald-100 text-lg md:text-xl opacity-90 font-medium italic text-white"><i class="ri-trophy-line mr-2"></i>รายการกิจกรรม (คลิกที่แถวเพื่อดูรายละเอียด)</p>
            </div>
            @if(officerchk())
            <div class="shrink-0" id="excel_tools_area">
                <a href="{{ url('subjectgroup') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-white text-emerald-800 font-black rounded-2xl hover:bg-emerald-50 transition-all shadow-lg uppercase italic tracking-widest text-sm transform active:scale-95">
                    <i class="ri-add-circle-line text-xl"></i> จัดการรายการ
                </a>
            </div>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-separate border-spacing-y-2" id="table_activities">
                    <thead>
                        <tr class="text-gray-400 text-[10px] uppercase tracking-[0.2em] font-black">
                            <th class="px-6 py-2 text-center w-16">ID</th>
                            <th class="px-6 py-2">ชื่อรายการกิจกรรม</th>
                            <th class="px-6 py-2">กลุ่มสาระฯ</th>
                            <th class="px-6 py-2 text-center">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm font-bold">
                        @forelse($activity as $l)
                        <tr class="group bg-white hover:bg-emerald-50/30 transition-all border border-gray-100 shadow-sm rounded-2xl cursor-pointer main-row" 
                            data-id="{{$l->id}}"
                            data-detail='<div class="child-container">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div class="space-y-3 text-left">
                                        <div class="bg-white p-3 rounded-xl border border-slate-200">
                                            <p class="text-[10px] text-gray-400 font-black uppercase italic">ศูนย์การแข่งขัน</p>
                                            <p class="italic font-bold text-emerald-800 text-sm">{{$l->getactivitybranch->branch_name ?? "ไม่พบข้อมูล"}}</p>
                                        </div>
                                        <div class="bg-white p-3 rounded-xl border border-slate-200">
                                            <p class="text-[10px] text-gray-400 font-black uppercase italic">ระดับชั้น</p>
                                            <p class="italic font-bold text-emerald-800 text-sm">{{$l->getclass->class_name}}</p>
                                        </div>
                                    </div>
                                    <div class="space-y-3">
                                        <div class="grid grid-cols-2 gap-2 text-white">
                                            <div class="bg-emerald-600 p-3 rounded-xl text-white text-center shadow-sm">
                                                <p class="text-[10px] font-black uppercase opacity-80 italic text-white">นักเรียน/ทีม</p>
                                                <p class="text-xl font-black italic text-white leading-none">{{$l->activity_studennumber}}</p>
                                            </div>
                                            <div class="bg-emerald-600 p-3 rounded-xl text-white text-center shadow-sm">
                                                <p class="text-[10px] font-black uppercase opacity-80 italic text-white">ครู/ทีม</p>
                                                <p class="text-xl font-black italic text-white leading-none">{{$l->activity_teachernumber}}</p>
                                            </div>
                                        </div>
                                        <div class="bg-white p-3 rounded-xl border border-slate-200 text-left">
                                            <p class="text-[10px] text-gray-400 font-black uppercase italic text-white">วัน-เวลาแข่งขัน</p>
                                            <p class="italic font-bold text-sm text-slate-700"><i class="ri-calendar-line text-emerald-500"></i> {{ Datethai($l->activity_day) }} | {{$l->activity_time}} น.</p>
                                        </div>
                                    </div>
                                    <div class="bg-slate-900 p-4 rounded-2xl text-white flex flex-col justify-center shadow-xl border-l-4 border-emerald-400 text-left">
                                        <p class="text-[10px] text-emerald-400 font-black uppercase mb-1 italic">สถานที่แข่งขัน</p>
                                        <p class="font-bold italic text-lg text-white leading-tight"><i class="ri-map-pin-2-line"></i> {{$l->activity_branchroom}}</p>
                                    </div>
                                </div>
                            </div>'>
                            <td class="px-6 py-5 text-center text-slate-300 italic">{{ $l->id }}</td>
                            <td class="px-6 py-5">
                                <div class="font-black text-gray-800 text-base uppercase italic tracking-tight group-hover:text-emerald-700 transition-colors">
                                    {{$l->activity_name}}
                                </div>
                                <div class="text-[10px] text-gray-400 mt-1 uppercase italic tracking-widest leading-none">
                                    <i class="ri-information-line"></i> คลิกเพื่อดูข้อมูลเพิ่มเติม
                                </div>
                            </td>
                            <td class="px-6 py-5 text-gray-500 italic uppercase">{{$l->getactivitysubject->subjectgroup_name}}</td>
                            <td class="px-6 py-5 text-center">
                                <span class="text-emerald-600 bg-emerald-50 px-4 py-2 rounded-xl text-[10px] font-black italic border border-emerald-100 group-hover:bg-emerald-600 group-hover:text-white transition-all uppercase">View Details</span>
                            </td>
                        </tr>
                        @empty
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.tailwindcss.min.js"></script>

<script>
    $(document).ready(function() {
        if ($.fn.DataTable) {
            // แก้ไข ID ให้ตรงกับตารางคือ #table_activities
            var table = $('#table_activities').DataTable({
                "responsive": true,
                "autoWidth": false,
                "pageLength": 25,
                "dom": '<"flex flex-col md:flex-row justify-between items-center mb-6 gap-4"f>rt<"flex flex-col md:flex-row justify-between items-center mt-6 gap-4"ip>',
                "language": {
                    "search": "",
                    "searchPlaceholder": "🔍 ค้นหาชื่อกิจกรรม หรือ กลุ่มสาระ...",
                },
                "initComplete": function(settings, json) {
                    $('#loading-overlay').addClass('opacity-0');
                    setTimeout(function() {
                        $('#loading-overlay').remove();
                    }, 500);
                }
            });

            // ระบบ Child Rows สำหรับกางข้อมูลเมื่อคลิกแถว
            $('#table_activities tbody').on('click', 'tr.main-row', function () {
                var tr = $(this);
                var row = table.row(tr);
                var detailHtml = tr.attr('data-detail'); 

                if (row.child.isShown()) {
                    row.child.hide();
                    tr.removeClass('bg-emerald-50/50 shadow-inner');
                } else {
                    // ปิดแถวอื่นๆ ที่กางอยู่ก่อน (ถ้าต้องการให้กางได้ทีละแถว)
                    table.rows().every(function () {
                        if (this.child.isShown()) {
                            this.child.hide();
                            $(this.node()).removeClass('bg-emerald-50/50 shadow-inner');
                        }
                    });
                    
                    // เปิดแถวที่คลิก
                    row.child(detailHtml).show();
                    tr.addClass('bg-emerald-50/50 shadow-inner');
                    $(row.child()).addClass('animate-fade-in');
                }
            });
        }
    });
</script>
@endsection