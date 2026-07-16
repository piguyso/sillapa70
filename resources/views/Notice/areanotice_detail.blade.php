@extends('layouts.apparea')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10 font-sarabun text-slate-800">

    <div class="relative bg-gradient-to-r from-amber-800 to-orange-600 rounded-3xl shadow-xl p-8 md:p-12 mb-10 text-white overflow-hidden border-b-8 border-orange-500 text-white">
        <div class="relative z-10 text-white">
            <h2 class="text-lg md:text-xl font-bold opacity-90 italic mb-2 text-white leading-none">
                @guest
                    {{ session('app_name')}} ระดับเครือข่ายสถานศึกษา {{session()->get('schoolgroup')}}
                @else
                    {{ session('app_name')}} ระดับเครือข่ายสถานศึกษา
                @endguest
                ปีการศึกษา {{ session('app_year') }}
            </h2>
            <h1 class="text-3xl md:text-5xl font-black tracking-tight uppercase italic text-white leading-none">
                Announcement Hub
            </h1>
            <p class="mt-4 text-orange-100 font-bold italic uppercase tracking-widest text-sm text-white">
                <i class="ri-broadcast-line mr-1"></i> ติดตามข่าวสารและประกาศล่าสุด
            </p>
        </div>
        <i class="ri-notification-badge-line absolute -right-10 -bottom-10 text-[15rem] opacity-10 rotate-12 text-white"></i>
    </div>

    @forelse($notice as $l)
    <div class="mb-12 animate-fade-in text-slate-800">
        <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden text-slate-800">
            <div class="bg-slate-50 px-8 py-6 border-b border-slate-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 text-slate-800">
                <div class="flex items-center gap-4 text-slate-800">
                    <a href="{{url('area/'.session('area'))}}" class="w-10 h-10 flex items-center justify-center bg-white text-orange-600 rounded-xl border border-slate-200 hover:bg-orange-600 hover:text-white transition-all shadow-sm group">
                        <i class="ri-arrow-left-s-line text-2xl group-hover:-translate-x-1 transition-transform"></i>
                    </a>
                    <h3 class="text-lg md:text-xl font-black text-slate-800 uppercase italic tracking-tight leading-tight text-slate-800">
                        {{$l->notice_text}}
                    </h3>
                </div>
                <span class="px-4 py-1.5 bg-orange-100 text-orange-700 rounded-full text-[10px] font-black uppercase italic shadow-sm">
                    Updated: {{Datethai6($l->updated_at)}}
                </span>
            </div>

            <div class="p-8 md:p-12 text-slate-800">
                <div class="prose prose-slate max-w-none italic text-slate-700 font-medium leading-relaxed mb-8 text-slate-800">
                    {!! 
                        preg_replace(
                            '/(https?:\/\/[^\s<]+)/', 
                            '<a href="$1" target="_blank" rel="noopener noreferrer" class="text-orange-600 font-black underline decoration-orange-200 hover:decoration-orange-500 transition-all">$1</a>', 
                            $l->notice_detail
                        ) 
                    !!}
                </div>

                <hr class="border-slate-100 mb-8">

                @php
                    $fileName = $l->notice_file ?? null;
                    $filePath = $fileName ? asset('upload/' . $fileName) : null;
                    $fileExtension = $fileName ? strtolower(pathinfo($fileName, PATHINFO_EXTENSION)) : null;
                    $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                    $pdfExtensions = ['pdf'];
                    $isFileAvailable = $fileName && file_exists(public_path('upload/' . $fileName));
                @endphp

                @if ($isFileAvailable)
                    <div class="bg-slate-50 rounded-2xl p-6 border border-slate-100 text-slate-800">
                        <div class="flex items-center gap-2 mb-4 text-slate-800">
                            <i class="ri-attachment-2 text-orange-600 text-xl"></i>
                            <span class="text-xs font-black uppercase tracking-widest text-slate-400">Attached File: {{ $fileName }}</span>
                        </div>

                        {{-- 🖼️ รูปภาพ --}}
                        @if (in_array($fileExtension, $imageExtensions))
                            <div class="relative group">
                                <img src="{{ $filePath }}" class="rounded-xl border-4 border-white shadow-lg max-w-full h-auto mx-auto group-hover:opacity-95 transition-opacity">
                                <a href="{{ $filePath }}" target="_blank" class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity bg-black/20 rounded-xl">
                                    <span class="bg-white text-orange-600 px-4 py-2 rounded-lg font-black shadow-xl italic uppercase text-xs">View Full Image</span>
                                </a>
                            </div>

                        {{-- 📄 PDF --}}
                        @elseif (in_array($fileExtension, $pdfExtensions))
                            <div class="rounded-xl overflow-hidden border border-slate-200 shadow-inner bg-white">
                                <iframe src="{{ $filePath }}" width="100%" height="500px" class="border-none"></iframe>
                            </div>
                            <div class="mt-4 flex justify-end">
                                <a href="{{ $filePath }}" target="_blank" class="inline-flex items-center gap-2 px-6 py-3 bg-orange-600 text-white rounded-xl font-black text-sm hover:bg-orange-700 transition-all shadow-lg shadow-orange-100 uppercase italic text-white">
                                    <i class="ri-download-cloud-2-line text-white"></i> Download PDF
                                </a>
                            </div>

                        {{-- 🗄️ อื่นๆ --}}
                        @else
                            <div class="flex items-center justify-between p-4 bg-white rounded-xl border border-slate-200">
                                <div class="flex items-center gap-3">
                                    <i class="ri-file-unknow-line text-3xl text-slate-300"></i>
                                    <span class="font-bold text-slate-600 uppercase">{{ strtoupper($fileExtension) }} File</span>
                                </div>
                                <a href="{{ $filePath }}" target="_blank" class="px-4 py-2 bg-slate-800 text-white rounded-lg font-black text-xs hover:bg-orange-600 transition-all uppercase italic text-white">
                                    Download
                                </a>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="flex items-center gap-2 text-slate-300 italic text-sm">
                        <i class="ri-information-line"></i> ไม่มีไฟล์แนบสำหรับประกาศนี้
                    </div>
                @endif
            </div>
        </div>
    </div>
    @empty
        <div class="text-center py-20 bg-slate-50 rounded-3xl border border-dashed border-slate-200">
            <i class="ri-inbox-line text-5xl text-slate-200 mb-4 block"></i>
            <p class="text-slate-400 font-bold italic tracking-widest uppercase">No announcements at this time</p>
        </div>
    @endforelse
</div>
@endsection