@extends('layouts.app')

@section('content')
<script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>
<style>
    /* ปรับแต่งความสูงของ Editor */
    .ck-editor__editable {
        min-height: 200px;
    }
    .ck.ck-editor__main>.ck-editor__editable:not(.ck-focused) {
        border-color: #e5e7eb;
    }
</style>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 text-slate-800">

    <div class="bg-gradient-to-r from-primary to-emerald-600 rounded-2xl shadow-lg p-8 mb-8 text-white">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ url('/') }}" class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center hover:bg-white/40 transition-all border border-white/30 shadow-sm">
                    <i class="ri-arrow-left-line text-2xl text-white"></i>
                </a>
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold leading-tight uppercase tracking-tight">จัดการประกาศและข่าวสาร</h1>
                    <p class="text-green-50 text-lg opacity-90 font-medium italic">จัดการข้อมูลข่าวสารและไฟล์แนบสำหรับสมาชิก</p>
                </div>
            </div>
        </div>
    </div>

    @if(Session::has('massage'))
        <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 flex items-center gap-3 rounded-r-lg shadow-sm">
            <i class="ri-checkbox-circle-fill text-xl"></i>
            <span class="font-bold">{{ Session::get('massage') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2 order-2 lg:order-1">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-gray-50 px-8 py-5 border-b border-gray-100 flex items-center justify-between">
                    <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2 uppercase tracking-wide">
                        <i class="ri-notification-3-line text-primary"></i> รายการประกาศทั้งหมด
                    </h2>
                </div>
                <div class="p-0 overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/50 text-gray-400 text-[10px] uppercase tracking-widest font-black border-b border-gray-100">
                                <th class="px-6 py-4 w-32">วันที่ประกาศ</th>
                                <th class="px-6 py-4">หัวข้อและรายละเอียด</th>
                                <th class="px-6 py-4 w-40">ไฟล์แนบ</th>
                                <th class="px-6 py-4 text-center w-24">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 text-sm">
                            @forelse($shownotice as $l)
                            <tr class="hover:bg-gray-50/50 transition-colors group">
                                <td class="px-6 py-6 align-top">
                                    <div class="flex items-center gap-1 text-gray-400 font-bold text-xs">
                                        <i class="ri-time-line"></i> {{Datethai6($l->updated_at)}}
                                    </div>
                                </td>
                                <td class="px-6 py-6 align-top">
                                    <div class="font-black text-gray-800 text-base mb-2 group-hover:text-primary transition-colors italic">
                                        {{$l->notice_text}}
                                    </div>
                                    <div class="text-gray-500 text-xs leading-relaxed bg-gray-50 p-3 rounded-xl border border-gray-100 ck-content">
                                        {!! $l->notice_detail !!}
                                    </div>
                                </td>
                                <td class="px-6 py-6 align-top">
                                    @if($l->notice_file)
                                        <a href="upload/{{$l->notice_file}}" target="_blank" class="inline-flex items-center gap-2 px-3 py-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-600 hover:text-white transition-all text-[10px] font-bold border border-blue-100 break-all">
                                            <i class="ri-file-download-line text-sm"></i>
                                            {{$l->notice_file}}
                                        </a>
                                    @else
                                        <span class="text-gray-300 italic text-xs">ไม่มีไฟล์</span>
                                    @endif
                                </td>
                                <td class="px-6 py-6 align-top text-center">
                                    <form id="delform{{$l->id}}" method="post" action="{{ route('Notice.destroy',$l->id)}}" class="inline">
                                        <input type="hidden" name="_method" value="DELETE"/>
                                        {!!csrf_field()!!}
                                        <button type="button" onclick="DelConfirm({{$l->id}})" class="w-10 h-10 flex items-center justify-center bg-red-50 text-red-500 rounded-xl hover:bg-red-500 hover:text-white transition-all shadow-sm border border-red-100">
                                            <i class="ri-delete-bin-line text-lg"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-20 text-center">
                                    <div class="bg-gray-50 rounded-3xl p-10 border border-dashed border-gray-200 inline-block">
                                        <i class="ri-notification-off-line text-5xl text-gray-200 block mb-4"></i>
                                        <span class="text-gray-400 font-bold">ยังไม่มีรายการประกาศในขณะนี้</span>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="lg:col-span-1 order-1 lg:order-2">
            <div class="bg-white rounded-3xl shadow-lg border border-gray-100 overflow-hidden sticky top-8">
                <div class="bg-primary px-8 py-5 text-white">
                    <h2 class="text-lg font-bold flex items-center gap-2">
                        <i class="ri-add-circle-line"></i> เพิ่มประกาศใหม่
                    </h2>
                </div>
                <div class="p-8">
                    <form method="POST" action="{{ Route('Notice.store') }}" enctype="multipart/form-data" class="space-y-5">
                        {{ csrf_field() }}

                        <div>
                            <label for="notice_text" class="block text-sm font-bold text-gray-700 mb-2">หัวข้อประกาศ</label>
                            <input id="notice_text" type="text" name="notice_text" value="{{ old('notice_text') }}" 
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all font-bold"
                                placeholder="ระบุหัวข้อสั้นๆ">
                            @if ($errors->has('notice_text'))
                                <p class="mt-1 text-xs text-red-500 font-bold italic">{{ $errors->first('notice_text') }}</p>
                            @endif
                        </div>

                        <div>
                            <label for="notice_detail" class="block text-sm font-bold text-gray-700 mb-2">รายละเอียด</label>
                            <textarea id="notice_detail" name="notice_detail">{{ old('notice_detail') }}</textarea>
                            @if ($errors->has('notice_detail'))
                                <p class="mt-1 text-xs text-red-500 font-bold italic">{{ $errors->first('notice_detail') }}</p>
                            @endif
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">ไฟล์แนบ (PDF หรือ รูปภาพ)</label>
                            <div class="relative group">
                                <input type="file" name="notice_file" id="chooseFile" 
                                    accept="image/*,.pdf"
                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                    onchange="previewFile()">
                                <div id="uploadPlaceholder" class="bg-gray-50 border-2 border-dashed border-gray-200 rounded-xl py-6 px-4 text-center group-hover:border-primary transition-colors">
                                    <i class="ri-upload-cloud-2-line text-3xl text-gray-300 group-hover:text-primary mb-2 block"></i>
                                    <span class="text-xs text-gray-400 font-bold">คลิกเลือกไฟล์</span>
                                </div>
                            </div>

                            <div id="previewContainer" class="hidden mt-4 p-4 bg-gray-50 rounded-2xl border border-gray-200 relative animate-in fade-in zoom-in duration-300">
                                <button type="button" onclick="clearFile()" class="absolute -top-2 -right-2 w-8 h-8 bg-red-500 text-white rounded-full shadow-lg flex items-center justify-center hover:bg-red-600 z-20">
                                    <i class="ri-close-line"></i>
                                </button>
                                <div class="flex flex-col items-center">
                                    <img id="imagePreview" src="#" alt="Preview" class="hidden max-h-48 rounded-lg shadow-sm mb-2 object-contain">
                                    <div id="fileInfo" class="hidden flex items-center gap-3 w-full">
                                        <div class="w-12 h-12 bg-primary/10 text-primary rounded-xl flex items-center justify-center text-2xl">
                                            <i class="ri-file-text-line"></i>
                                        </div>
                                        <div class="overflow-hidden">
                                            <p id="fileName" class="text-sm font-bold text-gray-800 truncate"></p>
                                            <p id="fileSize" class="text-[10px] text-gray-400 font-bold"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if ($errors->has('file'))
                                <p class="mt-1 text-xs text-red-500 font-bold italic">{{ $errors->first('file') }}</p>
                            @endif
                        </div>

                        <div class="pt-4 text-center">
                            <button type="submit" class="w-full bg-primary text-white font-black py-4 rounded-2xl hover:bg-emerald-700 shadow-lg shadow-emerald-200 transition-all flex items-center justify-center gap-2 uppercase tracking-widest text-sm">
                                บันทึกข้อมูลประกาศ
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // เริ่มต้นใช้งาน Rich Text Editor (CKEditor 5)
    ClassicEditor
        .create(document.querySelector('#notice_detail'), {
            toolbar: ['bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'undo', 'redo'],
        })
        .catch(error => {
            console.error(error);
        });

    function previewFile() {
        const file = document.getElementById('chooseFile').files[0];
        const previewContainer = document.getElementById('previewContainer');
        const imagePreview = document.getElementById('imagePreview');
        const fileInfo = document.getElementById('fileInfo');
        const fileName = document.getElementById('fileName');
        const fileSize = document.getElementById('fileSize');
        const placeholder = document.getElementById('uploadPlaceholder');

        if (file) {
            previewContainer.classList.remove('hidden');
            placeholder.classList.add('bg-emerald-50', 'border-primary');
            fileName.textContent = file.name;
            fileSize.textContent = (file.size / (1024 * 1024)).toFixed(2) + ' MB';

            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreview.classList.remove('hidden');
                    fileInfo.classList.add('hidden');
                }
                reader.readAsDataURL(file);
            } else {
                imagePreview.classList.add('hidden');
                fileInfo.classList.remove('hidden');
            }
        }
    }

    function clearFile() {
        const input = document.getElementById('chooseFile');
        const previewContainer = document.getElementById('previewContainer');
        const placeholder = document.getElementById('uploadPlaceholder');
        input.value = '';
        previewContainer.classList.add('hidden');
        placeholder.classList.remove('bg-emerald-50', 'border-primary');
    }

    function DelConfirm(id) {
        if (confirm('คุณต้องการลบประกาศนี้ใช่หรือไม่?')) {
            document.getElementById('delform' + id).submit();
        }
    }
</script>
@endsection