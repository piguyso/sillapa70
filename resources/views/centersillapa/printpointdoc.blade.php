@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 text-slate-800">
    
    <div class="bg-gradient-to-r from-primary to-emerald-600 rounded-3xl shadow-xl p-8 md:p-12 mb-8 text-white relative overflow-hidden">
        <div class="relative z-10">
            <h1 class="text-3xl md:text-5xl font-black mb-4 tracking-tight">
                {{session('app_name').' '.session('app_group')}}
            </h1>
            <p class="text-green-50 text-lg md:text-xl opacity-90 font-medium">
                <i class="ri-map-pin-2-fill mr-2"></i>{{session('app_area')}}
            </p>
        </div>
        <i class="ri-trophy-line absolute -right-10 -bottom-10 text-[15rem] opacity-10 rotate-12"></i>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        
        <div class="bg-gray-50/50 px-8 py-6 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="../../" class="group inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-white text-primary border border-gray-200 hover:bg-primary hover:text-white transition-all shadow-sm">
                    <i class="ri-arrow-left-s-line text-2xl group-hover:-translate-x-1 transition-transform"></i>
                </a>
                <div>
                    <h2 class="text-xl font-bold text-gray-800 leading-none mb-1">รายการกิจกรรมที่มีการแข่งขัน</h2>
                    <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">Competition Management</p>
                </div>
            </div>
            <div class="flex items-center gap-2 bg-white px-4 py-2 rounded-2xl border border-gray-100 shadow-sm">
                <i class="ri-list-check-3 text-primary text-xl"></i>
                <span class="text-sm font-black text-gray-600 uppercase">รวม {{ count($schoolcountactivity) }} รายการ</span>
            </div>
        </div>

        <div class="p-8">
            @if (session('status'))
                <div class="mb-8 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 flex items-center gap-3 rounded-xl shadow-sm">
                    <i class="ri-checkbox-circle-fill text-xl"></i>
                    <span class="font-bold">{{ session('status') }}</span>
                </div>
            @endif

            <div class="overflow-x-auto p-1">
                {{-- เปลี่ยน id เป็น printTable เพื่อให้เจาะจง --}}
                <table class="w-full text-left border-separate border-spacing-y-3" id="printTable">
                    <thead>
                        <tr class="text-gray-400 text-[10px] uppercase tracking-[0.2em] font-black">
                            <th class="px-6 py-2 text-center w-16">#</th>
                            <th class="px-6 py-2">กลุ่มสาระ / รายการแข่งขัน</th>
                            <th class="px-6 py-2 text-center">ระดับชั้น</th>
                            <th class="px-6 py-2 text-center">จำนวนทีม</th>
                            <th class="px-6 py-2 text-center w-80">พิมพ์เอกสาร</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @php $i = 1; @endphp
                        @foreach($schoolcountactivity as $s)
                        <tr class="group bg-white hover:bg-gray-50/50 transition-all border border-gray-100 shadow-sm rounded-2xl">
                            <td class="px-6 py-6 text-center text-gray-300 font-black text-lg">
                                {{ sprintf("%02d", $i++) }}
                            </td>
                            <td class="px-6 py-6 font-bold">
                                <div class="text-[10px] font-black text-primary uppercase tracking-wider mb-1">
                                    {{$s->getactivityname->getactivitysubject->subjectgroup_name}}
                                </div>
                                <a href="{{url('/')}}/viewactivitydetail/{{$s->school_activity}}" class="font-black text-gray-800 hover:text-emerald-600 transition-colors block text-base leading-tight uppercase italic underline decoration-emerald-100 decoration-2 underline-offset-4">
                                    {{$s->getactivityname->activity_name}}
                                </a>
                            </td>
                            <td class="px-6 py-6 text-center">
                                <span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-full text-[10px] font-black tracking-wider border border-blue-100 uppercase">
                                    {{$s->getactivityname->getclass->class_name}}
                                </span>
                            </td>
                            <td class="px-6 py-6 text-center">
                                <div class="inline-flex flex-col items-center justify-center w-14 h-14 rounded-2xl bg-gray-50 border border-gray-100">
                                    <span class="text-lg font-black text-gray-700 leading-none">{{$s->count}}</span>
                                    <span class="text-[8px] text-gray-400 font-bold uppercase">Teams</span>
                                </div>
                            </td>
                            <td class="px-6 py-6">
                                <div class="grid grid-cols-2 gap-2 text-center">
                                    <a href="{{url('/')}}/showprintpointdoc/{{$s->getactivityname->id}}" target="_blank" class="p-2 bg-white border border-gray-200 text-blue-600 rounded-xl hover:bg-blue-600 hover:text-white transition-all text-[9px] font-black flex items-center justify-center gap-1 shadow-sm uppercase tracking-tighter">
                                        <i class="ri-edit-box-line"></i> คะแนน
                                    </a>
                                    <a href="{{url('/')}}/studentregisterform/{{$s->getactivityname->id}}" target="_blank" class="p-2 bg-white border border-gray-200 text-blue-600 rounded-xl hover:bg-blue-600 hover:text-white transition-all text-[9px] font-black flex items-center justify-center gap-1 shadow-sm uppercase tracking-tighter">
                                        <i class="ri-user-follow-line"></i> รายงานตัว
                                    </a>
                                    <a href="{{url('/')}}/teacherregisterform/{{$s->getactivityname->id}}" target="_blank" class="p-2 bg-white border border-gray-200 text-indigo-600 rounded-xl hover:bg-indigo-600 hover:text-white transition-all text-[9px] font-black flex items-center justify-center gap-1 shadow-sm uppercase tracking-tighter">
                                        <i class="ri-time-line"></i> ครู
                                    </a>
                                    <a href="{{url('/')}}/jowregisterform/{{$s->getactivityname->id}}" target="_blank" class="p-2 bg-white border border-gray-200 text-indigo-600 rounded-xl hover:bg-indigo-600 hover:text-white transition-all text-[9px] font-black flex items-center justify-center gap-1 shadow-sm uppercase tracking-tighter">
                                        <i class="ri-shield-user-line"></i> กรรมการ
                                    </a>
                                    <a href="{{url('/')}}/printjow_showbg/{{$s->getactivityname->id}}" target="_blank" class="col-span-2 p-2 bg-emerald-600 text-white rounded-xl hover:shadow-lg transition-all text-[9px] font-black flex items-center justify-center gap-1 shadow-md uppercase tracking-wider">
                                        <i class="ri-medal-fill text-xs"></i> พิมพ์เกียรติบัตรกรรมการ
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if(Session::has('massage'))
                <div class="mt-8 p-4 bg-primary text-white rounded-2xl shadow-lg flex items-center gap-3">
                    <i class="ri-notification-3-line text-xl"></i>
                    <span class="font-bold">{{ Session::get('massage') }}</span>
                </div>
            @endif

        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof jQuery !== 'undefined' && $.fn.DataTable) {
            
            if ($.fn.DataTable.isDataTable('#printTable')) {
                $('#printTable').DataTable().destroy();
            }

            $('#printTable').DataTable({
                "responsive": true,
                "autoWidth": false,
                "pageLength": 10,
                /* dom แบบเน้นให้อยู่บรรทัดเดียวกัน: 
                   f = filter อยู่บนขวา
                   t = table
                   i & p = info และ pagination อยู่ล่าง */
                "dom": '<"flex justify-end mb-4"f>rt<"flex flex-col md:flex-row justify-between items-center mt-6 gap-4"ip>',
                "language": {
                   "search": '<i class="ri-search-line text-primary text-xl"></i>', 
                   "searchPlaceholder": "ค้นหารายการแข่งขัน...",
                   "paginate": { 
                       "next": '<i class="ri-arrow-right-s-line"></i>', 
                       "previous": '<i class="ri-arrow-left-s-line"></i>' 
                   },
                   "info": "Showing _START_ to _END_ of _TOTAL_ Entries",
                }
            });
        }
    });
</script>
@endsection