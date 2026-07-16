@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <div class="bg-gradient-to-r from-primary to-emerald-600 rounded-2xl shadow-lg p-8 mb-8 text-white">
        <h1 class="text-3xl md:text-4xl font-bold mb-2">{{session('app_name').' '.session('app_group')}}</h1>
        <p class="text-green-50 text-lg opacity-90">{{session('app_area')}}</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-100">
            <div class="flex flex-col md:flex-row md:items-center gap-4">
                <a href="../viewactivity/{{$activity->activity_subject}}" 
                   class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white text-primary border border-gray-200 hover:bg-primary hover:text-white transition-all shadow-sm">
                    <i class="ri-arrow-left-line text-xl"></i>
                </a>
                <div class="flex-1">
                    <h2 class="text-xl font-bold text-gray-800">
                        {{$activity->getactivitysubject->subjectgroup_name}} 
                        <span class="mx-2 text-gray-300">|</span> 
                        {{$activity->activity_name}} {{ $activity->getclass->class_name ?? 'ระบุช่วงชั้น' }}
                    </h2>
                    <div class="mt-1 flex flex-wrap gap-x-4 gap-y-1 text-sm text-gray-500">
                        <span class="flex items-center gap-1"><i class="ri-user-smile-line text-primary"></i> นักเรียน {{$activity->activity_studennumber}} คน</span>
                        <span class="flex items-center gap-1"><i class="ri-user-star-line text-primary"></i> ครูผู้ควบคุม {{$activity->activity_teachernumber}} คน</span>
                        <span class="flex items-center gap-1"><i class="ri-group-line text-primary"></i> {{$activity->activity_schoolteamnumber}} ทีมต่อโรงเรียน</span>
                    </div>
                </div>
            </div>
        </div>
<div class="p-6">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div class="text-gray-600 text-sm italic">
            <i class="ri-information-line"></i> คุณสามารถค้นหาหรือส่งออกข้อมูลผลการแข่งขันได้ที่นี่
        </div>
        
        <div class="flex gap-2">
            <button onclick="exportTableToExcel('table1', 'results-{{$activity->id}}')" 
                    class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-100 text-emerald-700 border border-emerald-200 rounded-xl font-bold hover:bg-emerald-600 hover:text-white transition-all shadow-sm">
                <i class="ri-file-excel-fill text-xl"></i>
                ดาวน์โหลดไฟล์ Excel
            </button>
        </div>
    </div>

    @if (session('status'))
        @endif

    </div>
        <div class="p-6">
            @if (session('status'))
                <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 flex items-center gap-3 rounded-r-lg">
                    <i class="ri-checkbox-circle-line text-xl"></i>
                    {{ session('status') }}
                </div>
            @endif

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse" id="table1">
                    <thead>
                        <tr class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wider border-b border-gray-200">
                            <th class="px-4 py-4 font-bold text-center w-12">#</th>
                            <th class="px-4 py-4 font-bold">เครือข่าย</th>
                            <th class="px-4 py-4 font-bold">ชื่อโรงเรียน</th>
                            <th class="px-4 py-4 font-bold">รายชื่อนักเรียน</th>
                            <th class="px-4 py-4 font-bold">ครูผู้ควบคุม</th>
                            <th class="px-4 py-4 font-bold text-center">คะแนน</th>
                            <th class="px-4 py-4 font-bold text-center">อันดับ</th>
                            <th class="px-4 py-4 font-bold text-center">เหรียญ</th>
                            <th class="px-4 py-4 font-bold text-center">เกียรติบัตร</th>
                        </tr>
                    </thead>
<tbody class="divide-y divide-gray-100 text-sm">
    <?php $x=0;?>
    @forelse($addschool as $l)
    <tr class="hover:bg-gray-50/80 transition-colors">
        <td class="px-4 py-4 text-center text-gray-400 font-medium">
            <?php $x++; echo $x; ?>
        </td>
        <td class="px-4 py-4 text-gray-600">{{$l->getschoolgroup->schoolgroup_name}}</td>
        
        <td class="px-4 py-4 font-bold text-gray-800">
            <button onclick="openSchoolModal('school-modal-{{$l->id}}')" class="text-green-600 hover:text-green-800 hover:underline transition-colors focus:outline-none text-left flex items-center gap-2">
                {{$l->getschoolname->school_name}}
                <i class="ri-external-link-line text-xs text-green-400"></i>
            </button>

            <div id="school-modal-{{$l->id}}" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="fixed inset-0 bg-gray-900/60 transition-opacity backdrop-blur-sm" onclick="closeSchoolModal('school-modal-{{$l->id}}')"></div>

                <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                    <div class="flex min-h-full items-center justify-center p-2 text-center sm:p-6">
                        <div class="relative transform overflow-hidden rounded-2xl bg-gray-50 text-left shadow-2xl transition-all w-full sm:max-w-3xl flex flex-col max-h-[95vh]">
                            
                            <div class="bg-gradient-to-r from-green-600 to-green-800 px-4 py-3 flex justify-between items-center text-white shrink-0">
                                <h3 class="text-lg sm:text-xl font-bold flex items-center gap-2">
                                    <div class="p-1.5 bg-white/20 rounded-lg">
                                        <i class="ri-building-4-line text-sm"></i>
                                    </div>
                                    <span class="truncate">{{$l->getschoolname->school_name}}</span>
                                </h3>
                                <button type="button" onclick="closeSchoolModal('school-modal-{{$l->id}}')" class="text-white/70 hover:text-white hover:bg-white/20 p-1 rounded-lg transition-colors focus:outline-none">
                                    <i class="ri-close-line text-xl"></i>
                                </button>
                            </div>

                            <div class="p-3 sm:p-5 overflow-y-auto flex-1">
                                
                                <div class="bg-white rounded-xl p-3 border border-gray-100 shadow-sm mb-4 flex flex-col sm:flex-row gap-3 justify-between items-start sm:items-center">
                                    <div class="flex-1">
                                        <p class="text-[10px] text-gray-500 mb-0.5 uppercase tracking-wide">รายการแข่งขัน / ช่วงชั้น</p>
                                        <p class="font-bold text-gray-800 text-sm mb-2">
                                            {{ $activity->activity_name ?? 'ระบุชื่อรายการแข่งขัน' }} 
                                            <span class="text-green-600 font-medium">({{ $activity->getclass->class_name ?? 'ระบุช่วงชั้น' }})</span>
                                        </p>
                                        
                                        <p class="text-[10px] text-gray-500 mb-0.5 uppercase tracking-wide">กลุ่มโรงเรียน</p>
                                        <p class="font-bold text-gray-800 text-sm">{{$l->getschoolgroup->schoolgroup_name}}</p>
                                    </div>
                                    <div class="flex gap-3 text-center w-full sm:w-auto">
                                        <div class="bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-100 flex-1 sm:flex-none">
                                            <p class="text-[10px] text-gray-500 mb-0.5">คะแนนรวม</p>
                                            <p class="font-bold text-lg {{ $activity->activity_status==1 && $l->student_score > 0 ? 'text-green-600' : 'text-gray-400' }}">
                                                @if($activity->activity_status==1) {{$l->student_score}} @else - @endif
                                            </p>
                                        </div>
                                        <div class="bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-100 flex-1 sm:flex-none">
                                            <p class="text-[10px] text-gray-500 mb-0.5">อันดับ</p>
                                            <p class="font-bold text-lg text-gray-800">
                                                @if($activity->activity_status==1 && $l->student_score > 0) #{{$l->activity_rank}} @else - @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    
                                    <div class="bg-white rounded-xl border border-green-100 shadow-sm overflow-hidden">
                                        <div class="bg-green-50/80 px-3 py-2 border-b border-green-100 flex items-center gap-2">
                                            <i class="ri-group-line text-green-600 text-sm"></i>
                                            <h4 class="font-bold text-gray-800 text-xs">รายชื่อผู้เข้าแข่งขัน</h4>
                                        </div>
                                        <div class="p-2 space-y-1">
                                            <?php $stdCountModal = 1; ?>
                                            @foreach($student as $s)
                                                @foreach($s->getstudentname as $item)
                                                    @if($l->id == $item->school && $item->type == 0)
                                                        <div class="flex items-center gap-2 p-1.5 hover:bg-gray-50 rounded-md transition-colors">
                                                            <div class="flex-shrink-0 w-5 h-5 rounded-full bg-green-100 text-green-700 flex items-center justify-center font-bold text-[10px]">
                                                                {{ $stdCountModal }}
                                                            </div>
                                                            <div class="text-gray-700 text-xs">
                                                                {{$item->getstudenttitle->title}}{{$item->name}} {{$item->lastname}}
                                                            </div>
                                                        </div>
                                                        <?php $stdCountModal++; ?>
                                                    @endif
                                                @endforeach
                                            @endforeach
                                            @if($stdCountModal == 1)
                                                <p class="text-[11px] text-gray-400 text-center py-2 italic">ไม่พบรายชื่อ</p>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="bg-white rounded-xl border border-green-100 shadow-sm overflow-hidden">
                                        <div class="bg-green-50/80 px-3 py-2 border-b border-green-100 flex items-center gap-2">
                                            <i class="ri-presentation-line text-green-600 text-sm"></i>
                                            <h4 class="font-bold text-gray-800 text-xs">ครูผู้ฝึกสอน</h4>
                                        </div>
                                        <div class="p-2 space-y-1">
                                            <?php $tchCountModal = 1; ?>
                                            @foreach($student as $s)
                                                @foreach($s->getstudentname as $item)
                                                    @if($l->id == $item->school && $item->type == 1)
                                                        <div class="flex items-center gap-2 p-1.5 hover:bg-gray-50 rounded-md transition-colors">
                                                            <div class="flex-shrink-0 w-5 h-5 rounded-full bg-green-100 text-green-700 flex items-center justify-center font-bold text-[10px]">
                                                                {{ $tchCountModal }}
                                                            </div>
                                                            <div class="text-gray-700 text-xs">
                                                                {{$item->getstudenttitle->title}}{{$item->name}} {{$item->lastname}}
                                                            </div>
                                                        </div>
                                                        <?php $tchCountModal++; ?>
                                                    @endif
                                                @endforeach
                                            @endforeach
                                            @if($tchCountModal == 1)
                                                <p class="text-[11px] text-gray-400 text-center py-2 italic">ไม่พบรายชื่อ</p>
                                            @endif
                                        </div>
                                    </div>

                                </div>
                            </div>
                            
                            <div class="bg-white px-4 py-3 border-t border-gray-100 shrink-0 flex justify-end">
                                <button type="button" onclick="closeSchoolModal('school-modal-{{$l->id}}')" class="inline-flex justify-center rounded-lg bg-gray-100 px-4 py-1.5 text-xs font-bold text-gray-700 hover:bg-gray-200 transition-colors focus:outline-none">
                                    ปิดหน้าต่าง
                                </button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            </td>
        
        <td class="px-4 py-4 leading-relaxed">
            <div class="space-y-1">
                <?php $stdCount = 1; ?>
                @foreach($student as $s)
                    @foreach($s->getstudentname as $item)
                        @if($l->id == $item->school && $item->type == 0)
                            <div class="flex items-center gap-2">
                                <span class="text-gray-500 font-mono text-[11px]">{{ $stdCount }}.</span>
                                {{$item->getstudenttitle->title}}{{$item->name}} {{$item->lastname}}
                            </div>
                            <?php $stdCount++; ?>
                        @endif
                    @endforeach
                @endforeach
            </div>
        </td>

        <td class="px-4 py-4 leading-relaxed text-gray-600">
            <div class="space-y-1">
                <?php $tchCount = 1; ?>
                @foreach($student as $s)
                    @foreach($s->getstudentname as $item)
                        @if($l->id == $item->school && $item->type == 1)
                            <div class="flex items-center gap-2 italic">
                                <span class="text-blue-400 font-mono text-[11px]">{{ $tchCount }}.</span>
                                {{$item->getstudenttitle->title}}{{$item->name}} {{$item->lastname}}
                            </div>
                            <?php $tchCount++; ?>
                        @endif
                    @endforeach
                @endforeach
            </div>
        </td>

        <td class="px-4 py-4 text-center font-bold text-lg">
            @if($activity->activity_status==1)
                <span class="text-primary">{{$l->student_score}}</span>
            @else
                <span class="text-gray-300">-</span>
            @endif
        </td>
        <td class="px-4 py-4 text-center">
            @if($activity->activity_status==1 && $l->student_score > 0)
                <div class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-800 text-white text-xs font-bold shadow-sm">
                    {{$l->activity_rank}}
                </div>
            @else
                <span class="text-gray-300">-</span>
            @endif
        </td>
        <td class="px-4 py-4 text-center">
            @if($activity->activity_status==1)
                @php $medal = getmedel($l->student_score); @endphp
                <span class="px-3 py-1 rounded-full text-xs font-bold 
                    {{ str_contains($medal, 'ทอง') && !str_contains($medal, 'แดง') ? 'bg-yellow-100 text-yellow-700' : '' }}
                    {{ str_contains($medal, 'เงิน') ? 'bg-slate-100 text-slate-700' : '' }}
                    {{ str_contains($medal, 'ทองแดง') ? 'bg-orange-100 text-orange-700' : '' }}
                    {{ str_contains($medal, 'เข้าร่วม') ? 'bg-blue-100 text-blue-700' : '' }}">
                    {{$medal}}
                </span>
            @else
                <span class="text-gray-300">-</span>
            @endif
        </td>
        <td class="px-4 py-4">
            @if($activity->activity_status == 1 && $l->student_score > 0)
            <div class="flex flex-col gap-2 min-w-[140px]">
                <form method="post" target="_blank" action="{{url('printPDF')}}">
                    {!!csrf_field()!!}
                    <input type="hidden" name="id" value="{{$l->id}}"/>
                    <input type="hidden" name="student_score" value="{{$l->student_score}}"/>
                    <input type="hidden" name="x" value="{{$l->activity_rank}}"/>
                    <button type="submit" class="w-full flex items-center justify-center gap-2 px-3 py-1.5 bg-blue-50 text-blue-600 border border-blue-200 rounded-lg text-xs font-bold hover:bg-blue-600 hover:text-white transition-all shadow-sm">
                        <i class="ri-printer-line"></i> ไม่มีพื้นหลัง
                    </button>
                </form>
                <form method="post" target="_blank" action="{{url('printPDF2')}}">
                    {!!csrf_field()!!}
                    <input type="hidden" name="id" value="{{$l->id}}"/>
                    <input type="hidden" name="student_score" value="{{$l->student_score}}"/>
                    <input type="hidden" name="x" value="{{$l->activity_rank}}"/>
                    <button type="submit" class="w-full flex items-center justify-center gap-2 px-3 py-1.5 bg-emerald-50 text-emerald-600 border border-emerald-200 rounded-lg text-xs font-bold hover:bg-emerald-600 hover:text-white transition-all shadow-sm">
                        <i class="ri-landscape-line"></i> มีพื้นหลัง
                    </button>
                </form>
            </div>
            @else
                <div class="text-center text-gray-300 text-xs italic">ยังไม่มีผลการแข่ง</div>
            @endif
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="9" class="px-4 py-10 text-center text-gray-400 italic bg-gray-50/50">
            <i class="ri-inbox-line text-2xl block mb-2"></i> ไม่พบข้อมูลโรงเรียนที่เข้าร่วม
        </td>
    </tr>
    @endforelse
</tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $addschool->links()}}
            </div>
        </div>
    </div>

    @if(Session::has('massage'))
        <div class="mt-6 p-4 bg-blue-50 border-l-4 border-blue-500 text-blue-700 rounded-r-lg shadow-sm flex items-center gap-3">
            <i class="ri-information-fill text-xl"></i>
            <span class="font-bold">{{Session::get('massage')}}</span>
        </div>
    @endif
</div>
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>

<script>
function exportTableToExcel(tableID, filename = ''){
    var downloadLink;
    var dataType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=UTF-8';
    var tableSelect = document.getElementById(tableID);
    
    // สร้าง Workbook จาก Table โดยตรง
    var wb = XLSX.utils.table_to_book(tableSelect, {sheet: "ผลการแข่งขัน"});
    
    // จัดการชื่อไฟล์
    filename = filename ? filename + '.xlsx' : 'excel_data.xlsx';
    
    // สั่งให้ดาวน์โหลด
    XLSX.writeFile(wb, filename);
}


</script>
<script>
    function openSchoolModal(modalId) {
        document.getElementById(modalId).classList.remove('hidden');
    }

    function closeSchoolModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
    }
</script>
@endsection