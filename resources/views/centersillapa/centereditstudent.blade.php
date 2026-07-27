@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 text-slate-800 font-sarabun text-sm">
    
    @if (session('status') || Session::has('massage'))
        <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 flex items-center gap-3 rounded-xl shadow-sm animate-fade-in">
            <i class="ri-checkbox-circle-fill text-xl"></i>
            <span class="font-bold">{{ session('status') ?: Session::get('massage') }}</span>
        </div>
    @endif

<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden mb-8">
    <div class="bg-gradient-to-r from-emerald-700 to-green-500 px-8 py-6 flex flex-col md:flex-row md:items-center gap-4 text-white">
        
        <a href="../centerviewactivityschool/{{$addschool[0]->school_activity}}" 
           class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-white/20 text-white border border-white/30 hover:bg-white hover:text-emerald-700 transition-all shadow-sm group shrink-0">
            <i class="ri-arrow-left-s-line text-2xl group-hover:-translate-x-1 transition-transform text-white"></i>
        </a>
        
        <div class="text-white flex-1">
            <h2 class="text-xl font-bold leading-none mb-1 tracking-wide text-white">{{$addschool[0]->getschoolname->school_name}}</h2>
            <p class="text-emerald-100 text-sm italic uppercase tracking-widest opacity-90 text-white leading-none">
                <i class="ri-flag-2-line mr-1 text-white"></i>{{$addschool[0]->getactivityname->activity_name}}
            </p>
        </div>

        {{-- หมายเหตุ: ปรับเงื่อนไข auth()->user()->role == 'admin' ให้ตรงกับระบบ Database ของคุณ --}}
        @if(auth()->check() && auth()->user()->position == '10')
        <div class="mt-4 md:mt-0 md:ml-auto shrink-0">
            <a href="{{url('addaddstudent/'.$addschool[0]->id)}}" 
               class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl bg-white text-emerald-700 hover:bg-emerald-50 hover:shadow-md transition-all font-bold text-sm group">
                <i class="ri-add-line mr-2 text-lg group-hover:rotate-90 transition-transform"></i>
                เพิ่มข้อมูล
            </a>
        </div>
        @endif

    </div>
</div>

    <div class="space-y-8">
        
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-emerald-50/50 px-8 py-5 border-b border-emerald-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-emerald-600 rounded-xl flex items-center justify-center text-white shadow-sm">
                        <i class="ri-user-smile-fill text-xl text-white"></i>
                    </div>
                    <h3 class="text-lg font-black text-emerald-900 uppercase italic">Student List / รายชื่อนักเรียน</h3>
                </div>
            </div>
            
            <div class="p-8 overflow-x-auto">
                <table class="w-full text-left border-separate border-spacing-y-2">
                    <thead>
                        <tr class="text-emerald-400 text-[10px] uppercase tracking-[0.2em] font-black">
                            <th class="px-6 py-2 text-center w-16">#</th>
                            <th class="px-6 py-2 w-24">รูปภาพ</th>
                            <th class="px-6 py-2">คำนำหน้า</th>
                            <th class="px-6 py-2">ชื่อ - นามสกุล</th>
                            <th class="px-6 py-2">ประเภท</th>
                            <th class="px-6 py-2 text-center">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @php $nm = 1; @endphp
                        @forelse($addstudent as $l)
                        <tr class="group bg-white hover:bg-emerald-50/30 transition-all border border-gray-100 shadow-sm rounded-2xl">
                            <td class="px-6 py-4 text-center font-bold text-emerald-300 text-lg italic border-l-4 border-transparent group-hover:border-emerald-500 rounded-l-2xl">{{$nm++}}</td>
                            
                            <td class="px-4 py-3">
                                <div class="w-20 h-20 rounded-2xl overflow-hidden bg-slate-100 border-2 border-white shadow-lg flex items-center justify-center">
                                    @if($l->student_image)
                                        <img src="{{ get_secure_file_url('picture', $l->student_image, $l->year) }}" class="w-full h-full object-cover">
                                    @else
                                        <i class="ri-user-smile-line text-4xl text-emerald-600/20"></i>
                                    @endif
                                </div>
                            </td>

                            <td class="px-6 py-4 font-bold text-gray-500">{{$l->getstudenttitle->title}}</td>
                            <td class="px-6 py-4 font-black text-slate-700 text-base italic uppercase">{{$l->name}} {{$l->lastname}}</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-full text-[10px] font-black border border-emerald-100 uppercase italic">
                                    นักเรียน
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center rounded-r-2xl">
                                <a href="{{'../centereditstudentname/'.$l->id}}" 
                                   class="inline-flex items-center justify-center p-2.5 bg-amber-50 text-amber-600 border border-amber-100 rounded-xl hover:bg-amber-500 hover:text-white transition-all shadow-sm">
                                    <i class="ri-edit-2-line text-lg"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="px-6 py-12 text-center text-gray-400 italic">ไม่พบข้อมูลนักเรียน</td></tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-6">{{ $addstudent->links() }}</div>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-emerald-50/50 px-8 py-5 border-b border-emerald-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-emerald-600 rounded-xl flex items-center justify-center text-white shadow-sm">
                        <i class="ri-user-star-fill text-xl text-white"></i>
                    </div>
                    <h3 class="text-lg font-black text-emerald-900 uppercase italic">Teacher List / รายชื่อครู</h3>
                </div>
            </div>
            
            <div class="p-8 overflow-x-auto">
                <table class="w-full text-left border-separate border-spacing-y-2">
                    <thead>
                        <tr class="text-emerald-400 text-[10px] uppercase tracking-[0.2em] font-black">
                            <th class="px-6 py-2 text-center w-16">#</th>
                            <th class="px-6 py-2 w-24">รูปภาพ</th>
                            <th class="px-6 py-2">คำนำหน้า</th>
                            <th class="px-6 py-2">ชื่อ - นามสกุล</th>
                            <th class="px-6 py-2">ประเภท</th>
                            <th class="px-6 py-2 text-center">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @php $nm = 1; @endphp
                        @forelse($addteacher as $l)
                        <tr class="group bg-white hover:bg-emerald-50/30 transition-all border border-gray-100 shadow-sm rounded-2xl">
                            <td class="px-6 py-4 text-center font-bold text-emerald-300 text-lg italic border-l-4 border-transparent group-hover:border-emerald-500 rounded-l-2xl">{{$nm++}}</td>
                            
                            <td class="px-4 py-3">
                                <div class="w-20 h-20 rounded-2xl overflow-hidden bg-slate-100 border-2 border-white shadow-lg flex items-center justify-center">
                                    @if($l->student_image)
                                        <img src="{{ get_secure_file_url('picture', $l->student_image, $l->year) }}" class="w-full h-full object-cover">
                                    @else
                                        <i class="ri-user-star-line text-4xl text-blue-600/20"></i>
                                    @endif
                                </div>
                            </td>

                            <td class="px-6 py-4 font-bold text-gray-500">{{studenttitle($l->title)}}</td>
                            <td class="px-6 py-4 font-black text-slate-700 text-base italic uppercase">{{$l->name}} {{$l->lastname}}</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 bg-green-50 text-green-600 rounded-full text-[10px] font-black border border-green-100 uppercase italic">
                                    ครูผู้ควบคุม
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center rounded-r-2xl">
                                <a href="{{'../centereditstudentname/'.$l->id}}" 
                                   class="inline-flex items-center justify-center p-2.5 bg-amber-50 text-amber-600 border border-amber-100 rounded-xl hover:bg-amber-500 hover:text-white transition-all shadow-sm">
                                    <i class="ri-edit-2-line text-lg"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="px-6 py-12 text-center text-gray-400 italic">ไม่พบข้อมูลครูผู้ควบคุม</td></tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-6">{{ $addteacher->links() }}</div>
            </div>
        </div>

    </div>
</div>
@endsection