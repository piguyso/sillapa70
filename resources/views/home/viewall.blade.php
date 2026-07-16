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
        <p class="text-emerald-700 font-bold animate-pulse text-lg">กำลังประมวลผลตารางสรุปเหรียญรางวัล...</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 text-slate-800 font-sarabun">
    
    <div class="bg-gradient-to-r from-primary to-emerald-600 rounded-3xl shadow-xl p-8 md:p-12 mb-8 text-white relative overflow-hidden">
        <div class="relative z-10">
            <h1 class="text-3xl md:text-5xl font-black mb-2 tracking-tight italic uppercase">
                {{session('app_name').' '.session('app_group')}}
            </h1>
            <p class="text-green-50 text-lg md:text-xl opacity-90 font-medium">
                <i class="ri-medal-line mr-2"></i>ตารางสรุปเหรียญรางวัลรายโรงเรียน
            </p>
        </div>
        <i class="ri-trophy-line absolute -right-10 -bottom-10 text-[15rem] opacity-10 rotate-12"></i>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        
        <div class="bg-gray-50/50 px-8 py-6 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="./" class="group inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-white text-primary border border-gray-200 hover:bg-primary hover:text-white transition-all shadow-sm">
                    <i class="ri-home-4-line text-2xl"></i>
                </a>
                <div>
                    <h2 class="text-xl font-bold text-gray-800 leading-none mb-1">อันดับเหรียญรางวัลแยกตามสถานศึกษา</h2>
                    <p class="text-[10px] text-emerald-600 font-black uppercase tracking-[0.2em] italic">Medal Standings Directory</p>
                </div>
            </div>

            <div id="excel_tools_area" class="flex items-center"></div>
        </div>

        <div class="p-8">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-separate border-spacing-y-3" id="table_medal_ranking">
                    <thead>
                        <tr class="text-gray-400 text-[10px] uppercase tracking-[0.2em] font-black">
                            <th class="px-6 py-2 text-center w-20">อันดับ</th>
                            <th class="px-6 py-2">ชื่อโรงเรียน</th>
                            <th class="px-6 py-2 text-center">เหรียญทอง</th>
                            <th class="px-6 py-2 text-center">เหรียญเงิน</th>
                            <th class="px-6 py-2 text-center">เหรียญทองแดง</th>
                            <th class="px-6 py-2 text-center text-primary">รวมเหรียญ</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @php $num1 = 1; @endphp
                        @forelse($dbrank as $l)
                        <tr class="group bg-white hover:bg-gray-50/50 transition-all border border-gray-100 shadow-sm rounded-2xl">
                            <td class="px-6 py-6 text-center" data-order="{{ $num1 }}">
                                <div class="w-10 h-10 mx-auto flex items-center justify-center rounded-xl font-black text-lg shadow-sm
                                    @if($num1 == 1) bg-yellow-400 text-white 
                                    @elseif($num1 == 2) bg-slate-300 text-slate-700
                                    @elseif($num1 == 3) bg-orange-400 text-white
                                    @else bg-gray-50 text-gray-400 border border-gray-100 @endif">
                                    {{ $num1 }}
                                </div>
                            </td>

                            <td class="px-6 py-6 font-black text-gray-800 italic uppercase">
                                <a href="showschooldetail/{{$l->school_name}}/0" target="_blank" class="hover:text-primary hover:underline transition-all">
                                    {{$selectschool[$l->school_name]}}
                                </a>
                            </td>

                            <td class="px-6 py-6 text-center bg-yellow-50/20">
                                <span class="text-lg font-black text-yellow-600">{{ number_format($l->Gold) }}</span>
                            </td>

                            <td class="px-6 py-6 text-center bg-slate-50/20">
                                <span class="text-lg font-black text-slate-500">{{ number_format($l->Silver) }}</span>
                            </td>

                            <td class="px-6 py-6 text-center bg-orange-50/20">
                                <span class="text-lg font-black text-orange-600">{{ number_format($l->Bronce) }}</span>
                            </td>

                            <td class="px-6 py-6 text-center">
                                <div class="inline-block px-5 py-1.5 bg-primary text-white font-black rounded-full shadow-lg shadow-primary/20 min-w-[60px]">
                                    {{ number_format($l->Gold + $l->Silver + $l->Bronce) }}
                                </div>
                            </td>
                        </tr>
                        @php $num1++; @endphp
                        @empty
                        <tr>
                            <td colspan="6" class="py-20 text-center text-gray-400 italic font-medium">ไม่พบข้อมูลอันดับเหรียญรางวัลในขณะนี้</td>
                        </tr>
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
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>

<script>
    $(document).ready(function() {
        if ($.fn.DataTable) {
            var table = $('#table_medal_ranking').DataTable({
                "responsive": true,
                "autoWidth": false,
                "pageLength": 100,
                // สั่งให้ตารางเรียงลำดับตามคอลัมน์แรก (index 0) จากน้อยไปมากเสมอ
                "order": [[ 0, "asc" ]],
                // ปิดการกดหัวตารางเพื่อเปลี่ยนการเรียงลำดับ เพื่อรักษาลำดับที่คุณคำนวณมา
                "ordering": true, 
                "dom": '<"flex justify-end mb-4"f>rt<"flex flex-col md:flex-row justify-between items-center mt-6 gap-4"ip>',
                "buttons": [
                    {
                        extend: 'excelHtml5',
                        text: '<i class="ri-file-excel-line mr-2"></i> ส่งออกสรุปเหรียญ (Excel)',
                        className: 'bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-3 rounded-2xl font-bold shadow-lg border-none transition-all flex items-center',
                        title: 'สรุปเหรียญรางวัลรายโรงเรียน_{{ date("d-m-Y") }}'
                    }
                ],
                "language": {
                   "search": '<i class="ri-search-line dt-search-icon"></i>', 
                   "searchPlaceholder": "ค้นหาชื่อโรงเรียน...",
                },
                "initComplete": function(settings, json) {
                    $('#loading-overlay').addClass('opacity-0');
                    setTimeout(function() {
                        $('#loading-overlay').remove();
                    }, 500);
                }
            });

            table.buttons().container().appendTo('#excel_tools_area');
        }
    });
</script>
@endsection