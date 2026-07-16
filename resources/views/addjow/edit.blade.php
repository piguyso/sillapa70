@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 text-slate-800">
    
    <div class="bg-gradient-to-r from-primary to-emerald-600 rounded-2xl shadow-lg p-8 mb-8 text-white">
        <div class="flex items-center gap-4">
            <a href="../../addjow/{{$jows->jow_activity}}" class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center hover:bg-white/40 transition-all border border-white/30 shadow-sm">
                <i class="ri-arrow-left-line text-2xl text-white"></i>
            </a>
            <div>
                <h1 class="text-2xl md:text-3xl font-bold leading-tight uppercase tracking-tight">แก้ไขข้อมูลกรรมการ</h1>
                <p class="text-green-50 opacity-90 font-medium">จัดการแก้ไขข้อมูลรายบุคคลของคณะกรรมการตัดสิน</p>
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
        <div class="bg-gray-50 px-8 py-5 border-b border-gray-100">
            <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2 uppercase tracking-wide">
                <i class="ri-user-settings-line text-primary"></i> ข้อมูลรายละเอียดกรรมการ
            </h2>
        </div>

        <form action="{{ Route('jow.update', $jows->id) }}" method="POST" class="p-8 space-y-6">
            {{ csrf_field() }}
            <input type="hidden" name="_method" value="put">
            <input type="hidden" name="school" value="{{$jows->id}}">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label for="jowclass" class="block text-sm font-bold text-gray-700 mb-2">ตำแหน่งในคณะกรรมการ</label>
                    <select id="jowclass" name="jowclass" 
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all font-bold">
                        <option value="ประธานกรรมการ" @if($jows->jowclass=='ประธานกรรมการ') selected @endif>ประธานกรรมการ</option>
                        <option value="กรรมการ" @if($jows->jowclass=='กรรมการ') selected @endif>กรรมการ</option>
                        <option value="กรรมการและเลขานุการ" @if($jows->jowclass=='กรรมการและเลขานุการ') selected @endif>กรรมการและเลขานุการ</option>
                    </select>
                    @if ($errors->has('jowclass'))
                        <p class="mt-1 text-xs text-red-500 font-bold italic">{{ $errors->first('jowclass') }}</p>
                    @endif
                </div>

                <div class="md:col-span-1">
                    <label for="jow_title" class="block text-sm font-bold text-gray-700 mb-2">คำนำหน้าชื่อ</label>
                    <select id="jow_title" name="jow_title" 
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all font-bold">
                        @foreach($title as $l)
                            <option value="{{$l->title_id}}" @if($jows->jow_title==$l->title_id) selected @endif>{{$l->title}}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('jow_title'))
                        <p class="mt-1 text-xs text-red-500 font-bold italic">{{ $errors->first('jow_title') }}</p>
                    @endif
                </div>

                <div class="md:col-span-1">
                    <label for="jow_name" class="block text-sm font-bold text-gray-700 mb-2">ชื่อ</label>
                    <input id="jow_name" type="text" name="jow_name" value="{{$jows->jow_name}}"
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all font-bold">
                    @if ($errors->has('jow_name'))
                        <p class="mt-1 text-xs text-red-500 font-bold italic">{{ $errors->first('jow_name') }}</p>
                    @endif
                </div>

                <div class="md:col-span-2">
                    <label for="jow_lastname" class="block text-sm font-bold text-gray-700 mb-2">นามสกุล / รายละเอียดเพิ่มเติม</label>
                    <input id="jow_lastname" type="text" name="jow_lastname" value="{{ $jows->jow_lastname }}"
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all font-bold">
                    @if ($errors->has('jow_lastname'))
                        <p class="mt-1 text-xs text-red-500 font-bold italic">{{ $errors->first('jow_lastname') }}</p>
                    @endif
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100">
                <a href="../../addjow/{{$jows->jow_activity}}" 
                    class="px-6 py-3 bg-white border border-gray-200 text-gray-600 font-bold rounded-xl hover:bg-gray-50 transition-all shadow-sm uppercase tracking-widest text-xs">
                    ยกเลิก
                </a>
                <button type="submit" 
                    class="px-10 py-3 bg-primary text-white font-black rounded-xl hover:bg-emerald-700 shadow-lg shadow-emerald-100 transition-all flex items-center gap-2 uppercase tracking-widest text-xs">
                    <i class="ri-save-line text-lg"></i>
                    อัปเดตข้อมูล
                </button>
            </div>
        </form>
    </div>
</div>
@endsection