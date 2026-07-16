@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 text-slate-800 font-sarabun">
    
    <div class="bg-gradient-to-r from-orange-500 to-amber-600 rounded-3xl shadow-xl p-8 md:p-12 mb-8 text-white relative overflow-hidden">
        <div class="relative z-10">
            <h1 class="text-2xl md:text-4xl font-black mb-4 tracking-tight italic uppercase">
                <i class="ri-printer-line mr-2"></i>พิมพ์เกียรติบัตรกรรมการดำเนินงาน
            </h1>
            <p class="text-orange-50 text-lg md:text-xl opacity-90 font-medium">
                {{ session('app_name')}} ระดับเครือข่ายสถานศึกษา {{session()->get('schoolgroup')}} ปีการศึกษา {{ session('app_year') }}
            </p>
        </div>
        <i class="ri-medal-fill absolute -right-10 -bottom-10 text-[15rem] opacity-10 rotate-12"></i>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        
        <div class="bg-gray-50/50 px-8 py-6 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{url('/report')}}" class="group inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-white text-orange-600 border border-gray-200 hover:bg-orange-600 hover:text-white transition-all shadow-sm">
                    <i class="ri-arrow-left-s-line text-2xl group-hover:-translate-x-1 transition-transform"></i>
                </a>
                <div>
                    <h2 class="text-xl font-bold text-gray-800 leading-none mb-1">รายชื่อกรรมการดำเนินงาน</h2>
                    <p class="text-[10px] text-orange-500 font-black uppercase tracking-[0.2em] italic">Staff & Organizing Committee Directory</p>
                </div>
            </div>

            @if(!$jow->isEmpty())
            <div class="flex items-center gap-2">
                <a href="{{url('printjow2')}}/{{$branch}}" target="_blank" class="flex items-center gap-2 px-6 py-3 bg-slate-800 text-white rounded-2xl text-xs font-bold hover:bg-black transition-all shadow-lg">
                    <i class="ri-printer-fill"></i> พิมพ์ทั้งหมด [ไม่มีพื้นหลัง]
                </a>
                <a href="{{url('printjow2_showbg')}}/{{$branch}}" target="_blank" class="flex items-center gap-2 px-6 py-3 bg-orange-500 text-white rounded-2xl text-xs font-bold hover:bg-orange-600 transition-all shadow-lg shadow-orange-100">
                    <i class="ri-image-fill"></i> พิมพ์ทั้งหมด [มีพื้นหลัง]
                </a>
            </div>
            @endif
        </div>

        <div class="p-8">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-separate border-spacing-y-3" id="table_staff_orange">
                    <thead>
                        <tr class="text-gray-400 text-[10px] uppercase tracking-[0.2em] font-black">
                            <th class="px-6 py-2 text-center w-16">#</th>
                            <th class="px-6 py-2">ชื่อ - นามสกุล</th>
                            <th class="px-6 py-2">ศูนย์การแข่งขัน</th>
                            <th class="px-6 py-2 text-center">พิมพ์รายบุคคล</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @php $x = ($jow->currentPage() - 1) * $jow->perPage(); @endphp
                        @forelse($jow as $l)
                        <tr class="group bg-white hover:bg-orange-50/30 transition-all border border-gray-100 shadow-sm rounded-2xl">
                            <td class="px-6 py-6 text-center font-black text-orange-300 text-lg italic">
                                {{ ++$x }}.
                            </td>
                            <td class="px-6 py-6 font-black text-gray-800 text-base">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-orange-100 rounded-xl flex items-center justify-center text-orange-600 font-black shadow-inner">
                                        {{ substr($l->jow_name, 0, 1) }}
                                    </div>
                                    {{$l->getjowtitle->title}} {{$l->jow_name}} {{$l->jow_lastname}}
                                </div>
                            </td>
                            <td class="px-6 py-6 font-bold text-gray-400 uppercase tracking-tighter italic">
                                <i class="ri-map-pin-2-line text-orange-400"></i> {{$l->getjowbranch->branch_name}}
                            </td>
                            <td class="px-6 py-6 text-center">
                                <div class="flex justify-center gap-2">
                                    <a href="../printreportjow/{{$l->id}}" target="_blank" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-xl text-xs font-bold hover:bg-slate-800 hover:text-white transition-all">ไม่มีพื้นหลัง</a>
                                    <a href="../printreportjow_showbg/{{$l->id}}" target="_blank" class="px-4 py-2 bg-orange-50 text-orange-600 border border-orange-100 rounded-xl text-xs font-bold hover:bg-orange-500 hover:text-white transition-all">มีพื้นหลัง</a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="py-20 text-center text-gray-400 italic">ไม่พบข้อมูลกรรมการดำเนินงาน</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-8">
                {{ $jow->links() }}
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        if ($.fn.DataTable) {
            $('#table_staff_orange').DataTable({
                "paging": false,
                "info": false,
                "dom": '<"flex justify-end mb-4"f>rt',
                "language": {
                    "search": "",
                    "searchPlaceholder": "🔍 ค้นหารายชื่อกรรมการ..."
                }
            });
        }
    });
</script>
@endsection