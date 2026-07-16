@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10 font-sarabun text-slate-800">
    
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ url('subjectgroup') }}" 
           class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-white text-emerald-600 border border-gray-200 hover:bg-emerald-600 hover:text-white transition-all shadow-sm group">
            <i class="ri-arrow-left-s-line text-2xl group-hover:-translate-x-1 transition-transform"></i>
        </a>
        <div>
            <h1 class="text-2xl font-black text-gray-800 leading-none mb-1 uppercase italic text-emerald-700">Edit Subject Group / แก้ไขกลุ่มสาระ</h1>
            <p class="text-sm text-gray-400 font-bold tracking-widest uppercase italic">Curriculum Management</p>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-xl shadow-slate-100 border border-gray-100 overflow-hidden">
        <div class="bg-emerald-600 px-8 py-6 text-white flex items-center gap-3">
            <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                <i class="ri-flask-line text-2xl"></i>
            </div>
            <div>
                <h3 class="text-lg font-black leading-none text-white">ปรับปรุงข้อมูลกลุ่มสาระ</h3>
                <p class="text-emerald-100 text-xs mt-1 opacity-80 uppercase tracking-tighter italic">Update subject group and competition branch</p>
            </div>
        </div>

        <div class="p-8 md:p-12">
            @if(Session::has('massage'))
                <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 flex items-center gap-3 rounded-xl shadow-sm animate-fade-in text-sm font-bold">
                    <i class="ri-checkbox-circle-fill text-xl"></i>
                    <span>{{ Session::get('massage') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ Route('subjectgroup.update', $subjectgroup->id) }}" class="space-y-8">
                {{ csrf_field() }}
                <input type="hidden" name="_method" value="put">

                <div class="grid grid-cols-1 gap-6">
                    <div class="space-y-2">
                        <label for="subjectgroup_name" class="block text-sm font-black text-gray-700 uppercase tracking-wider italic">
                            <i class="ri-font-size text-emerald-500 mr-1"></i> ชื่อกลุ่มสาระการเรียนรู้
                        </label>
                        <input id="subjectgroup_name" type="text" 
                               class="block w-full px-4 py-4 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-bold text-gray-700 shadow-inner" 
                               name="subjectgroup_name" value="{{ $subjectgroup->subjectgroup_name }}" required>
                        
                        @if ($errors->has('subjectgroup_name'))
                            <p class="text-red-500 text-xs font-bold mt-1 flex items-center gap-1 italic">
                                <i class="ri-error-warning-line"></i> {{ $errors->first('subjectgroup_name') }}
                            </p>
                        @endif
                    </div>

                    <div class="space-y-2">
                        <label for="subjectgroup_detail" class="block text-sm font-black text-gray-700 uppercase tracking-wider italic">
                            <i class="ri-information-line text-emerald-500 mr-1"></i> รายละเอียด / คำอธิบาย
                        </label>
                        <input id="subjectgroup_detail" type="text" 
                               class="block w-full px-4 py-4 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-bold text-gray-700 shadow-inner" 
                               name="subjectgroup_detail" value="{{ $subjectgroup->subjectgroup_detail }}">
                        
                        @if ($errors->has('subjectgroup_detail'))
                            <p class="text-red-500 text-xs font-bold mt-1 flex items-center gap-1 italic">
                                <i class="ri-error-warning-line"></i> {{ $errors->first('subjectgroup_detail') }}
                            </p>
                        @endif
                    </div>

                    <div class="space-y-2">
                        <label for="branch" class="block text-sm font-black text-gray-700 uppercase tracking-wider italic">
                            <i class="ri-map-pin-2-line text-emerald-500 mr-1"></i> ศูนย์การแข่งขันที่รับผิดชอบ
                        </label>
                        <div class="relative">
                            <select id="branch" name="branch" 
                                    class="appearance-none block w-full px-4 py-4 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-bold text-gray-700 shadow-inner cursor-pointer">
                                @foreach($branch as $i)
                                    <option value="{{$i->id}}" @if($activity_subject[0]->activity_branch == $i->id) selected @endif>
                                        {{$i->branch_name}}
                                    </option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-400">
                                <i class="ri-arrow-down-s-line text-xl"></i>
                            </div>
                        </div>

                        @if ($errors->has('branch'))
                            <p class="text-red-500 text-xs font-bold mt-1 flex items-center gap-1 italic">
                                <i class="ri-error-warning-line"></i> {{ $errors->first('branch') }}
                            </p>
                        @endif
                    </div>
                </div>

                <div class="pt-6 border-t border-gray-100 flex flex-col md:flex-row gap-4">
                    <button type="submit" 
                            class="flex-1 inline-flex items-center justify-center gap-2 px-8 py-4 bg-emerald-600 text-white rounded-2xl font-black text-lg hover:bg-emerald-700 hover:scale-[1.02] active:scale-95 transition-all shadow-xl shadow-emerald-100 uppercase italic">
                        <i class="ri-refresh-line text-xl"></i>
                        Update Subject Group
                    </button>
                    <a href="{{ url('subjectgroup') }}" 
                       class="inline-flex items-center justify-center px-8 py-4 bg-gray-100 text-gray-500 rounded-2xl font-black hover:bg-gray-200 transition-all uppercase italic text-sm">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection