<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
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

        @page {
            size: A4 landscape;
            /* ระยะเรียงตาม: บน ขวา ล่าง ซ้าย */
            margin: 0.5cm 2cm 0.5cm 2cm;
        }

        body {
            font-family: "THSarabunNew";
            margin: 0;
            padding: 0;
            width: 100%;
            overflow: hidden; /* บังคับตัดไม่ให้ขึ้นหน้าใหม่ */
        }

        .envelope-content {
            width: 100%;
            display: block;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo {
            width: 80px;
            margin-bottom: 5px;
        }

        .title-main {
            font-size: 40px; 
            font-weight: bold;
            margin: 0;
            line-height: 1;
        }

        .title-sub {
            font-size: 20px;
            font-weight: bold;
            margin: 0;
        }

        .info-container {
            margin-top: 15px;
        }

        .info-row {
            font-size: 30px; 
            margin-bottom: 5px; /* ลดระยะห่างระหว่างบรรทัดลงป้องกันหลุดหน้า */
            display: table;
            width: 100%;
        }

        .label {
            display: table-cell;
            font-weight: bold;
            width: 220px;
            white-space: nowrap;
            vertical-align: bottom;
        }

        .value {
            display: table-cell;
            border-bottom: 2px dotted #333;
            padding-left: 10px;
            vertical-align: bottom;
            line-height: 1;
        }

        .footer {
            text-align: right;
            font-size: 18px;
            font-weight: bold;
            margin-top: 20px;
            padding-right: 20px;
        }
    </style>
</head>
<body>

<div class="envelope-content">
    <div class="header">
        <img src="imgs/obec.jpg" class="logo"><br>
        <h1 class="title-main">ใบปิดหน้าซองเอกสารการแข่งขัน</h1>
        <div class="title-sub">
           งานศิลปหัตถกรรมนักเรียน {{ session('app_group') }} ปีการศึกษา {{ session('app_year') }}
        </div>
    </div>

    <div class="info-container">
        <div class="info-row">
            <span class="label">กลุ่มสาระฯ:</span>
            <span class="value">{{$activity->getactivitysubject->subjectgroup_name}}</span>
        </div>

        <div class="info-row">
            <span class="label">รายการแข่งขัน:</span>
            <span class="value">{{$activity->activity_name}}</span>
        </div>

        <div class="info-row">
            <span class="label">ระดับชั้น:</span>
            <span class="value">{{$activity->getclass->class_name}}</span>
        </div>

        <div class="info-row">
            <span class="label">ศูนย์การแข่งขัน:</span>
            <span class="value">{{$activity->getactivitybranch->branch_name}}</span>
        </div>
         <div class="info-row">
            <span class="label">อาคาร:</span>
            <span class="value">{{$activity->activity_branchroom}} เวลาการแข่งขัน {{$activity->activity_time}}</span>
        </div>
  
    </div>

    <div class="footer">
        {{ session('app_area') }}
    </div>
</div>

</body>
</html>