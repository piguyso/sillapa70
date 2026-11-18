{{-- รองรับ markup แบบ Bootstrap 3 ของหน้าเก่า (panel/table/btn/alert/glyphicon) ใน layout ที่เป็น Tailwind --}}
<style>
    .container { max-width: 1280px; margin-left: auto; margin-right: auto; padding: 0 16px; }
    .jumbotron { background: linear-gradient(135deg,#ea580c,#9a3412); color: #fff; border-radius: 24px; padding: 24px 32px; margin: 24px 0; box-shadow: 0 10px 25px rgba(154,52,18,.25); }
    .jumbotron h1, .jumbotron h2 { font-size: 1.5rem; font-weight: 800; margin: 0; line-height: 1.4; }
    .panel { background: #fff; border-radius: 20px; box-shadow: 0 4px 18px rgba(15,23,42,.08); margin-bottom: 20px; overflow: hidden; border: 1px solid #e2e8f0; }
    .panel-heading { background: #fff7ed; padding: 14px 20px; font-weight: 800; color: #9a3412; border-bottom: 1px solid #fed7aa; display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
    .panel-body { padding: 20px; overflow-x: auto; }
    .panel-footer { padding: 12px 20px; background: #f8fafc; border-top: 1px solid #e2e8f0; }
    .panel-body h4 { font-weight: 800; margin-bottom: 12px; }
    table.table { width: 100%; border-collapse: collapse; font-size: 14px; margin-bottom: 12px; }
    table.table th, table.table td { padding: 8px 10px; text-align: left; vertical-align: top; }
    table.table thead th { background: #f1f5f9; font-weight: 700; white-space: nowrap; border-bottom: 2px solid #cbd5e1; }
    table.table-bordered th, table.table-bordered td { border: 1px solid #e2e8f0; }
    table.table-hover tbody tr:hover { background: #fff7ed; }
    .btn { display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px; border-radius: 10px; font-weight: 700; font-size: 14px; cursor: pointer; border: 1px solid transparent; text-decoration: none; }
    .btn-info { background: #0ea5e9; color: #fff; } .btn-info:hover { background: #0284c7; }
    .btn-primary { background: #ea580c; color: #fff; } .btn-primary:hover { background: #c2410c; }
    .btn-success { background: #16a34a; color: #fff; } .btn-success:hover { background: #15803d; }
    .alert { padding: 12px 16px; border-radius: 12px; margin-bottom: 12px; }
    .alert-success { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
    .form-control { width: 100%; border: 1px solid #cbd5e1; border-radius: 10px; padding: 8px 12px; background: #fff; }
    .row { display: flex; flex-wrap: wrap; margin: 0 -8px; }
    .row > [class*="col-"] { padding: 0 8px; box-sizing: border-box; width: 100%; }
    @media (min-width: 768px) {
        .col-md-2 { width: 16.6667% !important; } .col-md-3 { width: 25% !important; } .col-md-4 { width: 33.3333% !important; }
        .col-md-6 { width: 50% !important; } .col-md-12 { width: 100% !important; }
        .col-md-offset-4 { margin-left: 33.3333%; }
    }
    .glyphicon { font-style: normal; font-family: inherit; }
    .glyphicon-backward::before { content: "◀"; } .glyphicon-print::before { content: "🖨"; }
    .glyphicon-info-sign::before { content: "ℹ"; } .glyphicon-pencil::before { content: "✎"; }
    @media print { nav, .no-print { display: none !important; } .jumbotron { background: none; color: #000; box-shadow: none; } }
</style>
