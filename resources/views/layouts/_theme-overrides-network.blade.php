{{-- สร้างอัตโนมัติ: แปลงคลาสสีส้ม/อำพันในหน้าระดับเครือข่ายให้ใช้สีประจำเครือข่าย (--ui-primary) --}}
<style>
    .bg-amber-100 { background-color: color-mix(in srgb, var(--ui-primary) 16%, white) !important; }
    .bg-amber-50 { background-color: color-mix(in srgb, var(--ui-primary) 8%, white) !important; }
    .bg-amber-50\/30 { background-color: color-mix(in srgb, color-mix(in srgb, var(--ui-primary) 8%, white) 30%, transparent) !important; }
    .bg-amber-900 { background-color: color-mix(in srgb, var(--ui-primary) 55%, black) !important; }
    .bg-orange-100 { background-color: color-mix(in srgb, var(--ui-primary) 16%, white) !important; }
    .bg-orange-300 { background-color: color-mix(in srgb, var(--ui-primary) 42%, white) !important; }
    .bg-orange-400 { background-color: var(--ui-primary) !important; }
    .bg-orange-50 { background-color: color-mix(in srgb, var(--ui-primary) 8%, white) !important; }
    .bg-orange-50\/30 { background-color: color-mix(in srgb, color-mix(in srgb, var(--ui-primary) 8%, white) 30%, transparent) !important; }
    .bg-orange-50\/50 { background-color: color-mix(in srgb, color-mix(in srgb, var(--ui-primary) 8%, white) 50%, transparent) !important; }
    .bg-orange-500 { background-color: var(--ui-primary) !important; }
    .bg-orange-600 { background-color: var(--ui-primary) !important; }
    .bg-orange-700 { background-color: color-mix(in srgb, var(--ui-primary) 85%, black) !important; }
    .border-amber-100 { border-color: color-mix(in srgb, var(--ui-primary) 16%, white) !important; }
    .border-amber-200 { border-color: color-mix(in srgb, var(--ui-primary) 28%, white) !important; }
    .border-amber-400 { border-color: var(--ui-primary) !important; }
    .border-amber-500\/30 { border-color: color-mix(in srgb, var(--ui-primary) 30%, transparent) !important; }
    .border-orange-100 { border-color: color-mix(in srgb, var(--ui-primary) 16%, white) !important; }
    .border-orange-200 { border-color: color-mix(in srgb, var(--ui-primary) 28%, white) !important; }
    .border-orange-500 { border-color: var(--ui-primary) !important; }
    .border-orange-600 { border-color: var(--ui-primary) !important; }
    .decoration-orange-200 { text-decoration-color: color-mix(in srgb, var(--ui-primary) 28%, white) !important; }
    .file\:bg-orange-50::file-selector-button { background-color: color-mix(in srgb, var(--ui-primary) 8%, white) !important; }
    .file\:bg-orange-600::file-selector-button { background-color: var(--ui-primary) !important; }
    .file\:text-orange-700::file-selector-button { color: color-mix(in srgb, var(--ui-primary) 85%, black) !important; }
    .focus\:border-orange-500:focus { border-color: var(--ui-primary) !important; }
    .focus\:ring-orange-500\/10:focus { --tw-ring-color: color-mix(in srgb, var(--ui-primary) 10%, transparent) !important; }
    .from-amber-800 { --tw-gradient-from: color-mix(in srgb, var(--ui-primary) 70%, black) var(--tw-gradient-from-position) !important; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, transparent) !important; }
    .from-orange-500 { --tw-gradient-from: var(--ui-primary) var(--tw-gradient-from-position) !important; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, transparent) !important; }
    .group:hover .group-hover\:border-orange-500 { border-color: var(--ui-primary) !important; }
    .group:hover .group-hover\:text-amber-200 { color: color-mix(in srgb, var(--ui-primary) 28%, white) !important; }
    .group:hover .group-hover\:text-orange-600 { color: var(--ui-primary) !important; }
    .hover\:bg-amber-50:hover { background-color: color-mix(in srgb, var(--ui-primary) 8%, white) !important; }
    .hover\:bg-amber-50\/30:hover { background-color: color-mix(in srgb, color-mix(in srgb, var(--ui-primary) 8%, white) 30%, transparent) !important; }
    .hover\:bg-amber-600:hover { background-color: var(--ui-primary) !important; }
    .hover\:bg-amber-900:hover { background-color: color-mix(in srgb, var(--ui-primary) 55%, black) !important; }
    .hover\:bg-orange-50:hover { background-color: color-mix(in srgb, var(--ui-primary) 8%, white) !important; }
    .hover\:bg-orange-50\/30:hover { background-color: color-mix(in srgb, color-mix(in srgb, var(--ui-primary) 8%, white) 30%, transparent) !important; }
    .hover\:bg-orange-50\/50:hover { background-color: color-mix(in srgb, color-mix(in srgb, var(--ui-primary) 8%, white) 50%, transparent) !important; }
    .hover\:bg-orange-500:hover { background-color: var(--ui-primary) !important; }
    .hover\:bg-orange-600:hover { background-color: var(--ui-primary) !important; }
    .hover\:bg-orange-700:hover { background-color: color-mix(in srgb, var(--ui-primary) 85%, black) !important; }
    .hover\:border-amber-600:hover { border-color: var(--ui-primary) !important; }
    .hover\:border-orange-100:hover { border-color: color-mix(in srgb, var(--ui-primary) 16%, white) !important; }
    .hover\:decoration-orange-500:hover { text-decoration-color: var(--ui-primary) !important; }
    .hover\:file\:bg-orange-100:hover::file-selector-button { background-color: color-mix(in srgb, var(--ui-primary) 16%, white) !important; }
    .hover\:file\:bg-orange-700:hover::file-selector-button { background-color: color-mix(in srgb, var(--ui-primary) 85%, black) !important; }
    .hover\:text-amber-700:hover { color: color-mix(in srgb, var(--ui-primary) 85%, black) !important; }
    .hover\:text-amber-800:hover { color: color-mix(in srgb, var(--ui-primary) 70%, black) !important; }
    .hover\:text-orange-600:hover { color: var(--ui-primary) !important; }
    .hover\:text-orange-700:hover { color: color-mix(in srgb, var(--ui-primary) 85%, black) !important; }
    .shadow-orange-100 { --tw-shadow-color: color-mix(in srgb, var(--ui-primary) 16%, white) !important; --tw-shadow: var(--tw-shadow-colored) !important; }
    .text-amber-200 { color: color-mix(in srgb, var(--ui-primary) 28%, white) !important; }
    .text-amber-500 { color: var(--ui-primary) !important; }
    .text-amber-600 { color: var(--ui-primary) !important; }
    .text-amber-700 { color: color-mix(in srgb, var(--ui-primary) 85%, black) !important; }
    .text-amber-700\/20 { color: color-mix(in srgb, color-mix(in srgb, var(--ui-primary) 85%, black) 20%, transparent) !important; }
    .text-amber-800 { color: color-mix(in srgb, var(--ui-primary) 70%, black) !important; }
    .text-amber-900 { color: color-mix(in srgb, var(--ui-primary) 55%, black) !important; }
    .text-orange-100 { color: color-mix(in srgb, var(--ui-primary) 16%, white) !important; }
    .text-orange-200 { color: color-mix(in srgb, var(--ui-primary) 28%, white) !important; }
    .text-orange-300 { color: color-mix(in srgb, var(--ui-primary) 42%, white) !important; }
    .text-orange-400 { color: var(--ui-primary) !important; }
    .text-orange-500 { color: var(--ui-primary) !important; }
    .text-orange-600 { color: var(--ui-primary) !important; }
    .text-orange-700 { color: color-mix(in srgb, var(--ui-primary) 85%, black) !important; }
    .text-orange-800 { color: color-mix(in srgb, var(--ui-primary) 70%, black) !important; }
    .text-orange-900 { color: color-mix(in srgb, var(--ui-primary) 55%, black) !important; }
    .to-amber-900 { --tw-gradient-to: color-mix(in srgb, var(--ui-primary) 55%, black) var(--tw-gradient-to-position) !important; }
    .to-orange-600 { --tw-gradient-to: var(--ui-primary) var(--tw-gradient-to-position) !important; }
</style>
