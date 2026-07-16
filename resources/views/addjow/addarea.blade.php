@extends('layouts.apparea')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 font-sarabun text-slate-800">

    <div class="relative bg-gradient-to-r from-amber-800 to-orange-600 rounded-3xl shadow-xl p-8 md:p-12 mb-8 text-white overflow-hidden border-b-8 border-orange-500">
        <div class="relative z-10 text-white">
            <h2 class="text-xl md:text-2xl font-bold opacity-90 italic mb-2 text-white">
                {{ session('app_name')}} ระดับเครือข่ายสถานศึกษา {{Auth::user()->getschoolgroup->schoolgroup_name}}
            </h2>
            <h1 class="text-3xl md:text-5xl font-black tracking-tight uppercase italic text-white leading-none">
                บันทึกรายชื่อกรรมการ
            </h1>
        </div>
        <i class="ri-user-star-line absolute -right-10 -bottom-10 text-[15rem] opacity-10 rotate-12 text-white"></i>
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
                    <div class="flex items-center gap-4 text-slate-800">
                        <a href="../jowviewactivity/{{$activity->activity_subject}}" class="w-10 h-10 flex items-center justify-center bg-white text-orange-600 rounded-xl border border-slate-200 hover:bg-orange-600 hover:text-white transition-all shadow-sm group">
                            <i class="ri-arrow-left-s-line text-2xl group-hover:-translate-x-1 transition-transform"></i>
                        </a>
                        <div>
                            <h2 class="text-base font-black text-slate-800 uppercase italic tracking-wide leading-none text-slate-800">รายชื่อกรรมการตัดสิน</h2>
                            <p class="text-[10px] text-orange-500 font-bold mt-1 italic uppercase tracking-widest leading-none">{{$activity->activity_name}}</p>
                        </div>
                    </div>
                </div>

                <div class="p-4 md:p-8">
                    <div class="overflow-x-auto text-slate-800">
                        <table class="w-full text-left border-separate border-spacing-y-3">
                            <thead>
                                <tr class="text-slate-400 text-[10px] uppercase tracking-[0.2em] font-black italic">
                                    <th class="px-6 py-2 text-center w-12 text-slate-400">#</th>
                                    <th class="px-6 py-2 text-slate-400">ชื่อ-นามสกุล</th>
                                    <th class="px-6 py-2 text-slate-400">ตำแหน่งหน้าที่</th>
                                    <th class="px-6 py-2 text-right text-slate-400">จัดการ</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm font-bold italic text-slate-800">
                                @php $index = 0 @endphp
                                @forelse($jow as $l)
                                @php $index++ @endphp
                                <tr class="bg-white hover:bg-orange-50/50 transition-all border border-slate-100 shadow-sm rounded-2xl group">
                                    <td class="px-6 py-5 text-center text-slate-300 rounded-l-2xl border-l-4 border-transparent group-hover:border-orange-500">
                                        {{ $index }}
                                    </td>
                                    <td class="px-6 py-5">
                                        <p class="text-slate-800 font-black italic uppercase leading-none">{{$l->getjowtitle->title}}{{$l->jow_name}} {{$l->jow_lastname}}</p>
                                        <span class="text-[9px] bg-slate-100 px-2 py-0.5 rounded-full text-slate-400 mt-2 inline-block">กรรมการตัดสิน</span>
                                    </td>
                                    <td class="px-6 py-5">
                                        <span class="px-3 py-1 bg-orange-100 text-orange-700 rounded-lg text-[11px] font-black italic">
                                            {{$l->jowclass}}
                                        </span>
                                    </td>
                                    <td class="px-6 py-5 text-right rounded-r-2xl">
                                        <div class="flex justify-end gap-2">
                                            <a href="{{url('area/areajow/'.$l->id.'/edit')}}" class="w-9 h-9 flex items-center justify-center bg-blue-50 text-blue-600 rounded-xl hover:bg-blue-600 hover:text-white transition-all shadow-sm">
                                                <i class="ri-edit-2-line"></i>
                                            </a>
                                            <button onclick="DelConfirm({{$l->id}})" class="w-9 h-9 flex items-center justify-center bg-red-50 text-red-600 rounded-xl hover:bg-red-600 hover:text-white transition-all shadow-sm">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                            <form id="delform{{$l->id}}" method="post" action="{{ route('areajow.destroy',$l->id)}}" class="hidden">
                                                <input type="hidden" name="_method" value="DELETE"/> {!!csrf_field()!!}
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="4" class="text-center py-10 font-bold italic text-slate-400">ยังไม่มีรายชื่อกรรมการสำหรับรายการนี้</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="bg-white rounded-3xl shadow-xl shadow-slate-100 border border-slate-100 overflow-hidden sticky top-10">
                <div class="bg-amber-900 px-8 py-6 text-white flex items-center gap-3 text-white">
                    <div class="w-10 h-10 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                        <i class="ri-user-add-line text-xl text-white"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-black leading-none text-white uppercase italic text-white">Add Judge</h3>
                        <p class="text-orange-200 text-[10px] mt-1 font-bold italic uppercase opacity-80 text-white">เพิ่มชื่อกรรมการตัดสิน</p>
                    </div>
                </div>

                <div class="p-8">
                    <form method="POST" action="{{ Route('areajow.store') }}" class="space-y-6 font-bold italic text-slate-800">
                        {{ csrf_field() }}
                        <input type="hidden" name="activity" value="{{$id}}"/>
                        <input type="hidden" name="school" value=""/>
                        <input type="hidden" name="jow_type" value="0"/>

                        <div class="space-y-1 text-slate-800">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] italic">หน้าที่/ตำแหน่ง</label>
                            <select name="jowclass" class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition-all font-black text-slate-700 outline-none text-slate-800">
                                <option value="ประธานกรรมการ">ประธานกรรมการ</option>
                                <option value="กรรมการ">กรรมการ</option>
                                <option value="กรรมการและเลขานุการ">กรรมการและเลขานุการ</option>
                            </select>
                            @if ($errors->has('jowclass')) <p class="text-red-500 text-[9px] italic mt-1 font-black leading-none text-slate-800">* {{ $errors->first('jowclass') }}</p> @endif
                        </div>

                        <div class="space-y-1 text-slate-800">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] italic text-slate-800">คำนำหน้า</label>
                            <select name="title" class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition-all font-black text-slate-700 outline-none text-slate-800">
                                @foreach($title as $l)
                                    <option value="{{$l->title_id}}">{{$l->title}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="space-y-1 text-slate-800">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] italic text-slate-800">ชื่อ (First Name)</label>
                            <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition-all shadow-inner font-black text-slate-700 text-slate-800">
                            @if ($errors->has('name')) <p class="text-red-500 text-[9px] italic mt-1 font-black leading-none text-slate-800">* {{ $errors->first('name') }}</p> @endif
                        </div>

                        <div class="space-y-1 text-slate-800">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] italic text-slate-800">นามสกุล (Last Name)</label>
                            <input type="text" name="lastname" value="{{ old('lastname') }}" required class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition-all shadow-inner font-black text-slate-700 text-slate-800">
                            @if ($errors->has('lastname')) <p class="text-red-500 text-[9px] italic mt-1 font-black leading-none text-slate-800">* {{ $errors->first('lastname') }}</p> @endif
                        </div>

                        <button type="submit" class="w-full py-4 bg-orange-600 text-white rounded-2xl font-black text-sm hover:bg-orange-700 hover:scale-[1.02] active:scale-95 transition-all shadow-xl shadow-orange-100 uppercase italic tracking-widest text-white mt-4">
                            <i class="ri-save-3-line mr-1 text-white"></i> เพิ่มรายชื่อกรรมการ
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function DelConfirm(id) {
        if (confirm('⚠️ ยืนยันการลบรายชื่อกรรมการท่านนี้? ข้อมูลนี้จะไม่สามารถกู้คืนได้')) {
            document.getElementById('delform' + id).submit();
        }
    }
</script>
@endsection