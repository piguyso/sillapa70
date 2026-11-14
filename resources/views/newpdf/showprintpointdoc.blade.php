<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <style>
        @font-face { font-family: THSarabunNew; src: url("{{ public_path('fonts/THSarabunNew.ttf') }}") format('truetype'); }
        @font-face { font-family: THSarabunNew; font-weight: bold; src: url("{{ public_path('fonts/THSarabunNew Bold.ttf') }}") format('truetype'); }
        @page { size: A4 landscape; margin: .6cm 1cm; }
        body { margin: 0; font-family: THSarabunNew; font-size: 18px; }
        .score-page { position: relative; width: 100%; height: 18cm; overflow: hidden; }
        .new-page { page-break-before: always; }
        .heading { text-align: center; line-height: 1.18; margin-bottom: 8px; }
        .heading img { float: left; width: 50px; }
        .score-table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        .score-table th, .score-table td { border: 1px solid #000; padding: 3px 5px; height: 25px; }
        .score-table th { text-align: center; font-weight: bold; }
        .center { text-align: center; }
        .signatures { position: absolute; right: 0; bottom: 0; left: 0; width: 100%; text-align: center; page-break-inside: avoid; }
        .chairman { margin-bottom: 16px; line-height: 1.35; }
        .judge-table { width: 100%; border-collapse: separate; border-spacing: 18px 0; table-layout: fixed; }
        .judge-table td { padding: 0 12px; text-align: center; vertical-align: top; line-height: 1.35; }
        .signature-line { white-space: nowrap; }
    </style>
</head>
<body>
@php
    $teamItems = method_exists($addschool, 'items') ? collect($addschool->items()) : collect($addschool);
    $schoolsPerPage = 8;
    $pages = $teamItems->chunk($schoolsPerPage);
    if ($pages->isEmpty()) $pages = collect([collect()]);
@endphp

@foreach($pages as $pageIndex => $teams)
<section class="score-page{{ $loop->first ? '' : ' new-page' }}">
    <div class="heading">
        <img src="{{ public_path('imgs/obec.jpg') }}" alt="ตราสัญลักษณ์">
        <strong>แบบบันทึกคะแนน ผลการแข่งขัน</strong><br>
        {{ session('app_name') }} {{ session('app_group') }} ปีการศึกษา {{ session('app_year') }}<br>
        กลุ่มสาระการเรียนรู้ {{ $activity->getactivitysubject->subjectgroup_name ?? '-' }}
        กิจกรรม {{ $activity->activity_name ?? '-' }} {{ $activity->getclass->class_name ?? '' }}<br>
        {{ $activity->getactivitybranch->branch_name ?? '' }} {{ session('app_area') }}<br>
        วันที่ _________ เดือน __________________ พ.ศ. _________
    </div>

    <table class="score-table">
        <thead><tr>
            <th style="width:5%">ที่</th><th style="width:45%">โรงเรียน</th>
            <th style="width:16%">คะแนน</th><th style="width:14%">ลำดับที่</th><th style="width:20%">หมายเหตุ</th>
        </tr></thead>
        <tbody>
        @forelse($teams as $rowIndex => $team)
            <tr>
                <td class="center">{{ ($pageIndex * $schoolsPerPage) + $rowIndex + 1 }}</td>
                <td>{{ $team->getschoolname->school_name ?? $team->school_name ?? '-' }}</td>
                <td></td><td></td><td></td>
            </tr>
        @empty
            <tr><td colspan="5" class="center">ไม่พบข้อมูล</td></tr>
        @endforelse
        </tbody>
    </table>

    <div class="signatures">
        <div class="chairman">
            ลงชื่อ........................................................ ประธานศูนย์การจัดการแข่งขัน<br>
            (........................................................)
        </div>
        @if($jow->count())
        <table class="judge-table"><tr>
            @foreach($jow as $judge)
            <td>
                <div class="signature-line">ลงชื่อ........................................................</div>
                <div>({{ $judge->getjowtitle->title ?? '' }}{{ $judge->jow_name }} {{ $judge->jow_lastname }})</div>
                <div>{{ $judge->jowclass }}</div>
            </td>
            @endforeach
        </tr></table>
        @endif
    </div>
</section>
@endforeach
</body>
</html>
