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
    
    @if(Session::has('massage'))
        <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 flex items-center gap-3 rounded-xl shadow-sm animate-fade-in">
            <i class="ri-checkbox-circle-fill text-xl"></i>
            <span class="font-bold">{{ Session::get('massage') }}</span>
        </div>
    @endif

    <div class="flex items-center gap-4 mb-8 text-white">
        <a href="../centerviewactivityschool/{{$addschool[0]->school_activity}}" 
           class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-white text-emerald-600 border border-gray-200 hover:bg-emerald-600 hover:text-white transition-all shadow-sm group">
            <i class="ri-arrow-left-s-line text-2xl group-hover:-translate-x-1 transition-transform"></i>
        </a>
        <div>
            <h1 class="text-2xl font-black text-gray-800 leading-none mb-1 uppercase italic text-emerald-700">Edit Profile / แก้ไขข้อมูล</h1>
            <p class="text-sm text-gray-400 font-bold tracking-widest uppercase italic">{{$addschool[0]->getschoolname->school_name}}</p>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-xl shadow-slate-100 border border-gray-100 overflow-hidden">
        <div class="bg-emerald-600 px-8 py-6 text-white flex items-center gap-3">
            <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                <i class="ri-user-settings-line text-2xl text-white"></i>
            </div>
            <div>
                <h3 class="text-lg font-black leading-none text-white">แบบฟอร์มแก้ไขข้อมูล</h3>
                <p class="text-emerald-100 text-xs mt-1 opacity-80 uppercase tracking-tighter text-white">Please ensure all information is correct</p>
            </div>
        </div>

        <div class="p-8 md:p-12">
            <form method="POST" action="{{ url('updatestudentname',$addstudent->id) }}" class="space-y-8 font-bold italic" enctype="multipart/form-data">
                {{ csrf_field() }}
                <input type="hidden" name="school" value="{{$addschool[0]->id}}">
                <input type="hidden" name="image_base64" id="image_base64">

                <div class="flex flex-col items-center justify-center pb-8 border-b border-slate-50">
                    <div id="preview-container" class="relative">
                        <div class="preview-box">
                            <img id="current-image" 
                                 src="{{ $addstudent->student_image ? asset('picture/'.$addstudent->student_image) : 'https://ui-avatars.com/api/?name='.urlencode($addstudent->name).'&background=random' }}" 
                                 class="w-full h-full object-cover">
                        </div>
                        <label for="upload" class="absolute -bottom-2 -right-2 w-12 h-12 bg-emerald-600 text-white rounded-2xl flex items-center justify-center cursor-pointer hover:bg-slate-800 transition-all shadow-xl">
                            <i class="ri-camera-3-line text-2xl text-white"></i>
                            <input type="file" id="upload" class="hidden" accept="image/*">
                        </label>
                    </div>

                    <div id="crop-modal-area" class="hidden fixed inset-0 z-[999] flex items-center justify-center p-4">
                        <div class="absolute inset-0 bg-slate-900/90 backdrop-blur-sm"></div>
                        <div class="relative bg-white rounded-3xl shadow-2xl max-w-lg w-full p-6 text-center">
                            <div id="upload-demo"></div>
                            <div class="flex gap-3 mt-6">
                                <button type="button" id="btn-cancel-crop" class="flex-1 py-3 bg-slate-100 text-slate-500 rounded-2xl font-black uppercase italic">ยกเลิก</button>
                                <button type="button" id="btn-crop" class="flex-[2] py-3 bg-emerald-600 text-white rounded-2xl font-black shadow-lg uppercase italic text-white">ใช้รูปนี้</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-2">
                    <label for="title" class="block text-sm font-black text-gray-700 uppercase tracking-wider italic">
                        <i class="ri-focus-3-line text-emerald-500 mr-1"></i> คำนำหน้า
                    </label>
                    <div class="relative">
                        <select class="block w-full px-4 py-4 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all appearance-none font-bold text-gray-700 shadow-inner outline-none" 
                                id="title" name="title">
                            @foreach($title as $l)
                                <option value="{{$l->title_id}}" @if($addstudent->title==$l->title_id) selected @endif>{{$l->title}}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-400">
                            <i class="ri-arrow-down-s-line text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="name" class="block text-sm font-black text-gray-700 uppercase tracking-wider italic">
                            <i class="ri-user-line text-emerald-500 mr-1"></i> ชื่อ
                        </label>
                        <input id="addstudent_name" type="text" 
                               class="block w-full px-4 py-4 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-bold text-gray-700 shadow-inner" 
                               name="name" value="{{$addstudent->name}}" placeholder="ระบุชื่อ">
                    </div>

                    <div class="space-y-2">
                        <label for="lastname" class="block text-sm font-black text-gray-700 uppercase tracking-wider italic">
                            <i class="ri-id-card-line text-emerald-500 mr-1"></i> นามสกุล
                        </label>
                        <input id="lastname" type="text" 
                               class="block w-full px-4 py-4 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-bold text-gray-700 shadow-inner" 
                               name="lastname" value="{{ $addstudent->lastname }}" placeholder="ระบุนามสกุล">
                    </div>
                </div>

                <div class="pt-6 border-t border-gray-100 flex flex-col md:flex-row gap-4">
                    <button type="submit" 
                            class="flex-1 inline-flex items-center justify-center gap-2 px-8 py-4 bg-emerald-600 text-white rounded-2xl font-black text-lg hover:bg-emerald-700 hover:scale-[1.02] active:scale-95 transition-all shadow-xl shadow-emerald-100 text-white">
                        <i class="ri-save-line text-xl text-white"></i>
                        UPDATE / บันทึกข้อมูล
                    </button>
                    <a href="../centerviewactivityschool/{{$addschool[0]->school_activity}}" 
                       class="inline-flex items-center justify-center px-8 py-4 bg-gray-100 text-gray-500 rounded-2xl font-black hover:bg-gray-200 transition-all">
                        CANCEL
                    </a>
                </div>
            </form>
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