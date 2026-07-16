@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 text-slate-800">

    <div class="bg-gradient-to-r from-primary to-emerald-600 rounded-2xl shadow-lg p-8 mb-8 text-white">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="../centerviewactivity/{{$activity->activity_subject}}" class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center hover:bg-white/40 transition-all border border-white/30 shadow-sm">
                    <i class="ri-arrow-left-line text-2xl text-white"></i>
                </a>
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold leading-tight">{{$activity->getactivitysubject->subjectgroup_name}}</h1>
                    <p class="text-green-50 text-lg opacity-90">{{$activity->activity_name}}</p>
                </div>
            </div>
            <div class="flex flex-wrap gap-2">
                <span class="bg-white/20 backdrop-blur-md px-3 py-1 rounded-lg border border-white/20 text-xs font-bold uppercase tracking-wider">นร. {{$activity->activity_studennumber}} คน</span>
                <span class="bg-white/20 backdrop-blur-md px-3 py-1 rounded-lg border border-white/20 text-xs font-bold uppercase tracking-wider">ครู {{$activity->activity_teachernumber}} คน</span>
                <span class="bg-white/20 backdrop-blur-md px-3 py-1 rounded-lg border border-white/20 text-xs font-bold uppercase tracking-wider">{{$activity->activity_schoolteamnumber}} ทีม/โรงเรียน</span>
            </div>
        </div>
    </div>

    @if(Session::has('massage'))
        <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 flex items-center gap-3 rounded-r-lg shadow-sm animate-pulse">
            <i class="ri-checkbox-circle-fill text-xl"></i>
            <span class="font-bold">{{ Session::get('massage') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-gray-50 px-8 py-5 border-b border-gray-100 flex items-center justify-between">
            <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                <i class="ri-edit-2-line text-primary"></i> บันทึกผลคะแนนรายโรงเรียน
            </h2>
            <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest bg-white px-3 py-1 rounded-lg border border-gray-200">
                ACTIVITY ID: #{{$activity->id}}
            </div>
        </div>

        <form id="saveform" method="POST" action="{{ Route('centersillapa.store') }}" class="p-0">
            {{ csrf_field() }}
            <input type="hidden" name="id" value="{{$activity->id}}"/>
            <input type="hidden" id="activity_status" name="activity_status" value=""/>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 text-gray-500 text-[10px] uppercase tracking-[0.1em] font-black border-b border-gray-100">
                            <th class="px-6 py-4 text-center">#</th>
                            <th class="px-6 py-4">โรงเรียน / เครือข่าย</th>
                            <th class="px-6 py-4">รายชื่อนักเรียน</th>
                            <th class="px-6 py-4">ครูผู้ควบคุม</th>
                            <th class="px-6 py-4 text-center w-32">คะแนน (100)</th>
                            <th class="px-6 py-4 text-center w-20">อันดับ</th>
                            <th class="px-6 py-4 text-center">รางวัลที่ได้</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @php $x=0; @endphp
                        @forelse($addschool as $l)
                        <tr class="hover:bg-blue-50/30 transition-colors group">
                            <td class="px-6 py-4 text-center text-gray-400 font-black">
                                @php $x++; echo $x; @endphp
                            </td>
                            <td class="px-6 py-4">
                                <a href="../centereditstudent/{{$l->id}}" class="font-black text-gray-800 hover:text-primary transition-colors block leading-tight">
                                    {{$l->getschoolname->school_name}}
                                </a>
                                <span class="text-[10px] font-bold text-gray-400 italic">เครือข่าย: {{$l->getschoolgroup->schoolgroup_name}}</span>
                            </td>
                            <td class="px-6 py-4 text-xs leading-relaxed">
                                @foreach($student as $s)
                                    @php $ii=1; @endphp
                                    @foreach($s->getstudentname as $item)
                                        @if($l->id == $item->school && $item->type == 0)
                                            <div class="flex items-center gap-1">
                                                <span class="text-gray-300 font-mono">{{ $ii }}.</span>
                                                {{$item->getstudenttitle->title}}{{$item->name}} {{$item->lastname}}
                                            </div>
                                            @php $ii++; @endphp
                                        @endif
                                    @endforeach
                                @endforeach
                            </td>
                            <td class="px-6 py-4 text-xs leading-relaxed text-blue-600/70">
                                @foreach($student as $s)
                                    @php $kk=1; @endphp
                                    @foreach($s->getstudentname as $item)
                                        @if($l->id == $item->school && $item->type == 1)
                                            <div class="flex items-center gap-1 italic">
                                                <span class="text-blue-200 font-mono">{{ $kk }}.</span>
                                                {{$item->getstudenttitle->title}}{{$item->name}} {{$item->lastname}}
                                            </div>
                                            @php $kk++; @endphp
                                        @endif
                                    @endforeach
                                @endforeach
                            </td>
                            <td class="px-6 py-4">
                                <input id="activity_score_{{$l->id}}" 
                                       type="number" step="any" 
                                       onfocus="this.select();" 
                                       class="w-full bg-white border-2 border-gray-100 rounded-xl px-3 py-2 text-center font-black text-lg text-primary focus:border-primary focus:ring-0 transition-all outline-none {{ $activity->activity_status==1 ? 'bg-gray-50 opacity-50 cursor-not-allowed' : '' }}" 
                                       name="activity_score[{{$l->id}}]" 
                                       value="{{$l->student_score}}" 
                                       {{ $activity->activity_status==1 ? 'readOnly' : '' }}>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <input type="text" 
                                       class="w-12 bg-gray-50 border border-gray-200 rounded-lg py-1 text-center font-bold text-gray-400" 
                                       name="activity_rank1[{{$l->id}}]" 
                                       value="{{$x}}" readOnly>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php $medal = getmedel($l->student_score); @endphp
                               
                                    {{ str_contains($medal, 'ทอง') && !str_contains($medal, 'แดง') ? 'bg-yellow-50 text-yellow-700 border-yellow-200' : '' }}
                                    {{ str_contains($medal, 'เงิน') ? 'bg-slate-50 text-slate-600 border-slate-200' : '' }}
                                    {{ str_contains($medal, 'ทองแดง') ? 'bg-orange-50 text-orange-700 border-orange-200' : '' }}
                                    {{ str_contains($medal, 'เข้าร่วม') ? 'bg-blue-50 text-blue-700 border-blue-200' : '' }}
                                    {{ $medal }}
                                
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="px-6 py-12 text-center text-gray-400 italic font-bold">ไม่พบข้อมูลผู้สมัครแข่งขัน</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-8 py-8 bg-gray-50 border-t border-gray-100">
                <div class="bg-red-50 border-l-4 border-red-500 p-5 rounded-r-2xl mb-8">
                    <h4 class="text-red-800 font-black text-sm uppercase tracking-widest mb-2 flex items-center gap-2">
                        <i class="ri-error-warning-fill text-xl"></i> คำเตือนและระเบียบปฏิบัติ
                    </h4>
                    <ul class="text-xs text-red-700 space-y-1 font-bold">
                        <li>• หากมีการแก้ไขคะแนนจะต้องกด <span class="underline">"บันทึกคะแนน"</span> ก่อนเสมอเพื่อจัดลำดับใหม่</li>
                        <li>• เมื่อกด <span class="underline">"ประกาศผลการแข่งขัน"</span> แล้ว ข้อมูลจะถูก Lock ทันที ไม่สามารถแก้ไขได้</li>
                        <li>• หากต้องการแก้ไขหลังประกาศผล ต้องติดต่อแอดมินกลางเพื่อทำการปลดล็อคเท่านั้น</li>
                    </ul>
                </div>

                <div class="flex flex-col sm:flex-row items-center justify-end gap-4">
                    @if($activity->activity_status==0) 
                        <button type="submit" name="activity_chk" value='0' 
                                class="w-full sm:w-auto px-8 py-4 bg-white border-2 border-primary text-primary font-black rounded-2xl hover:bg-primary hover:text-white transition-all shadow-md flex items-center justify-center gap-2" 
                                onClick="return confirm('ยืนยันการบันทึกคะแนนเพื่อจัดลำดับ?');">
                            <i class="ri-save-3-line text-xl"></i>
                            บันทึกคะแนนและจัดลำดับ
                        </button>
                        
                        <button type="submit" name="activity_chk" value='1' 
                                class="w-full sm:w-auto px-8 py-4 bg-red-600 text-white font-black rounded-2xl hover:bg-red-700 hover:shadow-2xl hover:shadow-red-200 transition-all shadow-md flex items-center justify-center gap-2" 
                                onClick="return confirm('ยืนยันการประกาศผลการแข่งขัน? ข้อมูลจะถูก Lock และแก้ไขไม่ได้อีก');">
                            <i class="ri-notification-badge-line text-xl"></i>
                            ประกาศผลการแข่งขันสู่สาธารณะ
                        </button>
                    @else
                        <div class="flex items-center gap-2 text-emerald-600 font-black bg-emerald-50 px-6 py-4 rounded-2xl border-2 border-emerald-100">
                            <i class="ri-lock-2-fill text-2xl"></i>
                            ประกาศผลเรียบร้อยแล้ว (ข้อมูลถูกล็อค)
                        </div>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <div class="mt-8 flex justify-center">
        {{ $addschool->links() }}
    </div>
</div>
@endsection