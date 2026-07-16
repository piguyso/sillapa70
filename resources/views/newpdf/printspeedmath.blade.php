<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <style>
        @font-face {
            font-family: 'THSarabunNew';
            font-style: normal;
            font-weight: normal;
            src: url("{{ public_path('fonts/THSarabunNew.ttf') }}") format('truetype');
        }
        @font-face {
            font-family: 'THSarabunNew';
            font-style: normal;
            font-weight: bold;
            src: url("{{ public_path('fonts/THSarabunNew Bold.ttf') }}") format('truetype');
        }

        body {
            font-family: "THSarabunNew";
            font-size: 16px; /* ปรับขนาด Font เป็น 16 ตามสั่ง */
            margin-top: 0.1cm;
            margin-bottom: 0.1cm;
            margin-left: 0.5cm;
            margin-right: 0.5cm;
        }

        @page {
            size: A4 portrait;
            margin: 0.3cm;
        }

        .answer-container {
            margin-bottom: 8px; /* ลดระยะห่างระหว่างชุดลง */
            padding: 0;
            width: 100%;
            page-break-inside: avoid;
        }

        .main-table {
            width: 100%;
            border-collapse: collapse;
            border: 2px solid #000;
        }

        .main-table th, .main-table td {
            border: 1px solid #000;
            padding: 2px; /* ลด padding เพื่อประหยัดพื้นที่ */
            text-align: center;
        }

        .main-table th {
            font-size: 16px; 
            font-weight: bold;
        }

        /* ปรับความสูงตารางเพื่อให้ลงได้ 4 โรงเรียนต่อหน้า */
        .drawing-area {
            height: 155px; 
            vertical-align: top !important;
        }

        .info-row-text {
            font-size: 16px; /* ปรับขนาด Font เป็น 16 ตามสั่ง */
            font-weight: bold;
            padding: 2px 0;
            line-height: 1.1;
        }
        
        u {
            padding: 0 5px;
        }
    </style>
</head>
<body>

@forelse($addschool as $l)
    <div class="answer-container">
        <div class="info-row-text">
            การแข่งขันคิดเลขเร็ว ระดับชั้น <u>{{ $activity->getclass->class_name }}</u> &nbsp;<br>
            ชื่อ-สกุล <u>{{$l->getstudentname[0]->getstudenttitle->title}} {{$l->getstudentname[0]->name}} {{$l->getstudentname[0]->lastname}}</u> &nbsp;
            โรงเรียน <u>{{ $l->getschoolname->school_name }}</u> &nbsp;
            เลขที่ <u>{{ $loop->iteration }}</u> &nbsp;
            ข้อที่ ..........
        </div>

        <table class="main-table">
            <thead>
                <tr>
                    <th style="width: 50%;">วิธีการและคำตอบ</th>
                    <th style="width: 50%;">พื้นที่สำหรับทดเลข</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="drawing-area"></td>
                    <td class="drawing-area"></td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- แก้ไขเงื่อนไขให้ขึ้นหน้าใหม่ทุกๆ 4 รายการ --}}
    @if($loop->iteration % 4 == 0 && !$loop->last)
        <div style="page-break-after: always;"></div>
    @endif

@empty
    <div class="text-center">ไม่พบข้อมูล</div>
@endforelse

</body>
</html>