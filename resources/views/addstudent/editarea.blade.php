@extends('layouts.apparea')

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

<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10 font-sarabun text-slate-800">

    <div class="relative bg-gradient-to-r from-amber-800 to-orange-600 rounded-3xl shadow-xl p-8 md:p-12 mb-8 text-white overflow-hidden border-b-8 border-orange-500 text-white">
        <div class="relative z-10">
            <h1 class="text-2xl md:text-4xl font-black tracking-tight uppercase italic text-white leading-none">แก้ไขข้อมูลรายบุคคล</h1>
        </div>
        <i class="ri-user-settings-line absolute -right-10 -bottom-10 text-[10rem] opacity-10 rotate-12 text-white"></i>
    </div>

    <div class="bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden">
        <div class="p-8 md:p-12">
            <form id="update-form" class="space-y-8 font-bold italic" method="POST" action="{{ Route('areaaddstudent.update',$addstudent->id) }}" enctype="multipart/form-data">
                {{ csrf_field() }}
                <input type="hidden" name="_method" value="put">
                <input type="hidden" name="school" value="{{$addschool[0]->id}}">
                <input type="hidden" name="image_base64" id="image_base64">

                <div class="flex flex-col items-center justify-center pb-8 border-b border-slate-50">
                    <div id="preview-container" class="relative">
                        <div class="preview-box">
                       {{-- บรรทัดนี้ในไฟล์เดิมของคุณ --}}
<img id="current-image" src="{{ $addstudent->student_image ? asset('picture/'.$addstudent->student_image) : 'https://ui-avatars.com/api/?name='.urlencode($addstudent->name).'&background=random' }}" class="w-full h-full object-cover"> </div>
                        <label for="upload" class="absolute -bottom-2 -right-2 w-12 h-12 bg-orange-600 text-white rounded-2xl flex items-center justify-center cursor-pointer hover:bg-slate-800 transition-all shadow-xl">
                            <i class="ri-camera-3-line text-2xl"></i>
                            <input type="file" id="upload" class="hidden" accept="image/*">
                        </label>
                    </div>

                    <div id="crop-modal-area" class="hidden fixed inset-0 z-[999] flex items-center justify-center p-4">
                        <div class="absolute inset-0 bg-slate-900/90 backdrop-blur-sm"></div>
                        <div class="relative bg-white rounded-3xl shadow-2xl max-w-lg w-full p-6">
                            <div id="upload-demo"></div>
                            <div class="flex gap-3 mt-6">
                                <button type="button" id="btn-cancel-crop" class="flex-1 py-3 bg-slate-100 text-slate-500 rounded-2xl font-black uppercase italic">ยกเลิก</button>
                                <button type="button" id="btn-crop" class="flex-[2] py-3 bg-orange-600 text-white rounded-2xl font-black shadow-lg uppercase italic">Apply & Save</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2 space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase italic">คำนำหน้า</label>
                        <select name="title" class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none">
                            @foreach($title as $l)
                                <option value="{{$l->title_id}}" @if($addstudent->title==$l->title_id) selected @endif>{{$l->title}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase italic">ชื่อ</label>
                        <input type="text" name="name" value="{{$addstudent->name}}" required class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase italic">นามสกุล</label>
                        <input type="text" name="lastname" value="{{ $addstudent->lastname }}" required class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl">
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-4 pt-6">
                    <button type="submit" class="flex-1 py-4 bg-orange-600 text-white rounded-2xl font-black text-base shadow-xl uppercase italic">อัปเดตข้อมูล</button>
                    <a href="{{ url('area/addaddstudent/'.$addschool[0]->id) }}" class="inline-flex items-center justify-center px-8 py-4 bg-slate-100 text-slate-500 rounded-2xl font-black uppercase italic">ยกเลิก</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // ใช้คำสั่งนี้เพื่อให้มั่นใจว่าโหลด Croppie เสร็จก่อนเริ่มทำงาน
    window.addEventListener('load', function() {
        var el = document.getElementById('upload-demo');
        var uploadInput = document.getElementById('upload');
        var imageBase64Input = document.getElementById('image_base64');
        var currentImage = document.getElementById('current-image');
        var modalArea = document.getElementById('crop-modal-area');
        
        // ตรวจสอบว่ามี Library หรือไม่
        if (typeof Croppie === 'undefined') {
            alert('ไม่สามารถโหลดไลบรารีตัดรูปภาพได้ โปรดตรวจสอบการเชื่อมต่ออินเทอร์เน็ต');
            return;
        }

        var vanilla = new Croppie(el, {
            viewport: { width: 250, height: 250, type: 'square' },
            boundary: { width: 300, height: 300 },
            showZoomer: true,
            enableExif: true
        });

        // เมื่อเลือกไฟล์
        uploadInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    modalArea.classList.remove('hidden');
                    vanilla.bind({
                        url: e.target.result
                    });
                }
                reader.readAsDataURL(this.files[0]);
            }
        });

        // กดยืนยันการตัดรูป
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

        // ยกเลิก
        document.getElementById('btn-cancel-crop').addEventListener('click', function() {
            modalArea.classList.add('hidden');
            uploadInput.value = '';
        });
    });
</script>
@endsection