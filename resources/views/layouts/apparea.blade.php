<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
        $themePrimary = config('app.theme_primary') ?: session('theme_primary', '#059669');
        // สีประจำเครือข่าย (ถ้าตั้งไว้) ทับสี theme ของระบบ เฉพาะเว็บระดับเครือข่าย
        $path = request()->path();
        $netId = $networkId ?? (preg_match('#area/(?:showall_print\d*|report/\w+)/(\d+)$#', $path, $pm) ? (int) $pm[1] : null)
            ?? request()->attributes->get('schoolgroup_id') ?? session('area') ?? Auth::user()?->schoolgroup;
        // ผู้ใช้เครือข่ายที่เปิดหน้าจัดการของตัวเอง (URL ไม่มีรหัสเครือข่ายต่อท้าย) ใช้สีเครือข่ายของตัวเอง
        // ไม่ใช้ session('area') ซึ่งเปลี่ยนตามเครือข่ายอื่นที่เพิ่งเปิดดู
        if ((int) Auth::user()?->position === 5 && (int) Auth::user()->schoolgroup > 0
            && ! ($networkId ?? null) && ! request()->attributes->get('schoolgroup_id') && ! preg_match('#/\d+/?$#', '/'.$path)) {
            $netId = (int) Auth::user()->schoolgroup;
        }
        $netColor = \App\Schoolgroup::colorFor($netId);
        if ($netColor) { $themePrimary = $netColor; }
        $themeSecondary = config('app.theme_secondary') ?: session('theme_secondary', '#0f172a');
        $appName = config('app.name') ?: session('app_name', 'งานศิลปหัตถกรรมนักเรียน');
    @endphp
    <title>{{ $appName }}</title>

    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/form-system.css') }}">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Sarabun', 'sans-serif'] },
                    colors: { 
                        primary: @json($themePrimary),
                        primaryDark: @json($themePrimary),
                        secondary: @json($themeSecondary),
                        // ชุดสีสำหรับเว็บนี้: ทุกคลาสที่ใช้สีเหล่านี้ (emerald/orange/amber/blue/indigo/teal/pink/purple) จะตาม theme
                        @foreach(['emerald', 'orange', 'amber', 'blue', 'indigo', 'teal', 'pink', 'purple'] as $family)
                        {{ $family }}: @json(theme_palette($themePrimary)),
                        @endforeach
                    }
                }
            }
        }
    </script>

    {{-- jQuery + DataTables ต้องโหลดใน head เพื่อให้สคริปต์ของหน้า ($(document).ready, .DataTable) ใช้งานได้ --}}
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.tailwindcss.min.js"></script>
    <script>
        if (window.jQuery && jQuery.fn.dataTable) {
            jQuery.extend(true, jQuery.fn.dataTable.defaults, {
                language: { emptyTable: 'ไม่พบข้อมูล', zeroRecords: 'ไม่พบข้อมูลที่ค้นหา', infoEmpty: 'ไม่มีข้อมูล' }
            });
            jQuery.fn.dataTable.ext.errMode = 'none';
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.tailwindcss.min.css">
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
@include('layouts._legacy-bootstrap')
@include('layouts._theme-overrides')
<style>
    /* ยกระดับการออกแบบระบบปุ่มควบคุมหน้า (Pagination) ให้สวยงามและมองเห็นง่าย */
    
    /* 1. สำหรับ DataTables (Tailwind) */
    .dataTables_wrapper .dataTables_paginate {
        margin-top: 15px !important;
        margin-bottom: 10px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: flex-end !important;
        gap: 6px !important;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button {
        border: 1px solid #d1d5db !important; /* ขอบชัดเจน */
        background-color: #ffffff !important;
        color: #374151 !important;
        padding: 6px 14px !important;
        border-radius: 6px !important;
        cursor: pointer !important;
        font-weight: 600 !important;
        font-size: 14px !important;
        transition: all 0.2s ease-in-out !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        text-decoration: none !important;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background-color: #f3f4f6 !important;
        border-color: #9ca3af !important;
        color: var(--ui-primary) !important;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current,
    .dataTables_wrapper .dataTables_paginate .paginate_button.active {
        background-color: var(--ui-primary) !important;
        color: #ffffff !important;
        border-color: var(--ui-primary) !important;
        font-weight: 700 !important;
        box-shadow: 0 4px 6px -1px rgba(5, 150, 105, 0.2), 0 2px 4px -1px rgba(5, 150, 105, 0.1) !important;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled,
    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover {
        background-color: #f9fafb !important;
        color: #9ca3af !important;
        border-color: #e5e7eb !important;
        cursor: not-allowed !important;
        opacity: 0.6 !important;
    }

    /* 2. สำหรับ Laravel Pagination (ทั้ง Tailwind และ Bootstrap) */
    nav[role="navigation"], .pagination {
        margin-top: 20px !important;
        margin-bottom: 20px !important;
        display: flex !important;
        justify-content: center !important;
        align-items: center !important;
        gap: 6px !important;
        list-style: none !important;
        padding-left: 0 !important;
        width: 100% !important;
    }

    /* ตกแต่งลิงก์และปุ่มตัวเลข */
    nav[role="navigation"] a,
    nav[role="navigation"] span,
    .pagination .page-link {
        border: 1px solid #d1d5db !important;
        background-color: #ffffff !important;
        color: #374151 !important;
        padding: 8px 16px !important;
        border-radius: 6px !important;
        font-weight: 600 !important;
        font-size: 14px !important;
        text-decoration: none !important;
        transition: all 0.2s ease-in-out !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
    }

    /* โหมดโฮเวอร์ */
    nav[role="navigation"] a:hover,
    .pagination .page-link:hover {
        background-color: #f3f4f6 !important;
        border-color: #9ca3af !important;
        color: var(--ui-primary) !important;
    }

    /* โหมดกำลังใช้งาน (Active) */
    nav[role="navigation"] span[aria-current="page"],
    nav[role="navigation"] .bg-emerald-600,
    nav[role="navigation"] .bg-primary,
    .pagination .page-item.active .page-link {
        background-color: var(--ui-primary) !important;
        color: #ffffff !important;
        border-color: var(--ui-primary) !important;
        font-weight: 700 !important;
        box-shadow: 0 4px 6px -1px rgba(5, 150, 105, 0.2), 0 2px 4px -1px rgba(5, 150, 105, 0.1) !important;
    }

    /* ปรับแต่งปุ่ม Disabled */
    nav[role="navigation"] span[aria-disabled="true"],
    .pagination .page-item.disabled .page-link {
        background-color: #f9fafb !important;
        color: #9ca3af !important;
        border-color: #e5e7eb !important;
        cursor: not-allowed !important;
        opacity: 0.6 !important;
    }

    /* บังคับขนาดตัวอักษรในตารางทุกหน้า */
    table, 
    table th, 
    table td, 
    .dataTables_wrapper .dataTables_info,
    .dataTables_wrapper .dataTables_paginate {
        /* ปรับขนาดตามความต้องการ (16px หรือ 18px กำลังสวยสำหรับภาษาไทย) */
        font-size: 16px !important; 
        line-height: 1.6 !important;
    }

    /* บังคับหัวตาราง (Th) ให้ตัวหนาและขนาดเท่ากัน */
    table thead th {
        font-size: 16px !important;
        font-weight: 800 !important;
        white-space: nowrap; /* ป้องกันหัวตารางตัดบรรทัด */
    }

    /* กรณีเป็นหน้าจอมือถือ (Responsive) */
    @media (max-width: 768px) {
        table td, table th {
            font-size: 14px !important; /* ย่อลงนิดเดียวในมือถือเพื่อไม่ให้ตารางล้น */
        }
    }
    
    /* บังคับฟอนต์ Sarabun ให้คมชัดทุกหน้า */
    body {
        font-family: 'Sarabun', sans-serif !important;
    }
    /* 1. ขยาย Container ให้กว้างขึ้น (เกือบเต็มจอ) */
    .container, .max-w-7xl {
        max-width: 90% !important; /* กางออก 95% ของความกว้างหน้าจอ */
        width: 90% !important;
    }

    /* 2. ถ้าใช้ Tailwind และอยากให้กางแบบเต็มพิกัด */
    .max-w-7xl {
        max-width: 100% !important; /* หรือใช้ 1600px ถ้าไม่อยากให้เต็มจอจนเกินไป */
    }

    /* 3. ปรับลด Padding ด้านข้างเพื่อให้พื้นที่ตารางมากขึ้น */
    .px-4, .sm:px-6, .lg:px-8 {
        padding-left: 1rem !important;
        padding-right: 1rem !important;
    }

    /* 4. แถม: ช่วยให้ตารางที่ยาวมากๆ เลื่อนซ้าย-ขวาได้ในมือถือโดยไม่ทำให้หน้าเว็บเพี้ยน */
    .overflow-x-auto {
        -webkit-overflow-scrolling: touch;
    }
</style>
    <style>
        body { background-color: #fafaf9; } /* Stone 50 ให้ความรู้สึกอุ่นกว่าเทา */
        .note-editor { font-family: 'Sarabun', sans-serif; }

        /* Custom Scrollbar */
        .scrollbar-thin::-webkit-scrollbar { width: 5px; }
        .scrollbar-thin::-webkit-scrollbar-track { background: transparent; }
        .scrollbar-thin::-webkit-scrollbar-thumb { background: #d6d3d1; border-radius: 10px; }
        .scrollbar-thin::-webkit-scrollbar-thumb:hover { background: #78350f; }

        /* DataTables Styling - ปรับเป็นโทนน้ำตาล/ทอง */
        .dataTables_wrapper .dataTables_filter {
            @apply mb-8 flex justify-end items-center !important;
            width: 100% !important;
        }
        .dataTables_wrapper .dataTables_filter label {
            @apply flex items-center bg-white border-2 border-amber-500/30 rounded-2xl shadow-sm hover:shadow-md hover:border-amber-600 transition-all duration-300 !important;
            min-width: 400px !important;
            height: 65px !important;
            padding: 0 1.25rem !important;
        }
        .dataTables_wrapper .dataTables_filter input {
            @apply border-none focus:ring-0 p-0 ml-3 pl-8 font-bold text-gray-700 placeholder-gray-300 bg-transparent !important;
            font-size: 1.1rem !important; 
            width: 400px !important;
            height: 40px !important;
            outline: none !important;
        }
        .dataTables_wrapper .dataTables_paginate {
            @apply flex flex-row items-center justify-center mt-8 !important;
            gap: 6px !important; 
        }
        .dataTables_wrapper .dataTables_paginate span {
            @apply flex flex-row gap-1.5 !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            @apply flex items-center justify-center min-w-[40px] h-[40px] bg-white border border-gray-100 text-gray-600 rounded-xl font-black text-sm transition-all duration-200 cursor-pointer !important;
            margin: 0 !important;
            padding: 0 10px !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            @apply bg-primary text-white border-primary shadow-md !important;
        }
    </style>

@include('layouts._pagination')
</head>
<body class="flex flex-col min-h-screen text-slate-800" style="--ui-primary: {{ $themePrimary }}; --ui-secondary: {{ $themeSecondary }};">

    <nav class="bg-primary shadow-lg sticky top-0 z-50" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                
                <div class="flex items-center">
                    <a href="{{ url('/') }}" class="flex-shrink-0 flex items-center text-white gap-2 group">
                        <img class="h-10 w-auto bg-white rounded-full p-1" src="{{ asset('imgs/obec_icon2.png') }}" alt="Logo">
                        <span class="font-bold text-lg tracking-wide group-hover:text-amber-200 transition">
                            {{ session('app_name').' ระดับเครือข่ายสถานศึกษา ปีการศึกษา '.session('app_year') }}
                        </span>
                    </a>
                </div>

                <div class="hidden md:flex md:items-center md:space-x-4">
                    <a href="{{url('/area/'.session('area'))??'/'}}" class="text-white hover:bg-primaryDark px-3 py-2 rounded-md text-sm font-medium transition">
                        <i class="ri-home-4-line mr-1"></i> หน้าหลัก
                    </a>

                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.away="open = false" class="text-white hover:bg-primaryDark px-3 py-2 rounded-md text-sm font-medium flex items-center gap-1">
                            <i class="ri-information-line mr-1"></i> ข้อมูลสารสนเทศ <i class="ri-arrow-down-s-line text-xs"></i>
                        </button>
                        <div x-show="open" class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl py-2 z-50 border border-gray-100 transition-all" style="display: none;">
                            <a href="{{ url('area/report/participants/'.(session('area') ?: Auth::user()?->schoolgroup)) }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-amber-50 hover:text-secondary font-medium"><i class="ri-file-excel-2-line"></i> รายงานผู้เข้าร่วม (Excel)</a>
                            <div class="border-t border-gray-100 my-1"></div>
                            <a href="{{ url('area/report/'.(session('area') ?: Auth::user()?->schoolgroup))}}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-amber-50 hover:text-secondary font-bold"><i class="ri-medal-line text-amber-500"></i> เกียรติบัตร</a>
                        </div>
                    </div>

                    @guest
                        <a href="{{ route('login') }}" class="bg-secondary hover:bg-emerald-800 text-white px-6 py-2 rounded-full text-sm font-bold shadow-lg transition transform active:scale-95">
                            <i class="ri-login-circle-line mr-1"></i> Login
                        </a>
                    @else
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" @click.away="open = false" class="flex items-center gap-2 text-white hover:bg-primaryDark px-3 py-2 rounded-md text-sm font-medium transition">
                                <span class="bg-white text-primary rounded-full w-8 h-8 flex items-center justify-center font-bold shadow-sm">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </span>
                                <div class="text-left leading-tight hidden lg:block">
                                    <div class="font-bold">{{ Auth::user()->name }}</div>
                                    <div class="text-[10px] text-amber-200 uppercase tracking-tighter">{{ position(Auth::user()->position) }}</div>
                                </div>
                                <i class="ri-arrow-down-s-line text-xs ml-1"></i>
                            </button>
                            
                            <div x-show="open" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 transform scale-95"
                                 x-transition:enter-end="opacity-100 transform scale-100"
                                 class="absolute right-0 mt-2 w-72 bg-white rounded-3xl shadow-2xl py-3 z-50 border border-gray-100 max-h-[calc(100vh-100px)] overflow-y-auto scrollbar-thin" 
                                 style="display: none;">
                                
                                @if(Auth::user()->position >= 10 || Auth::user()->position==1 || Auth::user()->position==5 || Auth::user()->position == 0)
                                    <div class="px-5 py-2 text-[10px] font-black text-amber-600 uppercase tracking-[0.2em] italic">Management / ผู้ดูแล</div>
                                    
                                    <div class="px-2 space-y-0.5">
                                        @if(Auth::user()->position==1 || Auth::user()->position==10)
                                            <a href="{{ url('centersillapa')}}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-bold text-slate-600 hover:bg-amber-50 hover:text-amber-800 rounded-2xl transition-all">
                                                <i class="ri-edit-2-line text-amber-600 text-lg"></i> บันทึกผลการแข่งขัน
                                            </a>
                                            <a href="{{ url('jow')}}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-bold text-slate-600 hover:bg-amber-50 hover:text-amber-800 rounded-2xl transition-all">
                                                <i class="ri-user-star-line text-amber-600 text-lg"></i> กรรมการตัดสิน
                                            </a>
                                            <a href="{{ url('printpointdoc')}}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-bold text-slate-600 hover:bg-amber-50 hover:text-amber-800 rounded-2xl transition-all">
                                                <i class="ri-file-list-3-line text-amber-600 text-lg"></i> พิมพ์เอกสาร
                                            </a>
                                            <a href="{{ url('shownotice')}}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-bold text-slate-600 hover:bg-amber-50 hover:text-amber-800 rounded-2xl transition-all">
                                                <i class="ri-megaphone-line text-amber-600 text-lg"></i> เพิ่มประกาศ
                                            </a>
                                        @endif

                                        @if(Auth::user()->position == 10)
                                            <div class="my-3 border-t border-slate-100 mx-4"></div>
                                            <div class="px-4 py-1 mb-1">
                                                <span class="text-[10px] font-black text-emerald-600 uppercase tracking-[0.2em] italic">
                                                    <i class="ri-shield-user-line mr-1"></i> Super Admin Control
                                                </span>
                                            </div>

                                            <a href="{{ url('title')}}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-bold text-slate-600 hover:bg-amber-50 hover:text-amber-800 rounded-2xl transition-all">
                                                <i class="ri-text-spacing text-amber-600 text-lg"></i> จัดการคำนำหน้า
                                            </a>
                                            <a href="{{ url('myuser')}}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-bold text-slate-600 hover:bg-amber-50 hover:text-amber-800 rounded-2xl transition-all">
                                                <i class="ri-user-settings-line text-amber-600 text-lg"></i> จัดการข้อมูลผู้ใช้
                                            </a>
                                            <a href="{{ url('branch')}}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-bold text-slate-600 hover:bg-amber-50 hover:text-amber-800 rounded-2xl transition-all">
                                                <i class="ri-map-pin-2-line text-amber-600 text-lg"></i> จัดการข้อมูลศูนย์แข่งขัน
                                            </a>
                                            <a href="{{ url('schoolgroup')}}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-bold text-slate-600 hover:bg-amber-50 hover:text-amber-800 rounded-2xl transition-all">
                                                <i class="ri-group-line text-amber-600 text-lg"></i> จัดการเครือข่ายสถานศึกษา
                                            </a>
                                            <a href="{{ url('school')}}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-bold text-slate-600 hover:bg-amber-50 hover:text-amber-800 rounded-2xl transition-all">
                                                <i class="ri-government-line text-amber-600 text-lg"></i> จัดการข้อมูลโรงเรียน
                                            </a>
                                            <a href="{{ url('Notice')}}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-bold text-slate-600 hover:bg-amber-50 hover:text-amber-800 rounded-2xl transition-all">
                                                <i class="ri-notification-3-line text-amber-600 text-lg"></i> จัดการประกาศ
                                            </a>
                                            <a href="{{ url('config')}}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-bold text-slate-600 hover:bg-emerald-50 hover:text-emerald-800 border-t border-emerald-50 mt-2 rounded-2xl transition-all">
                                                <i class="ri-settings-5-line text-emerald-600 text-lg"></i> ตั้งค่าระบบ (Super Admin)
                                            </a>
                                            <a href="{{ url('systemactive')}}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-bold text-emerald-700 hover:bg-emerald-50 rounded-2xl transition-all">
                                                <i class="ri-toggle-line text-emerald-600 text-lg"></i> ปิด/เปิดระบบบันทึก
                                            </a>
                                            <a href="{{ url('datatranfer')}}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-bold text-slate-600 hover:bg-emerald-50 hover:text-emerald-800 rounded-2xl transition-all">
                                                <i class="ri-download-cloud-2-line text-emerald-500 text-lg"></i> ดึงข้อมูลจากเครือข่าย
                                            </a>

                                            <div class="my-3 border-t border-slate-100 mx-4"></div>
                                            <div class="px-4 py-1 mb-1">
                                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] italic">Competition Config</span>
                                            </div>

                                            <a href="{{ url('class')}}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-bold text-slate-600 hover:bg-amber-50 hover:text-amber-800 rounded-2xl transition-all">
                                                <i class="ri-stack-line text-amber-600 text-lg"></i> จัดการช่วงชั้น
                                            </a>
                                            <a href="{{ url('subjectgroup')}}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-bold text-slate-600 hover:bg-amber-50 hover:text-amber-800 rounded-2xl transition-all">
                                                <i class="ri-book-3-line text-amber-600 text-lg"></i> จัดการรายวิชา
                                            </a>
                                            <a href="{{ url('activity')}}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-bold text-slate-600 hover:bg-amber-50 hover:text-amber-800 rounded-2xl transition-all">
                                                <i class="ri-flag-2-line text-amber-600 text-lg"></i> จัดการรายการแข่งขัน
                                            </a>
                                            <a href="{{ url('dayofsillapa')}}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-bold text-slate-600 hover:bg-amber-50 hover:text-amber-800 rounded-2xl transition-all">
                                                <i class="ri-calendar-todo-line text-amber-600 text-lg"></i> จัดการวันการแข่งขัน
                                            </a>
                                        @endif

                                        @if(Auth::user()->position==5)
                                            <div class="border-t border-gray-100 my-2"></div>
                                            <a href="{{ url('area/printdocs') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-bold text-gray-700 hover:bg-emerald-50 hover:text-emerald-800 rounded-2xl transition">
                                                <i class="ri-printer-line text-emerald-600 text-lg"></i> พิมพ์เอกสาร
                                            </a>
                                          

                                            <a href="{{ url('area/areasubjectgroup/'.Auth::user()->schoolgroup ?? '')}}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-bold text-gray-700 hover:bg-emerald-50 hover:text-emerald-800 rounded-2xl transition-all">
                                            <i class="ri-user-add-line text-emerald-600 text-lg"></i> 
                                            บันทึกข้อมูลนักเรียน : {{ Auth::user()->name }} {{ Auth::user()->lastname}}
                                        </a>

                                        <a href="{{ url('areajow')}}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-bold text-gray-700 hover:bg-emerald-50 hover:text-emerald-800 rounded-2xl transition-all">
                                            <i class="ri-user-star-line text-emerald-600 text-lg"></i> 
                                            บันทึกรายชื่อกรรมการตัดสิน
                                        </a>

                                        <a href="{{ url('area/addjow2')}}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-bold text-gray-700 hover:bg-emerald-50 hover:text-emerald-800 rounded-2xl transition-all">
                                            <i class="ri-team-line text-emerald-600 text-lg"></i> 
                                            บันทึกรายชื่อกรรมการดำเนินงาน
                                        </a>

                                        <div class="my-2 border-t border-gray-100 mx-4"></div>

                                        <a href="{{ url('area/areasubjectgroup/'.Auth::user()->schoolgroup ?? '')}}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-bold text-gray-700 hover:bg-emerald-50 hover:text-emerald-800 rounded-2xl transition-all">
                                            <i class="ri-trophy-line text-emerald-600 text-lg"></i> 
                                            บันทึกผลการแข่งขัน
                                        </a>

                                        <a href="{{ url('area/areasubjectgroup/'.Auth::user()->schoolgroup ?? '')}}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-bold text-gray-700 hover:bg-emerald-50 hover:text-emerald-800 rounded-2xl transition-all">
                                            <i class="ri-arrow-go-back-line text-emerald-600 text-lg"></i> 
                                            ยกเลิกผลการแข่งขัน
                                        </a>

                                        <div class="my-2 border-t border-gray-100 mx-4"></div>

                                        <a href="{{ url('area/shownotice')}}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-bold text-gray-700 hover:bg-emerald-50 hover:text-emerald-800 rounded-2xl transition-all">
                                            <i class="ri-notification-3-line text-emerald-600 text-lg"></i> 
                                            เพิ่มประกาศแจ้งผู้เข้าร่วมแข่งขัน
                                        </a>

                                        <a href="{{ url('area/report/participants/'.(session('area') ?: Auth::user()?->schoolgroup)) }}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-bold text-gray-700 hover:bg-emerald-50 hover:text-emerald-800 rounded-2xl transition-all">
                                            <i class="ri-file-excel-2-line text-emerald-600 text-lg"></i>
                                            รายงานผู้เข้าร่วม (Excel)
                                        </a>

                                        <a href="{{ url('area/areaconfig')}}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-bold text-gray-700 hover:bg-emerald-50 hover:text-emerald-800 rounded-2xl transition-all">
                                            <i class="ri-settings-4-line text-emerald-600 text-lg"></i> 
                                            การตั้งค่าระบบ
                                        </a>

                                        <a href="{{ url('area/areauser')}}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-bold text-gray-700 hover:bg-emerald-50 hover:text-emerald-800 rounded-2xl transition-all">
                                            <i class="ri-user-settings-line text-emerald-600 text-lg"></i> 
                                            จัดการข้อมูลผู้ใช้งาน
                                        </a>
                                        @endif

                                        @if(Auth::user()->position==0)
                                            
                                        <a href="{{ url('area/areasubjectgroup/'.Auth::user()->schoolgroup ?? '')}}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-bold text-gray-700 hover:bg-emerald-50 hover:text-emerald-800 rounded-2xl transition-all">
                                            <i class="ri-trophy-line text-emerald-600 text-lg"></i> 
                                            บันทึกรายชื่อนักเรียน
                                        </a>
                                        <a href="{{ url('shoolPrintCardArea/')}}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-bold text-gray-700 hover:bg-emerald-50 hover:text-emerald-800 rounded-2xl transition-all">
                                            <i class="ri-id-card-line text-emerald-600 text-lg"></i>
                                            พิมพ์บัตร
                                        </a>
                                        @endif
                                    </div>
                                @endif

                                <div class="border-t border-gray-100 my-2"></div>
                                <div class="px-2 space-y-0.5">
                                    <a href="{{ url('center/myuser/'.auth::user()->id.'/edit')}}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-bold text-gray-600 hover:bg-slate-50 rounded-2xl transition-all">
                                        <i class="ri-key-2-line text-lg"></i> เปลี่ยนรหัสผ่าน
                                    </a>
                                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="flex items-center gap-3 px-4 py-2.5 text-sm font-black text-red-600 hover:bg-red-50 rounded-2xl transition-all">
                                        <i class="ri-logout-circle-r-line text-lg"></i> LOGOUT / ออกจากระบบ
                                    </a>
                                </div>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">{{ csrf_field() }}</form>
                            </div>
                        </div>
                    @endguest
                </div>

                <div class="-mr-2 flex md:hidden">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="bg-primaryDark inline-flex items-center justify-center p-2 rounded-md text-white hover:text-white hover:bg-primary focus:outline-none transition">
                        <i class="ri-menu-3-line text-2xl" x-show="!mobileMenuOpen"></i>
                        <i class="ri-close-line text-2xl" x-show="mobileMenuOpen" style="display:none;"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="md:hidden bg-primaryDark" x-show="mobileMenuOpen" x-transition style="display:none;">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                <a href="{{url('/')}}" class="text-white block px-3 py-2 rounded-md text-base font-medium hover:bg-primary">หน้าหลัก</a>
                @guest
                    <a href="{{ route('login') }}" class="text-white block px-3 py-2 rounded-md text-base font-medium hover:bg-primary">Login</a>
                @endguest
            </div>
        </div>
    </nav>

    <main class="flex-grow">
        <br>
        @yield('content')
    </main>

    <footer class="bg-white border-t-4 border-primary mt-12 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="mb-4 flex items-center justify-center gap-4">
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-50 text-emerald-700 rounded-full text-xs font-bold border border-emerald-200 shadow-sm">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                    <span>ผู้ใช้งานออนไลน์: <strong class="font-black">{{ number_format(online_count()) }}</strong> คน</span>
                </div>
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-slate-50 text-slate-700 rounded-full text-xs font-bold border border-slate-200 shadow-sm">
                    <i class="ri-eye-line text-slate-500 text-sm"></i>
                    <span>ผู้เข้าชมทั้งหมด: <strong class="font-black">{{ number_format(visitor_count()) }}</strong> ครั้ง</span>
                </div>
            </div>
            @if(filled(config('app.area')))
                <p class="text-gray-700 text-sm font-bold mb-2"><i class="ri-map-pin-2-line text-primary mr-1"></i>{{ config('app.area') }}</p>
            @endif
            <p class="text-gray-500 text-sm font-bold">© COPYRIGHT 2026, ALL RIGHTS RESERVED</p>
            <p class="text-gray-400 text-xs mt-1">Version 1.2.0 (Develop by <a href="https://www.facebook.com/piguyso" class="text-primary hover:underline">piguyso</a>)</p>
        </div>
    </footer>

    
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script>
        window.showCustomConfirm = function(title, message, onConfirm, okText = 'ยืนยัน', isDanger = true) {
            const iconBg = isDanger ? '#fef2f2' : '#ecfdf5';
            const iconColor = isDanger ? '#ef4444' : getComputedStyle(document.body).getPropertyValue('--ui-primary').trim();
            const iconClass = isDanger ? 'ri-error-warning-line' : 'ri-question-line';
            const btnBg = isDanger ? '#dc2626' : getComputedStyle(document.body).getPropertyValue('--ui-primary').trim();

            const modalHtml = `
                <div id="custom-confirm-modal" style="position: fixed; inset: 0; z-index: 10000; display: flex; align-items: center; justify-content: center; background-color: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); transition: all 0.2s;" class="fixed inset-0 z-[10000] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm animate-in fade-in duration-200">
                    <div style="background-color: white; border-radius: 24px; padding: 32px; max-width: 448px; width: 100%; mx: 16px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); border: 1px solid #f1f5f9; text-align: center; font-family: 'Sarabun', 'Inter', sans-serif;" class="bg-white rounded-3xl p-8 max-w-md w-full mx-4 shadow-2xl border border-slate-100 text-center animate-in zoom-in duration-300">
                        <div style="width: 64px; height: 64px; background-color: ${iconBg}; color: ${iconColor}; border-radius: 9999px; display: flex; align-items: center; justify-content: center; margin: 0 auto 24px; font-size: 30px;" class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-6 text-3xl">
                            <i class="${iconClass}"></i>
                        </div>
                        <h3 style="font-size: 20px; font-weight: 900; color: #1e293b; margin-bottom: 8px; margin-top: 0;" class="text-xl font-black text-slate-800 mb-2 font-sarabun">${title}</h3>
                        <p style="font-size: 14px; color: #64748b; font-weight: 500; margin-bottom: 32px; line-height: 1.625; white-space: pre-line;" class="text-sm text-slate-500 font-medium mb-8 font-sarabun leading-relaxed">${message}</p>
                        <div style="display: flex; gap: 16px;" class="flex gap-4 font-sarabun">
                            <button id="confirm-cancel" style="flex: 1; padding: 14px 0; background-color: #f1f5f9; color: #475569; border-radius: 16px; border: none; font-weight: 900; font-size: 14px; cursor: pointer; transition: all 0.2s;" class="flex-1 py-3.5 bg-slate-100 text-slate-600 rounded-2xl font-black text-sm hover:bg-slate-200 transition-all uppercase tracking-wider">
                                ยกเลิก
                            </button>
                            <button id="confirm-ok" style="flex: 1; padding: 14px 0; background-color: ${btnBg}; color: white; border-radius: 16px; border: none; font-weight: 900; font-size: 14px; cursor: pointer; transition: all 0.2s;" class="flex-1 py-3.5 text-white rounded-2xl font-black text-sm hover:opacity-90 active:scale-95 transition-all shadow-lg uppercase tracking-wider">
                                ${okText}
                            </button>
                        </div>
                    </div>
                </div>
            `;
            document.body.insertAdjacentHTML('beforeend', modalHtml);
            const modal = document.getElementById('custom-confirm-modal');
            const cancelBtn = document.getElementById('confirm-cancel');
            const okBtn = document.getElementById('confirm-ok');
            const closeModal = () => { modal.remove(); };
            cancelBtn.addEventListener('click', closeModal);
            okBtn.addEventListener('click', () => {
                closeModal();
                if (typeof onConfirm === 'function') {
                    onConfirm();
                }
            });
            modal.addEventListener('click', (e) => {
                if (e.target === modal) closeModal();
            });
        };

        window.showCustomAlert = function(title, message, type = 'info', onOk = null) {
            let iconBg = '#eff6ff';
            let iconColor = '#3b82f6';
            let iconClass = 'ri-information-line';
            let btnBg = '#3b82f6';

            if (type === 'success') {
                iconBg = '#ecfdf5';
                iconColor = getComputedStyle(document.body).getPropertyValue('--ui-primary').trim();
                iconClass = 'ri-checkbox-circle-line';
                btnBg = getComputedStyle(document.body).getPropertyValue('--ui-primary').trim();
            } else if (type === 'error' || type === 'danger') {
                iconBg = '#fef2f2';
                iconColor = '#ef4444';
                iconClass = 'ri-error-warning-line';
                btnBg = '#dc2626';
            } else if (type === 'warning') {
                iconBg = '#fffbeb';
                iconColor = '#f59e0b';
                iconClass = 'ri-alert-line';
                btnBg = '#d97706';
            }

            const modalHtml = `
                <div id="custom-alert-modal" style="position: fixed; inset: 0; z-index: 10000; display: flex; align-items: center; justify-content: center; background-color: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); transition: all 0.2s;" class="fixed inset-0 z-[10000] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm animate-in fade-in duration-200">
                    <div style="background-color: white; border-radius: 24px; padding: 32px; max-width: 448px; width: 100%; mx: 16px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); border: 1px solid #f1f5f9; text-align: center; font-family: 'Sarabun', 'Inter', sans-serif;" class="bg-white rounded-3xl p-8 max-w-md w-full mx-4 shadow-2xl border border-slate-100 text-center animate-in zoom-in duration-300">
                        <div style="width: 64px; height: 64px; background-color: ${iconBg}; color: ${iconColor}; border-radius: 9999px; display: flex; align-items: center; justify-content: center; margin: 0 auto 24px; font-size: 30px;" class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-6 text-3xl">
                            <i class="${iconClass}"></i>
                        </div>
                        <h3 style="font-size: 20px; font-weight: 900; color: #1e293b; margin-bottom: 8px; margin-top: 0;" class="text-xl font-black text-slate-800 mb-2 font-sarabun">${title}</h3>
                        <p style="font-size: 14px; color: #64748b; font-weight: 500; margin-bottom: 32px; line-height: 1.625; white-space: pre-line;" class="text-sm text-slate-500 font-medium mb-8 font-sarabun leading-relaxed">${message}</p>
                        <div style="display: flex;" class="font-sarabun">
                            <button id="alert-ok" style="flex: 1; padding: 14px 0; background-color: ${btnBg}; color: white; border-radius: 16px; border: none; font-weight: 900; font-size: 14px; cursor: pointer; transition: all 0.2s;" class="flex-1 py-3.5 text-white rounded-2xl font-black text-sm uppercase tracking-wider hover:opacity-90 active:scale-95 transition-all">
                                ตกลง
                            </button>
                        </div>
                    </div>
                </div>
            `;
            document.body.insertAdjacentHTML('beforeend', modalHtml);
            const modal = document.getElementById('custom-alert-modal');
            const okBtn = document.getElementById('alert-ok');
            const closeModal = () => {
                modal.remove();
                if (typeof onOk === 'function') {
                    onOk();
                }
            };
            okBtn.addEventListener('click', closeModal);
            modal.addEventListener('click', (e) => {
                if (e.target === modal) closeModal();
            });
        };
    </script>
    @if(Session::has('massage') || Session::has('success') || Session::has('status'))
        <div id="toast-notification" style="position: fixed; bottom: 24px; right: 24px; z-index: 10000; display: flex; align-items: center; gap: 12px; background-color: var(--ui-primary); color: white; padding: 16px 24px; border-radius: 16px; box-shadow: 0 10px 15px -3px color-mix(in srgb, var(--ui-primary) 30%, transparent), 0 4px 6px -4px color-mix(in srgb, var(--ui-primary) 30%, transparent); font-family: 'Sarabun', sans-serif; font-weight: bold; font-size: 14px; animation: slideInUp 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards; transition: all 0.3s ease;">
            <i class="ri-checkbox-circle-fill" style="font-size: 20px;"></i>
            <span>{{ Session::get('massage') ?? Session::get('success') ?? Session::get('status') }}</span>
        </div>
        <style>
            @keyframes slideInUp {
                from {
                    transform: translateY(100px);
                    opacity: 0;
                }
                to {
                    transform: translateY(0);
                    opacity: 1;
                }
            }
        </style>
        <script>
            setTimeout(() => {
                const toast = document.getElementById('toast-notification');
                if (toast) {
                    toast.style.transform = 'translateY(20px)';
                    toast.style.opacity = '0';
                    setTimeout(() => toast.remove(), 300);
                }
            }, 5000);
        </script>
    @endif
    @if(Session::has('error'))
        <div id="toast-error" style="position: fixed; bottom: 24px; right: 24px; z-index: 10000; display: flex; align-items: center; gap: 12px; background-color: #ef4444; color: white; padding: 16px 24px; border-radius: 16px; box-shadow: 0 10px 15px -3px rgba(239, 68, 68, 0.3); font-family: 'Sarabun', sans-serif; font-weight: bold; font-size: 14px; animation: slideInUp 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards; transition: all 0.3s ease;">
            <i class="ri-error-warning-fill" style="font-size: 20px;"></i>
            <span>{{ Session::get('error') }}</span>
        </div>
        <script>
            setTimeout(() => {
                const toast = document.getElementById('toast-error');
                if (toast) {
                    toast.style.transform = 'translateY(20px)';
                    toast.style.opacity = '0';
                    setTimeout(() => toast.remove(), 300);
                }
            }, 5000);
        </script>
    @endif
<script src="{{ asset('js/ui-feedback.js') }}?v={{ filemtime(public_path('js/ui-feedback.js')) }}"></script>
<script src="{{ asset('js/image-upload-rules.js') }}"></script>
<script>
    // ส่งสัญญาณว่ายังเปิดหน้าเว็บอยู่ เพื่อให้นับเป็นผู้ใช้ออนไลน์ถูกต้องแม้ไม่ได้เปลี่ยนหน้า
    (function () {
        var ping = function () {
            if (document.hidden) return;
            fetch("{{ url('visitor-ping') }}", { headers: { 'X-Visitor-Ping': '1' }, credentials: 'same-origin' }).catch(function () {});
        };
        setInterval(ping, 120000);
    })();
</script>
</body>
</html>
