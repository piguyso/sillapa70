@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
<style>
    #loading-overlay { @apply fixed inset-0 z-[9999] flex items-center justify-center bg-white/80 backdrop-blur-sm transition-opacity duration-500; }
    .spinner { @apply w-16 h-16 border-4 border-emerald-200 border-t-emerald-600 rounded-full animate-spin; }
    .ql-toolbar.ql-snow {
        @apply border-none bg-gray-50 border-b border-gray-200 p-2 !important;
    }
    .ql-container.ql-snow {
        @apply border-none !important;
        font-family: 'Sarabun', sans-serif !important;
    }
</style>

<div id="loading-overlay">
    <div class="text-center">
        <div class="spinner mb-4"></div>
        <p class="text-emerald-700 font-bold animate-pulse text-lg">กำลังเตรียมข้อมูลการแก้ไข...</p>
    </div>
</div>

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10 font-sarabun text-slate-800">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ url('Notice') }}" class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-white text-emerald-600 border border-gray-200 hover:bg-emerald-600 hover:text-white transition-all shadow-sm group">
            <i class="ri-arrow-left-s-line text-2xl group-hover:-translate-x-1 transition-transform"></i>
        </a>
        <div>
            <h1 class="text-2xl font-black text-gray-800 leading-none mb-1 uppercase italic text-emerald-700">Update Notice / แก้ไขประกาศ</h1>
            <p class="text-sm text-gray-400 font-bold tracking-widest uppercase italic">Notice & Media Management</p>
        </div>
    </div>

    @forelse($notice as $l)
    <div class="bg-white rounded-3xl shadow-xl shadow-slate-100 border border-gray-100 overflow-hidden">
        <div class="bg-emerald-600 px-8 py-6 text-white flex items-center gap-3">
            <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                <i class="ri-file-edit-line text-2xl"></i>
            </div>
            <div>
                <h3 class="text-lg font-black leading-none text-white">จัดการเนื้อหาและไฟล์แนบ</h3>
                <p class="text-emerald-100 text-xs mt-1 opacity-80 uppercase tracking-tighter italic">Modify content and attachment</p>
            </div>
        </div>

        <div class="p-8 md:p-12">
            <form method="POST" action="{{ route('Notice.update', $l->id) }}" enctype="multipart/form-data" class="space-y-8">
                {{ csrf_field() }}
                <input type="hidden" name="_method" value="PATCH"> <div class="grid grid-cols-1 gap-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-sm font-black text-gray-700 uppercase tracking-wider italic">หัวข้อประกาศ</label>
                            <input type="text" name="notice_text" value="{{ $l->notice_text }}" required class="block w-full px-4 py-4 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-bold text-gray-700 shadow-inner">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-black text-gray-700 uppercase tracking-wider italic">ชนิดของประกาศ</label>
                            <select name="notice_type" class="block w-full px-4 py-4 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-bold text-gray-700 shadow-inner">
                                <option value="1" @if($l->notice_type==1) selected @endif>📢 ประกาศทั่วไป (General)</option>
                                <option value="2" @if($l->notice_type==2) selected @endif>📂 รายการเอกสาร (Document)</option>
                            </select>
                        </div>
                    </div>

                    <div class="bg-slate-50 p-6 md:p-8 rounded-3xl border border-dashed border-emerald-200 space-y-4">
                        <label class="block text-sm font-black text-emerald-600 uppercase tracking-widest italic mb-2">
                            <i class="ri-attachment-2 mr-1"></i> Attachment / ไฟล์แนบ
                        </label>
                        <div class="flex flex-col md:flex-row gap-8 items-center">
                            <div class="w-full md:w-1/3 text-center space-y-3">
                                <p class="text-[10px] font-black text-gray-400 uppercase italic">Preview / ตัวอย่าง:</p>
                                <div class="aspect-video w-full rounded-2xl overflow-hidden shadow-lg border-4 border-white bg-gray-100 flex items-center justify-center relative">
                                    @php
                                        $filename = $l->notice_file ?? '';
                                        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                                        $filePath = !empty($filename) ? url('/upload/'.$filename) : '';
                                        $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                    @endphp
                                    <img id="img_preview" src="{{ $isImage ? $filePath : '' }}" class="w-full h-full object-cover @if(!$isImage) hidden @endif">
                                    <div id="icon_preview" class="flex flex-col items-center @if($isImage || empty($filename)) hidden @endif">
                                        <i class="ri-file-pdf-2-fill text-5xl text-red-500"></i>
                                        <span class="text-[10px] font-bold text-gray-500 mt-2 truncate max-w-[150px]">{{ $filename }}</span>
                                    </div>
                                    <i id="placeholder_icon" class="ri-image-add-line text-4xl text-gray-300 @if(!empty($filename)) hidden @endif"></i>
                                    <div id="new_file_badge" class="absolute top-2 right-2 bg-amber-500 text-white text-[10px] px-2 py-1 rounded-lg font-black uppercase shadow-md hidden animate-bounce">New File</div>
                                </div>
                            </div>
                            <div class="w-full md:w-2/3 space-y-2">
                                <p class="text-[10px] font-black text-emerald-500 uppercase italic">อัปโหลดไฟล์ใหม่ (JPG, PNG, PDF):</p>
                                <div class="relative group cursor-pointer">
                                    <input type="file" name="notice_file" id="notice_file_input" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20" onchange="previewFile(this)">
                                    <div class="p-10 border-2 border-dashed border-emerald-300 rounded-3xl bg-white group-hover:bg-emerald-50 group-hover:border-emerald-500 transition-all text-center">
                                        <i class="ri-upload-cloud-2-line text-4xl text-emerald-500 group-hover:scale-110 transition-transform block"></i>
                                        <p class="text-sm font-black text-emerald-700 mt-3">คลิกเพื่อเปลี่ยนไฟล์แนบ</p>
                                        <p id="file_name_display" class="text-[10px] text-gray-400 mt-2 italic">เลือกไฟล์เพื่อแทนที่ไฟล์เดิม</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-black text-gray-700 uppercase tracking-wider italic">รายละเอียดประกาศ</label>
                        <input type="hidden" name="notice_detail" id="notice_detail" value="{{ $l->notice_detail }}">
                        <div class="rounded-2xl overflow-hidden shadow-inner bg-white border border-gray-200">
                            <div id="quill_editor" style="min-height: 300px; font-family: 'Sarabun', sans-serif;">
                                {!! $l->notice_detail !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-8 border-t border-gray-100 flex flex-col md:flex-row gap-4">
                    <button type="submit" class="flex-1 bg-emerald-600 text-white rounded-2xl font-black py-4 hover:bg-emerald-700 transition-all shadow-xl shadow-emerald-100 uppercase tracking-widest italic">
                        Update Notice
                    </button>
                    <a href="{{ url('Notice') }}" class="flex-1 bg-gray-100 text-gray-500 rounded-2xl font-black py-4 text-center hover:bg-gray-200 transition-all uppercase italic tracking-widest text-sm">Cancel</a>
                </div>
            </form>
        </div>
    </div>
    @empty
    <p>ไม่พบข้อมูล</p>
    @endforelse
</div>

<script>
    // Initialize Quill Editor
    const quill = new Quill('#quill_editor', {
        theme: 'snow',
        placeholder: 'เขียนรายละเอียดประกาศตรงนี้...',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'color': [] }, { 'background': [] }],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                ['link', 'clean']
            ]
        }
    });

    // Remove loading overlay immediately
    const overlay = document.getElementById('loading-overlay');
    if (overlay) {
        overlay.classList.add('opacity-0');
        setTimeout(() => overlay.remove(), 500);
    }

    // Sync Quill content to hidden input on submit
    document.querySelectorAll('form').forEach(form => {
        const hiddenInput = form.querySelector('#notice_detail');
        const editorDiv = form.querySelector('#quill_editor');
        if (hiddenInput && editorDiv) {
            form.addEventListener('submit', function() {
                hiddenInput.value = quill.root.innerHTML;
            });
        }
    });
</script>

    function previewFile(input) {
        const file = input.files[0];
        const imgPreview = document.getElementById('img_preview');
        const iconPreview = document.getElementById('icon_preview');
        const placeholderIcon = document.getElementById('placeholder_icon');
        const fileNameDisplay = document.getElementById('file_name_display');
        const newBadge = document.getElementById('new_file_badge');

        if (file) {
            fileNameDisplay.innerText = "เตรียมอัปโหลด: " + file.name;
            newBadge.classList.remove('hidden');
            if (file.type.match('image.*')) {
                const reader = new FileReader();
                reader.onload = (e) => { 
                    imgPreview.src = e.target.result; imgPreview.classList.remove('hidden');
                    iconPreview.classList.add('hidden'); placeholderIcon.classList.add('hidden'); 
                };
                reader.readAsDataURL(file);
            } else if (file.type === 'application/pdf') {
                imgPreview.classList.add('hidden'); placeholderIcon.classList.add('hidden');
                iconPreview.classList.remove('hidden'); iconPreview.querySelector('span').innerText = file.name;
            }
        }
    }
</script>
@endsection