@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
<style>
    .ql-toolbar.ql-snow {
        border: none !important;
        border-bottom: 1px solid #e2e8f0 !important;
        background: #f8fafc !important;
        padding: 8px 12px !important;
    }
    .ql-container.ql-snow {
        border: none !important;
        font-family: 'Sarabun', sans-serif !important;
        font-size: 0.95rem !important;
        color: #374151 !important;
    }
</style>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 font-sarabun text-slate-800">

    <div class="bg-gradient-to-r from-slate-800 to-slate-900 rounded-3xl shadow-xl p-8 md:p-12 mb-8 text-white relative overflow-hidden">
        <div class="relative z-10">
            <h1 class="text-3xl md:text-5xl font-black mb-4 tracking-tight italic uppercase">
                System Configuration
            </h1>
            <p class="text-slate-300 text-lg md:text-xl opacity-90 font-medium italic">
                <i class="ri-settings-5-line mr-2"></i>ปรับแต่งข้อมูลพื้นฐานของระบบและรูปแบบเกียรติบัตร
            </p>
        </div>
        <i class="ri-terminal-box-line absolute -right-10 -bottom-10 text-[15rem] opacity-10 rotate-12"></i>
    </div>

    @forelse($config as $l)
    <form method="POST" enctype="multipart/form-data" action="{{ Route('config.update',$l->id) }}" class="space-y-8 animate-fade-in">
        {{ csrf_field() }}
        <input type="hidden" name="_method" value="put">
        <input type="hidden" name="cropped_logo" id="cropped_logo">
        <input type="hidden" name="cropped_qr" id="cropped_qr">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 space-y-6">
                    <h2 class="text-xl font-black text-gray-800 flex items-center gap-2 border-b border-gray-50 pb-4 italic uppercase">
                        <i class="ri-edit-2-line text-emerald-500"></i> General Settings
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2 flex items-center gap-6 p-4 bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                            <div class="relative">
                                <div class="w-24 h-24 bg-white rounded-xl border flex items-center justify-center overflow-hidden shadow-sm">
                                    <img id="img_preview_logo" src="{{ $l->app_logo ? url('/upload/'.$l->app_logo) : 'https://ui-avatars.com/api/?name=LOGO&background=random' }}" class="max-w-full max-h-full object-contain">
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-black text-gray-400 uppercase italic">System Logo (Crop)</label>
                                <input type="file" id="logo_input" accept="image/*" class="text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                                <p class="text-[10px] text-slate-400 italic">* แนะนำสัดส่วน 1:1 หรือวงกลม</p>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-black text-gray-400 uppercase tracking-widest italic">Application Name</label>
                            <input type="text" name="app_name" value="{{ $l->app_name }}" class="w-full px-4 py-3 rounded-2xl border border-gray-200 focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-bold text-gray-700 shadow-inner">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-black text-gray-400 uppercase tracking-widest italic">Application Group</label>
                            <input type="text" name="app_group" value="{{ $l->app_group }}" class="w-full px-4 py-3 rounded-2xl border border-gray-200 focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-bold text-gray-700 shadow-inner">
                        </div>
                        <div class="space-y-2 md:col-span-2">
                            <label class="block text-sm font-black text-gray-400 uppercase tracking-widest italic">Application Area / Location</label>
                            <input type="text" name="app_area" value="{{ $l->app_area }}" class="w-full px-4 py-3 rounded-2xl border border-gray-200 focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-bold text-gray-700 shadow-inner">
                        </div>
                        <div class="space-y-2 md:col-span-2">
                            <label class="block text-sm font-black text-gray-400 uppercase tracking-widest italic">Application Slogan / Motto (คำขวัญระบบ)</label>
                            <input type="hidden" name="app_slogan" id="app_slogan" value="{{ $l->app_slogan }}">
                            <div class="w-full rounded-2xl border border-gray-200 overflow-hidden shadow-inner bg-white">
                                <div id="quill_editor" style="min-height: 120px; font-family: 'Sarabun', sans-serif;">
                                    {!! $l->app_slogan !!}
                                </div>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-black text-gray-400 uppercase tracking-widest italic">Year (พ.ศ.)</label>
                            <div class="flex gap-2">
                                <select name="app_year" class="w-full px-4 py-3 rounded-2xl border border-gray-200 focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-bold text-gray-700 shadow-inner text-center">
                                    @foreach($years as $y)
                                        <option value="{{ $y->years }}" {{ $l->app_year == $y->years ? 'selected' : '' }}>{{ $y->years }}</option>
                                    @endforeach
                                </select>
                                <button type="button" onclick="openYearModal()" class="px-4 py-3 bg-emerald-50 hover:bg-emerald-100 text-emerald-600 rounded-2xl font-black text-xs border border-emerald-200 transition-all flex items-center gap-1 shadow-sm whitespace-nowrap">
                                    <i class="ri-calendar-event-line"></i> จัดการปี
                                </button>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-black text-gray-400 uppercase tracking-widest italic">Certificate Year</label>
                            <input type="text" name="cerf_year" value="{{ $l->app_year }}" class="w-full px-4 py-3 rounded-2xl border border-gray-200 focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-bold text-gray-700 shadow-inner text-center">
                        </div>
                    </div>
                </div>

                <!-- Links & Documents Settings Card -->
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 space-y-6">
                    <h2 class="text-xl font-black text-gray-800 flex items-center gap-2 border-b border-gray-50 pb-4 italic uppercase">
                        <i class="ri-links-line text-emerald-500"></i> Links & Documents Settings (ลิงก์และเอกสารสำคัญ)
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2 space-y-2">
                            <label class="block text-sm font-black text-gray-400 uppercase tracking-widest italic">เกณฑ์การแข่งขัน (Link)</label>
                            <input type="url" name="link_criteria" value="{{ $l->link_criteria }}" placeholder="https://..." class="w-full px-4 py-3 rounded-2xl border border-gray-200 focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-bold text-gray-700 shadow-inner">
                        </div>

                        <div class="md:col-span-2 space-y-2">
                            <label class="block text-sm font-black text-gray-400 uppercase tracking-widest italic">สอบถาม AI เรื่องเกณฑ์ (Link)</label>
                            <input type="url" name="link_ai" value="{{ $l->link_ai }}" placeholder="https://..." class="w-full px-4 py-3 rounded-2xl border border-gray-200 focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-bold text-gray-700 shadow-inner">
                        </div>

                        <div class="md:col-span-2 space-y-2">
                            <label class="block text-sm font-black text-gray-400 uppercase tracking-widest italic">แบบสอบถามความพึงพอใจ (Link)</label>
                            <input type="url" name="link_survey" value="{{ $l->link_survey }}" placeholder="https://..." class="w-full px-4 py-3 rounded-2xl border border-gray-200 focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-bold text-gray-700 shadow-inner">
                        </div>

                        <div class="md:col-span-2 flex items-center gap-6 p-4 bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                            <div class="relative">
                                <div class="w-24 h-24 bg-white rounded-xl border flex items-center justify-center overflow-hidden shadow-sm flex-shrink-0">
                                    <img id="img_preview_qr" src="{{ $l->survey_qr ? url('/upload/'.$l->survey_qr) : url('/imgs/qr-code.png') }}" class="max-w-full max-h-full object-contain">
                                </div>
                            </div>
                            <div class="space-y-2 w-full">
                                <label class="block text-sm font-black text-gray-400 uppercase italic">QR Code แบบสอบถาม</label>
                                <input type="file" id="qr_input" accept="image/*" class="text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 w-full">
                                <p class="text-[10px] text-slate-400 italic">* อัปโหลดรูปภาพ QR Code สำหรับแบบสอบถาม</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="px-10 py-4 bg-emerald-600 hover:bg-emerald-700 text-white font-black rounded-2xl shadow-xl shadow-emerald-100 transition-all transform active:scale-95 flex items-center gap-2 italic uppercase tracking-widest">
                        <i class="ri-save-3-line text-xl"></i> Save Configuration
                    </button>
                </div>
            </div>

            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
                    <div class="flex items-center justify-between mb-6 border-b border-gray-50 pb-4">
                        <h2 class="text-lg font-black text-gray-800 italic uppercase">Certificate Template</h2>
                        <a href="{{url('/')}}/printtest" target="_blank" class="text-xs font-bold text-emerald-600 hover:underline"><i class="ri-eye-line"></i> Preview</a>
                    </div>

                    <div class="space-y-4">
                        <div class="relative group aspect-[1.414/1] w-full rounded-2xl overflow-hidden bg-slate-100 border-2 border-dashed border-slate-200 flex items-center justify-center">
                            <img id="img_preview_cert" src="{{url('/')}}/upload/{{$l->app_certificate}}" class="w-full h-full object-contain">
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <i class="ri-upload-cloud-2-line text-white text-4xl"></i>
                            </div>
                        </div>

                        <div class="relative">
                            <input type="file" name="app_certificate" id="cert_input" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" onchange="previewCert(event)">
                            <div class="w-full py-3 bg-slate-50 border border-slate-200 rounded-xl text-center text-xs font-bold text-slate-500 group-hover:bg-slate-100 transition-all">
                                <i class="ri-image-edit-line mr-1"></i> คลิกเพื่อเปลี่ยนไฟล์พื้นหลัง (.jpg, .png)
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    @empty
        <div class="bg-white p-20 rounded-3xl border border-dashed text-center text-gray-400">
            <i class="ri-error-warning-line text-5xl mb-2"></i>
            <p class="font-bold uppercase tracking-widest">No Configuration Found</p>
        </div>
    @endforelse

    <div id="cropModal" class="hidden fixed inset-0 z-[100] bg-black/80 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-2xl w-full overflow-hidden">
            <div class="p-6 border-b flex justify-between items-center">
                <h3 id="crop_title" class="font-black italic uppercase">Crop System Logo</h3>
                <button type="button" onclick="closeCropModal()" class="text-slate-400 hover:text-slate-600"><i class="ri-close-line text-2xl"></i></button>
            </div>
            <div class="p-6">
                <div class="max-h-[60vh] overflow-hidden bg-slate-100 rounded-xl">
                    <img id="crop_image_element" src="" class="max-w-full">
                </div>
            </div>
            <div class="p-6 border-t bg-slate-50 flex justify-end gap-3">
                <button type="button" onclick="closeCropModal()" class="px-6 py-2 font-bold text-slate-500">Cancel</button>
                <button type="button" id="btn_crop_done" class="px-8 py-2 bg-emerald-600 text-white rounded-xl font-bold">Crop & Use</button>
            </div>
        </div>
    </div>

    <!-- Year Management Modal -->
    <div id="yearModal" class="hidden fixed inset-0 z-[100] bg-black/80 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-md w-full overflow-hidden shadow-2xl border border-slate-100 animate-fade-in font-sarabun text-slate-800">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                <h3 class="font-black italic uppercase text-lg text-slate-800 flex items-center gap-2">
                    <i class="ri-calendar-line text-emerald-500"></i> จัดการปีการศึกษา
                </h3>
                <button type="button" onclick="closeYearModal()" class="text-slate-400 hover:text-slate-600 transition-colors"><i class="ri-close-line text-2xl"></i></button>
            </div>
            
            <div class="p-6 space-y-6">
                <!-- Form to Add Year -->
                <form id="addYearForm" onsubmit="submitAddYear(event)" class="space-y-3">
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest italic leading-none">เพิ่มปีการศึกษาใหม่ (พ.ศ.)</label>
                    <div class="flex gap-2">
                        <input type="text" id="new_year_input" required placeholder="ตัวอย่าง: 2571" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-bold text-gray-700 shadow-inner text-center text-sm">
                        <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-sm transition-all shadow-md shadow-emerald-100">
                            เพิ่มปี
                        </button>
                    </div>
                    <p id="add_year_error" class="text-xs text-red-600 font-bold hidden italic leading-none"></p>
                </form>

                <hr class="border-slate-100">

                <!-- List of Years -->
                <div class="space-y-3">
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest italic leading-none">ปีการศึกษาทั้งหมดในระบบ</label>
                    <div class="max-h-48 overflow-y-auto pr-1 space-y-2" id="years_list_container">
                        @foreach($years as $y)
                            <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl border border-slate-100 group hover:bg-slate-100/50 transition-all" id="year_row_{{ $y->id }}">
                                <span class="font-bold text-sm text-slate-700">{{ $y->years }}</span>
                                <button type="button" onclick="deleteYear({{ $y->id }}, '{{ $y->years }}')" class="w-8 h-8 inline-flex items-center justify-center bg-red-50 text-red-500 rounded-lg hover:bg-red-500 hover:text-white transition-all shadow-sm">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <div class="p-6 border-t border-slate-100 bg-slate-50 flex justify-end">
                <button type="button" onclick="closeYearModal()" class="px-6 py-2.5 font-bold text-slate-500 text-sm hover:text-slate-700 transition-colors">ปิดหน้าต่าง</button>
            </div>
        </div>
    </div>

    <hr class="my-12 border-gray-100">

    <div class="space-y-6">
        <h3 class="text-2xl font-black text-slate-800 flex items-center gap-2 italic uppercase">
            <i class="ri-layout-grid-line text-emerald-500"></i> Area Certificates
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($configarea as $m)
                <div class="group bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-xl hover:shadow-emerald-500/5 transition-all">
                    <div class="aspect-[1.414/1] bg-slate-50 relative overflow-hidden">
                        <img src="{{url('/upload/'.$m->app_certificate)}}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" alt="Certificate">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    </div>
                    <div class="p-5">
                        <p class="font-black text-gray-700 text-sm truncate uppercase italic tracking-tight">
                            {{$m->getschoolgroup->schoolgroup_name ?? 'ADMIN SETTING'}}
                        </p>
                    </div>
                </div>
            @empty
                <p class="text-gray-400 italic text-sm">ยังไม่มีข้อมูลเกียรติบัตรรายพื้นที่</p>
            @endforelse
        </div>
    </div>
</div>

<script>
    let cropper;
    let cropTarget = 'logo'; // 'logo' or 'qr'
    const logoInput = document.getElementById('logo_input');
    const qrInput = document.getElementById('qr_input');
    const cropModal = document.getElementById('cropModal');
    const cropImageElement = document.getElementById('crop_image_element');
    const btnCropDone = document.getElementById('btn_crop_done');
    const cropTitle = document.getElementById('crop_title');

    // Handle Logo Selection
    logoInput.addEventListener('change', function(e) {
        const files = e.target.files;
        if (files && files.length > 0) {
            cropTarget = 'logo';
            if (cropTitle) cropTitle.innerText = "Crop System Logo";
            const reader = new FileReader();
            reader.onload = function(event) {
                cropImageElement.src = event.target.result;
                cropModal.classList.remove('hidden');
                if (cropper) cropper.destroy();
                cropper = new Cropper(cropImageElement, {
                    aspectRatio: 1,
                    viewMode: 2,
                });
            };
            reader.readAsDataURL(files[0]);
        }
    });

    // Handle QR Selection
    if (qrInput) {
        qrInput.addEventListener('change', function(e) {
            const files = e.target.files;
            if (files && files.length > 0) {
                cropTarget = 'qr';
                if (cropTitle) cropTitle.innerText = "Crop QR Code";
                const reader = new FileReader();
                reader.onload = function(event) {
                    cropImageElement.src = event.target.result;
                    cropModal.classList.remove('hidden');
                    if (cropper) cropper.destroy();
                    cropper = new Cropper(cropImageElement, {
                        aspectRatio: 1,
                        viewMode: 2,
                    });
                };
                reader.readAsDataURL(files[0]);
            }
        });
    }

    btnCropDone.addEventListener('click', function() {
        const canvas = cropper.getCroppedCanvas({
            width: 512,
            height: 512
        });
        const base64data = canvas.toDataURL('image/png');
        if (cropTarget === 'logo') {
            document.getElementById('img_preview_logo').src = base64data;
            document.getElementById('cropped_logo').value = base64data;
        } else if (cropTarget === 'qr') {
            document.getElementById('img_preview_qr').src = base64data;
            document.getElementById('cropped_qr').value = base64data;
        }
        closeCropModal();
    });

    function closeCropModal() {
        cropModal.classList.add('hidden');
        logoInput.value = "";
        if (qrInput) qrInput.value = "";
    }

    function previewCert(event) {
        const reader = new FileReader();
        reader.onload = function() {
            const output = document.getElementById('img_preview_cert');
            output.src = reader.result;
        }
        reader.readAsDataURL(event.target.files[0]);
    }

    // --- Year Modal Controls ---
    let yearsModified = false;

    function openYearModal() {
        document.getElementById('yearModal').classList.remove('hidden');
    }

    function closeYearModal() {
        document.getElementById('yearModal').classList.add('hidden');
        if (yearsModified) {
            location.reload();
        }
    }

    function submitAddYear(e) {
        e.preventDefault();
        const yearInput = document.getElementById('new_year_input');
        const yearVal = yearInput.value.trim();
        const errorEl = document.getElementById('add_year_error');

        errorEl.classList.add('hidden');

        fetch("{{ route('config.addYear') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ year: yearVal })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                yearInput.value = '';
                yearsModified = true;
                
                // Add to list UI
                const container = document.getElementById('years_list_container');
                const row = document.createElement('div');
                row.className = "flex items-center justify-between p-3 bg-slate-50 rounded-xl border border-slate-100 group hover:bg-slate-100/50 transition-all";
                row.id = `year_row_${data.year.id}`;
                row.innerHTML = `
                    <span class="font-bold text-sm text-slate-700">${data.year.years}</span>
                    <button type="button" onclick="deleteYear(${data.year.id}, '${data.year.years}')" class="w-8 h-8 inline-flex items-center justify-center bg-red-50 text-red-500 rounded-lg hover:bg-red-500 hover:text-white transition-all shadow-sm">
                        <i class="ri-delete-bin-line"></i>
                    </button>
                `;
                container.insertBefore(row, container.firstChild);
            } else {
                errorEl.innerText = "เกิดข้อผิดพลาดในการบันทึก";
                errorEl.classList.remove('hidden');
            }
        })
        .catch(err => {
            errorEl.innerText = "ปีการศึกษานี้มีอยู่แล้ว หรือข้อมูลไม่ถูกต้อง";
            errorEl.classList.remove('hidden');
        });
    }


    function deleteYear(id, yearVal) {
        showCustomConfirm(
            'ยืนยันการลบปีการศึกษา',
            `ยืนยันการลบปีการศึกษา ${yearVal} ใช่หรือไม่? การกระทำนี้ไม่สามารถย้อนกลับได้`,
            function() {
                fetch(`{{ url('config/years') }}/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById(`year_row_${id}`).remove();
                        yearsModified = true;
                    }
                });
            }
        );
    }

    // Listen to year select change to reload page with the selected year's config
    const yearSelect = document.querySelector('select[name="app_year"]');
    if (yearSelect) {
        yearSelect.addEventListener('change', function() {
            const yearVal = this.value;
            window.location.href = "{{ route('config.index') }}?year=" + yearVal;
        });
    }

    // Initialize Quill Editor
    const quill = new Quill('#quill_editor', {
        theme: 'snow',
        modules: {
            toolbar: [
                ['bold', 'italic', 'underline'],
                [{ 'color': [] }, { 'background': [] }],
                ['clean']
            ]
        }
    });

    // Sync Quill content to hidden input on submit
    document.querySelectorAll('form').forEach(form => {
        const hiddenInput = form.querySelector('#app_slogan');
        const editorDiv = form.querySelector('#quill_editor');
        if (hiddenInput && editorDiv) {
            form.addEventListener('submit', function() {
                hiddenInput.value = quill.root.innerHTML;
            });
        }
    });
</script>
@endsection