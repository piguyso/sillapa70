@extends('layouts.apparea')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10 font-sarabun text-slate-800">

    <div class="relative bg-gradient-to-r from-amber-800 to-orange-600 rounded-3xl shadow-xl p-8 md:p-12 mb-8 text-white overflow-hidden border-b-8 border-orange-500">
        <div class="relative z-10 text-white">
            <h2 class="text-lg md:text-xl font-bold opacity-90 italic mb-2 text-white leading-none">
                {{ session('app_name')}} ระดับเครือข่ายสถานศึกษา
            </h2>
            <h1 class="text-2xl md:text-4xl font-black tracking-tight uppercase italic text-white leading-none">
                แก้ไขข้อมูลผู้ใช้งาน
            </h1>
        </div>
        <i class="ri-user-settings-line absolute -right-10 -bottom-10 text-[10rem] opacity-10 rotate-12 text-white"></i>
    </div>

    @if(Session::has('massage'))
        <div class="mb-8 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 rounded-xl shadow-sm font-bold animate-fade-in italic text-sm flex items-center gap-3">
            <i class="ri-checkbox-circle-fill text-xl"></i> {{ Session::get('massage') }}
        </div>
    @endif

    <div class="bg-white rounded-3xl shadow-xl shadow-slate-100 border border-slate-100 overflow-hidden">
        <div class="bg-slate-50 px-8 py-6 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ url()->previous() }}" class="w-10 h-10 flex items-center justify-center bg-white text-orange-600 rounded-xl border border-slate-200 hover:bg-orange-600 hover:text-white transition-all shadow-sm group">
                    <i class="ri-arrow-left-s-line text-2xl group-hover:-translate-x-1 transition-transform"></i>
                </a>
                <h3 class="text-base font-black text-slate-800 uppercase italic tracking-widest leading-none">User Profile Settings</h3>
            </div>
            <span class="text-[10px] bg-orange-100 text-orange-700 px-3 py-1 rounded-full font-black uppercase italic tracking-tighter">USER ID: #{{$data->id}}</span>
        </div>

        <div class="p-8 md:p-12">
            <form class="space-y-8 font-bold italic" method="POST" action="{{ url('myuserareaupdate') }}">
                {{ csrf_field() }}
                <input type="hidden" name="_method" value="put">
                <input type="hidden" name="id" value="{{$data->id}}">

                <div class="space-y-6">
                    <div class="flex items-center gap-2 text-orange-600">
                        <i class="ri-profile-line"></i>
                        <h4 class="text-xs font-black uppercase tracking-widest">ข้อมูลส่วนตัว</h4>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest italic">ชื่อ (Name)</label>
                            <input type="text" name="name" value="{{$data->name}}" required
                                   class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition-all font-black text-slate-700 shadow-inner outline-none">
                            @if ($errors->has('name')) <p class="text-red-500 text-[10px] mt-1 font-black italic">* {{ $errors->first('name') }}</p> @endif
                        </div>

                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest italic">นามสกุล (Lastname)</label>
                            <input type="text" name="lastname" value="{{$data->lastname}}" required
                                   class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition-all font-black text-slate-700 shadow-inner outline-none">
                            @if ($errors->has('lastname')) <p class="text-red-500 text-[10px] mt-1 font-black italic">* {{ $errors->first('lastname') }}</p> @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest italic">เบอร์โทรติดต่อ</label>
                            <input type="text" name="phonenumber" value="{{$data->phonenumber}}"
                                   class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition-all font-black text-slate-700 shadow-inner outline-none" placeholder="08x-xxxxxxx">
                        </div>

                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest italic">ตำแหน่งในระบบ</label>
                            <select name="position" class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition-all font-black text-slate-700 outline-none">
                                <option value="5" @if($data->position == 5) selected @endif>เครือข่ายสถานศึกษา</option>
                                <option value="0" @if($data->position == 0) selected @endif>โรงเรียน</option>
                            </select>
                        </div>
                    </div>
                </div>

                <hr class="border-slate-100">

                <div class="space-y-6">
                    <div class="flex items-center gap-2 text-orange-600">
                        <i class="ri-shield-keyhole-line"></i>
                        <h4 class="text-xs font-black uppercase tracking-widest">ข้อมูลบัญชีผู้ใช้งาน</h4>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Username (Email)</label>
                            <input type="text" name="email" value="{{ $data->email }}" required
                                   class="w-full px-5 py-4 bg-slate-100 border border-slate-200 rounded-2xl font-black text-slate-400 cursor-not-allowed outline-none" readonly>
                            <p class="text-[9px] text-slate-400 mt-1 italic leading-none font-bold">ไม่สามารถแก้ไข Username ได้</p>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest italic text-orange-600">เปลี่ยนพาสเวิร์ด (Password)</label>
                            <input type="text" name="password" 
                                   class="w-full px-5 py-4 bg-orange-50/50 border border-orange-200 rounded-2xl focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition-all font-black text-slate-700 shadow-inner outline-none" placeholder="ปล่อยว่างหากไม่ต้องการเปลี่ยน">
                            @if ($errors->has('password')) <p class="text-red-500 text-[10px] mt-1 font-black italic">* {{ $errors->first('password') }}</p> @endif
                        </div>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-4 pt-6">
                    <button type="submit" class="flex-1 py-4 bg-orange-600 text-white rounded-2xl font-black text-base hover:bg-orange-700 hover:scale-[1.02] active:scale-95 transition-all shadow-xl shadow-orange-100 uppercase italic tracking-widest">
                        <i class="ri-refresh-line mr-1"></i> Update Profile
                    </button>
                    <a href="{{ url()->previous() }}" class="inline-flex items-center justify-center px-8 py-4 bg-slate-100 text-slate-500 rounded-2xl font-black hover:bg-slate-200 transition-all uppercase italic text-xs tracking-widest">
                        Cancel
                    </a>
                </div>
            </form>
        </div>

        <div class="px-8 py-6 bg-amber-50 border-t border-amber-100 text-amber-800 italic">
            <p class="text-[10px] font-black uppercase tracking-widest mb-1 flex items-center gap-1">
                <i class="ri-information-line"></i> Security Tip:
            </p>
            <p class="text-[11px] font-bold leading-relaxed opacity-80">
                หากมีการเปลี่ยนพาสเวิร์ดใหม่ ระบบจะใช้พาสเวิร์ดนั้นในการล็อกอินครั้งถัดไปทันที โปรดตรวจสอบความถูกต้องของตัวอักษรก่อนทำการบันทึก
            </p>
        </div>
    </div>
</div>
@endsection