@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

<style>
    /* --- สไตล์เดิมของคุณ ห้ามเปลี่ยนเด็ดขาด --- */
    .id-card { width: 280px; height: 485px; border-radius: 20px; position: relative; overflow: hidden; background: white; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); display: inline-block; margin: 10px; vertical-align: top; border: 1px solid #f1f5f9; text-align: left; }
    .hidden-card { display: none !important; }
    .watermark { position: absolute; top: 55%; left: 50%; transform: translate(-50%, -50%); width: 220px; opacity: 0.12; z-index: 0; pointer-events: none; }
    .id-card > div { position: relative; z-index: 1; }
    
    @media print {
        .no-print, header, footer, nav, aside, .sidebar, .main-header, .filter-section, .content-header, .main-footer { display: none !important; }
        body, html { background: white !important; margin: 0 !important; padding: 0 !important; width: 100%; visibility: visible !important; }
        .content-wrapper, .wrapper, .content, .container-fluid, .max-w-7xl, main { display: block !important; position: relative !important; width: 100% !important; margin: 0 !important; padding: 0 !important; min-height: 0 !important; visibility: visible !important; }
        #card-wrapper { display: block !important; text-align: center; width: 100%; visibility: visible !important; }
        .id-card.hidden-card { display: none !important; }
        .id-card { visibility: visible !important; display: inline-block !important; width: 280px !important; height: 485px !important; box-shadow: none !important; border: 1px solid #ddd !important; margin: 8px !important; break-inside: avoid; page-break-inside: avoid; -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
        .id-card * { visibility: visible !important; }
        @page { size: A4 portrait; margin-top: 1.5cm; margin-bottom: 0.5cm; margin-left: 0.5cm; margin-right: 0.5cm; }
    }

    /* 2. ปรับแต่ง Choices.js ให้เข้ากับ Design คุณ */
    .choices__inner {
        border-radius: 1rem !important;
        background-color: #f8fafc !important;
        border: 1px solid #e2e8f0 !important;
        padding: 0.5rem 1rem !important;
        font-weight: 700 !important;
        min-height: 50px !important;
    }
    .choices__list--dropdown {
        border-radius: 1rem !important;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important;
    }
    .choices__item { font-family: 'Sarabun', sans-serif; font-style: italic; }
    /* บังคับให้ Dropdown ของตัวเลือกแสดงทับบัตรเสมอ */
    .choices {
        z-index: 5 !important; /* ตั้งค่าให้สูงกว่า z-index ของ id-card */
    }

    .choices__list--dropdown {
        z-index: 10 !important; /* ตั้งค่าตัวรายการให้สูงขึ้นไปอีก */
    }

    /* หากคุณไม่ได้ใช้ Choices.js ให้ใช้ตัวนี้แทน */
    #filter-activity {
        position: relative;
        z-index: 50;
    }
</style>

<div class="max-w-7xl mx-auto px-4 py-8 font-sarabun italic">
    
    <div class="no-print filter-section mb-8 bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h1 class="text-2xl font-black text-slate-800 uppercase italic leading-none">ระบบพิมพ์บัตรประจำตัว</h1>
                <p class="text-slate-500 text-sm font-bold mt-2">ใช้ Choices.js ค้นหารายการ | พิมพ์ 4 ใบต่อหน้า</p>
            </div>
            <button onclick="window.print()" class="px-8 py-4 bg-emerald-600 text-white rounded-2xl font-black shadow-lg hover:bg-slate-800 transition-all flex items-center gap-2">
                <i class="ri-printer-line text-xl"></i> พิมพ์บัตรที่เลือก
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 mt-4 border-t border-slate-50">
            <div class="space-y-1">
                <label class="text-[10px] font-black text-slate-400 uppercase italic px-1">คัดกรองประเภท</label>
                <select id="filter-type" onchange="filterCards()" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl font-bold text-slate-700 outline-none">
                    <option value="all">ทั้งหมด</option>
                    <option value="type-0">เฉพาะนักเรียน</option>
                    <option value="type-1">เฉพาะครู</option>
                </select>
            </div>
            <div class="space-y-1">
                <label class="text-[10px] font-black text-slate-400 uppercase italic px-1">ค้นหารายการแข่งขัน</label>
                <select id="filter-activity" onchange="filterCards()" class="w-full">
                    <option value="all">ทุกรายการแข่งขัน</option>
                    @php 
                        $activityOptions = collect();
                        foreach($addschool as $item) {
                            $actName = $item->getactivityname->activity_name ?? 'ไม่มีชื่อกิจกรรม';
                            $className = $item->getactivityname->getclass->class_name ?? '';
                            $fullName = $actName . ' ' . $className;
                            
                            if (!$activityOptions->contains('name', $fullName)) {
                                $activityOptions->push([
                                    'name' => $fullName,
                                    'class_tag' => 'act-tag-' . md5($fullName)
                                ]);
                            }
                        }
                    @endphp
                    @foreach($activityOptions as $option)
                        <option value="{{ $option['class_tag'] }}">{{ $option['name'] }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div id="card-wrapper" class="text-center">
    @foreach($addschool as $activity)
        @foreach($activity->getstudentname as $person)
            @php
                $actName = $activity->getactivityname->activity_name ?? '';
                $className = $activity->getactivityname->getclass->class_name ?? '';
                $cardTag = 'act-tag-' . md5($actName . ' ' . $className);
            @endphp
            <div class="id-card item-card {{ $cardTag }} type-{{ $person->type }}">
                <img src="{{ asset('imgs/obec_icon2.png') }}" class="watermark" alt="Watermark">
                <div style="height: 100%; position: relative;">
                    <div class="bg-gradient-to-r from-emerald-800 to-green-600 p-3 flex items-center gap-3">
                        <div class="w-10 h-10 bg-white/20 rounded-lg p-1 shrink-0">
                            <img src="{{ asset('imgs/obec_icon2.png') }}" class="w-full h-full object-contain" alt="OBEC">
                        </div>
                        <div class="overflow-hidden">
                            <p class="text-[9px] font-black text-green-100 uppercase tracking-wider leading-none">Official ID Card</p>
                            <p class="text-[11px] font-black text-white leading-tight truncate">{{ session('app_name') }}</p>
                        </div>
                    </div>
                    <div class="mt-4 flex justify-center relative">
                        <a href="{{ url('addstudent/'.$person->id.'/edit') }}" class="no-print absolute top-0 right-10 w-8 h-8 bg-white/90 text-emerald-600 rounded-full flex items-center justify-center shadow-lg hover:bg-emerald-600 hover:text-white transition-all z-20 border border-green-100">
                            <i class="ri-edit-line text-lg"></i>
                        </a>
                        <div class="w-32 h-32 rounded-2xl border-4 border-white shadow-md overflow-hidden bg-slate-100">
                            @if($person->student_image)
                                <img src="{{ asset('picture/'.$person->student_image) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-300"><i class="ri-user-line text-5xl"></i></div>
                            @endif
                        </div>
                    </div>
                    <div class="mt-3 px-6 text-center">
                        <h4 class="text-base font-black text-slate-800 leading-tight">{{ $person->getstudenttitle->title ?? '' }}{{ $person->name }} {{ $person->lastname }}</h4>
                        <span class="inline-block mt-1 px-3 py-0.5 rounded-full {{ $person->type == 1 ? 'bg-amber-100 text-amber-800' : 'bg-teal-100 text-teal-800' }} text-[10px] font-black uppercase tracking-widest">{{ $person->type == 1 ? 'Instructor' : 'Student' }}</span>
                    </div>
                    <div class="mt-2 mx-4 p-2 bg-slate-50 rounded-2xl border border-slate-100 text-center space-y-1">
                        <p class="text-[10px] font-black text-slate-700 leading-tight uppercase">{{ $activity->getactivityname->activity_name ?? '' }}</p>
                        <p class="text-[9px] font-black text-emerald-600">{{ $activity->getactivityname->getclass->class_name ?? '' }}</p>
                        <div class="pt-1 mt-1 border-t border-slate-200">
                            <p class="text-[8px] font-bold text-slate-400 uppercase tracking-tighter">ศูนย์การแข่งขัน</p>
                            <p class="text-[9px] font-black text-slate-600">{{ $activity->getactivityname->getactivitybranch->branch_name ?? 'ไม่พบข้อมูลศูนย์' }}</p>
                        </div>
                    </div>
                    <div class="absolute bottom-3 left-0 right-0 px-4 text-center">
                        <p class="text-[9px] font-black text-slate-400 uppercase truncate">{{ $activity->getschoolname->school_name ?? '' }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    @endforeach
    </div>
</div>

<script>
    // 3. เริ่มต้น Choices.js
    document.addEventListener('DOMContentLoaded', function() {
        const element = document.getElementById('filter-activity');
        const choices = new Choices(element, {
            searchEnabled: true,
            itemSelectText: '',
            placeholder: true,
            placeholderValue: 'พิมพ์ค้นหารายการ...',
            noResultsText: 'ไม่พบข้อมูล',
            noChoicesText: 'ไม่มีรายการให้เลือก',
        });

        // ผูกเหตุการณ์เปลี่ยนค่าเพื่อให้ฟังก์ชันกรองทำงาน
        element.addEventListener('change', function() {
            filterCards();
        });
    });

    function filterCards() {
        const typeValue = document.getElementById('filter-type').value;
        const activityValue = document.getElementById('filter-activity').value;
        const cards = document.querySelectorAll('.item-card');

        cards.forEach(card => {
            const matchesType = (typeValue === 'all' || card.classList.contains(typeValue));
            const matchesActivity = (activityValue === 'all' || card.classList.contains(activityValue));

            if (matchesType && matchesActivity) {
                card.classList.remove('hidden-card');
            } else {
                card.classList.add('hidden-card');
            }
        });
    }
</script>
@endsection