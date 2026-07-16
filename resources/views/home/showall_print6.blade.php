@extends('layouts.app')

@section('content')
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
        <i class="ri-fire-line absolute -right-10 -bottom-10 text-[15rem] opacity-10 rotate-12"></i>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        
        <div class="bg-gray-50/50 px-8 py-6 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="../../" class="group inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-white text-primary border border-gray-200 hover:bg-primary hover:text-white transition-all shadow-sm">
                    <i class="ri-arrow-left-s-line text-2xl group-hover:-translate-x-1 transition-transform"></i>
                </a>
                <div>
                    <h2 class="text-xl font-bold text-gray-800 leading-none mb-1">รายการแข่งขันทั้งหมดที่มีการลงทะเบียน</h2>
                    <p class="text-[10px] text-emerald-600 font-black uppercase tracking-[0.2em] italic">Registered Activities Summary</p>
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
                {{-- เปลี่ยน ID ตารางให้ตรงกับที่ Script เรียกใช้ --}}
                <table class="w-full text-left border-separate border-spacing-y-3" id="table_activities">
                    <thead>
                        <tr class="text-gray-400 text-[10px] uppercase tracking-[0.2em] font-black">
                            <th class="px-6 py-2 text-center w-16">#</th>
                            <th class="px-6 py-2">กลุ่มสาระ</th>
                            <th class="px-6 py-2">รายการแข่งขัน</th>
                            <th class="px-6 py-2 text-center">ระดับชั้น</th>
                            <th class="px-6 py-2 text-center">จำนวนทีม</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @php $i = 1; @endphp
                        @foreach($schoolcountactivity as $s)
                        <tr class="group bg-white hover:bg-gray-50/50 transition-all border border-gray-100 shadow-sm rounded-2xl">
                            <td class="px-6 py-6 text-center font-black">
                                <span class="inline-block w-8 h-8 rounded-lg bg-gray-100 text-gray-500 leading-8 text-xs">
                                    {{ $i++ }}
                                </span>
                            </td>
                            <td class="px-6 py-6">
                                <span class="text-[10px] font-black text-primary uppercase tracking-wider block mb-1">
                                    {{$s->getactivityname->getactivitysubject->subjectgroup_name}}
                                </span>
                            </td>
                            <td class="px-6 py-6">
                                <a href="{{url('viewactivitydetail').'/'.$s->school_activity}}" class="font-black text-gray-800 hover:text-primary hover:underline transition-all italic uppercase">
                                    {{$s->getactivityname->activity_name}}
                                </a>
                            </td>
                            <td class="px-6 py-6 text-center">
                                <span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-full text-[10px] font-black border border-blue-100 uppercase">
                                    {{$s->getactivityname->getclass->class_name}}
                                </span>
                            </td>
                            <td class="px-6 py-6 text-center">
                                <div class="inline-flex items-center gap-1 font-black text-gray-700 bg-gray-50 px-3 py-1 rounded-lg border">
                                    <i class="ri-team-line text-emerald-500"></i>
                                    {{$s->count}} <span class="text-[10px] text-gray-400 ml-1">ทีม</span>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- --- ส่วนของ SCRIPTS --- --}}
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.tailwindcss.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>

<script>
    $(document).ready(function() {
        if ($.fn.DataTable && $.fn.DataTable.Buttons) {
            var table = $('#table_activities').DataTable({
                "responsive": true,
                "autoWidth": false,
                "pageLength": 50,
                "dom": '<"flex justify-end mb-4"f>rt<"flex flex-col md:flex-row justify-between items-center mt-6 gap-4"ip>',
                "buttons": [
                    {
                        extend: 'excelHtml5',
                        text: '<i class="ri-file-excel-line mr-2 text-lg"></i> ส่งออกไฟล์ EXCEL สรุปกิจกรรม',
                        className: 'bg-emerald-600 hover:bg-emerald-700 text-white px-8 py-3 rounded-2xl font-black text-sm shadow-xl shadow-emerald-100 transition-all flex items-center border-none transform hover:-translate-y-1',
                        title: 'สรุปรายการแข่งขันที่มีการลงทะเบียน_{{ date("d-m-Y") }}'
                    }
                ],
                "language": {
                   "search": "",
                   "searchPlaceholder": "🔍 ค้นหากลุ่มสาระ หรือรายการแข่งขัน...",
                }
            });
            table.buttons().container().appendTo('#excel_tools_area');
        }
    });
</script>
@endsection