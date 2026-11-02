@extends('layouts.apparea')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 font-sarabun text-slate-800">

    <div class="relative bg-gradient-to-r from-amber-800 to-orange-600 rounded-3xl shadow-xl p-8 md:p-12 mb-8 text-white overflow-hidden border-b-8 border-orange-500">
        <div class="relative z-10">
            <h2 class="text-xl md:text-2xl font-bold opacity-90 italic mb-2 text-white leading-none">
                {{ session('app_name')}} ระดับเครือข่ายสถานศึกษา {{Auth::user()->getschoolgroup->schoolgroup_name}}
            </h2>
            <h1 class="text-3xl md:text-5xl font-black tracking-tight uppercase italic text-white leading-none">
                จัดการรายชื่อผู้เข้าร่วม
            </h1>
        </div>
        <i class="ri-user-add-line absolute -right-10 -bottom-10 text-[15rem] opacity-10 rotate-12 text-white"></i>
    </div>

    @if (session('status') || Session::has('massage'))
        <div class="mb-8 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 rounded-2xl shadow-sm font-bold animate-fade-in italic text-sm flex items-center gap-3">
            <i class="ri-checkbox-circle-fill text-xl"></i> {{ session('status') ?: Session::get('massage') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2 space-y-8">
            
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6 flex items-center gap-6">
                <a href="../addaddschool/{{$addschool[0]->school_activity}}" class="w-12 h-12 flex items-center justify-center bg-orange-100 text-orange-600 rounded-2xl hover:bg-orange-600 hover:text-white transition-all shadow-sm group">
                    <i class="ri-arrow-left-line text-2xl group-hover:-translate-x-1 transition-transform"></i>
                </a>
                <div class="italic">
                    <h3 class="text-lg font-black text-slate-800 leading-none">
                        {{$addschool[0]->getschoolname->school_name}} 
                        <span class="text-orange-500 mx-2">→</span> 
                        {{$addschool[0]->getactivityname->activity_name}}
                    </h3>
                    <div class="mt-2 flex flex-wrap gap-2">
                        <span class="px-3 py-1 bg-slate-100 rounded-full text-[10px] font-black text-slate-500 uppercase tracking-widest flex items-center gap-1">
                            <i class="ri-user-smile-line"></i> นร. {{$addschool[0]->getactivityname->activity_studennumber}} คน
                        </span>
                        <span class="px-3 py-1 bg-slate-100 rounded-full text-[10px] font-black text-slate-500 uppercase tracking-widest flex items-center gap-1">
                            <i class="ri-user-voice-line"></i> ครู {{$addschool[0]->getactivityname->activity_teachernumber}} คน
                        </span>
                        <span class="px-3 py-1 bg-orange-600 text-white rounded-full text-[10px] font-black uppercase tracking-widest shadow-sm">
                            {{getactive($addschool[0]->getactivityname->activity_status)}}
                        </span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
                <div class="bg-teal-700 px-8 py-4 text-white flex justify-between items-center italic uppercase tracking-widest text-xs font-black">
                    <span class="flex items-center gap-2"><i class="ri-group-line"></i> รายชื่อนักเรียน (Students)</span>
                    <span class="bg-white/20 px-2 py-0.5 rounded text-[10px]">{{ count($addstudent) }} รายชื่อ</span>
                </div>
                <div class="p-2 md:p-4 overflow-x-auto">
                    <table class="w-full text-left border-separate border-spacing-y-4">
                        <tbody class="text-sm font-bold italic">
                            @forelse($addstudent as $l)
                            <tr class="bg-white hover:bg-slate-50 transition-all border border-slate-100 shadow-sm rounded-2xl">
                                <td class="px-4 py-6 text-slate-300 w-10 text-center rounded-l-2xl">
                                    <span class="text-xs font-black text-slate-200">{{ $loop->iteration }}</span>
                                </td>
                                
                                <td class="px-2 py-4 w-24">
                                    <div class="relative">
                                        <div class="w-20 h-20 rounded-2xl overflow-hidden bg-slate-100 border-2 border-white shadow-lg flex items-center justify-center">
                                            @if($l->student_image)
                                                <img src="{{ get_secure_file_url('picture', $l->student_image, $l->year) }}" class="w-full h-full object-cover">
                                            @else
                                                <i class="ri-user-smile-line text-4xl text-teal-600/20"></i>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <td class="px-4 py-6">
                                    <div class="flex flex-col">
                                        <span class="text-slate-800 text-lg font-black leading-tight">{{$l->getstudenttitle->title}}{{$l->name}} {{$l->lastname}}</span>
                                        <span class="text-[11px] text-teal-600 uppercase tracking-widest font-black mt-1 flex items-center gap-1">
                                            <i class="ri-checkbox-circle-line"></i> Student Participant
                                        </span>
                                    </div>
                                </td>
                                
                                <td class="px-6 py-6 text-right rounded-r-2xl">
                                    <div class="flex justify-end gap-2">
                                        @if((Auth::user()->position == 5 && Auth::user()->schoolgroup == $addschool[0]->school_schoolgroup) || (Auth::user()->position == 0 && Auth::user()->school_id == $addschool[0]->school) || Auth::user()->position == '10')
                                            <a href="{{url('areaaddstudent/'.$l->id.'/edit')}}" class="w-11 h-11 flex items-center justify-center bg-blue-50 text-blue-600 rounded-xl hover:bg-blue-600 hover:text-white transition-all shadow-sm" title="แก้ไขข้อมูล">
                                                <i class="ri-edit-line text-xl"></i>
                                            </a>
                                            @if($addschool[0]->getactivityname->activity_status == 0)
                                                <button onclick="DelConfirm({{$l->id}})" class="w-11 h-11 flex items-center justify-center bg-red-50 text-red-600 rounded-xl hover:bg-red-600 hover:text-white transition-all shadow-sm" title="ลบรายชื่อ">
                                                    <i class="ri-delete-bin-line text-xl"></i>
                                                </button>
                                                <form id="delform{{$l->id}}" method="post" action="{{ route('areaaddstudent.destroy',$l->id)}}" class="hidden">
                                                    <input type="hidden" name="_method" value="DELETE"/>
                                                    <input type="hidden" name="redirect_url" value="{{ url('area/addaddstudent/'.$addschool[0]->id) }}"/>
                                                    {!!csrf_field()!!}
                                                </form>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-12">
                                    <i class="ri-user-forbid-line text-5xl text-slate-200"></i>
                                    <p class="mt-2 text-slate-400 font-black italic text-slate-400">ยังไม่มีข้อมูลรายชื่อนักเรียนในระบบ</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
                <div class="bg-amber-900 px-8 py-4 text-white flex justify-between items-center italic uppercase tracking-widest text-xs font-black text-white">
                    <span class="flex items-center gap-2"><i class="ri-presentation-line text-white"></i> รายชื่อครูผู้ควบคุม (Teachers)</span>
                    <span class="bg-white/20 px-2 py-0.5 rounded text-[10px] text-white">{{ count($addteacher) }} รายชื่อ</span>
                </div>
                <div class="p-2 md:p-4 overflow-x-auto">
                    <table class="w-full text-left border-separate border-spacing-y-4">
                        <tbody class="text-sm font-bold italic">
                            @forelse($addteacher as $l)
                            <tr class="bg-white hover:bg-slate-50 transition-all border border-slate-100 shadow-sm rounded-2xl">
                                <td class="px-4 py-6 text-slate-300 w-10 text-center rounded-l-2xl">
                                    <span class="text-xs font-black text-slate-200">{{ $loop->iteration }}</span>
                                </td>
                                
                                <td class="px-2 py-4 w-24">
                                    <div class="w-20 h-20 rounded-2xl overflow-hidden bg-slate-100 border-2 border-white shadow-lg flex items-center justify-center">
                                        @if($l->student_image)
                                            <img src="{{ get_secure_file_url('picture', $l->student_image, $l->year) }}" class="w-full h-full object-cover">
                                        @else
                                            <i class="ri-user-star-line text-4xl text-amber-700/20"></i>
                                        @endif
                                    </div>
                                </td>

                                <td class="px-4 py-6 text-slate-800">
                                    <div class="flex flex-col">
                                        <span class="text-slate-800 text-lg font-black leading-tight">{{$l->getstudenttitle->title}}{{$l->name}} {{$l->lastname}}</span>
                                        <span class="text-[11px] text-amber-700 uppercase tracking-widest font-black mt-1 flex items-center gap-1">
                                            <i class="ri-shield-user-line"></i> Instructor / Coach
                                        </span>
                                    </div>
                                </td>

                                <td class="px-6 py-6 text-right rounded-r-2xl">
                                    <div class="flex justify-end gap-2">
                                        @if((Auth::user()->position == 5 && Auth::user()->schoolgroup == $addschool[0]->school_schoolgroup) || (Auth::user()->position == 0 && Auth::user()->school_id == $addschool[0]->school) || Auth::user()->position == '10')
                                            <a href="{{url('areaaddstudent/'.$l->id.'/edit')}}" class="w-11 h-11 flex items-center justify-center bg-blue-50 text-blue-600 rounded-xl hover:bg-blue-600 hover:text-white transition-all shadow-sm">
                                                <i class="ri-edit-line text-xl"></i>
                                            </a>
                                            @if($addschool[0]->getactivityname->activity_status == 0)
                                                <button onclick="DelConfirm({{$l->id}})" class="w-11 h-11 flex items-center justify-center bg-red-50 text-red-600 rounded-xl hover:bg-red-600 hover:text-white transition-all shadow-sm">
                                                    <i class="ri-delete-bin-line text-xl"></i>
                                                </button>
                                                <form id="delform{{$l->id}}" method="post" action="{{ route('areaaddstudent.destroy',$l->id)}}" class="hidden">
                                                    <input type="hidden" name="_method" value="DELETE"/>
                                                    <input type="hidden" name="redirect_url" value="{{ url('area/addaddstudent/'.$addschool[0]->id) }}"/>
                                                    {!!csrf_field()!!}
                                                </form>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-12">
                                    <i class="ri-user-shared-2-line text-5xl text-slate-200"></i>
                                    <p class="mt-2 text-slate-400 font-black italic text-slate-400">ยังไม่มีข้อมูลรายชื่อครูผู้ควบคุมในระบบ</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="lg:col-span-1">
            @if((Auth::user()->position == 5 && Auth::user()->schoolgroup == $addschool[0]->school_schoolgroup ) || (Auth::user()->position == 0 && Auth::user()->school_id == $addschool[0]->school ))
            <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden sticky top-10">
                <div class="bg-orange-600 px-8 py-6 text-white flex items-center gap-3 text-white">
                    <div class="w-10 h-10 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                        <i class="ri-user-add-line text-xl text-white"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-black uppercase italic leading-none text-white">Quick Register</h3>
                        <p class="text-orange-100 text-[10px] mt-1 font-bold italic uppercase opacity-80 text-white leading-none">เพิ่มรายชื่อเข้าระบบทันที</p>
                    </div>
                </div>

                <div class="p-8">
                    <form method="POST" action="{{ Route('areaaddstudent.store') }}" class="space-y-5 font-bold italic">
                        {{ csrf_field() }}
                        <input type="hidden" name="activity" value="{{$addschool[0]->school_activity}}"/>
                        <input type="hidden" name="school" value="{{$addschool[0]->id}}"/>

                        <div class="space-y-1">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic px-1 text-slate-400">คำนำหน้าชื่อ</label>
                            <select name="title" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition-all font-black text-slate-700 outline-none">
                                @foreach($title as $l)
                                    <option value="{{$l->title_id}}">{{$l->title}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="space-y-1">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic px-1 text-slate-400">ชื่อจริง (First Name)</label>
                            <input type="text" name="name" value="{{ old('name') }}" required placeholder="ไม่ต้องมีคำนำหน้า" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition-all shadow-inner font-black text-slate-700">
                        </div>

                        <div class="space-y-1">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic px-1 text-slate-400">นามสกุล (Last Name)</label>
                            <input type="text" name="lastname" value="{{ old('lastname') }}" required placeholder="ระบุนามสกุล" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition-all shadow-inner font-black text-slate-700">
                        </div>

                        <div class="space-y-1">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic px-1 text-slate-400">ประเภทบุคลากร</label>
                            <select name="type" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition-all font-black text-slate-700 outline-none">
                                <option value="0">นักเรียน (Student)</option>
                                <option value="1">ครูผู้ควบคุม (Instructor)</option>
                            </select>
                        </div>

                        <button type="submit" class="w-full py-4 bg-orange-600 text-white rounded-2xl font-black text-sm hover:bg-orange-700 hover:scale-[1.02] active:scale-95 transition-all shadow-xl shadow-orange-100 uppercase italic tracking-widest text-white mt-4 flex items-center justify-center gap-2">
                            <i class="ri-save-3-line text-lg text-white"></i> ยืนยันการลงทะเบียน
                        </button>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
    function DelConfirm(id) {
        showCustomConfirm(
            'ยืนยันการลบรายชื่อ',
            '⚠️ คุณต้องการลบรายชื่อผู้เข้าร่วมนี้ออกจากระบบใช่หรือไม่?<br><span class="text-red-600 font-bold">ข้อมูลนี้จะไม่สามารถกู้คืนได้</span>',
            function() {
                document.getElementById('delform' + id).submit();
            }
        );
    }
</script>
@endsection