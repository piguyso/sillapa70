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

    /* gradient และเงาสีเขียว (emerald/green) -> สี theme */
    .from-emerald-50 { --tw-gradient-from: color-mix(in srgb, var(--ui-primary) 16%, white) var(--tw-gradient-from-position) !important; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, transparent) !important; }
    .via-emerald-50 { --tw-gradient-stops: var(--tw-gradient-from), color-mix(in srgb, var(--ui-primary) 16%, white) var(--tw-gradient-via-position), var(--tw-gradient-to, transparent) !important; }
    .to-emerald-50 { --tw-gradient-to: color-mix(in srgb, var(--ui-primary) 16%, white) var(--tw-gradient-to-position) !important; }
    .shadow-emerald-50 { --tw-shadow-color: color-mix(in srgb, var(--ui-primary) 20%, transparent) !important; --tw-shadow: var(--tw-shadow-colored) !important; }
    .from-emerald-100 { --tw-gradient-from: color-mix(in srgb, var(--ui-primary) 16%, white) var(--tw-gradient-from-position) !important; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, transparent) !important; }
    .via-emerald-100 { --tw-gradient-stops: var(--tw-gradient-from), color-mix(in srgb, var(--ui-primary) 16%, white) var(--tw-gradient-via-position), var(--tw-gradient-to, transparent) !important; }
    .to-emerald-100 { --tw-gradient-to: color-mix(in srgb, var(--ui-primary) 16%, white) var(--tw-gradient-to-position) !important; }
    .shadow-emerald-100 { --tw-shadow-color: color-mix(in srgb, var(--ui-primary) 20%, transparent) !important; --tw-shadow: var(--tw-shadow-colored) !important; }
    .from-emerald-200 { --tw-gradient-from: var(--ui-primary) var(--tw-gradient-from-position) !important; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, transparent) !important; }
    .via-emerald-200 { --tw-gradient-stops: var(--tw-gradient-from), var(--ui-primary) var(--tw-gradient-via-position), var(--tw-gradient-to, transparent) !important; }
    .to-emerald-200 { --tw-gradient-to: var(--ui-primary) var(--tw-gradient-to-position) !important; }
    .shadow-emerald-200 { --tw-shadow-color: color-mix(in srgb, var(--ui-primary) 20%, transparent) !important; --tw-shadow: var(--tw-shadow-colored) !important; }
    .from-emerald-300 { --tw-gradient-from: var(--ui-primary) var(--tw-gradient-from-position) !important; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, transparent) !important; }
    .via-emerald-300 { --tw-gradient-stops: var(--tw-gradient-from), var(--ui-primary) var(--tw-gradient-via-position), var(--tw-gradient-to, transparent) !important; }
    .to-emerald-300 { --tw-gradient-to: var(--ui-primary) var(--tw-gradient-to-position) !important; }
    .shadow-emerald-300 { --tw-shadow-color: color-mix(in srgb, var(--ui-primary) 20%, transparent) !important; --tw-shadow: var(--tw-shadow-colored) !important; }
    .from-emerald-400 { --tw-gradient-from: var(--ui-primary) var(--tw-gradient-from-position) !important; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, transparent) !important; }
    .via-emerald-400 { --tw-gradient-stops: var(--tw-gradient-from), var(--ui-primary) var(--tw-gradient-via-position), var(--tw-gradient-to, transparent) !important; }
    .to-emerald-400 { --tw-gradient-to: var(--ui-primary) var(--tw-gradient-to-position) !important; }
    .shadow-emerald-400 { --tw-shadow-color: color-mix(in srgb, var(--ui-primary) 20%, transparent) !important; --tw-shadow: var(--tw-shadow-colored) !important; }
    .from-emerald-500 { --tw-gradient-from: var(--ui-primary) var(--tw-gradient-from-position) !important; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, transparent) !important; }
    .via-emerald-500 { --tw-gradient-stops: var(--tw-gradient-from), var(--ui-primary) var(--tw-gradient-via-position), var(--tw-gradient-to, transparent) !important; }
    .to-emerald-500 { --tw-gradient-to: var(--ui-primary) var(--tw-gradient-to-position) !important; }
    .shadow-emerald-500 { --tw-shadow-color: color-mix(in srgb, var(--ui-primary) 20%, transparent) !important; --tw-shadow: var(--tw-shadow-colored) !important; }
    .from-emerald-600 { --tw-gradient-from: var(--ui-primary) var(--tw-gradient-from-position) !important; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, transparent) !important; }
    .via-emerald-600 { --tw-gradient-stops: var(--tw-gradient-from), var(--ui-primary) var(--tw-gradient-via-position), var(--tw-gradient-to, transparent) !important; }
    .to-emerald-600 { --tw-gradient-to: var(--ui-primary) var(--tw-gradient-to-position) !important; }
    .shadow-emerald-600 { --tw-shadow-color: color-mix(in srgb, var(--ui-primary) 20%, transparent) !important; --tw-shadow: var(--tw-shadow-colored) !important; }
    .from-emerald-700 { --tw-gradient-from: color-mix(in srgb, var(--ui-primary) 85%, black) var(--tw-gradient-from-position) !important; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, transparent) !important; }
    .via-emerald-700 { --tw-gradient-stops: var(--tw-gradient-from), color-mix(in srgb, var(--ui-primary) 85%, black) var(--tw-gradient-via-position), var(--tw-gradient-to, transparent) !important; }
    .to-emerald-700 { --tw-gradient-to: color-mix(in srgb, var(--ui-primary) 85%, black) var(--tw-gradient-to-position) !important; }
    .shadow-emerald-700 { --tw-shadow-color: color-mix(in srgb, var(--ui-primary) 20%, transparent) !important; --tw-shadow: var(--tw-shadow-colored) !important; }
    .from-emerald-800 { --tw-gradient-from: color-mix(in srgb, var(--ui-primary) 70%, black) var(--tw-gradient-from-position) !important; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, transparent) !important; }
    .via-emerald-800 { --tw-gradient-stops: var(--tw-gradient-from), color-mix(in srgb, var(--ui-primary) 70%, black) var(--tw-gradient-via-position), var(--tw-gradient-to, transparent) !important; }
    .to-emerald-800 { --tw-gradient-to: color-mix(in srgb, var(--ui-primary) 70%, black) var(--tw-gradient-to-position) !important; }
    .shadow-emerald-800 { --tw-shadow-color: color-mix(in srgb, var(--ui-primary) 20%, transparent) !important; --tw-shadow: var(--tw-shadow-colored) !important; }
    .from-emerald-900 { --tw-gradient-from: color-mix(in srgb, var(--ui-primary) 55%, black) var(--tw-gradient-from-position) !important; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, transparent) !important; }
    .via-emerald-900 { --tw-gradient-stops: var(--tw-gradient-from), color-mix(in srgb, var(--ui-primary) 55%, black) var(--tw-gradient-via-position), var(--tw-gradient-to, transparent) !important; }
    .to-emerald-900 { --tw-gradient-to: color-mix(in srgb, var(--ui-primary) 55%, black) var(--tw-gradient-to-position) !important; }
    .shadow-emerald-900 { --tw-shadow-color: color-mix(in srgb, var(--ui-primary) 20%, transparent) !important; --tw-shadow: var(--tw-shadow-colored) !important; }
    .from-green-50 { --tw-gradient-from: color-mix(in srgb, var(--ui-primary) 16%, white) var(--tw-gradient-from-position) !important; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, transparent) !important; }
    .via-green-50 { --tw-gradient-stops: var(--tw-gradient-from), color-mix(in srgb, var(--ui-primary) 16%, white) var(--tw-gradient-via-position), var(--tw-gradient-to, transparent) !important; }
    .to-green-50 { --tw-gradient-to: color-mix(in srgb, var(--ui-primary) 16%, white) var(--tw-gradient-to-position) !important; }
    .shadow-green-50 { --tw-shadow-color: color-mix(in srgb, var(--ui-primary) 20%, transparent) !important; --tw-shadow: var(--tw-shadow-colored) !important; }
    .from-green-100 { --tw-gradient-from: color-mix(in srgb, var(--ui-primary) 16%, white) var(--tw-gradient-from-position) !important; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, transparent) !important; }
    .via-green-100 { --tw-gradient-stops: var(--tw-gradient-from), color-mix(in srgb, var(--ui-primary) 16%, white) var(--tw-gradient-via-position), var(--tw-gradient-to, transparent) !important; }
    .to-green-100 { --tw-gradient-to: color-mix(in srgb, var(--ui-primary) 16%, white) var(--tw-gradient-to-position) !important; }
    .shadow-green-100 { --tw-shadow-color: color-mix(in srgb, var(--ui-primary) 20%, transparent) !important; --tw-shadow: var(--tw-shadow-colored) !important; }
    .from-green-200 { --tw-gradient-from: var(--ui-primary) var(--tw-gradient-from-position) !important; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, transparent) !important; }
    .via-green-200 { --tw-gradient-stops: var(--tw-gradient-from), var(--ui-primary) var(--tw-gradient-via-position), var(--tw-gradient-to, transparent) !important; }
    .to-green-200 { --tw-gradient-to: var(--ui-primary) var(--tw-gradient-to-position) !important; }
    .shadow-green-200 { --tw-shadow-color: color-mix(in srgb, var(--ui-primary) 20%, transparent) !important; --tw-shadow: var(--tw-shadow-colored) !important; }
    .from-green-300 { --tw-gradient-from: var(--ui-primary) var(--tw-gradient-from-position) !important; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, transparent) !important; }
    .via-green-300 { --tw-gradient-stops: var(--tw-gradient-from), var(--ui-primary) var(--tw-gradient-via-position), var(--tw-gradient-to, transparent) !important; }
    .to-green-300 { --tw-gradient-to: var(--ui-primary) var(--tw-gradient-to-position) !important; }
    .shadow-green-300 { --tw-shadow-color: color-mix(in srgb, var(--ui-primary) 20%, transparent) !important; --tw-shadow: var(--tw-shadow-colored) !important; }
    .from-green-400 { --tw-gradient-from: var(--ui-primary) var(--tw-gradient-from-position) !important; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, transparent) !important; }
    .via-green-400 { --tw-gradient-stops: var(--tw-gradient-from), var(--ui-primary) var(--tw-gradient-via-position), var(--tw-gradient-to, transparent) !important; }
    .to-green-400 { --tw-gradient-to: var(--ui-primary) var(--tw-gradient-to-position) !important; }
    .shadow-green-400 { --tw-shadow-color: color-mix(in srgb, var(--ui-primary) 20%, transparent) !important; --tw-shadow: var(--tw-shadow-colored) !important; }
    .from-green-500 { --tw-gradient-from: var(--ui-primary) var(--tw-gradient-from-position) !important; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, transparent) !important; }
    .via-green-500 { --tw-gradient-stops: var(--tw-gradient-from), var(--ui-primary) var(--tw-gradient-via-position), var(--tw-gradient-to, transparent) !important; }
    .to-green-500 { --tw-gradient-to: var(--ui-primary) var(--tw-gradient-to-position) !important; }
    .shadow-green-500 { --tw-shadow-color: color-mix(in srgb, var(--ui-primary) 20%, transparent) !important; --tw-shadow: var(--tw-shadow-colored) !important; }
    .from-green-600 { --tw-gradient-from: var(--ui-primary) var(--tw-gradient-from-position) !important; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, transparent) !important; }
    .via-green-600 { --tw-gradient-stops: var(--tw-gradient-from), var(--ui-primary) var(--tw-gradient-via-position), var(--tw-gradient-to, transparent) !important; }
    .to-green-600 { --tw-gradient-to: var(--ui-primary) var(--tw-gradient-to-position) !important; }
    .shadow-green-600 { --tw-shadow-color: color-mix(in srgb, var(--ui-primary) 20%, transparent) !important; --tw-shadow: var(--tw-shadow-colored) !important; }
    .from-green-700 { --tw-gradient-from: color-mix(in srgb, var(--ui-primary) 85%, black) var(--tw-gradient-from-position) !important; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, transparent) !important; }
    .via-green-700 { --tw-gradient-stops: var(--tw-gradient-from), color-mix(in srgb, var(--ui-primary) 85%, black) var(--tw-gradient-via-position), var(--tw-gradient-to, transparent) !important; }
    .to-green-700 { --tw-gradient-to: color-mix(in srgb, var(--ui-primary) 85%, black) var(--tw-gradient-to-position) !important; }
    .shadow-green-700 { --tw-shadow-color: color-mix(in srgb, var(--ui-primary) 20%, transparent) !important; --tw-shadow: var(--tw-shadow-colored) !important; }
    .from-green-800 { --tw-gradient-from: color-mix(in srgb, var(--ui-primary) 70%, black) var(--tw-gradient-from-position) !important; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, transparent) !important; }
    .via-green-800 { --tw-gradient-stops: var(--tw-gradient-from), color-mix(in srgb, var(--ui-primary) 70%, black) var(--tw-gradient-via-position), var(--tw-gradient-to, transparent) !important; }
    .to-green-800 { --tw-gradient-to: color-mix(in srgb, var(--ui-primary) 70%, black) var(--tw-gradient-to-position) !important; }
    .shadow-green-800 { --tw-shadow-color: color-mix(in srgb, var(--ui-primary) 20%, transparent) !important; --tw-shadow: var(--tw-shadow-colored) !important; }
    .from-green-900 { --tw-gradient-from: color-mix(in srgb, var(--ui-primary) 55%, black) var(--tw-gradient-from-position) !important; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, transparent) !important; }
    .via-green-900 { --tw-gradient-stops: var(--tw-gradient-from), color-mix(in srgb, var(--ui-primary) 55%, black) var(--tw-gradient-via-position), var(--tw-gradient-to, transparent) !important; }
    .to-green-900 { --tw-gradient-to: color-mix(in srgb, var(--ui-primary) 55%, black) var(--tw-gradient-to-position) !important; }
    .shadow-green-900 { --tw-shadow-color: color-mix(in srgb, var(--ui-primary) 20%, transparent) !important; --tw-shadow: var(--tw-shadow-colored) !important; }
    [class*="shadow-emerald-"][class*="/"], [class*="shadow-green-"][class*="/"] { --tw-shadow-color: color-mix(in srgb, var(--ui-primary) 10%, transparent) !important; }
</style>
