@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10 font-sarabun text-slate-800">
    
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ url('class') }}" 
           class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-white text-emerald-600 border border-gray-200 hover:bg-emerald-600 hover:text-white transition-all shadow-sm group">
            <i class="ri-arrow-left-s-line text-2xl group-hover:-translate-x-1 transition-transform"></i>
        </a>
        <div>
            <h1 class="text-2xl font-black text-gray-800 leading-none mb-1 uppercase italic text-emerald-700">Edit Class / แก้ไขระดับชั้น</h1>
            <p class="text-sm text-gray-400 font-bold tracking-widest uppercase italic">Category Management</p>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-xl shadow-slate-100 border border-gray-100 overflow-hidden">
        <div class="bg-emerald-600 px-8 py-6 text-white flex items-center gap-3">
            <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                <i class="ri-book-read-line text-2xl"></i>
            </div>
            <div>
                <h3 class="text-lg font-black leading-none text-white">ปรับปรุงข้อมูลระดับชั้น</h3>
                <p class="text-emerald-100 text-xs mt-1 opacity-80 uppercase tracking-tighter italic">Update competition class details</p>
            </div>
        </div>

        <div class="p-8 md:p-12">
            @if(Session::has('massage'))
                <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 flex items-center gap-3 rounded-xl shadow-sm animate-fade-in text-sm">
                    <i class="ri-checkbox-circle-fill text-xl"></i>
                    <span class="font-bold">{{ Session::get('massage') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ Route('class.update', $class->id) }}" class="space-y-8">
                {{ csrf_field() }}
                <input type="hidden" name="_method" value="put">

                <div class="grid grid-cols-1 gap-6">
                    <div class="space-y-2">
                        <label for="class_name" class="block text-sm font-black text-gray-700 uppercase tracking-wider italic">
                            <i class="ri-graduation-cap-line text-emerald-500 mr-1"></i> ชื่อระดับชั้น
                        </label>
                        <input id="class_name" type="text" 
                               class="block w-full px-4 py-4 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-bold text-gray-700 shadow-inner" 
                               name="class_name" value="{{ $class->class_name }}" required placeholder="ระบุชื่อระดับชั้น...">
                        
                        @if ($errors->has('class_name'))
                            <p class="text-red-500 text-xs font-bold mt-1 flex items-center gap-1 italic">
                                <i class="ri-error-warning-line"></i> {{ $errors->first('class_name') }}
                            </p>
                        @endif
                    </div>

                    <div class="space-y-2">
                        <label for="class_detail" class="block text-sm font-black text-gray-700 uppercase tracking-wider italic">
                            <i class="ri-file-info-line text-emerald-500 mr-1"></i> รายละเอียดเพิ่มเติม
                        </label>
                        <textarea id="class_detail" name="class_detail" rows="4"
                                  class="block w-full px-4 py-4 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-bold text-gray-700 shadow-inner"
                                  placeholder="ระบุข้อมูลเพิ่มเติมเกี่ยวกับระดับชั้นนี้...">{{ $class->class_detail }}</textarea>
                        
                        @if ($errors->has('class_detail'))
                            <p class="text-red-500 text-xs font-bold mt-1 flex items-center gap-1 italic">
                                <i class="ri-error-warning-line"></i> {{ $errors->first('class_detail') }}
                            </p>
                        @endif
                    </div>
                </div>

                <div class="pt-6 border-t border-gray-100 flex flex-col md:flex-row gap-4">
                    <button type="submit" 
                            class="flex-1 inline-flex items-center justify-center gap-2 px-8 py-4 bg-emerald-600 text-white rounded-2xl font-black text-lg hover:bg-emerald-700 hover:scale-[1.02] active:scale-95 transition-all shadow-xl shadow-emerald-100 italic uppercase">
                        <i class="ri-save-3-line text-xl"></i>
                        Update Data
                    </button>
                    <a href="{{ url('class') }}" 
                       class="inline-flex items-center justify-center px-8 py-4 bg-gray-100 text-gray-500 rounded-2xl font-black hover:bg-gray-200 transition-all uppercase italic text-sm">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection