@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10 font-sarabun text-slate-800">

    <div class="bg-gradient-to-r from-emerald-800 to-teal-600 rounded-3xl shadow-xl p-8 md:p-12 mb-8 text-white relative overflow-hidden border-b-8 border-emerald-500 text-white">
        <div class="relative z-10 text-white">
            <h1 class="text-3xl md:text-5xl font-black mb-2 tracking-tight italic uppercase text-white leading-none">
                Edit Account
            </h1>
            <p class="text-emerald-50 text-lg opacity-90 font-medium italic text-white">
                <i class="ri-user-settings-line mr-2"></i> แก้ไขข้อมูลและกำหนดสิทธิ์ผู้ใช้งานระบบ
            </p>
        </div>
        <i class="ri-shield-keyhole-line absolute -right-10 -bottom-10 text-[12rem] opacity-10 rotate-12 text-white"></i>
    </div>

    <div class="bg-white rounded-3xl shadow-xl shadow-slate-200 border border-slate-100 overflow-hidden">
        <div class="bg-slate-50 px-8 py-5 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ url()->previous() }}" class="w-10 h-10 flex items-center justify-center bg-white text-emerald-600 rounded-xl border border-slate-200 hover:bg-emerald-600 hover:text-white transition-all shadow-sm group">
                    <i class="ri-arrow-left-line text-xl group-hover:-translate-x-1 transition-transform"></i>
                </a>
                <h2 class="text-base font-black text-slate-800 uppercase italic tracking-widest leading-none">Account Configuration</h2>
            </div>
            <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-[10px] font-black italic shadow-sm uppercase">User ID: #{{$id}}</span>
        </div>

        <form method="POST" action="{{ route('myuser.update', $id) }}" class="p-8 md:p-12 space-y-10">
            {{ csrf_field() }}
            <input type="hidden" name="_method" value="put">

            <div class="space-y-6">
                <div class="flex items-center gap-2 text-emerald-600">
                    <i class="ri-id-card-line text-xl"></i>
                    <h4 class="text-xs font-black uppercase tracking-[0.2em] italic">Personal Information</h4>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest italic ml-2">ชื่อ (First Name)</label>
                        <input type="text" name="name" value="{{ $data->name }}" required class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-bold text-slate-700 outline-none shadow-inner">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest italic ml-2">นามสกุล (Last Name)</label>
                        <input type="text" name="lastname" value="{{ $data->lastname }}" required class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-bold text-slate-700 outline-none shadow-inner">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest italic ml-2">เบอร์โทรศัพท์ (Phone)</label>
                    <input type="text" name="phonenumber" value="{{ $data->phonenumber }}" class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-bold text-slate-700 outline-none shadow-inner" placeholder="08x-xxxxxxx">
                </div>
            </div>

            <hr class="border-slate-100">

            <div class="space-y-6">
                <div class="flex items-center gap-2 text-emerald-600">
                    <i class="ri-git-repository-private-line text-xl"></i>
                    <h4 class="text-xs font-black uppercase tracking-[0.2em] italic">Privileges & Affiliation</h4>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest italic ml-2">สิทธิ์การใช้งาน (Position)</label>
                        <select name="position" class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-bold text-slate-700 outline-none">
                            <option value="10" @if($data->position == 10) selected @endif>Super Admin (เขตพื้นที่)</option>
                            <option value="5" @if($data->position == 5) selected @endif>เครือข่ายสถานศึกษา</option>
                            <option value="1" @if($data->position == 1) selected @endif>ศูนย์แข่งขัน</option>
                            <option value="0" @if($data->position == 0) selected @endif>โรงเรียน</option>
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest italic ml-2">ศูนย์การแข่งขัน (Branch)</label>
                        <select name="branch" class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-bold text-slate-700 outline-none">
                            <option value="">-- เลือกศูนย์การแข่งขัน --</option>
                            @foreach($branch as $b)
                                <option value="{{ $b->id }}" @if($data->branch == $b->id) selected @endif>{{ $b->branch_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest italic ml-2">เครือข่ายสถานศึกษา (Group)</label>
                        <select name="schoolgroup" class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-bold text-slate-700 outline-none">
                            <option value="">-- เลือกเครือข่าย --</option>
                            @foreach($schoolgroup as $sg)
                                <option value="{{ $sg->id }}" @if($data->schoolgroup == $sg->id) selected @endif>{{ $sg->schoolgroup_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest italic ml-2">สังกัดโรงเรียน (School)</label>
                        <select name="school" class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-bold text-slate-700 outline-none">
                            <option value="">-- เลือกโรงเรียน --</option>
                            @foreach($school as $s)
                                <option value="{{ $s->id }}" @if($data->school == $s->id) selected @endif>{{ $s->school_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <hr class="border-slate-100">

            <div class="space-y-6">
                <div class="flex items-center gap-2 text-emerald-600">
                    <i class="ri-user-star-line text-xl"></i>
                    <h4 class="text-xs font-black uppercase tracking-[0.2em] italic">Account Access</h4>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest italic ml-2">ชื่อบัญชี / Email (Username)</label>
                        <input type="email" name="email" value="{{ $data->email }}" required class="w-full px-5 py-4 bg-slate-100 border border-slate-200 rounded-2xl font-bold text-slate-400 cursor-not-allowed outline-none" readonly>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-emerald-600 uppercase tracking-widest italic ml-2">รหัสผ่านใหม่ (Reset Password)</label>
                        <input type="text" name="password" class="w-full px-5 py-4 bg-emerald-50/30 border border-emerald-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-bold text-slate-700 outline-none shadow-sm" placeholder="ปล่อยว่างไว้หากไม่ต้องการเปลี่ยน">
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-4 pt-6">
                <button type="submit" class="flex-1 py-4 bg-emerald-600 text-white rounded-2xl font-black text-base hover:bg-emerald-700 hover:scale-[1.02] active:scale-95 transition-all shadow-xl shadow-emerald-100 uppercase italic tracking-widest">
                    <i class="ri-save-line mr-1"></i> Update Account Info
                </button>
                <a href="{{ url()->previous() }}" class="px-10 py-4 bg-slate-100 text-slate-500 rounded-2xl font-black hover:bg-slate-200 transition-all uppercase italic text-xs tracking-widest text-center">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection