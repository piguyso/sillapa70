{{-- รูปแบบ pagination กลาง (DataTables และ Laravel paginator) ใช้สี theme ผ่าน --ui-primary --}}
<style>
    /* ===== DataTables ===== */
    .dataTables_wrapper .dataTables_paginate {
        display: flex !important; flex-wrap: wrap !important; align-items: center !important; justify-content: flex-end !important;
        gap: 6px !important; margin: 18px 0 8px !important; padding: 0 !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        display: inline-flex !important; align-items: center !important; justify-content: center !important;
        min-width: 40px !important; height: 40px !important; padding: 0 14px !important; margin: 0 !important;
        border: 1px solid #e2e8f0 !important; border-radius: 12px !important;
        background: #fff !important; color: #475569 !important;
        font-size: 15px !important; font-weight: 700 !important; line-height: 1 !important;
        box-shadow: 0 1px 2px rgba(15, 23, 42, .05) !important;
        cursor: pointer !important; transition: all .18s ease !important; text-decoration: none !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover:not(.disabled):not(.current) {
        background: color-mix(in srgb, var(--ui-primary) 8%, white) !important;
        border-color: color-mix(in srgb, var(--ui-primary) 45%, white) !important;
        color: var(--ui-primary) !important; transform: translateY(-1px);
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current,
    .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
        background: var(--ui-primary) !important; border-color: var(--ui-primary) !important; color: #fff !important;
        box-shadow: 0 6px 14px color-mix(in srgb, var(--ui-primary) 35%, transparent) !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.previous,
    .dataTables_wrapper .dataTables_paginate .paginate_button.next { padding: 0 18px !important; }
    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled,
    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover {
        background: #f8fafc !important; color: #cbd5e1 !important; border-color: #eef2f7 !important;
        box-shadow: none !important; cursor: not-allowed !important; opacity: 1 !important; transform: none !important;
    }
    .dataTables_wrapper .dataTables_paginate .ellipsis {
        display: inline-flex !important; align-items: center; justify-content: center; min-width: 24px;
        color: #94a3b8 !important; font-weight: 800; letter-spacing: 2px; border: 0 !important; background: transparent !important; box-shadow: none !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button:focus-visible {
        outline: 3px solid color-mix(in srgb, var(--ui-primary) 35%, transparent) !important; outline-offset: 2px;
    }
    .dataTables_wrapper .dataTables_info { color: #64748b !important; font-size: 14px !important; font-weight: 600; padding-top: 10px !important; }
    .dataTables_wrapper .dataTables_filter input {
        border: 1px solid #e2e8f0 !important; border-radius: 12px !important; padding: 9px 14px !important; min-width: 260px; outline: 0 !important;
        transition: all .18s ease;
    }
    .dataTables_wrapper .dataTables_filter input:focus {
        border-color: var(--ui-primary) !important; box-shadow: 0 0 0 3px color-mix(in srgb, var(--ui-primary) 18%, transparent) !important;
    }
    @media (max-width: 640px) {
        .dataTables_wrapper .dataTables_paginate { justify-content: center !important; }
        .dataTables_wrapper .dataTables_paginate .paginate_button { min-width: 36px !important; height: 36px !important; padding: 0 10px !important; }
        .dataTables_wrapper .dataTables_filter input { min-width: 100%; }
    }

    /* DataTables: "แสดง N แถว" ชิดซ้าย และช่องค้นหาชิดขวา อยู่แถวเดียวกัน */
    .dataTables_wrapper::after { content: ""; display: table; clear: both; }
    .dataTables_wrapper div.dataTables_length { float: left !important; margin: 0 0 12px !important; }
    .dataTables_wrapper div.dataTables_filter { float: right !important; width: auto !important; margin: 0 0 12px !important; display: block !important; }
    .dataTables_wrapper div.dataTables_length label, .dataTables_wrapper div.dataTables_filter label {
        display: inline-flex !important; align-items: center; gap: 8px; margin: 0; white-space: nowrap;
        min-width: 0 !important; height: auto !important; padding: 0 !important; border: 0 !important; background: transparent !important; box-shadow: none !important;
    }
    .dataTables_wrapper div.dataTables_filter input {
        width: auto !important; height: auto !important; margin: 0 !important; padding: 9px 14px 9px 38px !important;
        background: #fff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2.2' stroke-linecap='round' stroke-linejoin='round'%3E%3Ccircle cx='11' cy='11' r='7'/%3E%3Cpath d='m20 20-3.5-3.5'/%3E%3C/svg%3E") no-repeat 13px center !important;
    }
    .dataTables_wrapper table.dataTable, .dataTables_wrapper .dataTables_scroll { clear: both; }
    @media (max-width: 640px) {
        .dataTables_wrapper div.dataTables_length, .dataTables_wrapper div.dataTables_filter { float: none !important; width: 100% !important; }
        .dataTables_wrapper div.dataTables_filter label { width: 100%; }
        .dataTables_wrapper div.dataTables_filter input { flex: 1; min-width: 0; }
    }
    /* ===== Laravel paginator ===== */
    nav[role="navigation"] a, nav[role="navigation"] span[aria-current="page"] > span, nav[role="navigation"] span[aria-disabled="true"] > span,
    .pagination .page-link {
        min-width: 40px !important; height: 40px !important; padding: 0 14px !important; border-radius: 12px !important;
        border: 1px solid #e2e8f0 !important; background: #fff !important; color: #475569 !important; font-weight: 700 !important;
        display: inline-flex !important; align-items: center !important; justify-content: center !important;
        box-shadow: 0 1px 2px rgba(15, 23, 42, .05) !important; transition: all .18s ease !important;
    }
    nav[role="navigation"] a:hover, .pagination .page-link:hover {
        background: color-mix(in srgb, var(--ui-primary) 8%, white) !important;
        border-color: color-mix(in srgb, var(--ui-primary) 45%, white) !important; color: var(--ui-primary) !important;
    }
    nav[role="navigation"] span[aria-current="page"] > span, .pagination .page-item.active .page-link {
        background: var(--ui-primary) !important; border-color: var(--ui-primary) !important; color: #fff !important;
        box-shadow: 0 6px 14px color-mix(in srgb, var(--ui-primary) 35%, transparent) !important;
    }
    nav[role="navigation"] span[aria-disabled="true"] > span, .pagination .page-item.disabled .page-link {
        background: #f8fafc !important; color: #cbd5e1 !important; border-color: #eef2f7 !important; box-shadow: none !important; cursor: not-allowed !important;
    }

    /* ===== ระยะห่างระหว่างปุ่ม (ทั้งชุดตัวเลขที่อยู่ใน span ของ DataTables และ paginator ของ Laravel ที่ใช้ -ml-px ทำให้ปุ่มชิดกัน) ===== */
    .dataTables_wrapper .dataTables_paginate > span { display: inline-flex !important; flex-wrap: wrap; align-items: center; gap: 6px !important; }
    nav[role="navigation"] .-ml-px, nav[role="navigation"] [class*="-ml-px"] { margin-left: 0 !important; }
    nav[role="navigation"] span.relative.inline-flex,
    nav[role="navigation"] span.relative.z-0 { display: inline-flex !important; flex-wrap: wrap; gap: 6px !important; box-shadow: none !important; }
    nav[role="navigation"] > div, nav[role="navigation"] > div > div { display: flex; flex-wrap: wrap; align-items: center; gap: 12px; }
    .pagination { gap: 6px !important; }
    .pagination .page-item { margin: 0 !important; }

    /* ===== รีเซ็ตกรอบ/ช่องที่กฎเดิมใส่ให้ทุก span ใน nav (กล่องห่อปุ่ม และตัวเลขในข้อความ "Showing x to y of z") ===== */
    nav[role="navigation"] span.inline-flex.shadow-sm,
    nav[role="navigation"] span.relative.z-0 {
        border: 0 !important; padding: 0 !important; background: transparent !important; height: auto !important; min-width: 0 !important;
        border-radius: 0 !important; box-shadow: none !important; display: inline-flex !important; flex-wrap: wrap; gap: 6px !important;
    }
    nav[role="navigation"] p span, nav[role="navigation"] p {
        border: 0 !important; padding: 0 !important; background: transparent !important; height: auto !important; min-width: 0 !important;
        box-shadow: none !important; display: inline !important; color: #64748b !important; font-size: 14px !important; font-weight: 600;
    }
    nav[role="navigation"] p span.font-medium { color: #334155 !important; font-weight: 800; }
    nav[role="navigation"] a svg, nav[role="navigation"] span[aria-disabled="true"] svg { width: 16px; height: 16px; }

    /* span ชั้นนอกของหน้าปัจจุบัน/ปุ่มที่กดไม่ได้ไม่ต้องมีกรอบ (กรอบอยู่ที่ span ชั้นใน) */
    nav[role="navigation"] span[aria-current="page"], nav[role="navigation"] span[aria-disabled="true"] {
        border: 0 !important; padding: 0 !important; background: transparent !important; height: auto !important; min-width: 0 !important;
        box-shadow: none !important; display: inline-flex !important; opacity: 1 !important;
    }
</style>
