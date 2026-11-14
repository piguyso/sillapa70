<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>เลือกระบบการแข่งขัน | สพป.ชุมพร เขต 1</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; min-height: 100vh; display: grid; place-items: center; font-family: Tahoma, sans-serif; color: #1e293b; background: linear-gradient(135deg, #eef2ff, #fff 50%, #f5f3ff); }
        main { width: min(920px, calc(100% - 32px)); padding: 48px 0; }
        h1, p { text-align: center; }
        h1 { margin: 0; color: #312e81; }
        p { color: #64748b; line-height: 1.8; }
        .events { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px; margin-top: 32px; }
        .event { display: block; padding: 36px; border: 1px solid #c7d2fe; border-radius: 24px; color: inherit; text-decoration: none; background: #fff; box-shadow: 0 12px 30px #312e8114; transition: transform .2s, box-shadow .2s; }
        .event:hover { transform: translateY(-4px); box-shadow: 0 18px 40px #312e8124; }
        .number { display: block; font-size: 64px; font-weight: 700; color: #4f46e5; }
        .status { display: inline-block; margin-top: 16px; padding: 8px 14px; border-radius: 999px; color: #3730a3; background: #eef2ff; font-size: 14px; }
        @media (max-width: 640px) { .events { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
    <main>
        <h1>งานศิลปหัตถกรรมนักเรียน</h1>
        <p>สำนักงานเขตพื้นที่การศึกษาประถมศึกษาชุมพร เขต 1<br>เลือกครั้งที่จัดการแข่งขันเพื่อเข้าสู่ระบบ</p>
        <div class="events">
            @foreach ($events as $event)
                <a class="event" href="{{ url('/'.$event->event_number) }}">
                    <span class="number">{{ $event->event_number }}</span>
                    <strong>{{ $event->name ?: 'การแข่งขันครั้งที่ '.$event->event_number }}</strong><br>
                    <span class="status">{{ $event->is_active ? 'ระบบปัจจุบัน' : 'ข้อมูลย้อนหลัง' }}</span>
                </a>
            @endforeach
        </div>
    </main>
</body>
</html>
