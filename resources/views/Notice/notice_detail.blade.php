@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="bg-gradient-to-r from-primary to-emerald-500 rounded-2xl shadow-lg p-8 mb-8 text-white">
        <h2 class="text-2xl md:text-3xl font-bold flex items-center gap-3">
            <i class="ri-notification-3-line"></i>
            @guest
                {{ session('app_name') }} ระดับเครือข่ายสถานศึกษา {{ session()->get('schoolgroup') }} {{ 'ปีการศึกษา '. session('app_year') }}
            @else
                {{ session('app_name') }} ระดับเครือข่ายสถานศึกษา {{ 'ปีการศึกษา '. session('app_year') }}
            @endif
        </h2>
    </div>

    @forelse($notice as $l)
    <div class="mb-8 group">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden transition-all duration-300 hover:shadow-xl">
            
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex items-center gap-4">
                <a href="../" class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white text-primary border border-gray-200 hover:bg-primary hover:text-white transition-colors shadow-sm">
                    <i class="ri-arrow-left-line text-xl"></i>
                </a>
                <h3 class="text-lg md:text-xl font-bold text-gray-800 leading-tight">
                    {{$l->notice_text}}
                </h3>
            </div>

            <div class="p-6 md:p-8">
                
                <div class="prose prose-slate max-w-none text-gray-700 mb-8">
                    <div class="bg-gray-50/50 p-6 rounded-xl border border-dashed border-gray-200 leading-relaxed">
                        {!! 
                            preg_replace(
                                '/(https?:\/\/[^\s<]+)/', 
                                '<a href="$1" target="_blank" rel="noopener noreferrer" class="text-blue-600 font-medium underline underline-offset-4 hover:text-blue-800 break-all transition-colors">$1</a>', 
                                $l->notice_detail
                            ) 
                        !!}
                    </div>
                </div>

                @if (!empty($l->files))
                <div class="mt-8 pt-8 border-t border-gray-100 space-y-8">
                    @foreach($l->files as $fileName)
                        @php
                            $filePath = get_secure_file_url('upload', $fileName, $l->year);
                            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                            $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                            $pdfExtensions = ['pdf'];
                            $isFileAvailable = storage_file_exists('upload', $fileName, $l->year);
                        @endphp

                        @if ($isFileAvailable)
                            {{-- 🖼️ กรณีที่ 1: ไฟล์เป็นรูปภาพ --}}
                            @if (in_array($fileExtension, $imageExtensions))
                                <div class="space-y-4">
                                    <div class="flex items-center gap-2 text-primary font-bold">
                                        <i class="ri-image-line text-2xl"></i>
                                        <span>ไฟล์รูปภาพประกอบ: {{ $fileName }}</span>
                                    </div>
                                    <div class="relative group cursor-pointer overflow-hidden rounded-xl border-4 border-gray-50 shadow-inner">
                                        <img 
                                            src="{{ $filePath }}" 
                                            alt="รูปภาพประกอบประกาศ" 
                                            class="w-full h-auto transition-transform duration-500 group-hover:scale-[1.02]"
                                        >
                                    </div>
                                    <div class="flex justify-end">
                                        <a href="{{ $filePath }}" target="_blank" class="text-sm font-medium text-secondary hover:underline flex items-center gap-1">
                                            <i class="ri-zoom-in-line"></i> คลิกเพื่อดูรูปภาพขนาดเต็ม
                                        </a>
                                    </div>
                                </div>

                            {{-- 📄 กรณีที่ 2: ไฟล์เป็น PDF --}}
                            @elseif (in_array($fileExtension, $pdfExtensions))
                                <div class="space-y-4">
                                    <div class="flex items-center gap-2 text-primary font-bold">
                                        <i class="ri-file-pdf-2-line text-2xl text-red-500"></i>
                                        <span>ไฟล์เอกสาร PDF: {{ $fileName }}</span>
                                    </div>
                                    
                                    <div class="rounded-xl overflow-hidden border border-gray-200 shadow-inner h-[600px] w-full bg-gray-100">
                                        <iframe
                                            src="{{ $filePath }}"
                                            class="w-full h-full"
                                            frameborder="0"
                                            title="ไฟล์เอกสาร PDF"
                                        >
                                            <p>เบราว์เซอร์ของคุณไม่สามารถแสดงไฟล์ PDF ได้</p>
                                        </iframe>
                                    </div>
                                    
                                    <div class="flex justify-center">
                                        <a href="{{ $filePath }}" target="_blank" class="inline-flex items-center gap-2 px-6 py-3 bg-primary text-white font-bold rounded-full shadow-lg shadow-emerald-200 hover:bg-emerald-700 transition transform hover:-translate-y-1">
                                            <i class="ri-download-2-line"></i> ดาวน์โหลด/เปิดไฟล์ PDF
                                        </a>
                                    </div>
                                </div>

                            {{-- 🗄️ กรณีที่ 3: ไฟล์ประเภทอื่น --}}
                            @else
                                <div class="bg-blue-50 border border-blue-100 rounded-xl p-6 flex flex-col md:flex-row items-center justify-between gap-4">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 bg-white rounded-lg flex items-center justify-center text-blue-500 shadow-sm">
                                            <i class="ri-file-line text-2xl"></i>
                                        </div>
                                        <div>
                                            <div class="font-bold text-blue-900">{{ $fileName }}</div>
                                            <div class="text-sm text-blue-600">ประเภทไฟล์: {{ strtoupper($fileExtension ?? 'N/A') }}</div>
                                        </div>
                                    </div>
                                    <a href="{{ $filePath }}" target="_blank" class="w-full md:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition shadow-md shadow-blue-100">
                                        <i class="ri-download-cloud-line"></i> ดาวน์โหลดไฟล์
                                    </a>
                                </div>
                            @endif
                        @else
                            <div class="flex items-center gap-3 bg-amber-50 border border-amber-100 text-amber-800 p-4 rounded-xl">
                                <i class="ri-error-warning-line text-xl font-bold"></i>
                                <span class="text-sm font-medium">ไม่พบไฟล์แนบ: {{ $fileName }}</span>
                            </div>
                        @endif
                    @endforeach
                </div>

                @else
                {{-- ❌ กรณีที่ 4: ไม่มีไฟล์ --}}
                <div class="mt-8 flex items-center gap-3 bg-amber-50 border border-amber-100 text-amber-800 p-4 rounded-xl">
                    <i class="ri-error-warning-line text-xl"></i>
                    <span class="text-sm font-medium">ไม่พบไฟล์แนบสำหรับรายการนี้</span>
                </div>
                @endif

            </div> </div> </div>
    
    @empty
    <div class="text-center py-20">
        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gray-100 text-gray-400 mb-4">
            <i class="ri-inbox-line text-4xl"></i>
        </div>
        <h3 class="text-xl font-medium text-gray-500">ไม่มีข้อมูลประกาศ</h3>
    </div>
    @endforelse

</div>
@endsection