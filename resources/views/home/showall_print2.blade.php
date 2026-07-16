@extends('layouts.app')

@section('content')
<style>
    #loading-overlay {
        @apply fixed inset-0 z-[9999] flex items-center justify-center bg-white/80 backdrop-blur-sm transition-opacity duration-500;
    }
    .spinner {
        @apply w-16 h-16 border-4 border-emerald-200 border-t-emerald-600 rounded-full animate-spin;
    }
</style>

<div id="loading-overlay">
    <div class="text-center">
        <div class="spinner mb-4"></div>
        <p class="text-emerald-700 font-bold animate-pulse text-lg">กำลังโหลดข้อมูลจำนวนมาก กรุณารอสักครู่...</p>
    </div>
</div>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 text-slate-800 font-sarabun">
    
    <div class="bg-gradient-to-r from-primary to-emerald-600 rounded-3xl shadow-xl p-8 md:p-12 mb-8 text-white relative overflow-hidden">
        <div class="relative z-10">
            <h1 class="text-3xl md:text-5xl font-black mb-4 tracking-tight italic uppercase">
                {{session('app_name').' '.session('app_group')}}
            </h1>
            <p class="text-green-50 text-lg md:text-xl opacity-90 font-medium">
                <i class="ri-map-pin-2-fill mr-2"></i>{{session('app_area')}}
            </p>
        </div>
        <i class="ri-team-line absolute -right-10 -bottom-10 text-[15rem] opacity-10 rotate-12"></i>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        
        <div class="bg-gray-50/50 px-8 py-6 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="../../" class="group inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-white text-primary border border-gray-200 hover:bg-primary hover:text-white transition-all shadow-sm">
                    <i class="ri-arrow-left-s-line text-2xl group-hover:-translate-x-1 transition-transform"></i>
                </a>
                <div>
                    <h2 class="text-xl font-bold text-gray-800 leading-none mb-1">รายชื่อผู้เข้าร่วมการแข่งขันทั้งหมด</h2>
                    <p class="text-[10px] text-emerald-600 font-black uppercase tracking-[0.2em] italic">All Participants Directory</p>
                </div>
            </div>

            <div id="excel_tools_area" class="flex items-center"></div>
        </div>

        <div class="p-8">
            @if (session('status'))
                <div class="mb-8 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 flex items-center gap-3 rounded-xl shadow-sm">
                    <i class="ri-checkbox-circle-fill text-xl"></i>
                    <span class="font-bold">{{ session('status') }}</span>
                </div>
            @endif

            <div class="overflow-x-auto">
                <table class="w-full text-left border-separate border-spacing-y-3" id="table_all_participants">
                    <thead>
                        <tr class="text-gray-400 text-[10px] uppercase tracking-[0.2em] font-black">
                            <th class="px-6 py-2 text-center w-16">อันดับ</th>
                            <th class="px-6 py-2">กลุ่มสาระ / รายการแข่งขัน</th>
                            <th class="px-6 py-2 text-center">ระดับชั้น</th>
                            <th class="px-6 py-2">โรงเรียน</th>
                            <th class="px-6 py-2">รายชื่อนักเรียน</th>
                            <th class="px-6 py-2">รายชื่อครูฝึกสอน</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @foreach($student as $s)
                        <tr class="group bg-white hover:bg-gray-50/50 transition-all border border-gray-100 shadow-sm rounded-2xl">
                            <td class="px-6 py-6 text-center">
                                <div class="w-8 h-8 mx-auto flex items-center justify-center rounded-lg font-black text-sm
                                    @if($s->getaddschool->activity_rank == 1) bg-yellow-400 text-white shadow-sm
                                    @elseif($s->getaddschool->activity_rank == 2) bg-slate-400 text-white shadow-sm
                                    @elseif($s->getaddschool->activity_rank == 3) bg-orange-400 text-white shadow-sm
                                    @else bg-gray-50 text-gray-400 border border-gray-100 @endif">
                                    {{$s->getaddschool->activity_rank ?: '-'}}
                                </div>
                            </td>
                            <td class="px-6 py-6 font-bold">
                                <div class="text-[9px] font-black text-primary uppercase tracking-wider mb-1">
                                    {{$s->getstudentactivity->getactivitysubject->subjectgroup_name}}
                                </div>
                                <div class="text-gray-900 leading-tight uppercase italic text-base font-black">
                                    {{$s->getstudentactivity->activity_name}}
                                </div>
                            </td>
                            <td class="px-6 py-6 text-center">
                                <span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-full text-[10px] font-black border border-blue-100 uppercase">
                                    {{$s->getstudentactivity->getclass->class_name}}
                                </span>
                            </td>
                            <td class="px-6 py-6 font-black text-gray-700 italic uppercase">
                                {{$s->getaddschool->getschoolname->school_name}}
                            </td>
                            <td class="px-6 py-6">
                                @if($s->type == 0)
                                    <div class="flex flex-col">
                                        <span class="text-[10px] text-gray-400 font-bold uppercase">{{$s->getstudenttitle->title}}</span>
                                        <span class="font-bold text-gray-700">{{$s->name}} {{$s->lastname}}</span>
                                    </div>
                                @else
                                    <span class="text-gray-300 italic">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-6 font-bold text-emerald-600">
                                @if($s->type == 1)
                                    <div class="flex flex-col">
                                        <span class="text-[10px] text-emerald-400 font-bold uppercase">{{$s->getstudenttitle->title}}</span>
                                        <span>{{$s->name}} {{$s->lastname}}</span>
                                    </div>
                                @else
                                    <span class="text-gray-300 italic">-</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- --- ส่วนของ SCRIPTS: จัดเรียงลำดับใหม่และดึง Library เฉพาะที่จำเป็น --- --}}

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.tailwindcss.min.js"></script>

<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
<script>
    $(document).ready(function() {
        if ($.fn.DataTable) {
            var table = $('#table_all_participants').DataTable({
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
<script>
    $(document).ready(function() {
        // ตรวจสอบความพร้อมของ Library
        if ($.fn.DataTable && $.fn.DataTable.Buttons) {
            
            // ล้าง Instance เดิมเพื่อป้องกันการโหลดซ้ำ
            if ($.fn.DataTable.isDataTable('#table_all_participants')) {
                $('#table_all_participants').DataTable().destroy();
            }

            var table = $('#table_all_participants').DataTable({
                "responsive": true,
                "autoWidth": false,
                "pageLength": 50, // แสดง 50 รายการต่อหน้าเพื่อความรวดเร็ว
                "dom": '<"flex justify-end mb-4"f>rt<"flex flex-col md:flex-row justify-between items-center mt-6 gap-4"ip>',
                "buttons": [
                    {
                        extend: 'excelHtml5',
                        text: '<i class="ri-file-excel-2-line mr-2"></i> ส่งออกไฟล์ EXCEL ทั้งหมด',
                        className: 'bg-emerald-600 hover:bg-emerald-700 text-white px-8 py-3 rounded-2xl font-black text-sm shadow-xl shadow-emerald-100 transition-all flex items-center border-none transform hover:-translate-y-1',
                        title: 'รายชื่อผู้เข้าร่วมการแข่งขันทั้งหมด_{{ date("d-m-Y") }}',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5] // เลือกคอลัมน์ที่จะ Export
                        }
                    }
                ],
                "language": {
                   "search": '<i class="ri-search-line dt-search-icon"></i>', 
                   "searchPlaceholder": "ค้นหารายชื่อ, โรงเรียน หรือรายการ...",
                }
            });

            // ฉีดปุ่ม Excel เข้าไปในตำแหน่ง excel_tools_area
            table.buttons().container().appendTo('#excel_tools_area');

        } else {
            console.error("DataTables Buttons Library ไม่สามารถโหลดได้สำเร็จ");
        }
    });
</script>
@endsection