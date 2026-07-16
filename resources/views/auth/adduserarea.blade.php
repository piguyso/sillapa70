@extends('layouts.apparea')

@section('content')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.tailwindcss.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">

<style>
    /* Custom DataTables Styling */
    .dataTables_wrapper .dataTables_filter input {
        @apply bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 outline-none transition-all ml-2 font-bold text-sm;
    }
    .dt-buttons .dt-button {
        @apply bg-amber-900 text-white border-none rounded-xl px-4 py-2 text-[10px] font-black uppercase italic hover:bg-orange-600 transition-all shadow-md mr-2 mb-4;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        @apply rounded-lg border-none font-bold text-xs !important;
    }
</style>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 font-sarabun text-slate-800">

    <div class="relative bg-gradient-to-r from-amber-800 to-orange-600 rounded-3xl shadow-xl p-8 md:p-12 mb-8 text-white overflow-hidden border-b-8 border-orange-500">
        <div class="relative z-10 text-white">
            <h2 class="text-xl md:text-2xl font-bold opacity-90 italic mb-2 text-white">
                {{ session('app_name')}} ระดับเครือข่ายสถานศึกษา {{Auth::user()->getschoolgroup->schoolgroup_name}}
            </h2>
            <h1 class="text-3xl md:text-5xl font-black tracking-tight uppercase italic text-white leading-none">
                User Management
            </h1>
        </div>
        <i class="ri-group-line absolute -right-10 -bottom-10 text-[15rem] opacity-10 rotate-12 text-white"></i>
    </div>

    @if (session('status') || Session::has('massage'))
        <div class="mb-8 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 rounded-xl shadow-sm font-bold animate-fade-in italic text-sm flex items-center gap-3">
            <i class="ri-checkbox-circle-fill text-xl text-emerald-500"></i> {{ session('status') ?: Session::get('massage') }}
        </div>
    @endif

    <div class="bg-white rounded-3xl shadow-xl shadow-slate-100 border border-slate-100 overflow-hidden text-slate-800">
        <div class="bg-slate-50 px-8 py-5 border-b border-slate-100 flex justify-between items-center text-slate-800">
            <h2 class="text-base font-black text-slate-800 uppercase italic tracking-wide leading-none">รายชื่อผู้ใช้งานในเครือข่าย</h2>
        </div>

        <div class="p-4 md:p-8 text-slate-800">
            <div class="overflow-x-auto text-slate-800">
                <table id="userTable" class="w-full text-left border-separate border-spacing-y-3">
                    <thead>
                        <tr class="text-slate-400 text-[10px] uppercase tracking-[0.2em] font-black italic">
                            <th class="px-4 py-2 text-center w-12 text-slate-400">ID</th>
                            <th class="px-4 py-2 text-slate-400">บัญชีผู้ใช้ (Email)</th>
                            <th class="px-4 py-2 text-slate-400">ชื่อ-นามสกุล</th>
                            <th class="px-4 py-2 text-slate-400">ตำแหน่ง/กลุ่ม</th>
                            <th class="px-4 py-2 text-slate-400 text-center">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm font-bold italic text-slate-800">
                        @forelse($data as $l)
                        <tr class="bg-white hover:bg-orange-50/50 transition-all border border-slate-100 shadow-sm rounded-2xl group text-slate-800">
                            <td class="px-4 py-5 text-center text-slate-300 rounded-l-2xl border-l-4 border-transparent group-hover:border-orange-500 text-slate-800">
                                {{$l->id}}
                            </td>
                            <td class="px-4 py-5">
                                <p class="text-slate-800 font-black leading-none text-slate-800">{{$l->email}}</p>
                                <span class="text-[9px] text-slate-400 mt-1 inline-block uppercase italic">Phone: {{$l->phonenumber ?: '-'}}</span>
                            </td>
                            <td class="px-4 py-5 text-slate-800">
                                {{$l->name.' '.$l->lastname}}
                            </td>
                            <td class="px-4 py-5">
                                <div class="flex flex-col gap-1">
                                    <span class="text-[10px] text-orange-600 font-black uppercase tracking-tighter">
                                        {{position($l->position) ?: 'General User'}}
                                    </span>
                                    <span class="text-[11px] text-slate-500 font-bold italic leading-none">
                                        <i class="ri-hotel-line text-slate-300"></i> {{$l->getschoolgroup->schoolgroup_name ?? 'N/A'}}
                                    </span>
                                </div>
                            </td>
                            <td class="px-4 py-5 text-center rounded-r-2xl text-slate-800">
                                <div class="flex justify-center gap-2">
                                    <a href="{{'myuser/'.$l->id.'/edit?action=edit'}}" class="w-9 h-9 flex items-center justify-center bg-amber-50 text-amber-600 rounded-xl hover:bg-amber-600 hover:text-white transition-all shadow-sm">
                                        <i class="ri-pencil-line"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-6">
                {{ $data->links() }}
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.tailwindcss.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>


<script>
    $(document).ready(function() {
        if ($.fn.DataTable) {
            var table = $('#userTable').DataTable({
                "responsive": true,
                "autoWidth": false,
                "pageLength": 50,
                "deferRender": true, // ช่วยให้ Browser ไม่ค้างเวลาข้อมูลเยอะ
                "dom": '<"flex justify-end mb-4"f>rt<"flex flex-col md:flex-row justify-between items-center mt-6 gap-4"ip>',
                "buttons": [
                    {
                        extend: 'excelHtml5',
                        text: '<i class="ri-file-excel-line mr-2"></i> ส่งออก Excel',
                        className: 'bg-emerald-600 text-white px-6 py-3 rounded-2xl font-bold shadow-lg border-none'
                    }
                ],
                "language": {
                    "search": "",
                    "searchPlaceholder": "🔍 ค้นหาข้อมูล...",
                    "processing": "กำลังประมวลผล...", // ข้อความขณะค้นหา/เปลี่ยนหน้า
                    "loadingRecords": "กำลังโหลดข้อมูล...", 
                },
                // เมื่อวาดตารางเสร็จ (ทุกอย่างพร้อม) ให้ปิด Overlay
                "initComplete": function(settings, json) {
                    $('#loading-overlay').addClass('opacity-0');
                    setTimeout(function() {
                        $('#loading-overlay').remove(); // ลบทิ้งเพื่อไม่ให้ขวางหน้าจอ
                    }, 500);
                }
            });

            table.buttons().container().appendTo('#excel_tools_area');
        }
    });
</script>
@endsection