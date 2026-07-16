@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 font-sarabun text-slate-800">
    
    <div class="bg-gradient-to-r from-slate-800 to-emerald-900 rounded-3xl shadow-xl p-8 md:p-10 mb-8 text-white relative overflow-hidden border-b-8 border-emerald-500">
        <div class="relative z-10">
            <div class="flex items-center gap-4 mb-4">
                <a href="../addactivity/{{$activity->activity_subject}}" 
                   class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-white/10 hover:bg-white/20 text-white transition-all backdrop-blur-md">
                    <i class="ri-arrow-left-s-line text-2xl"></i>
                </a>
                <span class="px-3 py-1 bg-emerald-500 text-[10px] font-black uppercase tracking-widest rounded-lg shadow-sm">
                    {{$activity->getactivitysubject->subjectgroup_name}}
                </span>
            </div>
            <h1 class="text-2xl md:text-4xl font-black mb-4 italic uppercase tracking-tight">
                {{$activity->activity_name}}
            </h1>
            <div class="flex flex-wrap gap-4 text-xs font-bold italic opacity-90">
                <span class="flex items-center gap-1.5"><i class="ri-group-line text-emerald-400"></i> นักเรียน {{$activity->activity_studennumber}} คน</span>
                <span class="flex items-center gap-1.5"><i class="ri-user-star-line text-emerald-400"></i> ครู {{$activity->activity_teachernumber}} คน</span>
                <span class="flex items-center gap-1.5"><i class="ri-team-line text-emerald-400"></i> ทีมละ {{$activity->activity_schoolteamnumber}} รร.</span>
                <span class="px-3 py-0.5 bg-white/20 rounded-full border border-white/10 italic uppercase">{{getactive($activity->activity_status)}}</span>
            </div>
        </div>
        <i class="ri-community-line absolute -right-10 -bottom-10 text-[12rem] opacity-10 rotate-12"></i>
    </div>

    @if (session('status') || Session::has('massage'))
        <div class="mb-8 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 flex items-center gap-3 rounded-xl shadow-sm font-bold animate-fade-in text-sm italic">
            <i class="ri-checkbox-circle-fill text-xl"></i> {{ session('status') ?: Session::get('massage') }}
        </div>
    @endif

    <div class="grid grid-cols-1 gap-8">
        
        <div class="bg-white rounded-3xl shadow-xl shadow-slate-100 border border-gray-100 overflow-hidden">
            <div class="bg-slate-50 px-8 py-5 border-b border-gray-100 flex items-center justify-between">
                <h2 class="text-sm font-black text-slate-700 uppercase tracking-widest italic flex items-center gap-2">
                    <i class="ri-building-4-line text-emerald-600"></i> รายชื่อโรงเรียนที่สมัครเข้าร่วม
                </h2>
                <span class="text-[10px] font-black bg-slate-200 text-slate-500 px-3 py-1 rounded-full italic uppercase">Total: {{ $addschool->total() }} Schools</span>
            </div>

            <div class="p-6 md:p-8">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-separate border-spacing-y-4">
                        <thead>
                            <tr class="text-gray-400 text-[10px] uppercase tracking-[0.2em] font-black italic">
                                <th class="px-6 py-2 text-center w-16">#</th>
                                <th class="px-6 py-2">โรงเรียน / เครือข่าย</th>
                                <th class="px-6 py-2">รายชื่อนักเรียน</th>
                                <th class="px-6 py-2">รายชื่อครูผู้ควบคุม</th>
                                <th class="px-6 py-2 text-center">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm font-bold">
                            @php $y = ($addschool->currentPage() - 1) * $addschool->perPage() + 1; @endphp
                            @forelse($addschool as $l)
                            <tr class="bg-white hover:shadow-md transition-shadow border border-gray-100 rounded-2xl group">
                                <td class="px-6 py-6 text-center text-slate-300 italic align-top">{{ $y++ }}.</td>
                                <td class="px-6 py-6 align-top">
                                    <a href="../addaddstudent/{{$l->id}}" class="text-emerald-700 hover:text-emerald-900 transition-colors uppercase italic font-black text-base">
                                        {{$l->getschoolname->school_name}}
                                    </a>
                                    <div class="text-[10px] text-slate-400 mt-1 uppercase italic font-bold">
                                        <i class="ri-links-line"></i> {{$l->getschoolgroup->schoolgroup_name}}
                                    </div>
                                </td>
                                <td class="px-6 py-6 align-top text-xs leading-relaxed text-slate-600">
                                    @php $sx = 1; @endphp
                                    @foreach($student as $s)
                                        @foreach($s->getstudentname as $sn)
                                            @if($l->id == $sn->school && $sn->type == 0)
                                                <div class="mb-1"><span class="text-emerald-500">{{ $sx++ }}.</span> {{$sn->getstudenttitle->title}}{{$sn->name}} {{$sn->lastname}}</div>
                                            @endif
                                        @endforeach
                                    @endforeach
                                </td>
                                <td class="px-6 py-6 align-top text-xs leading-relaxed text-slate-600">
                                    @php $tx = 1; @endphp
                                    @foreach($student as $t)
                                        @foreach($t->getstudentname as $tn)
                                            @if($l->id == $tn->school && $tn->type == 1)
                                                <div class="mb-1"><span class="text-blue-500">{{ $tx++ }}.</span> {{$tn->getstudenttitle->title}}{{$tn->name}} {{$tn->lastname}}</div>
                                            @endif
                                        @endforeach
                                    @endforeach
                                </td>
                                <td class="px-6 py-6 text-center align-top">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="../addaddstudent/{{$l->id}}" class="w-10 h-10 flex items-center justify-center bg-emerald-50 text-emerald-600 rounded-xl hover:bg-emerald-600 hover:text-white transition-all shadow-sm" title="เพิ่มนักเรียน/ครู">
                                            <i class="ri-user-add-line text-lg"></i>
                                        </a>
                                        @if(adminchk() || ($l->school_schoolgroup == Auth::user()->schoolgroup && $activity->activity_status == 0))
                                            <button onclick="DelConfirm({{$l->id}})" class="w-10 h-10 flex items-center justify-center bg-red-50 text-red-600 rounded-xl hover:bg-red-600 hover:text-white transition-all shadow-sm" title="ลบโรงเรียน">
                                                <i class="ri-delete-bin-line text-lg"></i>
                                            </button>
                                            <form id="delform{{$l->id}}" method="post" action="{{ route('addschool.destroy',$l->id)}}" class="hidden">
                                                <input type="hidden" name="_method" value="DELETE"/> {!!csrf_field()!!}
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="px-6 py-10 text-center text-slate-400 italic">ไม่พบรายชื่อโรงเรียนที่สมัคร</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-8">{{ $addschool->links() }}</div>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-xl shadow-slate-100 border border-gray-100 overflow-hidden">
            <div class="bg-emerald-600 px-8 py-6 text-white flex items-center gap-3 leading-none">
                <i class="ri-add-circle-line text-2xl"></i>
                <div>
                    <h3 class="font-black italic uppercase tracking-widest text-sm">เพิ่มโรงเรียนเข้าร่วมการแข่งขัน</h3>
                    <p class="text-[10px] text-emerald-100 opacity-80 uppercase font-bold italic mt-1 tracking-tighter">Add Participating School</p>
                </div>
            </div>
            
            <div class="p-8 md:p-12">
                <form method="POST" action="{{ Route('addschool.store') }}" class="grid grid-cols-1 md:grid-cols-2 gap-8 items-end font-bold">
                    {{ csrf_field() }}
                    <input type="hidden" name="school_activity" value="{{$activity->id}}"/>

                    <div class="space-y-2">
                        <label class="block text-xs font-black text-slate-500 uppercase tracking-widest italic">เครือข่ายสถานศึกษา</label>
                        <select id="school_schoolgroup" name="school_schoolgroup" required
                                class="w-full px-4 py-4 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-bold text-gray-700 shadow-inner">
                            <option value="">-- เลือกเครือข่าย --</option>
                            @foreach($schoolgroup as $i)
                                <option value="{{$i->id}}">{{$i->schoolgroup_name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs font-black text-slate-500 uppercase tracking-widest italic">ชื่อโรงเรียน</label>
                        <select id="school_name" name="school_name" required
                                class="w-full px-4 py-4 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-bold text-gray-700 shadow-inner">
                            <option value="">-- กรุณาเลือกเครือข่ายก่อน --</option>
                        </select>
                    </div>

                    <div class="md:col-span-2 flex justify-center pt-4">
                        <button type="submit" class="w-full md:w-auto px-16 py-4 bg-emerald-600 text-white rounded-2xl font-black text-lg hover:bg-emerald-700 hover:scale-[1.02] active:scale-95 transition-all shadow-xl shadow-emerald-100 uppercase tracking-widest italic">
                            <i class="ri-save-3-line text-xl mr-1"></i> เพิ่มข้อมูลโรงเรียน
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function DelConfirm(id) {
        if (confirm('⚠️ ยืนยันการลบโรงเรียนนี้? รายชื่อนักเรียนและครูทั้งหมดในทีมนี้จะถูกลบออกด้วย!')) {
            document.getElementById('delform' + id).submit();
        }
    }

    $(document).ready(function() {
        // Simple Dependent Dropdown Logic
        $('#school_schoolgroup').on('change', function() {
            var groupID = $(this).val();
            var schoolSelect = $('#school_name');
            schoolSelect.html('<option value="">กำลังโหลด...</option>');
            
            if(groupID) {
                $.ajax({
                    url: '{{url("/addaddschool/getschool")}}/' + groupID, // ปรับ URL ตามที่ตั้งไว้ใน web.php/api.php
                    type: "GET",
                    dataType: "json",
                    success:function(data) {
                        schoolSelect.empty();
                        schoolSelect.append('<option value="">-- เลือกโรงเรียน --</option>');
                        $.each(data, function(key, value) {
                            schoolSelect.append('<option value="'+ key +'">'+ value +'</option>');
                        });
                    }
                });
            } else {
                schoolSelect.html('<option value="">-- กรุณาเลือกเครือข่ายก่อน --</option>');
            }
        });
    });
</script>
@endsection