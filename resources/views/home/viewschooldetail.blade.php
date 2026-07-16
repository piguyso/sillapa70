@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <div class="bg-gradient-to-r from-primary to-emerald-600 rounded-2xl shadow-lg p-8 mb-8 text-white">
        <h1 class="text-3xl md:text-4xl font-bold mb-2">{{session('app_name').' '.session('app_group')}}</h1>
        <p class="text-green-50 text-lg opacity-90">{{session('app_area')}}</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <div class="lg:col-span-1 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 font-bold text-gray-700 flex items-center gap-2">
                <i class="ri-pie-chart-2-line text-primary"></i> สรุปจำนวนเหรียญรางวัล
            </div>
            <div class="p-6">
                <div class="relative h-64 w-full">
                    <canvas id="medalPieChart"></canvas>
                </div>
                
                @php
                    $golds = 0; $silvers = 0; $bronzes = 0;
                    foreach($dbrank as $l) {
                        $golds += $l->Gold;
                        $silvers += $l->Silver;
                        $bronzes += $l->Bronce;
                    }
                    $totalMedals = $golds + $silvers + $bronzes;
                @endphp
            </div>
        </div>

        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden text-slate-800">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 font-bold text-gray-700 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i class="ri-medal-line text-secondary"></i>
                    สรุปผลการแข่งขันของ: 
                    <span class="text-primary">
                        {{ isset($addschool[0]) ? $addschool[0]->getschoolname->school_name : 'ไม่พบข้อมูลโรงเรียน' }}
                    </span>
                </div>
                <button onclick="exportTableToExcel('table_all_participants', 'results-school-{{$addschool[0]->getschoolname->id ?? 0}}')" 
                        class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-100 text-emerald-700 border border-emerald-200 rounded-xl font-bold hover:bg-emerald-600 hover:text-white transition-all shadow-sm text-sm">
                    <i class="ri-file-excel-fill text-lg"></i>
                    Export Excel
                </button>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                    <div class="p-4 bg-yellow-50 rounded-2xl border border-yellow-100 text-slate-800">
                        <img src="{{ url('imgs/gold.png') }}" class="w-8 h-8 mx-auto mb-2" alt="Gold">
                        <div class="text-xs text-yellow-600 font-bold uppercase">ทอง</div>
                        <div class="text-2xl font-black text-yellow-700">{{ $golds }}</div>
                    </div>
                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200">
                        <img src="{{ url('imgs/silver.png') }}" class="w-8 h-8 mx-auto mb-2" alt="Silver">
                        <div class="text-xs text-slate-500 font-bold uppercase">เงิน</div>
                        <div class="text-2xl font-black text-slate-600">{{ $silvers }}</div>
                    </div>
                    <div class="p-4 bg-orange-50 rounded-2xl border border-orange-100">
                        <img src="{{ url('imgs/bronze.png') }}" class="w-8 h-8 mx-auto mb-2" alt="Bronze">
                        <div class="text-xs text-orange-600 font-bold uppercase">ทองแดง</div>
                        <div class="text-2xl font-black text-orange-700">{{ $bronzes }}</div>
                    </div>
                    <div class="p-4 bg-primary rounded-2xl border border-emerald-600 text-white shadow-md">
                        <div class="text-xs font-bold uppercase opacity-80 mt-1">รวมเหรียญ</div>
                        <div class="text-2xl font-black">{{ $totalMedals }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden text-slate-800">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ ($schoolgroup == 0) ? '../../' : '../../showschool/'.$schoolgroup }}" 
                   class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white text-primary border border-gray-200 hover:bg-primary hover:text-white transition-all shadow-sm">
                    <i class="ri-arrow-left-line text-xl"></i>
                </a>
                <h3 class="text-xl font-bold text-gray-800">รายการแข่งขันที่เข้าร่วม</h3>
            </div>
        </div>

        <div class="p-6">
            @if (session('status'))
                <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 flex items-center gap-3 rounded-r-lg">
                    <i class="ri-checkbox-circle-line text-xl"></i> {{ session('status') }}
                </div>
            @endif

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse" id="table_all_participants">
                    <thead>
                        <tr class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wider border-b border-gray-200">
                            <th class="px-4 py-4 font-bold text-center w-12">#</th>
                            <th class="px-4 py-4 font-bold">รายการแข่งขัน</th>
                            <th class="px-4 py-4 font-bold">รายชื่อนักเรียน</th>
                            <th class="px-4 py-4 font-bold">รายชื่อครู</th>
                            <th class="px-4 py-4 font-bold text-center">คะแนน</th>
                            <th class="px-4 py-4 font-bold text-center">เหรียญ</th>
                            <th class="px-4 py-4 font-bold text-center">อันดับ</th>
                            <th class="px-4 py-4 font-bold text-center w-40" data-exclude="true">เกียรติบัตร</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @php $x = ($addschool->currentPage() - 1) * $addschool->perPage(); @endphp
                        @forelse($addschool as $l)
                        <tr class="hover:bg-gray-50/80 transition-colors">
                            <td class="px-4 py-4 text-center text-gray-400 font-medium">@php $x++; echo $x; @endphp.</td>
                            <td class="px-4 py-4 font-bold text-gray-800">
                                <a href="{{url('viewactivitydetail/')}}/{{$l->getactivityname->id}}">{{$l->getactivityname->activity_name}}</a>
                                <div class="text-[10px] text-gray-400 font-normal">ช่วงชั้น: {{$l->getactivityname->getclass->class_name}}</div>
                            </td>
                            <td class="px-4 py-4 leading-relaxed">
                                @php $ii=1; @endphp
                                @foreach($student as $s)
                                    @foreach($s->getstudentname as $item)
                                        @if($l->id == $item->school && $item->type == 0)
                                            <div class="whitespace-nowrap">{{ $ii }}. {{$item->getstudenttitle->title}}{{$item->name}} {{$item->lastname}}</div>
                                            @php $ii++; @endphp
                                        @endif
                                    @endforeach
                                @endforeach
                            </td>
                            <td class="px-4 py-4 leading-relaxed text-blue-600">
                                @php $kk=1; @endphp
                                @foreach($student as $s)
                                    @foreach($s->getstudentname as $item)
                                        @if($l->id == $item->school && $item->type == 1)
                                            <div class="whitespace-nowrap">{{ $kk }}. {{$item->getstudenttitle->title}}{{$item->name}} {{$item->lastname}}</div>
                                            @php $kk++; @endphp
                                        @endif
                                    @endforeach
                                @endforeach
                            </td>
                            <td class="px-4 py-4 text-center font-bold text-lg">
                                {{ ($l->getactivityname->activity_status == 1) ? $l->student_score : '-' }}
                            </td>
                            <td class="px-4 py-4 text-center">
                                @if($l->getactivityname->activity_status == 1)
                                    {{ getmedel($l->student_score) }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-4 py-4 text-center">
                                {{ ($l->getactivityname->activity_status == 1) ? $l->activity_rank : '-' }}
                            </td>
                            <td class="px-4 py-4 text-center" data-exclude="true">
                                @if($l->getactivityname->activity_status == 1 && $l->student_score > 0)
                                <div class="flex flex-col gap-1.5">
                                    <form method="post" target="_blank" action="{{url('printPDF')}}">
                                        {!!csrf_field()!!}
                                        <input type="hidden" name="id" value="{{$l->id}}"/>
                                        <button type="submit" class="w-full py-1 bg-blue-50 text-blue-600 border border-blue-200 rounded text-[10px] font-bold hover:bg-blue-600 transition-all">ไม่มีพื้นหลัง</button>
                                    </form>
                                    <form method="post" target="_blank" action="{{url('printPDF2')}}">
                                        {!!csrf_field()!!}
                                        <input type="hidden" name="id" value="{{$l->id}}"/>
                                        <button type="submit" class="w-full py-1 bg-emerald-50 text-emerald-600 border border-emerald-200 rounded text-[10px] font-bold hover:bg-emerald-600 transition-all">มีพื้นหลัง</button>
                                    </form>
                                </div>
                                @else
                                    <span class="text-gray-300 text-[10px]">รอประกาศผล</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="8" class="px-4 py-10 text-center text-gray-400">ไม่พบข้อมูล</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-6">{{ $addschool->links() }}</div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.tailwindcss.min.js"></script>

<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
<script>
// ฟังก์ชัน Export Excel แบบแยกคอลัมน์และกรองคอลัมน์ที่ไม่ต้องการออก
function exportTableToExcel(tableID, filename = ''){
    var tableSelect = document.getElementById(tableID);
    
    // สร้าง Workbook ใหม่จาก Table
    // เราจะสร้าง Clone ของ Table เพื่อลบคอลัมน์ "เกียรติบัตร" ออกก่อน Export เพื่อความสวยงามในไฟล์ Excel
    var tableClone = tableSelect.cloneNode(true);
    var rows = tableClone.rows;
    for (var i = 0; i < rows.length; i++) {
        // ลบคอลัมน์สุดท้าย (เกียรติบัตร) ออกทุกแถว
        rows[i].deleteCell(-1); 
    }

    var wb = XLSX.utils.table_to_book(tableClone, {sheet: "ผลการแข่งขัน"});
    filename = filename ? filename + '.xlsx' : 'school_results.xlsx';
    XLSX.writeFile(wb, filename);
}

$(function () {
    Chart.register(ChartDataLabels);
    var donutChartCanvas = $('#medalPieChart').get(0).getContext('2d');
    var totalMedals = {{ $golds + $silvers + $bronzes }};
    
    new Chart(donutChartCanvas, {
        type: 'doughnut',
        data: {
            labels: ['ทอง ({{$golds}})', 'เงิน ({{$silvers}})', 'ทองแดง ({{$bronzes}})'],
            datasets: [{
                data: [{{ $golds }}, {{ $silvers }}, {{ $bronzes }}],
                backgroundColor: ['#fbbf24', '#cbd5e1', '#d97706'],
                hoverOffset: 10,
                borderWidth: 0
            }]
        },
        options: {
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: { position: 'bottom', labels: { boxWidth: 12, padding: 20, font: { size: 12 }}},
                datalabels: {
                    color: '#1f2937', font: { weight: 'bold', size: 11 },
                    formatter: (value, ctx) => value > 0 ? value : ''
                }
            }
        },
        plugins: [{
            id: 'centerText',
            beforeDraw: function(chart) {
                var width = chart.width, height = chart.height, ctx = chart.ctx;
                ctx.restore();
                ctx.textBaseline = "middle";
                ctx.fillStyle = "#111827";
                ctx.font = "bold 14px Sarabun";
                ctx.fillText("รวม", Math.round((width - ctx.measureText("รวม").width) / 2), height / 2 - 15);
                ctx.font = "black 32px Sarabun";
                ctx.fillText(totalMedals, Math.round((width - ctx.measureText(totalMedals).width) / 2), height / 2 + 15);
                ctx.save();
            }
        }]
    });
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