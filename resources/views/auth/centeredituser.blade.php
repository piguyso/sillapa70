@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 text-slate-800">
    
    <div class="bg-gradient-to-r from-primary to-emerald-600 rounded-2xl shadow-lg p-8 mb-8 text-white">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center border border-white/30 shadow-sm">
                <i class="ri-lock-password-line text-2xl text-white"></i>
            </div>
            <div>
                <h1 class="text-2xl md:text-3xl font-bold leading-tight uppercase tracking-tight">เปลี่ยนรหัสผ่าน</h1>
                <p class="text-green-50 opacity-90 font-medium italic">เพื่อความปลอดภัย โปรดตั้งรหัสผ่านที่คาดเดาได้ยาก</p>
            </div>
        </div>
    </div>

    @if(Session::has('massage'))
        <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 flex items-center gap-3 rounded-r-lg shadow-sm">
            <i class="ri-checkbox-circle-fill text-xl"></i>
            <span class="font-bold">{{ Session::get('massage') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <form class="form-horizontal" method="POST" action="{{ url('updateuser') }}">
            {{ csrf_field() }}
            <input type="hidden" name="_method" value="put">
            <input type="hidden" name="id" value="{{auth::user()->id}}">

            <div class="p-8 space-y-8">
                
                <div>
                    <h3 class="text-sm font-black text-gray-400 uppercase tracking-[0.2em] mb-6 flex items-center gap-2">
                        <i class="ri-user-line"></i> ข้อมูลผู้ใช้งานปัจจุบัน
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-gray-50/50 p-6 rounded-2xl border border-gray-100">
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-1">ชื่อ - นามสกุล</label>
                            <p class="text-gray-700 font-bold uppercase">{{$data->name}} {{$data->lastname}}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-1">อีเมล / ชื่อผู้ใช้</label>
                            <p class="text-gray-700 font-bold italic">{{$data->email}}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-1">สังกัด / ศูนย์การแข่งขัน</label>
                            <p class="text-gray-700 font-bold">
                                @foreach($branch as $i)
                                    @if($i->id == $data->branch) {{$i->branch_name}} @endif
                                @endforeach
                            </p>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-1">เครือข่ายสถานศึกษา</label>
                            <p class="text-gray-700 font-bold">
                                @foreach($schoolgroup as $i)
                                    @if($i->id == $data->schoolgroup) {{$i->schoolgroup_name}} @endif
                                @endforeach
                            </p>
                        </div>
                    </div>
                </div>

                <hr class="border-gray-50">

                <div>
                    <h3 class="text-sm font-black text-gray-400 uppercase tracking-[0.2em] mb-6 flex items-center gap-2">
                        <i class="ri-shield-keyhole-line text-primary"></i> ตั้งค่ารหัสผ่านใหม่
                    </h3>
                    
                    <div class="max-w-md mx-auto">
                        <div class="bg-primary/5 p-8 rounded-3xl border-2 border-dashed border-primary/20">
                            <label for="password" class="block text-center text-sm font-bold text-primary mb-4 uppercase tracking-widest">
                                ระบุรหัสผ่านใหม่ที่ต้องการ
                            </label>
                            <input id="password" type="password" 
                                class="w-full bg-white border-2 border-primary rounded-2xl px-6 py-4 text-center font-black text-xl text-primary focus:ring-4 focus:ring-primary/10 focus:border-emerald-500 outline-none transition-all shadow-inner placeholder:text-gray-200" 
                                name="password" 
                                placeholder="••••••••"
                                required>
                            
                            @if ($errors->has('password'))
                                <p class="mt-4 text-center text-xs text-red-500 font-bold italic">
                                    <i class="ri-error-warning-line"></i> {{ $errors->first('password') }}
                                </p>
                            @endif

                            <p class="mt-4 text-[10px] text-center text-gray-400 font-bold uppercase leading-relaxed">
                                แนะนำ: ควรมีความยาวอย่างน้อย 8 ตัวอักษร<br>ประกอบด้วยตัวอักษรและตัวเลข
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 px-8 py-6 border-t border-gray-100 flex items-center justify-center">
                <button type="submit" class="group relative px-12 py-4 bg-primary text-white font-black rounded-2xl hover:bg-emerald-700 shadow-lg shadow-emerald-100 transition-all flex items-center gap-3 uppercase tracking-widest text-sm">
                    <i class="ri-save-3-line text-xl group-hover:scale-110 transition-transform"></i>
                    บันทึกรหัสผ่านใหม่
                </button>
            </div>
        </form>
    </div>
</div>
@endsection