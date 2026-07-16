<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <title>404 - ไม่พบหน้าที่คุณต้องการ</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 CSS -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>
        body {
            background: #f8fafc;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: system-ui, -apple-system, "Segoe UI", sans-serif;
        }

        .error-wrapper {
            max-width: 520px;
        }

        .error-card {
            background: #ffffff;
            border-radius: 18px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 14px 35px rgba(15, 23, 42, 0.08);
        }

        .error-code {
            font-size: 72px;
            font-weight: 800;
            letter-spacing: 4px;
            color: #111827;
        }

        .error-code span {
            color: #2563eb; /* น้ำเงินอ่อน ๆ */
        }

        .error-title {
            font-size: 22px;
            font-weight: 600;
            color: #111827;
        }

        .error-text {
            color: #6b7280;
        }

        .btn-main {
            border-radius: 999px;
            padding-inline: 22px;
            padding-block: 10px;
        }

        .badge-soft {
            background: #eff6ff;
            color: #1d4ed8;
            font-size: 11px;
            padding: 3px 10px;
            border-radius: 999px;
            text-transform: uppercase;
            letter-spacing: .08em;
        }

        .link-muted {
            color: #9ca3af;
            font-size: 13px;
        }

        .link-muted:hover {
            color: #6b7280;
        }
    </style>
</head>
<body>
<div class="error-wrapper px-3">
    <div class="error-card p-4 p-md-5 text-center">
        <div class="mb-3">
            <span class="badge-soft">Error 404</span>
        </div>

        <div class="error-code mb-2">
            4<span>0</span>4
        </div>

        <h1 class="error-title mb-2">
            ไม่พบหน้าที่คุณกำลังมองหา
        </h1>

        <p class="error-text mb-4">
            ลิงก์อาจถูกลบ ย้ายที่อยู่ใหม่ หรือพิมพ์ที่อยู่ผิดเล็กน้อย<br>
            ลองกลับไปหน้าแรก หรือตรวจสอบ URL อีกครั้งนะครับ
        </p>

        <div class="d-flex flex-column flex-md-row justify-content-center gap-2 mb-3">
            <a href="{{url('/')}}" class="btn btn-primary btn-main">
                กลับไปหน้าแรก
            </a>
            <button type="button" class="btn btn-outline-secondary btn-main"
                    onclick="history.back();">
                ย้อนกลับหน้าก่อนหน้า
            </button>
        </div>

        <div class="mt-2">
            <a href="/" class="link-muted text-decoration-none">
                &copy; {{ date('Y') ?? '2023' }} sillapa cpn1
            </a>
        </div>
    </div>
</div>

<!-- Bootstrap 5 JS (ถ้าต้องใช้ component บางตัว) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
