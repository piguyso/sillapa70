@extends('layouts.apparea')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 font-sarabun text-slate-800">

    <div class="relative bg-gradient-to-r from-amber-800 to-orange-600 rounded-3xl shadow-xl p-8 md:p-12 mb-8 text-white overflow-hidden border-b-8 border-orange-500 text-white">
        <div class="relative z-10 text-white">
            <h2 class="text-xl md:text-2xl font-bold opacity-90 italic mb-2 text-white leading-none">
                {{ session('app_name')}} ระดับเครือข่ายสถานศึกษา {{ session()->get('schoolgroup') }}
            </h2>
            <h1 class="text-3xl md:text-5xl font-black tracking-tight uppercase italic text-white leading-none text-white">
                @if(isset($addschool[0])) {{ $addschool[0]->getschoolname->school_name }} @else รายงานสรุปผลการแข่งขัน @endif
            </h1>
            <p class="mt-4 text-orange-100 font-bold italic uppercase tracking-widest text-sm text-white">
                <i class="ri-pie-chart-2-line mr-1 text-white"></i> สรุปเหรียญรางวัลและอันดับการแข่งขัน ปีการศึกษา {{ session('app_year') }}
            </p>
        </div>
        <i class="ri-medal-fill absolute -right-10 -bottom-10 text-[15rem] opacity-10 rotate-12 text-white"></i>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
        <div class="lg:col-span-1 bg-white rounded-3xl shadow-xl shadow-slate-100 border border-slate-100 overflow-hidden">
            <div class="bg-slate-50 px-6 py-4 border-b border-slate-100 font-black italic text-slate-700 text-xs uppercase tracking-widest">
                Medal Distribution
            </div>
            <div class="p-6">
                <div class="relative" style="height: 300px;">
                    <canvas id="medalPieChart"></canvas>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2 bg-white rounded-3xl shadow-xl shadow-slate-100 border border-slate-100 overflow-hidden">
             <div class="bg-slate-50 px-6 py-4 border-b border-slate-100 font-black italic text-slate-700 text-xs uppercase tracking-widest">
                Medal Tally
            </div>
            <div class="p-8">
                <div class="grid grid-cols-3 gap-4 mb-8">
                    @php $golds = 0; $silvers = 0; $bronzes = 0; foreach($dbrank as $l) { $golds += $l->Gold; $silvers += $l->Silver; $bronzes += $l->Bronce; } @endphp
                    
                    <div class="text-center p-4 bg-amber-50 rounded-2xl border border-amber-100">
                        <img src='{{ url("imgs/gold.png")}}' class="w-8 h-8 mx-auto mb-2">
                        <div class="text-2xl font-black text-amber-600">{{ $golds }}</div>
                        <div class="text-[10px] font-bold text-amber-800 uppercase italic">Gold</div>
                    </div>
                    <div class="text-center p-4 bg-slate-50 rounded-2xl border border-slate-100">
                        <img src='{{ url("imgs/silver.png")}}' class="w-8 h-8 mx-auto mb-2">
                        <div class="text-2xl font-black text-slate-600">{{ $silvers }}</div>
                        <div class="text-[10px] font-bold text-slate-500 uppercase italic">Silver</div>
                    </div>
                    <div class="text-center p-4 bg-orange-50 rounded-2xl border border-orange-100">
                        <img src='{{ url("imgs/bronze.png")}}' class="w-8 h-8 mx-auto mb-2">
                        <div class="text-2xl font-black text-orange-600">{{ $bronzes }}</div>
                        <div class="text-[10px] font-bold text-orange-800 uppercase italic">Bronze</div>
                    </div>
                </div>

                <div class="flex items-center justify-between p-6 bg-slate-900 rounded-3xl text-white shadow-xl shadow-slate-200">
                    <div>
                        <div class="text-xs font-black uppercase italic opacity-60">Total Medals</div>
                        <div class="text-4xl font-black italic text-white leading-none mt-1">{{ $golds + $silvers + $bronzes }}</div>
                    </div>
                    <i class="ri-trophy-fill text-5xl text-orange-500"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-xl shadow-slate-100 border border-slate-100 overflow-hidden">
        <div class="bg-slate-50 px-8 py-5 border-b border-slate-100 flex justify-between items-center text-slate-800">
            <div class="flex items-center gap-4 text-slate-800">
                <a href="{{ $schoolgroup == 0 ? url('../../') : url('../../showschool/'.$schoolgroup) }}" class="w-10 h-10 flex items-center justify-center bg-white text-orange-600 rounded-xl border border-slate-200 hover:bg-orange-600 hover:text-white transition-all shadow-sm group">
                    <i class="ri-arrow-left-s-line text-2xl group-hover:-translate-x-1 transition-transform text-white"></i>
                </a>
                <h2 class="text-base font-black text-slate-800 uppercase italic tracking-wide text-slate-800 leading-none">Detailed Competition Results</h2>
            </div>
        </div>

        <div class="p-4 md:p-8 text-slate-800">
            <div class="overflow-x-auto text-slate-800">
                <table class="w-full text-left border-separate border-spacing-y-3 text-slate-800">
                    <thead>
                        <tr class="text-slate-400 text-[10px] uppercase tracking-[0.2em] font-black italic text-slate-400">
                            <th class="px-4 py-2 text-center w-12">#</th>
                            <th class="px-4 py-2">รายการแข่งขัน</th>
                            <th class="px-4 py-2">รายชื่อนักเรียน/ครู</th>
                            <th class="px-4 py-2 text-center">คะแนน</th>
                            <th class="px-4 py-2 text-center">เหรียญ/อันดับ</th>
                            <th class="px-4 py-2 text-right">พิมพ์เกียรติบัตร</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs font-bold italic text-slate-800">
                        @php $x = $addschool->currentPage() == 1 ? 0 : $addschool->perPage() * ($addschool->currentPage() - 1); @endphp
                        @forelse($addschool as $l)
                        @php $x++; @endphp
                        <tr class="bg-white hover:bg-orange-50/50 transition-all border border-slate-100 shadow-sm rounded-2xl group text-slate-800">
                            <td class="px-4 py-6 text-center text-slate-300 rounded-l-2xl border-l-4 border-transparent group-hover:border-orange-500 text-slate-800">
                                {{ $x }}
                            </td>
                            <td class="px-4 py-6 text-slate-800">
                                <a href="../../viewactivitydetail/{{$l->school_activity}}" target="_blank" class="text-slate-800 font-black uppercase hover:text-orange-600 transition-colors text-slate-800">
                                    {{$l->getactivityname->activity_name}}
                                </a>
                                <p class="text-[9px] text-slate-400 mt-1 uppercase tracking-tighter">{{$l->getactivityname->getclass->class_name}}</p>
                            </td>
                            <td class="px-4 py-6 text-slate-800">
                                <div class="grid grid-cols-1 gap-2 text-slate-800">
                                    <div class="text-slate-600 text-[10px] text-slate-800">
                                        <p class="font-black text-orange-600 uppercase mb-1">Students:</p>
                                        @foreach($student as $s)
                                            @foreach($s->getstudentname as $st)
                                                @if($l->id == $st->school && $st->type == 0)
                                                    <div class="leading-tight">• {{$st->getstudenttitle->title}}{{$st->name}} {{$st->lastname}}</div>
                                                @endif
                                            @endforeach
                                        @endforeach
                                    </div>
                                    <div class="text-slate-400 font-medium italic">
                                        @foreach($student as $s)
                                            @foreach($s->getstudentname as $st)
                                                @if($l->id == $st->school && $st->type == 1)
                                                    <div class="leading-tight opacity-70">Teacher: {{$st->getstudenttitle->title}}{{$st->name}} {{$st->lastname}}</div>
                                                @endif
                                            @endforeach
                                        @endforeach
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-6 text-center text-slate-800">
                                <span class="text-lg font-black text-slate-800">
                                    @if($l->activity_status == "1" || $l->activity_status == "99") {{ $l->student_score }} @else - @endif
                                </span>
                            </td>
                            <td class="px-4 py-6 text-center text-slate-800">
                                @if($l->activity_status == "1" || $l->activity_status == "99")
                                    @php $medal = getmedel($l->student_score ?? ''); @endphp
                                    <div class="flex flex-col items-center gap-1">
                                        <span class="px-2 py-0.5 rounded-md text-[9px] font-black uppercase tracking-tighter shadow-sm
                                            @if($medal == 'เหรียญทอง') bg-yellow-400 text-yellow-900 
                                            @elseif($medal == 'เหรียญเงิน') bg-slate-300 text-slate-800
                                            @elseif($medal == 'เหรียญทองแดง') bg-orange-300 text-orange-900
                                            @else bg-slate-100 text-slate-400 @endif">
                                            {{ $medal }}
                                        </span>
                                        <span class="text-[10px] text-slate-400">อันดับที่ {{ $l->activity_rank }}</span>
                                    </div>
                                @else
                                    <span class="text-slate-300 font-normal italic">รอผลแข่งขัน</span>
                                @endif
                            </td>
                            <td class="px-4 py-6 text-right rounded-r-2xl text-slate-800">
                                <div class="flex flex-col gap-1 items-end text-slate-800">
                                    @if($l->activity_status == "1" || $l->activity_status == "99")
                                        <form method="post" target="_blank" action="{{url('area/printPDF')}}">
                                            {!!csrf_field()!!}
                                            <input type="hidden" name="id" value="{{$l->id}}"/>
                                            <input type="hidden" name="student_score" value="{{$l->student_score}}"/>
                                            <input type="hidden" name="x" value="{{$l->activity_rank}}"/>
                                            <button type="submit" class="w-full px-3 py-1 bg-slate-100 text-slate-600 rounded-lg hover:bg-slate-800 hover:text-white transition-all text-[8px] font-black uppercase italic shadow-sm">No BG</button>
                                        </form>
                                        <form method="post" target="_blank" action="{{url('area/printPDF2')}}">
                                            {!!csrf_field()!!}
                                            <input type="hidden" name="id" value="{{$l->id}}"/>
                                            <input type="hidden" name="student_score" value="{{$l->student_score}}"/>
                                            <input type="hidden" name="x" value="{{$l->activity_rank}}"/>
                                            <button type="submit" class="w-full px-3 py-1 bg-orange-100 text-orange-700 rounded-lg hover:bg-orange-600 hover:text-white transition-all text-[8px] font-black uppercase italic shadow-sm">With BG</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center py-10 italic text-slate-400">ยังไม่มีข้อมูลการสรุปผล</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-8 text-slate-800">
                {{ $addschool->links() }}
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
<script>
  $(function () {
    Chart.register(ChartDataLabels);
    var totalMedals = {{ $golds + $silvers + $bronzes }};
    var donutChartCanvas = $('#medalPieChart').get(0).getContext('2d');

    new Chart(donutChartCanvas, {
      type: 'doughnut',
      data: {
        labels: ['ทอง ({{ $golds }})', 'เงิน ({{ $silvers }})', 'ทองแดง ({{ $bronzes }})'],
        datasets: [{
          data: [{{ $golds }}, {{ $silvers }}, {{ $bronzes }}],
          backgroundColor: ['#fbbf24', '#cbd5e1', '#f97316'],
          borderWidth: 0
        }]
      },
      options: {
        maintainAspectRatio: false,
        cutout: '75%',
        plugins: {
          legend: { position: 'bottom', labels: { font: { family: 'Sarabun', size: 12, weight: 'bold' } } },
          datalabels: { display: false }
        }
      },
      plugins: [{
        id: 'centerText',
        beforeDraw: function(chart) {
          var width = chart.width, height = chart.height, ctx = chart.ctx;
          ctx.restore();
          ctx.textBaseline = "middle";
          ctx.fillStyle = "#1e293b";
          ctx.font = "bold 14px Sarabun";
          ctx.fillText("รวม", Math.round((width - ctx.measureText("รวม").width) / 2), height / 2 - 15);
          ctx.font = "black 40px Sarabun";
          ctx.fillText(totalMedals, Math.round((width - ctx.measureText(totalMedals).width) / 2), height / 2 + 15);
          ctx.save();
        }
      }]
    });
  });
</script>
@endsection