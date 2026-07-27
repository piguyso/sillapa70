@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js"></script>

<style>
    .preview-box {
        @apply w-40 h-40 rounded-3xl overflow-hidden border-4 border-white shadow-xl bg-slate-100 transition-all duration-500;
    }
    #upload-demo { width: 100%; height: 350px; margin: 0 auto; }
    #crop-modal-area.hidden { display: none; }
</style>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10 font-sarabun text-slate-800">
    
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ url('addaddstudent/'.$addschool[0]->id) }}" 
           class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-white text-emerald-600 border border-gray-200 hover:bg-emerald-600 hover:text-white transition-all shadow-sm group">
            <i class="ri-arrow-left-s-line text-2xl group-hover:-translate-x-1 transition-transform"></i>
        </a>
        <div>
            <h1 class="text-2xl font-black text-gray-800 leading-none mb-1 uppercase italic text-emerald-700">Edit Participant / แก้ไขข้อมูลผู้ร่วมแข่งขัน</h1>
            <p class="text-sm text-gray-400 font-bold tracking-widest uppercase italic">{{ $addschool[0]->getschoolname->school_name }}</p>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-xl shadow-slate-100 border border-gray-100 overflow-hidden">
        <div class="bg-emerald-600 px-8 py-6 text-white flex items-center gap-3">
            <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                <i class="ri-user-settings-line text-2xl text-white"></i>
            </div>
            <div>
                <h3 class="text-lg font-black leading-none text-white">ปรับปรุงข้อมูลรายบุคคล</h3>
                <p class="text-emerald-100 text-[10px] mt-1 opacity-80 uppercase tracking-tighter italic">Modify student or teacher credentials</p>
            </div>
        </div>

        <div class="p-8 md:p-12">
            @if(Session::has('massage'))
                <div class="mb-8 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 flex items-center gap-3 rounded-xl shadow-sm font-bold text-sm italic">
                    <i class="ri-checkbox-circle-fill text-xl"></i> {{ Session::get('massage') }}
                </div>
            @endif

            <form method="POST" action="{{ Route('addstudent.update', $addstudent->id) }}" class="space-y-8 font-bold" enctype="multipart/form-data">
                {{ csrf_field() }}
                <input type="hidden" name="_method" value="put">
                <input type="hidden" name="school" value="{{$addschool[0]->id}}">
                <input type="hidden" name="image_base64" id="image_base64">

                <div class="flex flex-col items-center justify-center pb-8 border-b border-slate-50">
                    <div id="preview-container" class="relative">
                        <div class="preview-box">
                            <img id="current-image" 
                                 src="{{ $addstudent->student_image ? get_secure_file_url('picture', $addstudent->student_image, $addstudent->year) : 'https://ui-avatars.com/api/?name='.urlencode($addstudent->name).'&background=random' }}" 
                                 class="w-full h-full object-cover">
                        </div>
                        <label for="upload" class="absolute -bottom-2 -right-2 w-12 h-12 bg-emerald-600 text-white rounded-2xl flex items-center justify-center cursor-pointer hover:bg-slate-800 transition-all shadow-xl">
                            <i class="ri-camera-3-line text-2xl"></i>
                            <input type="file" id="upload" class="hidden" accept="image/*">
                        </label>
                    </div>

                    <div id="crop-modal-area" class="hidden fixed inset-0 z-[999] flex items-center justify-center p-4">
                        <div class="absolute inset-0 bg-slate-900/90 backdrop-blur-sm"></div>
                        <div class="relative bg-white rounded-3xl shadow-2xl max-w-lg w-full p-6 text-center">
                            <div id="upload-demo"></div>
                            <div class="flex gap-3 mt-6">
                                <button type="button" id="btn-cancel-crop" class="flex-1 py-3 bg-slate-100 text-slate-500 rounded-2xl font-black uppercase italic">ยกเลิก</button>
                                <button type="button" id="btn-crop" class="flex-[2] py-3 bg-emerald-600 text-white rounded-2xl font-black shadow-lg uppercase italic">ใช้รูปนี้</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] italic">คำนำหน้าชื่อ</label>
                        <select name="title" class="w-full px-4 py-4 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-bold text-gray-700 shadow-inner">
                            @foreach($title as $l)
                                <option value="{{$l->title_id}}" @if($addstudent->title == $l->title_id) selected @endif>{{$l->title}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-2 md:col-span-1">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] italic">ชื่อ</label>
                        <input type="text" name="name" value="{{ $addstudent->name }}" required
                               class="w-full px-4 py-4 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-bold text-gray-700 shadow-inner">
                    </div>

                    <div class="space-y-2 md:col-span-1">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] italic">นามสกุล</label>
                        <input type="text" name="lastname" value="{{ $addstudent->lastname }}" required
                               class="w-full px-4 py-4 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-bold text-gray-700 shadow-inner">
                    </div>
                </div>

                <div class="pt-8 border-t border-gray-100 flex flex-col md:flex-row gap-4">
                    <button type="submit" 
                            class="flex-1 inline-flex items-center justify-center gap-2 px-8 py-4 bg-emerald-600 text-white rounded-2xl font-black text-lg hover:bg-emerald-700 hover:scale-[1.02] active:scale-95 transition-all shadow-xl shadow-emerald-100 uppercase italic tracking-widest text-white">
                        <i class="ri-refresh-line text-xl text-white"></i> Update Participant
                    </button>
                    <a href="{{ url('addaddstudent/'.$addschool[0]->id) }}" 
                       class="inline-flex items-center justify-center px-8 py-4 bg-gray-100 text-gray-500 rounded-2xl font-black hover:bg-gray-200 transition-all uppercase italic text-sm tracking-widest">
                        Cancel
                    </a>
                </div>
            </form>
        </div>

        <div class="mt-8 p-6 bg-slate-50 rounded-3xl border border-slate-100 flex items-start gap-4 italic text-xs text-slate-500">
            <i class="ri-information-line text-blue-500 text-xl"></i>
            <div>
                <p class="font-bold text-slate-700 mb-1">ข้อควรระวัง:</p>
                <p>การแก้ไขชื่อ-นามสกุล และรูปภาพ จะมีผลโดยตรงต่อการพิมพ์เกียรติบัตรและบัตรประจำตัว โปรดตรวจสอบความถูกต้องก่อนกดบันทึก</p>
            </div>
        </div>
    </div>
</div>

<script>
    window.addEventListener('load', function() {
        var el = document.getElementById('upload-demo');
        var uploadInput = document.getElementById('upload');
        var imageBase64Input = document.getElementById('image_base64');
        var currentImage = document.getElementById('current-image');
        var modalArea = document.getElementById('crop-modal-area');

        if (typeof Croppie === 'undefined') return;

        var vanilla = new Croppie(el, {
            viewport: { width: 250, height: 250, type: 'square' },
            boundary: { width: 300, height: 300 },
            showZoomer: true,
            enableExif: true
        });

        uploadInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    modalArea.classList.remove('hidden');
                    vanilla.bind({ url: e.target.result });
                }
                reader.readAsDataURL(this.files[0]);
            }
        });

        document.getElementById('btn-crop').addEventListener('click', function() {
            vanilla.result({
                type: 'base64',
                size: { width: 500, height: 500 },
                format: 'png'
            }).then(function(base64) {
                currentImage.src = base64;
                imageBase64Input.value = base64;
                modalArea.classList.add('hidden');
            });
        });

        document.getElementById('btn-cancel-crop').addEventListener('click', function() {
            modalArea.classList.add('hidden');
            uploadInput.value = '';
        });
    });
</script>
@endsection