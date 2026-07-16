@extends('layouts.apparea')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 font-sarabun text-slate-800">

    <div class="relative bg-gradient-to-r from-amber-800 to-orange-600 rounded-3xl shadow-xl p-8 md:p-12 mb-8 text-white overflow-hidden border-b-8 border-orange-500">
        <div class="relative z-10">
            <h2 class="text-xl md:text-2xl font-bold opacity-90 italic mb-2 text-white leading-none">
                {{ session('app_name')}} ระดับเครือข่ายสถานศึกษา {{Auth::user()->getschoolgroup->schoolgroup_name}}
            </h2>
            <h1 class="text-3xl md:text-5xl font-black tracking-tight uppercase italic text-white leading-none">
                จัดการรายชื่อโรงเรียนที่เข้าร่วม
            </h1>
        </div>
        <i class="ri-community-line absolute -right-10 -bottom-10 text-[15rem] opacity-10 rotate-12 text-white"></i>
    </div>

    @if (session('status') || Session::has('massage'))
        <div class="mb-8 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 rounded-xl shadow-sm font-bold animate-fade-in italic text-sm flex items-center gap-3">
            <i class="ri-checkbox-circle-fill text-xl text-emerald-500"></i> {{ session('status') ?: Session::get('massage') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2">
            <div class="bg-white rounded-3xl shadow-xl shadow-slate-100 border border-slate-100 overflow-hidden mb-8">
                <div class="bg-slate-50 px-8 py-5 border-b border-slate-100 flex justify-between items-center">
                    <div class="flex items-center gap-4">
                        <a href="{{url('area/areasubjectgroup/activity/'.$activity->activity_subject )}}" class="w-10 h-10 flex items-center justify-center bg-white text-orange-600 rounded-xl border border-slate-200 hover:bg-orange-600 hover:text-white transition-all shadow-sm group">
                            <i class="ri-arrow-left-s-line text-2xl group-hover:-translate-x-1 transition-transform"></i>
                        </a>
                        <div>
                            <h2 class="text-base font-black text-slate-800 uppercase italic tracking-wide leading-none">{{$activity->activity_name}}</h2>
                            <p class="text-[10px] text-slate-400 font-bold mt-1 italic uppercase tracking-widest text-slate-400">
                                เกณฑ์: นร. {{$activity->activity_studennumber}} | ครู {{$activity->activity_teachernumber}} | {{$activity->activity_schoolteamnumber}} ทีมต่อโรง
                            </p>
                        </div>
                    </div>
                </div>

                <div class="p-4 md:p-8">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-separate border-spacing-y-3">
                            <thead>
                                <tr class="text-slate-400 text-[10px] uppercase tracking-[0.2em] font-black italic">
                                    <th class="px-6 py-2 text-center w-12">#</th>
                                    <th class="px-6 py-2">โรงเรียน/เครือข่าย</th>
                                    <th class="px-6 py-2">รายชื่อนักเรียน/ครู</th>
                                    <th class="px-6 py-2 text-center">จัดการ</th>
                                </tr>
                            </thead>
                            <tbody class="text-xs font-bold italic">
                                @php $y = 1; @endphp
                                @forelse($addschool as $l)
                                <tr class="bg-white hover:bg-orange-50/50 transition-all border border-slate-100 shadow-sm rounded-2xl group">
                                    <td class="px-6 py-5 text-center text-slate-300 rounded-l-2xl border-l-4 border-transparent group-hover:border-orange-500">
                                        {{$y}}
                                    </td>
                                    <td class="px-6 py-5">
                                        <p class="text-[9px] text-slate-400 uppercase tracking-tighter">เครือข่าย {{$l->getschoolgroup->schoolgroup_name}}</p>
                                        @if($activity->activity_status == 0)
                                            <a href="../addaddstudent/{{$l->id}}" class="text-slate-800 hover:text-orange-600 font-black text-sm uppercase italic transition-colors">
                                                {{$l->getschoolname->school_name}}
                                            </a>
                                        @else
                                            <span class="text-slate-800 font-black text-sm uppercase italic">{{$l->getschoolname->school_name}}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-5 text-slate-500 leading-relaxed font-medium">
                                        <div class="mb-2">
                                            <p class="text-[9px] text-orange-500 uppercase font-black mb-1 italic">Student List:</p>
                                            @php $st_idx = 1; @endphp
                                            @foreach($student as $s)
                                                @foreach($s->getstudentname as $st)
                                                    @if($l->id == $st->school && $st->type == 0)
                                                        <div class="flex items-center gap-1 text-slate-700">
                                                            <span class="text-[9px] text-slate-300">{{$st_idx}}.</span> {{$st->getstudenttitle->title}}{{$st->name}} {{$st->lastname}}
                                                        </div>
                                                        @php $st_idx++; @endphp
                                                    @endif
                                                @endforeach
                                            @endforeach
                                        </div>
                                        <div>
                                            <p class="text-[9px] text-slate-400 uppercase font-black mb-1 italic">Teacher List:</p>
                                            @php $tc_idx = 1; @endphp
                                            @foreach($student as $s)
                                                @foreach($s->getstudentname as $st)
                                                    @if($l->id == $st->school && $st->type == 1)
                                                        <div class="flex items-center gap-1 text-slate-400">
                                                            <span class="text-[9px] text-slate-200">{{$tc_idx}}.</span> {{$st->getstudenttitle->title}}{{$st->name}} {{$st->lastname}}
                                                        </div>
                                                        @php $tc_idx++; @endphp
                                                    @endif
                                                @endforeach
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 text-center rounded-r-2xl">
                                        <div class="flex justify-center gap-2">
                                            @if($l->school == Auth::user()->school_id || (Auth::user()->position == 5 && $l->school_schoolgroup == Auth::user()->schoolgroup))
                                                @if($activity->activity_status == 0)
                                                    <a href="{{url('area/addaddstudent/'.$l->id)}}" class="w-9 h-9 flex items-center justify-center bg-blue-50 text-blue-600 rounded-xl hover:bg-blue-600 hover:text-white transition-all shadow-sm">
                                                        <i class="ri-edit-2-line"></i>
                                                    </a>
                                                    <button onclick="DelConfirm({{$l->id}})" class="w-9 h-9 flex items-center justify-center bg-red-50 text-red-600 rounded-xl hover:bg-red-600 hover:text-white transition-all shadow-sm">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                    <form id="delform{{$l->id}}" method="post" action="{{ route('areaaddschool.destroy',$l->id)}}" class="hidden">
                                                        <input type="hidden" name="_method" value="DELETE"/> {!!csrf_field()!!}
                                                    </form>
                                                @endif
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @php $y++; @endphp
                                @empty
                                <tr><td colspan="4" class="text-center py-10 font-bold italic text-slate-400">ยังไม่มีโรงเรียนเพิ่มรายชื่อเข้าร่วมแข่งขัน</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-6 font-bold italic">{{ $addschool->links() }}</div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-1">
            @if($activity->activity_status == 0)
            <div class="bg-white rounded-3xl shadow-xl shadow-slate-100 border border-slate-100 overflow-hidden sticky top-10">
                <div class="bg-amber-900 px-8 py-6 text-white flex items-center gap-3 text-white">
                    <div class="w-10 h-10 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                        <i class="ri-add-line text-xl text-white"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-black leading-none text-white uppercase italic text-white">Add Participating School</h3>
                        <p class="text-orange-200 text-[10px] mt-1 font-bold italic uppercase opacity-80 text-white">เพิ่มโรงเรียนที่เข้าร่วมการแข่งขัน</p>
                    </div>
                </div>

                <div class="p-8">
                    <form method="POST" action="{{ Route('areaaddschool.store') }}" class="space-y-6 font-bold italic">
                        {{ csrf_field() }}
                        <input type="hidden" name="school_activity" value="{{$activity->id}}"/>

                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">เครือข่ายสถานศึกษา</label>
                            <select id="school_schoolgroup" name="school_schoolgroup" class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition-all font-bold text-slate-700 outline-none">
                                <option value="">กรุณาเลือกเครือข่าย</option>
                                @foreach($schoolgroup as $i)
                                    <option value="{{$i->id}}">{{$i->schoolgroup_name}}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('school_schoolgroup'))
                                <p class="text-red-500 text-[9px] italic mt-1 font-black leading-none">* {{ $errors->first('school_schoolgroup') }}</p>
                            @endif
                        </div>

                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">ชื่อโรงเรียน</label>
                            <select id="school_name" name="school_name" class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition-all font-bold text-slate-700 outline-none">
                                <option value="">กรุณาเลือกโรงเรียน</option>
                            </select>
                            @if ($errors->has('school_name'))
                                <p class="text-red-500 text-[9px] italic mt-1 font-black leading-none">* {{ $errors->first('school_name') }}</p>
                            @endif
                        </div>

                        <button type="submit" class="w-full py-4 bg-orange-600 text-white rounded-2xl font-black text-sm hover:bg-orange-700 hover:scale-[1.02] active:scale-95 transition-all shadow-xl shadow-orange-100 uppercase italic tracking-widest text-white">
                            <i class="ri-save-3-line mr-1 text-white"></i> บันทึกเข้าร่วมแข่งขัน
                        </button>
                    </form>
                </div>
            </div>
            @else
            <div class="bg-slate-50 rounded-3xl p-8 border border-slate-200 border-dashed text-center italic">
                <i class="ri-lock-2-line text-4xl text-slate-300 mb-4 block"></i>
                <h4 class="font-black text-slate-800 uppercase tracking-tight">Registration Closed</h4>
                <p class="text-xs text-slate-500 mt-2 leading-relaxed">ระบบปิดการรับสมัครเนื่องจากมีการประกาศผลการแข่งขันแล้ว</p>
            </div>
            @endif
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    function DelConfirm(id) {
        if (confirm('⚠️ ยืนยันการลบโรงเรียนนี้ออกจากรายการแข่งขัน? ข้อมูลนักเรียนและครูทั้งหมดในทีมนี้จะถูกลบออกด้วย')) {
            document.getElementById('delform' + id).submit();
        }
    }
    
    // Ajax สำหรับเลือกโรงเรียนตามเครือข่าย (ถ้าคุณมี API รองรับ)
  /*  $('#school_schoolgroup').on('change', function() {
        var group_id = $(this).val();
        if(group_id) {
            // โค้ด Ajax เดิมของคุณตรงนี้เพื่อโหลดชื่อโรงเรียนเข้า #school_name
        }
    });*/

    $(document).ready(function() {
    $('#school_schoolgroup').on('change', function() {
        var groupID = $(this).val();
        var schoolSelect = $('#school_name');
        
        console.log("--- เริ่มการทำงาน ---");
        console.log("Selected Group ID:", groupID);

        schoolSelect.html('<option value="">กำลังโหลด++</option>');

        if(groupID) {
            $.ajax({
                url: '/area/addaddschool/getschool/' + groupID,
                type: "GET",
                dataType: "json",
                success: function(data) {
                    console.log("AJAX Success! ข้อมูลที่ได้รับ:", data);
                    
                    schoolSelect.empty();
                    schoolSelect.append('<option value="">-- เลือกโรงเรียน --</option>');

                    // ตรวจสอบว่า data ว่างหรือไม่
                    if (Object.keys(data).length === 0) {
                        console.warn("คำเตือน: ข้อมูลส่งกลับมาเป็นค่าว่าง { }");
                    }

                    $.each(data, function(key, value) {
                        // Log ดูรอบการวนซ้ำ
                        console.log("Adding Option -> Key: " + key + ", Value: " + value);
                        schoolSelect.append('<option value="'+ key +'">'+ value +'</option>');
                    });
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error!");
                    console.error("Status: " + status);
                    console.error("Error: " + error);
                    console.log("Response Text: ", xhr.responseText); // ดูว่า Server ส่ง Error อะไรกลับมา (เช่น 404 หรือ 500)
                }
            });
        } else {
            console.log("ไม่มี Group ID (ค่าเป็นว่าง)");
            schoolSelect.html('<option value="">-- กรุณาเลือกเครือข่ายก่อน --</option>');
        }
    });
});




</script>
@endsection