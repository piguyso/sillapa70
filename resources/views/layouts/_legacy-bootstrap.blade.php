{{-- รองรับ markup แบบ Bootstrap 3 ของหน้าเก่า (panel/table/btn/alert/glyphicon) ใน layout ที่เป็น Tailwind --}}
<style>
    .container { max-width: 1280px; margin-left: auto; margin-right: auto; padding: 0 16px; }
    .jumbotron { background: linear-gradient(135deg,var(--ui-primary),var(--ui-secondary)); color: #fff; border-radius: 24px; padding: 24px 32px; margin: 24px 0; box-shadow: 0 10px 25px color-mix(in srgb, var(--ui-primary) 25%, transparent); }
    .jumbotron h1, .jumbotron h2 { font-size: 1.5rem; font-weight: 800; margin: 0; line-height: 1.4; }
    .panel { background: #fff; border-radius: 20px; box-shadow: 0 4px 18px rgba(15,23,42,.08); margin-bottom: 20px; overflow: hidden; border: 1px solid #e2e8f0; }
    .panel-heading { background: color-mix(in srgb, var(--ui-primary) 8%, white); padding: 14px 20px; font-weight: 800; color: var(--ui-primary); border-bottom: 1px solid color-mix(in srgb, var(--ui-primary) 25%, white); display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
    .panel-body { padding: 20px; overflow-x: auto; }
    .panel-footer { padding: 12px 20px; background: #f8fafc; border-top: 1px solid #e2e8f0; }
    .panel-body h4 { font-weight: 800; margin-bottom: 12px; }
    table.table { width: 100%; border-collapse: collapse; font-size: 14px; margin-bottom: 12px; }
    table.table th, table.table td { padding: 8px 10px; text-align: left; vertical-align: top; }
    table.table thead th { background: #f1f5f9; font-weight: 700; white-space: nowrap; border-bottom: 2px solid #cbd5e1; }
    table.table-bordered th, table.table-bordered td { border: 1px solid #e2e8f0; }
    table.table-hover tbody tr:hover { background: color-mix(in srgb, var(--ui-primary) 8%, white); }
    .btn { display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px; border-radius: 10px; font-weight: 700; font-size: 14px; cursor: pointer; border: 1px solid transparent; text-decoration: none; }
    .btn-info { background: #0ea5e9; color: #fff; } .btn-info:hover { background: #0284c7; }
    .btn-primary { background: var(--ui-primary); color: #fff; } .btn-primary:hover { background: color-mix(in srgb, var(--ui-primary) 85%, black); }
    .btn-success { background: var(--ui-primary); color: #fff; } .btn-success:hover { background: color-mix(in srgb, var(--ui-primary) 85%, black); }
    .alert { padding: 12px 16px; border-radius: 12px; margin-bottom: 12px; }
    .alert-success { background: color-mix(in srgb, var(--ui-primary) 12%, white); color: var(--ui-primary); border: 1px solid color-mix(in srgb, var(--ui-primary) 30%, white); }
    .form-control { width: 100%; border: 1px solid #cbd5e1; border-radius: 10px; padding: 8px 12px; background: #fff; }
    .row { display: flex; flex-wrap: wrap; margin: 0 -8px; }
    .row > [class*="col-"] { padding: 0 8px; box-sizing: border-box; width: 100%; }
    @media (min-width: 768px) {
        .col-md-2 { width: 16.6667% !important; } .col-md-3 { width: 25% !important; } .col-md-4 { width: 33.3333% !important; }
        .col-md-6 { width: 50% !important; } .col-md-12 { width: 100% !important; }
        .col-md-offset-4 { margin-left: 33.3333%; }
    }
    .glyphicon { font-style: normal; font-family: inherit; }
    .glyphicon-backward::before { content: "◀"; } .glyphicon-print::before { content: none; }
    .glyphicon-info-sign::before { content: "ℹ"; } .glyphicon-pencil::before { content: "✎"; }
    /* modal แบบ Bootstrap: ซ่อนไว้จนกว่าจะกดเปิด */
    .modal { display: none; position: fixed; inset: 0; z-index: 1050; overflow-y: auto; background: rgba(15,23,42,.55); padding: 24px 12px; }
    .modal.show { display: block; }
    .modal-dialog { max-width: 500px; margin: 40px auto; } .modal-dialog.modal-lg { max-width: 800px; }
    .modal-content { background: #fff; border-radius: 20px; overflow: hidden; box-shadow: 0 25px 50px rgba(15,23,42,.25); }
    .modal-header { display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 16px 20px; border-bottom: 1px solid #e2e8f0; }
    .modal-title { font-weight: 800; margin: 0; }
    .modal-body { padding: 20px; } .modal-footer { padding: 12px 20px; border-top: 1px solid #e2e8f0; display: flex; justify-content: flex-end; gap: 8px; }
    .modal .close { font-size: 28px; line-height: 1; background: none; border: 0; cursor: pointer; color: #64748b; }
    .btn-secondary { background: #e2e8f0; color: #334155; }
    .list-group { list-style: none; padding: 0; margin: 0; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; }
    .list-group-item { padding: 10px 14px; border-bottom: 1px solid #e2e8f0; } .list-group-item:last-child { border-bottom: 0; }
    .list-group-item-success { background: color-mix(in srgb, var(--ui-primary) 12%, white); font-weight: 700; } .list-group-item-secondary { background: #f8fafc; }
    @media print { nav, .no-print { display: none !important; } .jumbotron { background: none; color: #000; box-shadow: none; } }
</style>

<script>
    // เปิด/ปิด modal แบบ Bootstrap (data-toggle="modal" / data-dismiss="modal") โดยไม่ต้องใช้ Bootstrap JS
    (function () {
        function closeAll() { document.querySelectorAll('.modal.show').forEach(function (m) { m.classList.remove('show'); }); document.body.style.overflow = ''; }
        document.addEventListener('click', function (e) {
            var open = e.target.closest('[data-toggle="modal"]');
            if (open) {
                e.preventDefault();
                var m = document.querySelector(open.getAttribute('data-target'));
                if (m) { m.classList.add('show'); document.body.style.overflow = 'hidden'; }
                return;
            }
            if (e.target.closest('[data-dismiss="modal"]') || (e.target.classList && e.target.classList.contains('modal'))) { closeAll(); }
        });
        document.addEventListener('keydown', function (e) { if (e.key === 'Escape') { closeAll(); } });
    })();
</script>
