@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 text-slate-800 font-sarabun">
    
    <div class="bg-gradient-to-r from-indigo-700 to-slate-800 rounded-3xl shadow-xl p-8 md:p-12 mb-8 text-white relative overflow-hidden">
        <div class="relative z-10">
            <h1 class="text-2xl md:text-4xl font-black mb-4 tracking-tight italic uppercase">
                <i class="ri-printer-line mr-2 text-indigo-300"></i>พิมพ์เกียรติบัตรกรรมการตัดสิน
            </h1>
            <p class="text-indigo-100 text-lg md:text-xl opacity-90 font-medium">
                {{ session('app_name')}} เครือข่ายสถานศึกษา {{session()->get('schoolgroup')}} ปีการศึกษา {{ session('app_year') }}
            </p>
        </div>
        <i class="ri-award-fill absolute -right-10 -bottom-10 text-[15rem] text-white opacity-5 rotate-12"></i>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        
        <div class="bg-gray-50/50 px-8 py-6 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="../report" class="group inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-white text-indigo-600 border border-gray-200 hover:bg-indigo-700 hover:text-white transition-all shadow-sm">
                    <i class="ri-arrow-left-s-line text-2xl group-hover:-translate-x-1 transition-transform"></i>
                </a>
                <div>
                    <h2 class="text-xl font-bold text-slate-700 leading-none mb-1">รายชื่อกรรมการแยกตามกิจกรรม</h2>
                    <p class="text-[10px] text-indigo-400 font-black uppercase tracking-[0.2em] italic">Judges List Management</p>
                </div>
            </div>
        </div>

        <div class="p-8">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-separate border-spacing-y-4" id="table_no_modal">
                    <thead>
                        <tr class="text-slate-400 text-[10px] uppercase tracking-[0.2em] font-black">
                            <th class="px-6 py-2 text-center w-16">#</th>
                            <th class="px-6 py-2 w-1/3">รายละเอียดกิจกรรม</th>
                            <th class="px-6 py-2">รายชื่อกรรมการ</th>
                            <th class="px-6 py-2 text-center">พิมพ์เกียรติบัตร</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @php $x = ($activity->currentPage() - 1) * $activity->perPage(); @endphp
                        @foreach($activity as $l)
                        <tr class="group bg-white hover:bg-indigo-50/30 transition-all border border-gray-100 shadow-sm rounded-2xl">
                            <td class="px-6 py-8 text-center font-black text-slate-300 text-xl border-l-4 border-indigo-100 group-hover:border-indigo-500 transition-all">
                                {{ ++$x }}
                            </td>
                            <td class="px-6 py-8">
                                <div class="mb-2 text-lg font-black text-indigo-700 leading-tight group-hover:text-indigo-900 transition-colors">
                                    {{$l->activity_name}}
                                </div>
                                <div class="grid grid-cols-1 gap-1 text-[11px] font-bold text-slate-500 italic uppercase">
                                    <span class="flex items-center gap-1"><i class="ri-book-open-line text-emerald-500"></i> {{$l->getactivitysubject->subjectgroup_name}}</span>
                                    <span class="flex items-center gap-1"><i class="ri-map-pin-line text-emerald-500"></i> {{$l->activity_branchroom}}</span>
                                    <span class="flex items-center gap-1 text-emerald-600 font-black"><i class="ri-calendar-event-line"></i> {{Datethai($l->activity_day)}} | {{$l->activity_time}}</span>
                                </div>
                            </td>
                            <td class="px-6 py-8">
                                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 group-hover:bg-white transition-colors">
                                    <div class="text-[9px] font-black text-indigo-400 uppercase mb-2 tracking-widest">คณะกรรมการตัดสิน</div>
                                    <div class="space-y-2">
                                        @foreach($jow as $i)
                                            @if($i->jow_activity == $l->id)
                                                <div class="flex items-center gap-2 font-bold text-slate-700">
                                                    <i class="ri-checkbox-circle-fill text-emerald-500"></i>
                                                    {{$i->getjowtitle->title}}{{$i->jow_name}} {{$i->jow_lastname}}
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-8 text-center">
                                <div class="flex flex-col gap-2 min-w-[140px]">
                                    <a href="../printjow/{{$l->id}}" target="_blank" class="flex items-center justify-center gap-2 px-4 py-2 bg-slate-700 text-white rounded-xl text-xs font-bold hover:bg-slate-900 transition-all shadow-md">
                                        <i class="ri-printer-line"></i> ไม่มีพื้นหลัง
                                    </a>
                                    <a href="../printjow_showbg/{{$l->id}}" target="_blank" class="flex items-center justify-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-xl text-xs font-bold hover:bg-indigo-800 transition-all shadow-md shadow-indigo-100">
                                        <i class="ri-image-line"></i> มีพื้นหลัง
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-8">
                {{ $activity->links() }}
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        if ($.fn.DataTable) {
            $('#table_no_modal').DataTable({
                "paging": false,
                "info": false,
                "autoWidth": false,
                "dom": '<"flex justify-end mb-4"f>rt',
                "language": {
                    "search": "",
                    "searchPlaceholder": "🔍 พิมพ์ชื่อกิจกรรมหรือกรรมการเพื่อค้นหา..."
                }
            });
        }
    });
</script>

<style>
    /* ปรับแต่งสีของช่องค้นหาให้เข้ากับธีม Indigo */
    .dataTables_filter label {
        @apply flex items-center bg-white border-2 border-indigo-100 rounded-2xl px-4 py-2 transition-all focus-within:border-indigo-500 !important;
    }
</style>
@endsection