<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ระบบบริหารจัดการโรงเรียน')</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Sarabun', sans-serif; }
        
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
            color: #059669 !important; /* สีเขียวหลัก */
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current,
        .dataTables_wrapper .dataTables_paginate .paginate_button.active {
            background-color: #059669 !important; /* สีเขียวเด่นชัด */
            color: #ffffff !important;
            border-color: #059669 !important;
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
            color: #059669 !important;
        }

        /* โหมดกำลังใช้งาน (Active) */
        nav[role="navigation"] span[aria-current="page"],
        nav[role="navigation"] .bg-emerald-600,
        nav[role="navigation"] .bg-primary,
        .pagination .page-item.active .page-link {
            background-color: #059669 !important;
            color: #ffffff !important;
            border-color: #059669 !important;
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
    </style>
</head>
<body class="bg-gray-50 text-slate-800">

    <div class="flex h-screen overflow-hidden">
        
        <aside class="w-64 bg-slate-900 text-white flex flex-col shadow-xl z-20 hidden md:flex">
            <div class="h-16 flex items-center justify-center border-b border-slate-800 font-bold text-xl tracking-wider">
                SCHOOL <span class="text-blue-400 ml-1">ADMIN</span>
            </div>
            
            <nav class="flex-1 py-4 overflow-y-auto">
                <ul class="space-y-1 px-2">
                    <li>
                        <a href="#" class="flex items-center px-4 py-3 rounded-lg bg-blue-600 text-white">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                            แดชบอร์ด
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center px-4 py-3 rounded-lg text-slate-400 hover:bg-slate-800 hover:text-white transition-colors">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            ข้อมูลนักเรียน
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <div class="flex-1 flex flex-col relative overflow-hidden">
            
            <header class="h-16 bg-white shadow-sm flex items-center justify-between px-6 z-10">
                <h1 class="text-xl font-semibold text-gray-800">
                    @yield('header_title', 'ยินดีต้อนรับ')
                </h1>
                <div class="flex items-center gap-3">
                    <span class="text-sm text-gray-600">Admin User</span>
                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold">A</div>
                </div>
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6">
                @yield('content')
            </main>

        </div>
    </div>

    @stack('scripts')
</body>
</html>