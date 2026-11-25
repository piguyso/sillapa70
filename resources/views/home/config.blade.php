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

    {{-- Event Selection & Status Bar --}}
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 mb-8">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            
            {{-- Left: Event Selector --}}
            <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                <div class="flex items-center gap-2">
                    <div class="w-10 h-10 rounded-2xl bg-slate-100 text-slate-700 flex items-center justify-center font-black">
                        <i class="ri-calendar-event-line text-xl"></i>
                    </div>
                    <div>
                        <div class="text-xs font-black text-slate-400 uppercase tracking-wider">ครั้งที่จัดการแข่งขัน</div>
                        <div class="text-sm font-bold text-slate-700">เลือกครั้งที่ต้องการดู/จัดการข้อมูล</div>
                    </div>
                </div>

                <div class="relative min-w-[280px]">
                    <select onchange="if(this.value) window.location.href = this.value;" class="w-full pl-4 pr-10 py-3 rounded-2xl border-2 border-slate-200 bg-slate-50 hover:bg-white focus:bg-white focus:border-emerald-500 font-bold text-slate-800 transition-all cursor-pointer shadow-sm">
                        @foreach($events as $event)
                            <option value="{{ event_url('config', $event->event_number) }}" {{ $event->id === $currentEvent->id ? 'selected' : '' }}>
                                ครั้งที่ {{ $event->event_number }} (ปีการศึกษา {{ $event->academic_year }}) {{ $event->is_active ? '— [ใช้งานปัจจุบัน]' : '— [ข้อมูลย้อนหลัง]' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Status Badge --}}
                <div>
                    @if($currentEvent->is_active)
                        <span class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-full text-xs font-black bg-emerald-100 text-emerald-700">
                            <i class="ri-checkbox-circle-fill text-sm"></i> ACTIVE (ระบบปัจจุบัน)
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-full text-xs font-black bg-amber-100 text-amber-800">
                            <i class="ri-lock-line text-sm"></i> READ ONLY (ข้อมูลย้อนหลัง)
                        </span>
                    @endif
                </div>
            </div>

            {{-- Right: Actions (Activate button if inactive, Delete button, and Add New Event button) --}}
            <div class="flex flex-wrap items-center gap-3">
                @if(Auth::user()->position == 10 && !$currentEvent->is_active)
                    <form id="activateEventForm" method="POST" action="{{ event_url('config/events/' . $currentEvent->id . '/activate', $currentEvent->event_number) }}">
                        @csrf
                        <button type="button" onclick="confirmActivateEvent()" class="px-5 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl font-black text-sm transition-all shadow-md flex items-center gap-2">
                            <i class="ri-check-double-line text-base"></i> ตั้งครั้งนี้เป็น Active
                        </button>
                    </form>

                    <form id="deleteEventForm" method="POST" action="{{ event_url('config/events/' . $currentEvent->id, $currentEvent->event_number) }}">
                        @csrf
                        @method('DELETE')
                        <button type="button" onclick="confirmDeleteEvent()" class="px-5 py-3 bg-red-50 hover:bg-red-600 text-red-600 hover:text-white border border-red-200 rounded-2xl font-black text-sm transition-all shadow-sm flex items-center gap-2">
                            <i class="ri-delete-bin-2-line text-base"></i> ลบครั้งที่ {{ $currentEvent->event_number }}
                        </button>
                    </form>
                @endif

                @if(Auth::user()->position == 10)
                    <button type="button" onclick="openNewEventModal()" class="px-5 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl font-black text-sm transition-all shadow-md flex items-center gap-2">
                        <i class="ri-add-circle-line text-base"></i> + เพิ่มครั้งการแข่งขันใหม่
                    </button>
                @endif
            </div>

        </div>
    </div>

    @if($readOnly)
        <div class="mb-6 p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-900 flex items-center justify-between gap-3 shadow-sm animate-fade-in">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center flex-shrink-0 text-xl font-bold">
                    <i class="ri-lock-line"></i>
                </div>
                <div>
                    <p class="font-black text-base">กำลังดูข้อมูลย้อนหลัง: ครั้งที่ {{ $currentEvent->event_number }} (ปีการศึกษา {{ $currentEvent->academic_year }})</p>
                    <p class="text-xs text-amber-700">คุณสามารถเปิดดูรายละเอียดทุกแท็บได้ตามปกติ แต่ไม่สามารถแก้ไขข้อมูลได้</p>
                </div>
            </div>
            @if(Auth::user()->position == 10)
                <span class="text-xs text-amber-700 italic hidden sm:inline-block">กดปุ่ม "ตั้งครั้งนี้เป็น Active" ด้านบนหากต้องการเปิดใช้งานครั้งนี้</span>
            @endif
        </div>
    @endif

    @forelse($config as $l)
    <form id="mainConfigForm" method="POST" enctype="multipart/form-data" action="{{ event_url('config/' . $l->id, $currentEvent->event_number) }}" class="space-y-6 animate-fade-in">
        {{ csrf_field() }}
        <input type="hidden" name="_method" value="put">
        <input type="hidden" name="cropped_logo" id="cropped_logo">
        <input type="hidden" name="cropped_qr" id="cropped_qr">
        <fieldset {{ $readOnly ? 'disabled' : '' }}>

        <!-- Tab Buttons -->
        <div class="flex flex-wrap border-b border-slate-100 mb-6 gap-2 bg-white p-3 rounded-2xl shadow-sm border border-gray-100">
            <button type="button" onclick="switchTab('general')" id="tab-btn-general" class="tab-btn py-3 px-6 font-black text-sm rounded-xl text-emerald-600 bg-emerald-50 transition-all flex items-center gap-2">
                <i class="ri-settings-5-line text-lg"></i> ข้อมูลทั่วไป (General Settings)
            </button>
            <button type="button" onclick="switchTab('colors')" id="tab-btn-colors" class="tab-btn py-3 px-6 font-black text-sm rounded-xl text-slate-500 hover:text-slate-700 transition-all flex items-center gap-2">
                <i class="ri-palette-line text-lg"></i> ตั้งค่าสีเว็บไซต์ (Theme Colors)
            </button>
            <button type="button" onclick="switchTab('links')" id="tab-btn-links" class="tab-btn py-3 px-6 font-black text-sm rounded-xl text-slate-500 hover:text-slate-700 transition-all flex items-center gap-2">
                <i class="ri-links-line text-lg"></i> ลิงก์และเอกสาร (Links & QR)
            </button>
            <button type="button" onclick="switchTab('certificate')" id="tab-btn-certificate" class="tab-btn py-3 px-6 font-black text-sm rounded-xl text-slate-500 hover:text-slate-700 transition-all flex items-center gap-2">
                <i class="ri-file-shield-2-line text-lg"></i> เกียรติบัตร (Certificate template)
            </button>
            <button type="button" onclick="switchTab('activities')" id="tab-btn-activities" class="tab-btn py-3 px-6 font-black text-sm rounded-xl text-slate-500 hover:text-slate-700 transition-all flex items-center gap-2">
                <i class="ri-folder-transfer-line text-lg"></i> Transfer โครงสร้างการแข่งขัน
            </button>
        </div>

        <!-- Tab Contents -->
        <div class="space-y-6">
            <!-- Tab 1: General Settings -->
            <div id="tab-content-general" class="tab-content block">
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 space-y-6">
                    <h2 class="text-xl font-black text-gray-800 flex items-center gap-2 border-b border-gray-50 pb-4 italic uppercase">
                        <i class="ri-edit-2-line text-emerald-500"></i> General Settings
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2 flex items-center gap-6 p-4 bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                            <div class="relative">
                                <div class="w-24 h-24 bg-white rounded-xl border flex items-center justify-center overflow-hidden shadow-sm">
                                    <img id="img_preview_logo" src="{{ $l->app_logo ? get_secure_file_url('upload', $l->app_logo, $l->app_year) : 'https://ui-avatars.com/api/?name=LOGO&background=random' }}" class="max-w-full max-h-full object-contain">
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-black text-gray-400 uppercase italic">System Logo (Crop)</label>
                                <input type="file" id="logo_input" accept="image/jpeg,image/png,image/webp" class="ui-file text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                                <p class="text-[10px] text-slate-400 italic">* ลากกรอบเลือกส่วนที่ต้องการได้อิสระ (ไม่บังคับสี่เหลี่ยมจัตุรัส)</p>
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
                        <div class="md:col-span-2">
                            <x-form.input label="ชื่อเรียกระดับเครือข่าย" name="network_label" :value="$l->network_label ?: 'เครือข่ายสถานศึกษา'" maxlength="100" list="network_label_options" placeholder="เครือข่ายสถานศึกษา" help="ระบบมี 3 ระดับ คือ ระดับโรงเรียน ระดับเครือข่าย (หรือกลุ่มโรงเรียน แล้วแต่กรณี) และระดับเขตพื้นที่การศึกษา ช่องนี้ใช้ตั้งชื่อเรียกระดับเครือข่ายที่เขียนไว้ในหน้าเว็บ เมนู เอกสาร PDF เกียรติบัตร และ Excel ไม่เปลี่ยนชื่อเครือข่ายที่มาจากฐานข้อมูล ค่าเริ่มต้นคือ เครือข่ายสถานศึกษา" />
                            <div class="md:col-span-2">
                                <x-form.input label="จำนวนตัวแทนเครือข่ายที่เข้าระดับเขตพื้นที่ (อันดับที่ 1 ถึง ...)" name="representative_count" type="number" min="1" max="10" step="1" :value="$l->representative_count ?: 3" help="กำหนดว่าผู้ชนะระดับเครือข่ายอันดับที่ 1 ถึงอันดับที่เท่าไรจะถูกโอนไประดับเขตพื้นที่ เช่น ใส่ 2 = อันดับ 1-2 ตั้งแยกตามครั้งการแข่งขัน ค่าเริ่มต้นคือ 3 (อันดับ 1-3)" />
                            </div>
                            <datalist id="network_label_options"><option value="เครือข่ายสถานศึกษา"><option value="กลุ่มโรงเรียน"><option value="กลุ่มโรงเรียน/เครือข่ายสถานศึกษา"></datalist>
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
                            <label class="block text-sm font-black text-gray-400 uppercase tracking-widest italic">Year (พ.ศ. / ปีการศึกษา)</label>
                            <input type="text" name="app_year" value="{{ $l->app_year ?: $currentEvent->academic_year }}" required class="w-full px-4 py-3 rounded-2xl border border-gray-200 focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-bold text-gray-700 shadow-inner text-center">
                            <p class="text-xs text-slate-400">ปีการศึกษาสำหรับการแข่งขันครั้งที่ {{ $currentEvent->event_number }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab: Theme Colors Settings -->
            <div id="tab-content-colors" class="tab-content hidden">
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 space-y-6">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2 border-b border-gray-50 pb-4">
                        <h2 class="text-xl font-black text-gray-800 flex items-center gap-2 italic uppercase">
                            <i class="ri-palette-line text-emerald-500"></i> ตั้งค่าสีเว็บไซต์ (Theme Colors) — แยกตามครั้งที่ {{ $currentEvent->event_number }}
                        </h2>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-bold">
                            <i class="ri-information-line"></i> สีที่ตั้งค่าจะแสดงเฉพาะในการแข่งขันครั้งที่ {{ $currentEvent->event_number }} (ปีการศึกษา {{ $currentEvent->academic_year }})
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 space-y-3">
                            <label class="block text-sm font-black text-slate-700 uppercase tracking-widest italic">สีหลักของเว็บไซต์ (Primary Color)</label>
                            <p class="text-xs text-slate-500">ใช้สำหรับแถบเมนูด้านบน (Navbar), ปุ่มหลัก, แถบหัวข้อ และขอบเน้น</p>
                            <div class="flex gap-3 items-center pt-1">
                                <input type="color" id="theme_primary_picker" name="theme_primary" value="{{ $l->theme_primary ?: '#059669' }}" oninput="syncThemeColor('primary', this.value)" class="w-16 h-12 rounded-xl border border-gray-200 p-1 cursor-pointer bg-white shadow-sm">
                                <input type="text" id="theme_primary_hex" value="{{ $l->theme_primary ?: '#059669' }}" maxlength="7" oninput="if(/^#[0-9A-Fa-f]{6}$/.test(this.value)) syncThemeColor('primary', this.value)" class="w-36 px-4 py-2.5 rounded-xl border border-gray-200 font-mono text-sm font-bold text-slate-700 uppercase bg-white">
                            </div>
                        </div>

                        <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 space-y-3">
                            <label class="block text-sm font-black text-slate-700 uppercase tracking-widest italic">สีรองของเว็บไซต์ (Secondary Color)</label>
                            <p class="text-xs text-slate-500">ใช้สำหรับไล่เฉดสีแบนเนอร์หน้าแรก (Hero Gradient), ปุ่มรอง และไอคอนเน้น</p>
                            <div class="flex gap-3 items-center pt-1">
                                <input type="color" id="theme_secondary_picker" name="theme_secondary" value="{{ $l->theme_secondary ?: '#0f172a' }}" oninput="syncThemeColor('secondary', this.value)" class="w-16 h-12 rounded-xl border border-gray-200 p-1 cursor-pointer bg-white shadow-sm">
                                <input type="text" id="theme_secondary_hex" value="{{ $l->theme_secondary ?: '#0f172a' }}" maxlength="7" oninput="if(/^#[0-9A-Fa-f]{6}$/.test(this.value)) syncThemeColor('secondary', this.value)" class="w-36 px-4 py-2.5 rounded-xl border border-gray-200 font-mono text-sm font-bold text-slate-700 uppercase bg-white">
                            </div>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <label class="block text-xs font-black text-gray-400 uppercase tracking-widest italic">ชุดสีสำเร็จรูปแนะนำ (คลิกเพื่อเลือกใช้)</label>
                        <div class="flex flex-wrap gap-3">
                            <button type="button" onclick="applyThemePreset('#059669', '#0f172a')" class="flex items-center gap-2 px-4 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-xs font-bold text-slate-700 shadow-sm transition-all">
                                <span class="w-4 h-4 rounded-full border border-black/10" style="background-color: #059669;"></span>
                                <span class="w-4 h-4 rounded-full border border-black/10 -ml-2" style="background-color: #0f172a;"></span>
                                เขียวมรกต (ค่าเริ่มต้น)
                            </button>
                            <button type="button" onclick="applyThemePreset('#b30584', '#0f172a')" class="flex items-center gap-2 px-4 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-xs font-bold text-slate-700 shadow-sm transition-all">
                                <span class="w-4 h-4 rounded-full border border-black/10" style="background-color: #b30584;"></span>
                                <span class="w-4 h-4 rounded-full border border-black/10 -ml-2" style="background-color: #0f172a;"></span>
                                ชมพูบานเย็น
                            </button>
                            <button type="button" onclick="applyThemePreset('#1d4ed8', '#0f172a')" class="flex items-center gap-2 px-4 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-xs font-bold text-slate-700 shadow-sm transition-all">
                                <span class="w-4 h-4 rounded-full border border-black/10" style="background-color: #1d4ed8;"></span>
                                <span class="w-4 h-4 rounded-full border border-black/10 -ml-2" style="background-color: #0f172a;"></span>
                                น้ำเงินรอยัลบลู
                            </button>
                            <button type="button" onclick="applyThemePreset('#7c3aed', '#1e1b4b')" class="flex items-center gap-2 px-4 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-xs font-bold text-slate-700 shadow-sm transition-all">
                                <span class="w-4 h-4 rounded-full border border-black/10" style="background-color: #7c3aed;"></span>
                                <span class="w-4 h-4 rounded-full border border-black/10 -ml-2" style="background-color: #1e1b4b;"></span>
                                ม่วงไวโอเล็ต
                            </button>
                            <button type="button" onclick="applyThemePreset('#be123c', '#1e293b')" class="flex items-center gap-2 px-4 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-xs font-bold text-slate-700 shadow-sm transition-all">
                                <span class="w-4 h-4 rounded-full border border-black/10" style="background-color: #be123c;"></span>
                                <span class="w-4 h-4 rounded-full border border-black/10 -ml-2" style="background-color: #1e293b;"></span>
                                แดงทับทิม
                            </button>
                            <button type="button" onclick="applyThemePreset('#d97706', '#451a03')" class="flex items-center gap-2 px-4 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-xs font-bold text-slate-700 shadow-sm transition-all">
                                <span class="w-4 h-4 rounded-full border border-black/10" style="background-color: #d97706;"></span>
                                <span class="w-4 h-4 rounded-full border border-black/10 -ml-2" style="background-color: #451a03;"></span>
                                ทองอำพัน
                            </button>
                        </div>
                    </div>

                    <!-- Live Preview -->
                    <div class="space-y-2 pt-2">
                        <label class="block text-xs font-black text-gray-400 uppercase tracking-widest italic">ตัวอย่างการแสดงผลสีธีม (ครั้งที่ {{ $currentEvent->event_number }})</label>
                        <div class="rounded-2xl overflow-hidden border border-slate-200 shadow-sm">
                            <div id="theme_preview_navbar" class="px-5 py-3 text-white flex items-center justify-between" style="background-color: {{ $l->theme_primary ?: '#059669' }};">
                                <span class="font-bold text-sm">{{ $l->app_name }} {{ $l->app_group }} {{ $l->app_year ?: $currentEvent->academic_year }}</span>
                                <span id="theme_preview_badge" class="px-3 py-1 rounded-full text-xs font-bold text-white" style="background-color: {{ $l->theme_secondary ?: '#0f172a' }};">ครั้งที่ {{ $currentEvent->event_number }}</span>
                            </div>
                            <div id="theme_preview_banner" class="p-6 text-center text-white" style="background: linear-gradient(to right, {{ $l->theme_primary ?: '#059669' }}, {{ $l->theme_secondary ?: '#0f172a' }}, {{ $l->theme_primary ?: '#059669' }});">
                                <div class="font-black text-lg">งานศิลปหัตถกรรมนักเรียน ครั้งที่ {{ $currentEvent->event_number }}</div>
                                <div class="text-xs opacity-80 mt-1">ปีการศึกษา {{ $l->app_year ?: $currentEvent->academic_year }} • {{ $l->app_area }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab 2: Links & Documents Settings -->
            <div id="tab-content-links" class="tab-content hidden">
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
                                    <img id="img_preview_qr" src="{{ $l->survey_qr ? get_secure_file_url('upload', $l->survey_qr, $l->app_year) : asset('imgs/qr-code.png') }}" class="max-w-full max-h-full object-contain">
                                </div>
                            </div>
                            <div class="space-y-2 w-full">
                                <label class="block text-sm font-black text-gray-400 uppercase italic">QR Code แบบสอบถาม</label>
                                <input type="file" id="qr_input" accept="image/jpeg,image/png,image/webp" class="ui-file text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 w-full">
                                <p class="text-[10px] text-slate-400 italic">* อัปโหลดรูปภาพ QR Code สำหรับแบบสอบถาม</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab 3: Certificate Template -->
            <div id="tab-content-certificate" class="tab-content hidden">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <div class="lg:col-span-2 bg-white rounded-3xl shadow-sm border border-gray-100 p-8 space-y-6">
                        <h2 class="text-xl font-black text-gray-800 flex items-center gap-2 border-b border-gray-50 pb-4 italic uppercase">
                            <i class="ri-file-shield-2-line text-emerald-500"></i> Certificate template
                        </h2>
                        
                        <div class="space-y-4">
                            <p class="text-slate-500 text-sm">
                                เลือกอัปโหลดไฟล์ภาพที่จะใช้เป็นพื้นหลังหลักของใบเกียรติบัตร ขนาดที่เหมาะสมและแนะนำคือ 1414 x 1000 พิกเซล (อัตราส่วนขนาดมาตรฐาน A4 แนวนอน) โดยจะต้องเป็นประเภทไฟล์ .jpg หรือ .png เท่านั้น
                            </p>
                            
                            <div class="pt-2">
                                <a href="{{url('/')}}/printtest" target="_blank" class="inline-flex items-center gap-2 px-6 py-3 bg-emerald-50 hover:bg-emerald-100 text-emerald-600 rounded-2xl font-black text-sm border border-emerald-200 transition-all shadow-sm">
                                    <i class="ri-eye-line text-lg"></i> ทดสอบพิมพ์เกียรติบัตร (Preview Test)
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="lg:col-span-1 bg-white rounded-3xl shadow-sm border border-gray-100 p-8 space-y-4">
                        <div class="flex items-center justify-between border-b border-gray-50 pb-4">
                            <h3 class="text-sm font-black text-gray-800 italic uppercase">Preview / File</h3>
                        </div>

                        <div class="space-y-4">
                            <div class="relative group aspect-[1.414/1] w-full rounded-2xl overflow-hidden bg-slate-100 border-2 border-dashed border-slate-200 flex items-center justify-center">
                                <img id="img_preview_cert" src="{{ get_secure_file_url('upload', $l->app_certificate, $l->app_year) }}" class="w-full h-full object-contain">
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <i class="ri-upload-cloud-2-line text-white text-4xl"></i>
                                </div>
                            </div>

                            <div class="relative">
                                <input type="file" name="app_certificate" id="cert_input" accept="image/jpeg,image/png,image/webp" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" onchange="previewCert(event)">
                                <div class="w-full py-3 bg-slate-50 border border-slate-200 rounded-xl text-center text-xs font-bold text-slate-500 group-hover:bg-slate-100 transition-all">
                                    <i class="ri-image-edit-line mr-1"></i> คลิกเพื่อเปลี่ยนไฟล์พื้นหลัง (.jpg, .png)
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="my-10 border-slate-100">

                <div class="space-y-6">
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <h3 class="text-2xl font-black text-slate-800 flex items-center gap-2 italic uppercase">
                            <i class="ri-layout-grid-line text-emerald-500"></i> Area Certificates
                        </h3>
                        <div class="flex items-center gap-3">
                            <span class="text-sm text-slate-500 font-medium">แสดงรูปพื้นหลังของ</span>
                            <span class="px-4 py-1.5 bg-emerald-100 text-emerald-700 rounded-full text-sm font-black">
                                <i class="ri-calendar-line mr-1"></i>ปีการศึกษา {{ $selectedYear }}
                            </span>
                            <span class="text-xs text-slate-400 italic">เปลี่ยนปีได้จากหัวข้อ "ข้อมูลทั่วไป"</span>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                        @forelse($configarea as $m)
                            <div class="group bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-xl hover:shadow-emerald-500/5 transition-all
                                {{ Auth::user()->position == '5' && Auth::user()->schoolgroup != $m->schoolgroup ? 'opacity-50 pointer-events-none' : '' }}">
                                <div class="aspect-[1.414/1] bg-slate-50 relative overflow-hidden">
                                    <img src="{{ get_secure_file_url('upload', $m->app_certificate, $m->app_year) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" alt="Certificate">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                    <div class="absolute top-2 right-2">
                                        <span class="px-2 py-0.5 bg-black/60 text-white text-[10px] font-black rounded-lg backdrop-blur-sm">
                                            {{ $m->app_year }}
                                        </span>
                                    </div>
                                    {{-- ปุ่ม upload สำหรับ admin หรือ position=5 เฉพาะ schoolgroup ตัวเอง --}}
                                    @if(!$readOnly && (Auth::user()->position != '5' || Auth::user()->schoolgroup == $m->schoolgroup))
                                    <label for="cert_area_{{ $m->id }}" class="absolute inset-0 cursor-pointer opacity-0 group-hover:opacity-100 flex items-end justify-center pb-3 transition-opacity z-10">
                                        <span class="px-3 py-1.5 bg-emerald-500 text-white text-xs font-black rounded-xl shadow-lg flex items-center gap-1">
                                            <i class="ri-upload-cloud-2-line"></i> เปลี่ยนรูป
                                        </span>
                                    </label>
                                    <input type="file" id="cert_area_{{ $m->id }}"
                                           class="hidden" accept="image/jpeg,image/png,image/webp"
                                           onchange="submitAreaCert('{{ event_url('config/' . $m->id, $currentEvent->event_number) }}', this)">
                                    @endif
                                </div>
                                <div class="p-5">
                                    <p class="font-black text-gray-700 text-sm truncate uppercase italic tracking-tight">
                                        {{$m->getschoolgroup->schoolgroup_name ?? 'ADMIN SETTING'}}
                                    </p>
                                    <p class="text-xs text-slate-400 mt-1">ปีการศึกษา {{ $m->app_year }}</p>
                                    @if(Auth::user()->position == '5' && Auth::user()->schoolgroup == $m->schoolgroup)
                                        <span class="inline-flex items-center gap-1 text-xs text-emerald-600 font-black mt-1">
                                            <i class="ri-shield-check-line"></i> เครือข่ายของคุณ
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="col-span-4 py-12 text-center">
                                <i class="ri-image-line text-4xl text-slate-300"></i>
                                <p class="text-gray-400 italic text-sm mt-3">ยังไม่มีข้อมูลเกียรติบัตรสำหรับปีการศึกษา {{ $selectedYear }}</p>
                                <p class="text-gray-300 text-xs mt-1">อัปโหลดรูปพื้นหลังในแต่ละพื้นที่ด้านบน</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Tab 4: Copy Activities -->
            <div id="tab-content-activities" class="tab-content hidden animate-fade-in">
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 space-y-6">
                    <h2 class="text-xl font-black text-gray-800 flex items-center gap-2 border-b border-gray-50 pb-4 italic uppercase">
                        <i class="ri-folder-transfer-line text-emerald-500"></i> Transfer ข้อมูลการแข่งขันจากครั้งก่อน
                    </h2>
                    
                    <div class="p-6 bg-emerald-50/30 rounded-2xl border border-emerald-100/50 space-y-4">
                        <h3 class="font-black text-emerald-800 text-base">Transfer ครั้งเดียวครบทั้ง 5 ส่วน:</h3>
                        <p class="text-emerald-700 text-sm leading-relaxed">
                            กลุ่มสาระ, ศูนย์การแข่งขัน, รายการแข่งขัน, สถานที่/ห้อง และวัน/เวลา จะถูกคัดลอกพร้อมกันใน transaction เดียว โดยสร้างเป็นข้อมูลใหม่ของครั้งปลายทาง
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4">
                        <div class="space-y-2">
                            <label class="block text-sm font-black text-gray-500 uppercase tracking-widest italic">ครั้งการแข่งขันต้นทาง</label>
                            <select id="source_event_select" class="ui-select font-bold">
                                <option value="" disabled selected>-- เลือกครั้งการแข่งขันต้นทาง --</option>
                                @foreach($events as $eventOption)
                                    @if($eventOption->id != $currentEvent->id)
                                        <option value="{{ $eventOption->id }}">ครั้งที่ {{ $eventOption->event_number }} / ปีการศึกษา {{ $eventOption->academic_year }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-black text-gray-500 uppercase tracking-widest italic">ครั้งการแข่งขันปลายทาง</label>
                            <input type="text" readonly value="ครั้งที่ {{ $currentEvent->event_number }} / ปีการศึกษา {{ active_year() }}" class="ui-input text-center font-bold">
                        </div>

                        <div class="md:col-span-2 p-4 bg-slate-50 rounded-2xl border border-slate-200">
                            <div class="flex items-center gap-3">
                                <input type="checkbox" id="clear_existing_checkbox" name="clear_existing" class="w-5 h-5 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500">
                                <label for="clear_existing_checkbox" class="text-sm font-bold text-gray-700 cursor-pointer">
                                    ล้างข้อมูลกิจกรรมเดิมของปีปัจจุบัน ({{ active_year() }}) ก่อนคัดลอกข้อมูลใหม่
                                </label>
                            </div>
                            <p class="text-xs text-red-500 font-bold mt-1 ml-8">* คำเตือน: ข้อมูลกิจกรรมทั้งหมดของปี {{ active_year() }} จะถูกลบออกถาวรก่อนจะคัดลอกใหม่ (หากเลือกข้อนี้)</p>
                        </div>
                    </div>

                    @if($readOnly)
                        <div class="p-4 bg-amber-50 rounded-2xl border border-amber-200 text-amber-800 text-sm font-bold flex items-center gap-2">
                            <i class="ri-information-line text-lg"></i> ครั้งนี้เป็นข้อมูลย้อนหลัง ไม่สามารถคัดลอกกิจกรรมลงในครั้งนี้ได้
                        </div>
                    @else
                        <div class="flex justify-end pt-4">
                            <button type="button" onclick="submitCopyActivities()" class="px-8 py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white font-black rounded-2xl shadow-xl shadow-emerald-100 transition-all flex items-center gap-2">
                                <i class="ri-folder-transfer-line text-lg"></i> Transfer ข้อมูลทั้งชุด
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        </fieldset>
        <!-- Persistent Save Buttons -->
        <div class="flex justify-end pt-4">
            @if($readOnly)
                <div class="px-8 py-3.5 bg-slate-100 border border-slate-200 text-slate-400 font-black rounded-2xl flex items-center gap-2 cursor-not-allowed">
                    <i class="ri-lock-line text-lg"></i> ข้อมูลย้อนหลัง (ปิดการแก้ไข)
                </div>
            @else
                <button type="button" onclick="confirmSaveConfig()" class="px-10 py-4 bg-emerald-600 hover:bg-emerald-700 text-white font-black rounded-2xl shadow-xl shadow-emerald-100 transition-all transform active:scale-95 flex items-center gap-2 italic uppercase tracking-widest">
                    <i class="ri-save-3-line text-xl"></i> Save Configuration
                </button>
            @endif
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
                            <div class="flex items-center justify-between p-3 {{ $y->active == 1 ? 'bg-emerald-50 border-emerald-200' : 'bg-slate-50 border-slate-100' }} rounded-xl border group hover:bg-slate-100/50 transition-all" id="year_row_{{ $y->id }}">
                                <div class="flex items-center gap-2">
                                    <span class="font-bold text-sm text-slate-700">{{ $y->years }}</span>
                                    @if($y->active == 1)
                                        <span class="px-2 py-0.5 bg-emerald-600 text-white text-[9px] font-black uppercase italic rounded-full shadow-sm">Active</span>
                                    @endif
                                </div>
                                <div class="flex items-center gap-2">
                                    @if($y->active == 0)
                                        <button type="button" onclick="setActiveYear({{ $y->id }}, '{{ $y->years }}')" class="px-3 py-1.5 bg-white text-slate-500 rounded-lg hover:bg-emerald-600 hover:text-white transition-all text-xs font-bold border border-slate-200 shadow-sm">
                                            ตั้งเป็นปีหลัก
                                        </button>
                                    @endif
                                    <button type="button" onclick="deleteYear({{ $y->id }}, '{{ $y->years }}')" class="w-8 h-8 inline-flex items-center justify-center bg-red-50 text-red-500 rounded-lg hover:bg-red-500 hover:text-white transition-all shadow-sm">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </div>
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

    <!-- New Event Modal -->
    <div id="newEventModal" class="hidden fixed inset-0 z-[100] bg-black/80 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-lg w-full overflow-hidden shadow-2xl border border-slate-100 animate-fade-in font-sarabun text-slate-800">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                <h3 class="font-black italic uppercase text-lg text-slate-800 flex items-center gap-2">
                    <i class="ri-add-circle-fill text-blue-600 text-xl"></i> เพิ่มครั้งการแข่งขันใหม่
                </h3>
                <button type="button" onclick="closeNewEventModal()" class="text-slate-400 hover:text-slate-600 transition-colors"><i class="ri-close-line text-2xl"></i></button>
            </div>
            
            <form id="newEventForm" method="POST" action="{{ event_url('config/events', $currentEvent->event_number) }}" onsubmit="return confirmCreateEvent(this)" class="p-6 space-y-4">
                @csrf
                <div class="space-y-1">
                    <label class="block text-xs font-black text-slate-500 uppercase">ครั้งที่ (Event Number)</label>
                    <input type="number" name="event_number" required min="1" placeholder="เช่น 75" class="w-full px-4 py-3 rounded-xl border border-slate-200 font-bold text-slate-700 focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                </div>
                <div class="space-y-1">
                    <label class="block text-xs font-black text-slate-500 uppercase">ปีการศึกษา พ.ศ. (Academic Year)</label>
                    <input type="number" name="academic_year" required min="2500" max="2600" placeholder="เช่น 2570" class="w-full px-4 py-3 rounded-xl border border-slate-200 font-bold text-slate-700 focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                </div>
                <div class="space-y-1">
                    <label class="block text-xs font-black text-slate-500 uppercase">ชื่อการแข่งขัน</label>
                    <input type="text" name="name" placeholder="เช่น งานศิลปหัตถกรรมนักเรียน ครั้งที่ 75" class="w-full px-4 py-3 rounded-xl border border-slate-200 font-bold text-slate-700 focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                </div>
                <p class="text-xs text-slate-400 italic leading-relaxed">
                    * เมื่อสร้างแล้ว ระบบจะตั้งค่าเริ่มต้นโดยคัดลอกจากการตั้งค่าปัจจุบัน และเปิดให้เข้าดู/ตั้งค่าได้ทันที
                </p>

                <div class="pt-4 flex justify-end gap-3 border-t border-slate-100">
                    <button type="button" onclick="closeNewEventModal()" class="px-5 py-2.5 font-bold text-slate-500 hover:bg-slate-100 rounded-xl transition-all">ยกเลิก</button>
                    <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-black shadow-md shadow-blue-100 transition-all flex items-center gap-1.5">
                        <i class="ri-save-line"></i> บันทึกและเพิ่มครั้งใหม่
                    </button>
                </div>
            </form>
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
                    aspectRatio: NaN, // crop อิสระ ไม่บังคับสัดส่วน
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
                        aspectRatio: NaN, // crop อิสระ ไม่บังคับสัดส่วน
                        viewMode: 2,
                    });
                };
                reader.readAsDataURL(files[0]);
            }
        });
    }

    btnCropDone.addEventListener('click', function() {
        const canvas = cropper.getCroppedCanvas({
            maxWidth: 1024,
            maxHeight: 1024,
            imageSmoothingQuality: "high"
        });
        const base64data = canvas.toDataURL('image/webp', 0.82);
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

        fetch("{{ event_url('config/years', $currentEvent->event_number) }}", {
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
                fetch(`{{ event_url('config/years', $currentEvent->event_number) }}/${id}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ _method: 'DELETE' })
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

    function setActiveYear(id, yearVal) {
        showCustomConfirm(
            'ตั้งค่าปีหลักของระบบ',
            `ยืนยันการตั้งค่าปีการศึกษา ${yearVal} เป็นปีการศึกษาหลักของระบบใช่หรือไม่?`,
            function() {
                fetch(`{{ event_url('config/years/active', $currentEvent->event_number) }}/${id}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
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
            showCustomConfirm(
                'สลับปีการศึกษาและข้อมูลระบบ',
                `ยืนยันการตั้งค่าและสลับระบบทั้งหมดเป็นปีการศึกษา ${yearVal} ใช่หรือไม่?`,
                function() {
                    fetch(`{{ event_url('config/years/active-value', $currentEvent->event_number) }}/${yearVal}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.location.href = "{{ event_url('config', $currentEvent->event_number) }}?year=" + yearVal;
                        } else {
                            showCustomAlert("ข้อผิดพลาด", "ไม่สามารถเปลี่ยนปีการศึกษาหลักได้", "error");
                        }
                    });
                },
                function() {
                    // Reset to the previous selected value by reloading
                    window.location.reload();
                }
            );
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

    @if($readOnly)
        quill.enable(false);
        const qlToolbar = document.querySelector('.ql-toolbar');
        if (qlToolbar) qlToolbar.style.display = 'none';
    @endif

    function submitAreaCert(actionUrl, fileInput) {
        if (!fileInput.files || !fileInput.files.length) return;
        const tempForm = document.createElement('form');
        tempForm.method = 'POST';
        tempForm.action = actionUrl;
        tempForm.enctype = 'multipart/form-data';
        tempForm.style.display = 'none';

        const csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = '{{ csrf_token() }}';
        tempForm.appendChild(csrf);

        const method = document.createElement('input');
        method.type = 'hidden';
        method.name = '_method';
        method.value = 'put';
        tempForm.appendChild(method);

        fileInput.name = 'app_certificate';
        tempForm.appendChild(fileInput);
        document.body.appendChild(tempForm);
        tempForm.submit();
    }

    function confirmSaveConfig() {
        const form = document.getElementById('mainConfigForm');
        if (!form) return;

        const appName = form.querySelector('input[name="app_name"]');
        if (appName && !appName.value.trim()) {
            showCustomAlert('กรุณากรอกข้อมูล', 'กรุณาระบุชื่องาน / Application Name ให้เรียบร้อย', 'warning', () => {
                switchTab('general');
                appName.focus();
            });
            return;
        }

        const appYear = form.querySelector('input[name="app_year"]');
        if (appYear && !appYear.value.trim()) {
            showCustomAlert('กรุณากรอกข้อมูล', 'กรุณาระบุปีการศึกษา (พ.ศ.) ให้เรียบร้อย', 'warning', () => {
                switchTab('general');
                appYear.focus();
            });
            return;
        }

        showCustomConfirm(
            'ยืนยันการบันทึกข้อมูล',
            'คุณต้องการบันทึกการตั้งค่าระบบใช่หรือไม่?',
            function() {
                const hiddenInput = form.querySelector('#app_slogan');
                if (hiddenInput && typeof quill !== 'undefined') {
                    hiddenInput.value = quill.root.innerHTML;
                }
                form.submit();
            },
            'บันทึกข้อมูล',
            false
        );
    }

    function confirmActivateEvent() {
        showCustomConfirm(
            'ยืนยันการตั้งเป็นระบบปัจจุบัน',
            'คุณต้องการตั้งการแข่งขันครั้งที่ {{ $currentEvent->event_number }} เป็นระบบปัจจุบันใช่หรือไม่?',
            function() {
                const form = document.getElementById('activateEventForm');
                if (form) form.submit();
            },
            'ตั้งเป็น Active',
            false
        );
    }

    function confirmDeleteEvent() {
        showCustomConfirm(
            'ยืนยันการลบครั้งการแข่งขัน',
            'คุณต้องการลบการแข่งขันครั้งที่ {{ $currentEvent->event_number }} (ปีการศึกษา {{ $currentEvent->academic_year }}) ใช่หรือไม่?\nข้อมูลการตั้งค่าและรายการทั้งหมดที่ผูกกับครั้งนี้จะถูกลบถาวร!',
            function() {
                const form = document.getElementById('deleteEventForm');
                if (form) form.submit();
            },
            'ยืนยันการลบ',
            true
        );
    }

    function confirmCreateEvent(form) {
        const evNum = form.querySelector('input[name="event_number"]').value.trim();
        const acYear = form.querySelector('input[name="academic_year"]').value.trim();

        if (!evNum || !acYear) {
            showCustomAlert('กรุณากรอกข้อมูล', 'กรุณาระบุครั้งที่และปีการศึกษาให้ครบถ้วน', 'warning');
            return false;
        }

        showCustomConfirm(
            'ยืนยันการเพิ่มครั้งการแข่งขัน',
            `คุณต้องการสร้างการแข่งขันครั้งที่ ${evNum} (พ.ศ. ${acYear}) ใช่หรือไม่?`,
            function() {
                form.submit();
            },
            'เพิ่มครั้งใหม่',
            false
        );
        return false;
    }

    function openNewEventModal() {
        const modal = document.getElementById('newEventModal');
        if (modal) modal.classList.remove('hidden');
    }

    function closeNewEventModal() {
        const modal = document.getElementById('newEventModal');
        if (modal) modal.classList.add('hidden');
    }

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

    function switchTab(tabId) {
        // Hide all tab contents
        document.querySelectorAll('.tab-content').forEach(el => {
            el.classList.add('hidden');
            el.classList.remove('block');
        });
        
        // Show selected tab content
        const activeContent = document.getElementById('tab-content-' + tabId);
        if (activeContent) {
            activeContent.classList.remove('hidden');
            activeContent.classList.add('block');
        }
        
        // Reset all tab button styles
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('text-emerald-600', 'bg-emerald-50');
            btn.classList.add('text-slate-500', 'hover:text-slate-700');
        });
        
        // Apply active styles to clicked button
        const activeBtn = document.getElementById('tab-btn-' + tabId);
        if (activeBtn) {
            activeBtn.classList.remove('text-slate-500', 'hover:text-slate-700');
            activeBtn.classList.add('text-emerald-600', 'bg-emerald-50');
        }
    }

    function syncThemeColor(type, val) {
        if (!val) return;
        const picker = document.getElementById('theme_' + type + '_picker');
        const hexInput = document.getElementById('theme_' + type + '_hex');
        if (picker && picker.value.toLowerCase() !== val.toLowerCase()) {
            picker.value = val;
        }
        if (hexInput && hexInput.value.toLowerCase() !== val.toLowerCase()) {
            hexInput.value = val;
        }
        const primaryVal = document.getElementById('theme_primary_picker')?.value || '#059669';
        const secondaryVal = document.getElementById('theme_secondary_picker')?.value || '#0f172a';
        const navPreview = document.getElementById('theme_preview_navbar');
        const badgePreview = document.getElementById('theme_preview_badge');
        const bannerPreview = document.getElementById('theme_preview_banner');
        if (navPreview) navPreview.style.backgroundColor = primaryVal;
        if (badgePreview) badgePreview.style.backgroundColor = secondaryVal;
        if (bannerPreview) bannerPreview.style.background = `linear-gradient(to right, ${primaryVal}, ${secondaryVal}, ${primaryVal})`;
    }

    function applyThemePreset(primaryHex, secondaryHex) {
        syncThemeColor('primary', primaryHex);
        syncThemeColor('secondary', secondaryHex);
    }

    function submitCopyActivities() {
        const sourceSelect = document.getElementById('source_event_select');
        const sourceEventId = sourceSelect.value;
        const sourceLabel = sourceSelect.options[sourceSelect.selectedIndex]?.text || '';
        const clearExisting = document.getElementById('clear_existing_checkbox').checked ? '1' : '0';

        if (!sourceEventId) {
            appAlert('กรุณาเลือกครั้งการแข่งขันต้นทาง', { title: 'ข้อมูลไม่ครบ' });
            return;
        }

        appConfirm(
            `ระบบจะ Transfer กลุ่มสาระ ศูนย์การแข่งขัน รายการแข่งขัน สถานที่/ห้อง และวัน/เวลา จาก ${sourceLabel} มายังครั้งที่ {{ $currentEvent->event_number }} พร้อมกัน`,
            { title: 'ยืนยัน Transfer ข้อมูลทั้งชุด', confirmText: 'เริ่ม Transfer', danger: false }
        ).then(function(confirmed) {
            if (confirmed) {
                const btn = document.querySelector('button[onclick="submitCopyActivities()"]');
                const originalText = btn.innerHTML;
                btn.disabled = true;
                btn.innerHTML = '<i class="ri-loader-4-line animate-spin text-lg"></i> กำลังคัดลอกข้อมูล...';

                fetch("{{ event_url('config/activities/copy', $currentEvent->event_number) }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        source_event_id: sourceEventId,
                        clear_existing: clearExisting
                    })
                })
                .then(response => response.json())
                .then(data => {
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                    if (data.success) {
                        showCustomAlert(
                            "Transfer ข้อมูลสำเร็จ",
                            `คัดลอกข้อมูลกิจกรรมเรียบร้อยแล้ว!\n- กลุ่มสาระการแข่งขัน: ${data.subjectgroups_count} รายการ\n- สถานที่แข่งขัน: ${data.branches_count} รายการ\n- กิจกรรมการแข่งขัน: ${data.activities_count} รายการ`,
                            'success',
                            function() {
                                window.location.href = "{{ event_url('config', $currentEvent->event_number) }}";
                            }
                        );
                    } else {
                        showCustomAlert('ไม่สามารถดำเนินการได้', data.message || 'เกิดข้อผิดพลาดในการคัดลอกข้อมูล', 'error');
                    }
                })
                .catch(err => {
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                    showCustomAlert('เกิดข้อผิดพลาด', 'เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์ กรุณาลองใหม่อีกครั้ง', 'error');
                });
            }
        });
    }
</script>
@endsection
