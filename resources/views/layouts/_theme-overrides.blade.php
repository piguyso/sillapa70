{{-- ทำให้ทุกสีเขียว (emerald/green) และสีที่เขียนตายตัวในหน้าเก่า ๆ ใช้สี theme ที่ตั้งค่าไว้ (--ui-primary) --}}
<style>
    :root { --ui-primary: {{ $themePrimary }}; --ui-secondary: {{ $themeSecondary }}; }
    .bg-emerald-50, .bg-green-50 { background-color: color-mix(in srgb, var(--ui-primary) 8%, white) !important; }
    .bg-emerald-100, .bg-green-100 { background-color: color-mix(in srgb, var(--ui-primary) 16%, white) !important; }
    .bg-emerald-400, .bg-emerald-500, .bg-emerald-600, .bg-emerald-700,
    .bg-green-400, .bg-green-500, .bg-green-600, .bg-green-700 { background-color: var(--ui-primary) !important; }
    .hover\:bg-emerald-50:hover, .hover\:bg-green-50:hover { background-color: color-mix(in srgb, var(--ui-primary) 8%, white) !important; }
    .hover\:bg-emerald-600:hover, .hover\:bg-emerald-700:hover, .hover\:bg-green-600:hover, .hover\:bg-green-700:hover { background-color: color-mix(in srgb, var(--ui-primary) 85%, black) !important; }
    .text-emerald-400, .text-emerald-500, .text-emerald-600, .text-emerald-700, .text-emerald-800,
    .text-green-500, .text-green-600, .text-green-700, .text-green-800 { color: var(--ui-primary) !important; }
    .hover\:text-emerald-600:hover, .hover\:text-emerald-700:hover, .hover\:text-green-700:hover { color: var(--ui-primary) !important; }
    .border-emerald-100, .border-emerald-200, .border-emerald-300, .border-emerald-400, .border-emerald-500, .border-emerald-600,
    .border-green-200, .border-green-300, .border-green-500, .border-green-600 { border-color: var(--ui-primary) !important; }
    .hover\:border-emerald-500:hover { border-color: var(--ui-primary) !important; }
    .ring-emerald-500, .focus\:ring-emerald-500:focus { --tw-ring-color: var(--ui-primary) !important; }
    /* ปุ่ม/แถบแบ่งหน้าแบบเดิม */
    .btn-primary, .btn-success, .label-success, .badge-success, .page-item.active .page-link { background-color: var(--ui-primary) !important; border-color: var(--ui-primary) !important; color: #fff !important; }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover,
    nav[role="navigation"] a:hover, .pagination .page-link:hover { color: var(--ui-primary) !important; }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current,
    .dataTables_wrapper .dataTables_paginate .paginate_button.active,
    nav[role="navigation"] span[aria-current="page"], .pagination .page-item.active .page-link {
        background-color: var(--ui-primary) !important; border-color: var(--ui-primary) !important; color: #fff !important;
    }
</style>
