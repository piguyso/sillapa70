@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10 font-sarabun text-slate-800">

    <div class="relative bg-gradient-to-r from-emerald-800 to-green-600 rounded-3xl shadow-xl p-8 md:p-12 mb-8 text-white overflow-hidden border-b-8 border-emerald-500">
        <div class="relative z-10 text-white">
            <h1 class="text-3xl md:text-5xl font-black mb-2 tracking-tight italic uppercase leading-none text-white">
                Edit Staff Info
            </h1>
            <p class="text-green-50 text-lg opacity-90 font-medium italic text-white">
                <i class="ri-user-settings-line mr-2"></i> แก้ไขข้อมูลกรรมการดำเนินงาน
            </p>
        </div>
        <i class="ri-edit-circle-line absolute -right-10 -bottom-10 text-[12rem] opacity-10 rotate-12 text-white"></i>
    </div>

    <div class="bg-white rounded-3xl shadow-xl shadow-slate-200 border border-slate-100 overflow-hidden">
        <div class="bg-slate-50 px-8 py-5 border-b border-slate-100 flex items-center gap-4">
            <a href="{{ url('/addjow2') }}" class="w-10 h-10 flex items-center justify-center bg-white text-emerald-600 rounded-xl border border-slate-200 hover:bg-emerald-600 hover:text-white transition-all shadow-sm group">
                <i class="ri-arrow-left-line text-xl group-hover:-translate-x-1 transition-transform"></i>
            </a>
            <h2 class="text-base font-black text-slate-800 uppercase italic tracking-widest leading-none">Staff Details</h2>
        </div>

        <form method="POST" action="{{ route('jow.update', $id) }}" class="p-8 md:p-12 space-y-8">
            {{ csrf_field() }}
            <input type="hidden" name="_method" value="PUT">
            <input type="hidden" name="jow_type" value="1"/>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="md:col-span-2 space-y-2">
                    <label class="block text-[10px] font-black text-emerald-600 uppercase tracking-widest italic ml-2">ศูนย์การแข่งขัน (Branch)</label>
                    <select name="jow_branch" class="w-full px-5 py-4 bg-emerald-50/30 border border-emerald-100 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-bold text-slate-700 outline-none shadow-inner">
                        @foreach($jowbranch as $l)
                            <option value="{{$l->id}}" @if($jow->jow_branch == $l->id) selected @endif>{{$l->branch_name ??'ไม่ระบุ'}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-emerald-600 uppercase tracking-widest italic ml-2">คำนำหน้า (Title)</label>
                    <select name="jow_title" class="w-full px-5 py-4 bg-emerald-50/30 border border-emerald-100 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-bold text-slate-700 outline-none shadow-inner">
                        @foreach($title as $l)
                            <option value="{{$l->title_id}}" @if($jow->jow_title == $l->id) selected @endif>{{$l->title}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="hidden md:block"></div> <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest italic ml-2">ชื่อ (First Name)</label>
                    <input type="text" name="jow_name" value="{{ $jow->jow_name }}" required class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-bold text-slate-700 outline-none shadow-inner">
                </div>

                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest italic ml-2">นามสกุล (Last Name)</label>
                    <input type="text" name="jow_lastname" value="{{ $jow->jow_lastname }}" required class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-bold text-slate-700 outline-none shadow-inner">
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-4 pt-6">
                <button type="submit" class="flex-1 py-4 bg-emerald-600 text-white rounded-2xl font-black text-base hover:bg-emerald-700 hover:scale-[1.02] active:scale-95 transition-all shadow-xl shadow-emerald-100 uppercase italic tracking-widest text-white">
                    <i class="ri-save-3-line mr-1 text-white"></i> Update Staff Info
                </button>
                <a href="{{ url()->previous() }}" class="px-10 py-4 bg-slate-100 text-slate-500 rounded-2xl font-black hover:bg-slate-200 transition-all uppercase italic text-xs tracking-widest text-center">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection