@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 font-sarabun text-slate-800 text-sm">
    
    <div class="bg-gradient-to-r from-slate-800 to-emerald-900 rounded-3xl shadow-xl p-8 md:p-10 mb-8 text-white relative overflow-hidden border-b-8 border-emerald-500">
        <div class="relative z-10">
            <div class="flex items-center gap-4 mb-4">
                <a href="../addaddschool/{{$addschool[0]->school_activity}}" 
                   class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-white/10 hover:bg-white/20 text-white transition-all backdrop-blur-md">
                    <i class="ri-arrow-left-s-line text-2xl text-white"></i>
                </a>
                <span class="px-3 py-1 bg-emerald-500 text-[10px] font-black uppercase tracking-widest rounded-lg shadow-sm text-white">
                    {{$addschool[0]->getschoolname->school_name}}
                </span>
            </div>
            <h1 class="text-2xl md:text-4xl font-black mb-4 italic uppercase tracking-tight text-white leading-tight">
                {{$addschool[0]->getactivityname->activity_name}}
            </h1>
            <div class="inline-flex items-center gap-2 px-3 py-1 bg-white/20 rounded-full border border-white/10 text-[10px] font-black italic uppercase text-white">
                <span class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></span>
                {{getactive($addschool[0]->getactivityname->activity_status)}}
            </div>
        </div>
        <i class="ri-user-settings-line absolute -right-10 -bottom-10 text-[12rem] opacity-10 rotate-12 text-white"></i>
    </div>

    @if (session('status') || Session::has('massage'))
        <div class="mb-8 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 flex items-center gap-3 rounded-xl shadow-sm font-bold animate-fade-in italic">
            <i class="ri-checkbox-circle-fill text-xl"></i> {{ session('status') ?: Session::get('massage') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-1 gap-8">
        
        <div class="bg-white rounded-3xl shadow-xl shadow-slate-100 border border-gray-100 overflow-hidden">
            
            <div class="p-8 border-b border-gray-100">
                <h2 class="text-base font-black text-emerald-700 uppercase tracking-widest italic flex items-center gap-2 mb-6">
                    <i class="ri-group-line"></i> รายชื่อนักเรียนผู้เข้าแข่งขัน
                </h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-separate border-spacing-y-2">
                        <thead>
                            <tr class="text-gray-400 text-[10px] uppercase tracking-[0.2em] font-black italic">
                                <th class="px-6 py-2 w-16 text-center">#</th>
                                <th class="px-6 py-2 w-24">รูปภาพ</th>
                                <th class="px-6 py-2">คำนำหน้า</th>
                                <th class="px-6 py-2">ชื่อ - นามสกุล</th>
                                <th class="px-6 py-2 text-center">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody class="font-bold">
                            @forelse($addstudent as $index => $l)
                            <tr class="group bg-white hover:bg-emerald-50 transition-all border border-gray-100 shadow-sm rounded-2xl">
                                <td class="px-6 py-4 text-center text-slate-300 italic border-l-4 border-transparent group-hover:border-emerald-500 rounded-l-2xl">{{ $index + 1 }}.</td>
                                
                                <td class="px-4 py-3">
                                    <div class="w-20 h-20 rounded-2xl overflow-hidden bg-slate-100 border-2 border-white shadow-lg flex items-center justify-center">
                                        @if($l->student_image)
                                            <img src="{{ get_secure_file_url('picture', $l->student_image, $l->year) }}" class="w-full h-full object-cover">
                                        @else
                                            <i class="ri-user-smile-line text-4xl text-emerald-600/20"></i>
                                        @endif
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-slate-500">{{$l->getstudenttitle->title}}</td>
                                <td class="px-6 py-4 text-slate-800 italic uppercase">{{$l->name}} {{$l->lastname}}</td>
                                
                                <td class="px-6 py-4 text-center rounded-r-2xl">
                                    <div class="flex justify-center gap-2">
                                        @if($addschool[0]->school_schoolgroup==Auth::user()->schoolgroup or Auth::user()->position == '10')
                                            <a href="{{'../addstudent/'.$l->id.'/edit?action=edit'}}" class="w-10 h-10 flex items-center justify-center bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-600 hover:text-white transition-all shadow-sm">
                                                <i class="ri-edit-2-line text-xl"></i>
                                            </a>
                                            @if($addschool[0]->getactivityname->activity_status == 0)
                                                <button onclick="DelConfirm({{$l->id}})" class="w-10 h-10 flex items-center justify-center bg-red-50 text-red-600 rounded-lg hover:bg-red-600 hover:text-white transition-all shadow-sm">
                                                    <i class="ri-delete-bin-line text-xl"></i>
                                                </button>
                                                <form id="delform{{$l->id}}" method="post" action="{{ route('addstudent.destroy',$l->id)}}" class="hidden">
                                                    <input type="hidden" name="_method" value="DELETE"/> {!!csrf_field()!!}
                                                </form>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center py-12 text-slate-400 italic">ไม่พบข้อมูลนักเรียน</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $addstudent->links() }}</div>
            </div>

            <div class="p-8 bg-slate-50/50">
                <h2 class="text-base font-black text-blue-700 uppercase tracking-widest italic flex items-center gap-2 mb-6">
                    <i class="ri-user-star-line"></i> รายชื่อครูผู้ควบคุม
                </h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-separate border-spacing-y-2">
                        <thead>
                            <tr class="text-gray-400 text-[10px] uppercase tracking-[0.2em] font-black italic">
                                <th class="px-6 py-2 w-16 text-center">#</th>
                                <th class="px-6 py-2 w-24">รูปภาพ</th>
                                <th class="px-6 py-2">คำนำหน้า</th>
                                <th class="px-6 py-2">ชื่อ - นามสกุล</th>
                                <th class="px-6 py-2 text-center">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody class="font-bold">
                            @forelse($addteacher as $index => $l)
                            <tr class="group bg-white hover:bg-blue-50 transition-all border border-gray-100 shadow-sm rounded-2xl">
                                <td class="px-6 py-4 text-center text-slate-300 italic border-l-4 border-transparent group-hover:border-blue-500 rounded-l-2xl">{{ $index + 1 }}.</td>
                                
                                <td class="px-4 py-3">
                                    <div class="w-20 h-20 rounded-2xl overflow-hidden bg-slate-100 border-2 border-white shadow-lg flex items-center justify-center">
                                        @if($l->student_image)
                                            <img src="{{ get_secure_file_url('picture', $l->student_image, $l->year) }}" class="w-full h-full object-cover">
                                        @else
                                            <i class="ri-user-star-line text-4xl text-blue-600/20"></i>
                                        @endif
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-slate-500">{{$l->getstudenttitle->title}}</td>
                                <td class="px-6 py-4 text-slate-800 italic uppercase">{{$l->name}} {{$l->lastname}}</td>
                                
                                <td class="px-6 py-4 text-center rounded-r-2xl">
                                    <div class="flex justify-center gap-2">
                                        @if($addschool[0]->school_schoolgroup==Auth::user()->schoolgroup or Auth::user()->position == '10')
                                            <a href="{{'../addstudent/'.$l->id.'/edit?action=edit'}}" class="w-10 h-10 flex items-center justify-center bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-600 hover:text-white transition-all shadow-sm">
                                                <i class="ri-edit-2-line text-xl"></i>
                                            </a>
                                            @if($addschool[0]->getactivityname->activity_status == 0)
                                                <button onclick="DelConfirm({{$l->id}})" class="w-10 h-10 flex items-center justify-center bg-red-50 text-red-600 rounded-lg hover:bg-red-600 hover:text-white transition-all shadow-sm">
                                                    <i class="ri-delete-bin-line text-xl"></i>
                                                </button>
                                                <form id="delform{{$l->id}}" method="post" action="{{ route('addstudent.destroy',$l->id)}}" class="hidden">
                                                    <input type="hidden" name="_method" value="DELETE"/> {!!csrf_field()!!}
                                                </form>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center py-8 text-slate-400 italic">ไม่พบข้อมูลครูผู้ควบคุม</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $addteacher->links() }}</div>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-xl shadow-slate-100 border border-gray-100 overflow-hidden mt-8">
            <div class="bg-emerald-600 px-8 py-6 text-white flex items-center gap-3">
                <i class="ri-user-add-line text-2xl text-white"></i>
                <div>
                    <h3 class="font-black italic uppercase tracking-widest text-sm text-white leading-none">เพิ่มนักเรียน/ครูผู้เข้าร่วม</h3>
                    <p class="text-[10px] text-emerald-100 font-bold italic mt-1 uppercase text-white leading-none">Registration Form</p>
                </div>
            </div>
            
            <div class="p-8 md:p-12">
                <form method="POST" action="{{ Route('addstudent.store') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 items-end font-bold italic">
                    {{ csrf_field() }}
                    <input type="hidden" name="activity" value="{{$addschool[0]->school_activity}}"/>
                    <input type="hidden" name="school" value="{{$addschool[0]->id}}"/>

                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest italic px-1">คำนำหน้า</label>
                        <select name="title" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-bold text-gray-700 shadow-inner outline-none">
                            @foreach($title as $l)
                                <option value="{{$l->title_id}}">{{$l->title}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest italic px-1">ชื่อ</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-bold text-gray-700 shadow-inner">
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest italic px-1">นามสกุล</label>
                        <input type="text" name="lastname" value="{{ old('lastname') }}" required
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-bold text-gray-700 shadow-inner">
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest italic px-1">บทบาท</label>
                        <select name="type" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-bold text-gray-700 shadow-inner outline-none">
                            <option value="0">นักเรียน</option>
                            <option value="1">ครูผู้ควบคุม</option>
                        </select>
                    </div>

                    <div class="lg:col-span-4 flex justify-end pt-4">
                        <button type="submit" class="w-full md:w-auto px-12 py-4 bg-emerald-600 text-white rounded-2xl font-black text-base hover:bg-emerald-700 hover:scale-[1.02] active:scale-95 transition-all shadow-xl shadow-emerald-100 uppercase italic tracking-widest text-white flex items-center justify-center gap-2">
                            <i class="ri-save-3-line text-white"></i> เพิ่มข้อมูลเข้าระบบ
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function DelConfirm(id) {
        if (confirm('⚠️ ยืนยันการลบรายชื่อนี้ออกจากทีม? ข้อมูลจะไม่สามารถกู้คืนได้')) {
            document.getElementById('delform' + id).submit();
        }
    }
</script>
@endsection