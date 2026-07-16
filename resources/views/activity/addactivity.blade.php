@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 font-sarabun text-slate-800">
    
    <div class="flex items-center justify-between mb-8">
        <div class="flex items-center gap-4">
            <a href="{{ url('subjectgroup') }}" 
               class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-white text-emerald-600 border border-gray-200 hover:bg-emerald-600 hover:text-white transition-all shadow-sm group">
                <i class="ri-arrow-left-s-line text-2xl group-hover:-translate-x-1 transition-transform"></i>
            </a>
            <div>
                <h1 class="text-2xl font-black text-gray-800 leading-none mb-1 uppercase italic text-emerald-700">Add Activity / เพิ่มกิจกรรม</h1>
                <p class="text-sm text-gray-400 font-bold tracking-widest uppercase italic">{{ $subjectgroup->subjectgroup_name }}</p>
            </div>
        </div>
        @if(officerchk())
        <a href="{{ url('subjectgroup') }}" class="hidden md:flex items-center gap-2 px-6 py-3 bg-slate-100 text-slate-600 rounded-xl font-bold text-sm hover:bg-slate-200 transition-all italic">
            <i class="ri-settings-line"></i> จัดการกลุ่มสาระ
        </a>
        @endif
    </div>

    @if (session('status') || Session::has('massage'))
        <div class="mb-8 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 flex items-center gap-3 rounded-xl shadow-sm font-bold animate-fade-in">
            <i class="ri-checkbox-circle-fill text-xl"></i> {{ session('status') ?: Session::get('massage') }}
        </div>
    @endif

    <div class="grid grid-cols-1 gap-8">
        <div class="bg-white rounded-3xl shadow-xl shadow-slate-100 border border-gray-100 overflow-hidden">
            <div class="bg-slate-800 px-8 py-5 text-white flex items-center justify-between">
                <h2 class="font-black italic uppercase tracking-widest text-sm flex items-center gap-2">
                    <i class="ri-list-settings-line text-emerald-400"></i> รายการกิจกรรมปัจจุบัน
                </h2>
                <span class="text-[10px] bg-white/10 px-3 py-1 rounded-full border border-white/10 italic font-bold uppercase">
                    {{ $activity->total() }} Activities Total
                </span>
            </div>
            
            <div class="p-4 md:p-8">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-separate border-spacing-y-2">
                        <thead>
                            <tr class="text-gray-400 text-[10px] uppercase tracking-[0.2em] font-black px-4">
                                <th class="px-6 py-3 text-center w-16">#</th>
                                <th class="px-6 py-3 italic">กิจกรรม / รายการ</th>
                                <th class="px-6 py-3 italic">ช่วงชั้น</th>
                                <th class="px-6 py-3 text-center italic">ทีมที่สมัคร</th>
                                <th class="px-6 py-3 italic">ศูนย์การแข่งขัน</th>
                                <th class="px-6 py-3 text-center italic">สถานะ</th>
                                @if(adminchk()) <th class="px-6 py-3 text-center italic">จัดการ</th> @endif
                            </tr>
                        </thead>
                        <tbody class="text-sm font-bold">
                            @php 
                                $x = ($activity->currentPage() - 1) * $activity->perPage(); 
                            @endphp
                            @forelse($activity as $l)
                            <tr class="group bg-white hover:bg-emerald-50/30 transition-all border border-gray-100 shadow-sm rounded-2xl">
                                <td class="px-6 py-5 text-center text-slate-300 italic">{{ ++$x }}.</td>
                                <td class="px-6 py-5">
                                    <a href="../addaddschool/{{$l->id}}" class="text-gray-800 group-hover:text-emerald-700 transition-colors uppercase italic font-black">
                                        {{$l->activity_name}}
                                    </a>
                                </td>
                                <td class="px-6 py-5">
                                    <span class="px-3 py-1 bg-slate-100 text-slate-500 rounded-lg text-[10px] uppercase font-black italic">{{$l->getclass->class_name}}</span>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    <a href="../viewaddschool/{{$l->id}}" class="px-4 py-1.5 bg-blue-50 text-blue-600 rounded-full text-xs font-black border border-blue-100 hover:bg-blue-600 hover:text-white transition-all italic shadow-sm">
                                        @if(isset($schoolcountactivity[$l->id])) {{ $schoolcountactivity[$l->id] }} @else 0 @endif ทีม
                                    </a>
                                </td>
                                <td class="px-6 py-5 text-slate-500 italic text-xs">
                                    {{$l->getactivitybranch->branch_name ?? 'ไม่พบข้อมูล'}}
                                </td>
                                <td class="px-6 py-5 text-center">
                                    <span class="text-[10px] font-black italic uppercase tracking-tighter">{{ getactivitystatus($l->activity_status) }}</span>
                                </td>
                                @if(adminchk())
                                <td class="px-6 py-5 text-center">
                                    <div class="flex justify-center gap-2 opacity-60 group-hover:opacity-100 transition-opacity">
                                        <a href="{{'../activity/'.$l->id.'/edit?action=edit'}}" class="w-8 h-8 flex items-center justify-center bg-amber-50 text-amber-600 rounded-lg hover:bg-amber-500 hover:text-white transition-all shadow-sm">
                                            <i class="ri-edit-2-line"></i>
                                        </a>
                                        <button onclick="DelConfirm({{$l->id}})" class="w-8 h-8 flex items-center justify-center bg-red-50 text-red-600 rounded-lg hover:bg-red-600 hover:text-white transition-all shadow-sm">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                        <form id="delform{{$l->id}}" method="post" action="{{ route('activity.destroy',$l->id)}}" class="hidden">
                                            <input type="hidden" name="_method" value="DELETE"/> {!!csrf_field()!!}
                                        </form>
                                    </div>
                                </td>
                                @endif
                            </tr>
                            @empty
                            <tr><td colspan="7" class="text-center py-10 italic text-slate-400">ไม่พบข้อมูลกิจกรรม</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-8">{{ $activity->links() }}</div>
            </div>
        </div>

        @if(adminchk())
        <div class="bg-white rounded-3xl shadow-xl shadow-slate-100 border border-gray-100 overflow-hidden">
            <div class="bg-emerald-600 px-8 py-6 text-white">
                <h3 class="text-lg font-black italic uppercase tracking-widest flex items-center gap-2 leading-none">
                    <i class="ri-add-circle-line text-2xl"></i> เพิ่มกิจกรรมใหม่ : {{ $subjectgroup->subjectgroup_name }}
                </h3>
            </div>
            
            <div class="p-8 md:p-12">
                <form method="POST" action="{{ Route('activity.store') }}" class="space-y-8">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="{{$id}}"/>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="md:col-span-2 space-y-2">
                            <label class="block text-sm font-black text-gray-500 uppercase tracking-widest italic">ชื่อกิจกรรม / การแข่งขัน</label>
                            <input type="text" name="activity_name" required value="{{ old('activity_name') }}"
                                   class="block w-full px-4 py-4 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-bold text-gray-700 shadow-inner"
                                   placeholder="ระบุชื่อกิจกรรมการแข่งขัน...">
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-black text-gray-500 uppercase tracking-widest italic">กลุ่มสาระการเรียนรู้</label>
                            <select name="activity_subject" class="block w-full px-4 py-4 bg-gray-50 border border-gray-200 rounded-2xl font-bold text-gray-700">
                                <option value="{{$subjectgroup->id}}">{{$subjectgroup->subjectgroup_name}}</option>
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-black text-gray-500 uppercase tracking-widest italic">ระดับชั้น / ช่วงชั้น</label>
                            <select name="activity_class" class="block w-full px-4 py-4 bg-gray-50 border border-gray-200 rounded-2xl font-bold text-gray-700">
                                @foreach($class as $i)
                                    <option value="{{$i->id}}">{{$i->class_name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-4 md:col-span-2 gap-6 bg-slate-50 p-6 rounded-3xl border border-slate-100">
                            <div class="space-y-2">
                                <label class="block text-[10px] font-black text-emerald-600 uppercase italic">จำนวนนักเรียน/ทีม</label>
                                <select name="activity_studennumber" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 font-bold">
                                    <option value="999">ไม่จำกัด</option>
                                    @for($i=1;$i<=50;$i++) <option value="{{$i}}">{{$i}} คน</option> @endfor
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-[10px] font-black text-emerald-600 uppercase italic">จำนวนครู/ทีม</label>
                                <select name="activity_teachernumber" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 font-bold">
                                    <option value="999">ไม่จำกัด</option>
                                    @for($i=1;$i<=10;$i++) <option value="{{$i}}">{{$i}} คน</option> @endfor
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-[10px] font-black text-emerald-600 uppercase italic">จำนวนทีม/รร.</label>
                                <select name="activity_schoolteamnumber" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 font-bold">
                                    <option value="999">ไม่จำกัด</option>
                                    @for($i=1;$i<=10;$i++) <option value="{{$i}}">{{$i}} ทีม</option> @endfor
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-[10px] font-black text-emerald-600 uppercase italic">คะแนนเต็ม</label>
                                <select name="activity_score" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 font-bold">
                                    <option value="100">100 คะแนน</option>
                                </select>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-black text-gray-500 uppercase tracking-widest italic">ศูนย์การแข่งขัน</label>
                            <select name="activity_branch" class="block w-full px-4 py-4 bg-gray-50 border border-gray-200 rounded-2xl font-bold text-gray-700 shadow-inner">
                                @foreach($branch as $i)
                                    <option value="{{$i->id}}">{{$i->branch_name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-black text-gray-500 uppercase tracking-widest italic">สถานที่ / อาคาร / ชั้น</label>
                            <input type="text" name="activity_branchroom" value="{{ old('activity_branchroom') }}"
                                   class="block w-full px-4 py-4 bg-gray-50 border border-gray-200 rounded-2xl font-bold text-gray-700 shadow-inner"
                                   placeholder="เช่น อาคาร 1 ชั้น 2 ห้อง 211...">
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-black text-gray-500 uppercase tracking-widest italic">วันที่แข่งขัน</label>
                            <select name="activity_day" class="block w-full px-4 py-4 bg-gray-50 border border-gray-200 rounded-2xl font-bold text-gray-700 shadow-inner">
                                @foreach($dayofsillapa as $i)
                                    <option value="{{$i->dayofsillapa}}">{{datethai($i->dayofsillapa)}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-black text-gray-500 uppercase tracking-widest italic">เวลาแข่งขัน</label>
                            <input type="text" name="activity_time" value="{{ old('activity_time') }}"
                                   class="block w-full px-4 py-4 bg-gray-50 border border-gray-200 rounded-2xl font-bold text-gray-700 shadow-inner"
                                   placeholder="เช่น 09.00 - 12.00 น.">
                        </div>
                    </div>

                    <div class="pt-8 border-t border-gray-100 flex justify-end">
                        <button type="submit" class="inline-flex items-center gap-2 px-12 py-4 bg-emerald-600 text-white rounded-2xl font-black text-lg hover:bg-emerald-700 hover:scale-[1.02] active:scale-95 transition-all shadow-xl shadow-emerald-100 uppercase tracking-widest italic">
                            <i class="ri-save-3-line text-xl"></i> บันทึกข้อมูลกิจกรรม
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @endif
    </div>
</div>

<script>
    function DelConfirm(id) {
        if (confirm('ยืนยันการลบรายการกิจกรรมนี้? ข้อมูลการสมัครแข่งขันที่เกี่ยวข้องจะถูกลบทิ้งทั้งหมด!')) {
            document.getElementById('delform' + id).submit();
        }
    }
</script>
@endsection