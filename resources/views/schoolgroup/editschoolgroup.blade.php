@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10 font-sarabun text-slate-800">
    
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ url('schoolgroup') }}" 
           class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-white text-emerald-600 border border-gray-200 hover:bg-emerald-600 hover:text-white transition-all shadow-sm group">
            <i class="ri-arrow-left-s-line text-2xl group-hover:-translate-x-1 transition-transform"></i>
        </a>
        <div>
            <h1 class="text-2xl font-black text-gray-800 leading-none mb-1 uppercase italic text-emerald-700">Edit Schoolgroup / แก้ไขเครือข่าย</h1>
            <p class="text-sm text-gray-400 font-bold tracking-widest uppercase italic">Organizational Structure</p>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-xl shadow-slate-100 border border-gray-100 overflow-hidden">
        <div class="bg-emerald-600 px-8 py-6 text-white flex items-center gap-3">
            <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                <i class="ri-node-tree text-2xl"></i>
            </div>
            <div>
                <h3 class="text-lg font-black leading-none text-white">ปรับปรุงข้อมูลเครือข่าย</h3>
                <p class="text-emerald-100 text-xs mt-1 opacity-80 uppercase tracking-tighter italic">Update schoolgroup organization details</p>
            </div>
        </div>

        <div class="p-8 md:p-12">
            @if(Session::has('massage'))
                <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 flex items-center gap-3 rounded-xl shadow-sm animate-fade-in">
                    <i class="ri-checkbox-circle-fill text-xl"></i>
                    <span class="font-bold">{{ Session::get('massage') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ Route('schoolgroup.update', $schoolgroup->id) }}" class="space-y-8">
                {{ csrf_field() }}
                <input type="hidden" name="_method" value="put">

                <div class="grid grid-cols-1 gap-6">
                    <div class="space-y-2">
                        <label for="schoolgroup_name" class="block text-sm font-black text-gray-700 uppercase tracking-wider italic">
                            <i class="ri-community-line text-emerald-500 mr-1"></i> ชื่อเครือข่ายสถานศึกษา
                        </label>
                        <input id="schoolgroup_name" type="text" 
                               class="block w-full px-4 py-4 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-bold text-gray-700 shadow-inner" 
                               name="schoolgroup_name" value="{{ $schoolgroup->schoolgroup_name }}" required placeholder="ระบุชื่อเครือข่าย...">
                        
                        @if ($errors->has('schoolgroup_name'))
                            <p class="text-red-500 text-xs font-bold mt-1 flex items-center gap-1">
                                <i class="ri-error-warning-line"></i> {{ $errors->first('schoolgroup_name') }}
                            </p>
                        @endif
                    </div>

                    <div class="space-y-2">
                        <label for="schoolgroup_detail" class="block text-sm font-black text-gray-700 uppercase tracking-wider italic">
                            <i class="ri-information-line text-emerald-500 mr-1"></i> รายละเอียดเครือข่าย
                        </label>
                        <textarea id="schoolgroup_detail" name="schoolgroup_detail" rows="4"
                                  class="block w-full px-4 py-4 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-bold text-gray-700 shadow-inner"
                                  placeholder="ระบุคำอธิบายเพิ่มเติม...">{{ $schoolgroup->schoolgroup_detail }}</textarea>
                        
                        @if ($errors->has('schoolgroup_detail'))
                            <p class="text-red-500 text-xs font-bold mt-1 flex items-center gap-1">
                                <i class="ri-error-warning-line"></i> {{ $errors->first('schoolgroup_detail') }}
                            </p>
                        @endif
                    </div>
                </div>

                <div class="pt-6 border-t border-gray-100 flex flex-col md:flex-row gap-4">
                    <button type="submit" 
                            class="flex-1 inline-flex items-center justify-center gap-2 px-8 py-4 bg-emerald-600 text-white rounded-2xl font-black text-lg hover:bg-emerald-700 hover:scale-[1.02] active:scale-95 transition-all shadow-xl shadow-emerald-100">
                        <i class="ri-save-3-line text-xl"></i>
                        UPDATE / บันทึกการแก้ไข
                    </button>
                    <a href="{{ url('schoolgroup') }}" 
                       class="inline-flex items-center justify-center px-8 py-4 bg-gray-100 text-gray-500 rounded-2xl font-black hover:bg-gray-200 transition-all uppercase italic">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection