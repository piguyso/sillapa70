@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10 font-sarabun text-slate-800">
    
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ url('addactivity/'.$activity->activity_subject) }}" 
           class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-white text-emerald-600 border border-gray-200 hover:bg-emerald-600 hover:text-white transition-all shadow-sm group">
            <i class="ri-arrow-left-s-line text-2xl group-hover:-translate-x-1 transition-transform"></i>
        </a>
        <div>
            <h1 class="text-2xl font-black text-gray-800 leading-none mb-1 uppercase italic text-emerald-700">Edit Activity / แก้ไขกิจกรรม</h1>
            <p class="text-sm text-gray-400 font-bold tracking-widest uppercase italic">Activity Details Management</p>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-xl shadow-slate-100 border border-gray-100 overflow-hidden">
        <div class="bg-emerald-600 px-8 py-6 text-white flex items-center gap-3">
            <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                <i class="ri-edit-box-line text-2xl"></i>
            </div>
            <div>
                <h3 class="text-lg font-black leading-none text-white">ปรับปรุงข้อมูลกิจกรรมการแข่งขัน</h3>
                <p class="text-emerald-100 text-xs mt-1 opacity-80 uppercase tracking-tighter italic">Update score, schedule, and room information</p>
            </div>
        </div>

        <div class="p-8 md:p-12">
            @if(Session::has('massage'))
                <div class="mb-8 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 flex items-center gap-3 rounded-xl shadow-sm animate-fade-in font-bold text-sm">
                    <i class="ri-checkbox-circle-fill text-xl"></i> {{ Session::get('massage') }}
                </div>
            @endif

            <form method="POST" action="{{ Route('activity.update',$activity->id) }}" class="space-y-8">
                {{ csrf_field() }}
                <input type="hidden" name="subjectgroupid" value="{{$activity->activity_subject}}"/>
                <input type="hidden" name="_method" value="put">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    
                    <div class="md:col-span-2 space-y-2">
                        <label class="block text-xs font-black text-gray-500 uppercase tracking-widest italic">ชื่อรายการกิจกรรม</label>
                        <input type="text" name="activity_name" value="{{$activity->activity_name}}" required
                               class="block w-full px-4 py-4 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-bold text-gray-700 shadow-inner">
                        @if ($errors->has('activity_name'))
                            <p class="text-red-500 text-[10px] font-bold mt-1 italic"> {{ $errors->first('activity_name') }}</p>
                        @endif
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs font-black text-gray-500 uppercase tracking-widest italic">ระดับชั้น / ช่วงชั้น</label>
                        <select name="activity_class" class="block w-full px-4 py-4 bg-gray-50 border border-gray-200 rounded-2xl font-bold text-gray-700 shadow-inner">
                            @foreach($class as $i)
                                <option value="{{$i->id}}" @if($activity->activity_class == $i->id) selected @endif >{{$i->class_name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs font-black text-gray-500 uppercase tracking-widest italic">จำนวนนักเรียนที่เข้าร่วม (คน)</label>
                        <select name="activity_studennumber" class="block w-full px-4 py-4 bg-gray-50 border border-gray-200 rounded-2xl font-bold text-gray-700 shadow-inner">
                            <option value="999">ไม่จำกัดจำนวน</option>
                            @for($i=1;$i<=50;$i++)
                                <option value="{{$i}}" @if($activity->activity_studennumber == $i) selected @endif>{{$i}} คน</option>
                            @endfor
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs font-black text-gray-500 uppercase tracking-widest italic">จำนวนครูที่เข้าร่วม (คน)</label>
                        <select name="activity_teachernumber" class="block w-full px-4 py-4 bg-gray-50 border border-gray-200 rounded-2xl font-bold text-gray-700 shadow-inner">
                            <option value="999">ไม่จำกัดจำนวน</option>
                            @for($i=1;$i<=10;$i++)
                                <option value="{{$i}}" @if($activity->activity_teachernumber == $i) selected @endif>{{$i}} คน</option>
                            @endfor
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs font-black text-gray-500 uppercase tracking-widest italic">จำนวนทีมที่เข้ารอบ</label>
                        <select name="activity_teamnumber" class="block w-full px-4 py-4 bg-gray-50 border border-gray-200 rounded-2xl font-bold text-gray-700 shadow-inner">
                            <option value="999">ไม่จำกัดจำนวน</option>
                            @for($i=1;$i<=5;$i++)
                                <option value="{{$i}}" @if($activity->activity_teamnumber == $i) selected @endif>{{$i}} ทีม</option>
                            @endfor
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs font-black text-gray-500 uppercase tracking-widest italic">จำนวนทีมต่อโรงเรียน</label>
                        <select name="activity_schoolteamnumber" class="block w-full px-4 py-4 bg-gray-50 border border-gray-200 rounded-2xl font-bold text-gray-700 shadow-inner">
                            <option value="999">ไม่จำกัดจำนวน</option>
                            @for($i=1;$i<=10;$i++)
                                <option value="{{$i}}" @if($activity->activity_schoolteamnumber == $i) selected @endif>{{$i}} ทีม</option>
                            @endfor
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs font-black text-gray-500 uppercase tracking-widest italic">คะแนนเต็ม (Score)</label>
                        <select name="activity_score" class="block w-full px-4 py-4 bg-gray-50 border border-gray-200 rounded-2xl font-bold text-gray-700 shadow-inner">
                            @for($i=100;$i<=100;$i++)
                                <option value="{{$i}}" @if($activity->activity_score == $i) selected @endif>{{$i}} คะแนน</option>
                            @endfor
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs font-black text-gray-500 uppercase tracking-widest italic">ศูนย์การแข่งขัน</label>
                        <select name="activity_branch" class="block w-full px-4 py-4 bg-gray-50 border border-gray-200 rounded-2xl font-bold text-gray-700 shadow-inner">
                            @foreach($branch as $i)
                                <option value="{{$i->id}}" @if($activity->activity_branch == $i->id) selected @endif >{{$i->branch_name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs font-black text-gray-500 uppercase tracking-widest italic">สถานที่ / อาคาร / ชั้น</label>
                        <input type="text" name="activity_branchroom" value="{{$activity->activity_branchroom}}"
                               class="block w-full px-4 py-4 bg-gray-50 border border-gray-200 rounded-2xl font-bold text-gray-700 shadow-inner" placeholder="ระบุอาคารหรือห้อง...">
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs font-black text-gray-500 uppercase tracking-widest italic">วันที่แข่งขัน</label>
                        <select name="activity_day" class="block w-full px-4 py-4 bg-gray-50 border border-gray-200 rounded-2xl font-bold text-gray-700 shadow-inner">
                            @foreach($dayofsillapa as $i)
                                <option value="{{$i->dayofsillapa}}" @if($i->dayofsillapa==$activity->activity_day) selected @endif>{{datethai($i->dayofsillapa)}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs font-black text-gray-500 uppercase tracking-widest italic">เวลาแข่งขัน</label>
                        <input type="text" name="activity_time" value="{{$activity->activity_time}}"
                               class="block w-full px-4 py-4 bg-gray-50 border border-gray-200 rounded-2xl font-bold text-gray-700 shadow-inner" placeholder="เช่น 09.00 - 12.00 น.">
                    </div>

                    <div class="md:col-span-2 bg-slate-50 p-6 rounded-3xl border border-dashed border-slate-200">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div class="space-y-1">
                                <label class="block text-xs font-black text-emerald-600 uppercase tracking-widest italic">Competition Status / สถานะการประกาศผล</label>
                                <p class="text-[10px] text-gray-400 font-bold italic uppercase tracking-tighter">เมื่อประกาศผลแล้ว ระบบจะเปิดให้ดาวน์โหลดเกียรติบัตรทันที</p>
                            </div>
                            <div class="w-full md:w-1/3">
                                <select name="activity_status" class="block w-full px-4 py-3 bg-white border border-gray-200 rounded-xl font-black text-gray-700 shadow-sm focus:ring-4 focus:ring-emerald-500/10">
                                    <option value="1" @if($activity->activity_status==1) selected @endif>✅ ประกาศผลแล้ว (Closed)</option>
                                    <option value="0" @if($activity->activity_status==0) selected @endif>⏳ ยังไม่ได้ประกาศผล (Pending)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-8 border-t border-gray-100 flex flex-col md:flex-row gap-4">
                    <button type="submit" 
                            class="flex-1 inline-flex items-center justify-center gap-2 px-8 py-4 bg-emerald-600 text-white rounded-2xl font-black text-lg hover:bg-emerald-700 hover:scale-[1.02] active:scale-95 transition-all shadow-xl shadow-emerald-100 uppercase italic tracking-widest">
                        <i class="ri-refresh-line text-xl"></i> Update Activity
                    </button>
                    <a href="{{ url('addactivity/'.$activity->activity_subject) }}" 
                       class="inline-flex items-center justify-center px-8 py-4 bg-gray-100 text-gray-500 rounded-2xl font-black hover:bg-gray-200 transition-all uppercase italic text-sm tracking-widest">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection