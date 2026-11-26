@extends($level === 'network' ? 'layouts.apparea' : 'layouts.app')

@section('content')
@php
    $isNet = $level === 'network';
    $base = $isNet ? 'area/report/%s/'.$networkId : 'report/%s';
    $t = $d['total'];
    $announced = $t['teams'] - $t['pending'];
    $pct = fn ($a, $b) => $b > 0 ? round($a / $b * 100) : 0;
    $medalCols = [
        ['k' => 'gold', 'label' => 'เหรียญทอง', 'sub' => '80 ขึ้นไป', 'bar' => 'bg-yellow-400', 'num' => 'text-yellow-600'],
        ['k' => 'silver', 'label' => 'เหรียญเงิน', 'sub' => '70-79.99', 'bar' => 'bg-slate-400', 'num' => 'text-slate-600'],
        ['k' => 'bronze', 'label' => 'เหรียญทองแดง', 'sub' => '60-69.99', 'bar' => 'bg-orange-500', 'num' => 'text-orange-700'],
        ['k' => 'pass', 'label' => 'ผ่านการแข่งขัน', 'sub' => 'ต่ำกว่า 60', 'bar' => 'bg-sky-400', 'num' => 'text-sky-700'],
        ['k' => 'other', 'label' => 'ไม่มีการแข่งขัน/งดมอบ', 'sub' => 'ไม่นับเหรียญ', 'bar' => 'bg-violet-300', 'num' => 'text-violet-600'],
        ['k' => 'pending', 'label' => 'รอประกาศผล', 'sub' => 'ยังไม่ประกาศ', 'bar' => 'bg-slate-200', 'num' => 'text-slate-400'],
    ];
    $kpis = [
        ['ทีมที่ลงทะเบียน', $t['teams'], 'ri-team-line', 'bg-sky-50 text-sky-600', 'text-sky-700', 'ทีม'],
        ['โรงเรียนที่เข้าร่วม', $d['schoolsJoined'], 'ri-school-line', 'bg-emerald-50 text-emerald-600', 'text-emerald-700', 'จาก '.number_format($d['schoolTotal']).' โรง'],
        ['นักเรียน', $d['students'], 'ri-graduation-cap-line', 'bg-violet-50 text-violet-600', 'text-violet-700', 'คน'],
        ['ครูผู้ควบคุม', $d['teachers'], 'ri-user-star-line', 'bg-amber-50 text-amber-600', 'text-amber-700', 'คน'],
        ['รายการที่มีผู้ลงทะเบียน', $d['activityWithTeams'], 'ri-trophy-line', 'bg-rose-50 text-rose-600', 'text-rose-700', 'จาก '.number_format($d['activityTotal']).' รายการ'],
        ['เหรียญทั้งหมด', $d['medals'], 'ri-medal-fill', 'bg-yellow-50 text-yellow-600', 'text-yellow-600', 'เหรียญ'],
    ];
    $head = $isNet ? 'from-amber-900 to-orange-700' : 'from-primary to-emerald-600';
    // แถบเหรียญซ้อนกัน (ทอง/เงิน/ทองแดง/ผ่าน/รอ) ใช้ซ้ำทุกตาราง
    $stack = function (array $r) use ($medalCols) {
        $sum = max(1, $r['teams']);
        $h = '<div class="flex h-2.5 w-full overflow-hidden rounded-full bg-slate-100">';
        foreach ($medalCols as $m) {
            if (($r[$m['k']] ?? 0) > 0) {
                $h .= '<div class="'.$m['bar'].'" style="width:'.($r[$m['k']] / $sum * 100).'%" title="'.$m['label'].' '.$r[$m['k']].'"></div>';
            }
        }

        return new \Illuminate\Support\HtmlString($h.'</div>');
    };
@endphp
<div class="mx-auto max-w-6xl px-4 py-6">
    <div class="mb-5 overflow-hidden rounded-3xl bg-gradient-to-r {{ $head }} p-5 text-white shadow-sm sm:p-7">
        <p class="text-sm text-white/80">สรุปผลการแข่งขัน ปีการศึกษา {{ active_year() }}</p>
        <h1 class="mt-1 text-xl font-black sm:text-2xl"><i class="ri-dashboard-line"></i> Dashboard {{ $isNet ? $networkName : 'ระดับเขตพื้นที่การศึกษา' }}</h1>
        <p class="mt-2 text-sm text-white/90">ประกาศผลแล้ว <b class="text-lg">{{ number_format($announced) }}</b> จาก {{ number_format($t['teams']) }} ทีม ({{ $pct($announced, $t['teams']) }}%)</p>
    </div>

    <div class="no-print mb-5 flex flex-wrap gap-2">
        <a href="{{ url($isNet ? 'area/dashboard/'.$networkId : 'dashboard') }}" class="rounded-xl border border-primary bg-primary px-4 py-1.5 text-sm font-bold text-white" style="background:var(--ui-primary);border-color:var(--ui-primary)">สรุป Dashboard</a>
        @foreach(\App\Services\ParticipantReportService::TYPES as $key => $ty)
            <a href="{{ url(sprintf($base, $key)) }}" class="rounded-xl border border-slate-300 bg-white px-4 py-1.5 text-sm font-bold text-slate-700">{{ $ty['file'] }}</a>
        @endforeach
        <button onclick="window.print()" class="rounded-xl border border-slate-300 bg-white px-4 py-1.5 text-sm font-bold text-slate-700"><i class="ri-printer-line"></i> พิมพ์</button>
    </div>

    {{-- ตัวเลขสรุป --}}
    <div class="mb-5 grid grid-cols-2 gap-3 sm:gap-4 lg:grid-cols-3">
        @foreach($kpis as [$label, $val, $icon, $soft, $num, $unit])
            <div class="rounded-2xl border border-slate-100 bg-white p-4 shadow-sm sm:p-5">
                <span class="mb-3 flex h-11 w-11 items-center justify-center rounded-xl text-2xl {{ $soft }}"><i class="{{ $icon }}"></i></span>
                <p class="text-3xl font-black leading-none sm:text-4xl {{ $num }}">{{ number_format($val) }}</p>
                <p class="mt-1.5 text-sm font-medium text-slate-500">{{ $label }}</p>
                <p class="text-xs text-slate-400">{{ $unit }}</p>
            </div>
        @endforeach
    </div>

    {{-- เหรียญรวม + ความคืบหน้า --}}
    <div class="mb-5 grid gap-4 lg:grid-cols-3">
        <section class="rounded-3xl border border-slate-100 bg-white p-5 shadow-sm lg:col-span-2">
            <h2 class="mb-4 font-black text-slate-800"><i class="ri-medal-line text-yellow-500"></i> ผลการแข่งขันรวม</h2>
            {!! $stack($t) !!}
            <div class="mt-4 grid grid-cols-2 gap-3 sm:grid-cols-6">
                @foreach($medalCols as $m)
                    <div class="rounded-xl bg-slate-50 p-3 text-center">
                        <p class="text-2xl font-black {{ $m['num'] }}">{{ number_format($t[$m['k']]) }}</p>
                        <p class="text-xs font-bold text-slate-600">{{ $m['label'] }}</p>
                        <p class="text-[10px] text-slate-400">{{ $m['sub'] }}</p>
                    </div>
                @endforeach
            </div>
        </section>
        <section class="rounded-3xl border border-slate-100 bg-white p-5 shadow-sm">
            <h2 class="mb-4 font-black text-slate-800"><i class="ri-checkbox-circle-line text-emerald-500"></i> ความคืบหน้าการประกาศผล</h2>
            @php $pg = $d['progress']; $pgTotal = array_sum($pg); @endphp
            @foreach([['all','ประกาศครบทุกทีมแล้ว','bg-emerald-500'],['part','ประกาศบางส่วน','bg-amber-400'],['none','ยังไม่ประกาศ','bg-slate-300']] as [$k,$lb,$bar])
                <div class="mb-3">
                    <div class="mb-1 flex justify-between text-sm"><span class="text-slate-600">{{ $lb }}</span><b class="text-slate-800">{{ number_format($pg[$k]) }} รายการ</b></div>
                    <div class="h-2 w-full overflow-hidden rounded-full bg-slate-100"><div class="h-full rounded-full {{ $bar }}" style="width:{{ $pct($pg[$k], $pgTotal) }}%"></div></div>
                </div>
            @endforeach
            <p class="text-xs text-slate-400">นับเฉพาะรายการที่มีทีมลงทะเบียน ({{ number_format($pgTotal) }} รายการ)</p>
        </section>
    </div>

    {{-- แยกตามกลุ่มสาระ --}}
    <section class="mb-5 rounded-3xl border border-slate-100 bg-white p-5 shadow-sm">
        <h2 class="mb-4 font-black text-slate-800"><i class="ri-book-open-line text-sky-500"></i> แยกตามกลุ่มสาระ/ประเภท</h2>
        <div class="space-y-4">
            @foreach($d['bySubject'] as $r)
                <div>
                    <div class="mb-1 flex flex-wrap items-baseline justify-between gap-x-3 text-sm">
                        <b class="text-slate-800">{{ $r['name'] }}</b>
                        <span class="text-xs text-slate-500">{{ number_format($r['teams']) }} ทีม ·
                            <span class="text-yellow-600">ทอง {{ $r['gold'] }}</span> ·
                            <span class="text-slate-500">เงิน {{ $r['silver'] }}</span> ·
                            <span class="text-orange-700">ทองแดง {{ $r['bronze'] }}</span> ·
                            <span class="text-sky-600">ผ่าน {{ $r['pass'] }}</span> ·
                            <span class="text-violet-500">ไม่แข่ง {{ $r['other'] }}</span> · <span class="text-slate-400">รอ {{ $r['pending'] }}</span></span>
                    </div>
                    {!! $stack($r) !!}
                </div>
            @endforeach
        </div>
        <p class="mt-4 flex flex-wrap gap-x-4 gap-y-1 text-xs text-slate-500">
            @foreach($medalCols as $m)<span><i class="inline-block h-2.5 w-2.5 rounded-full {{ $m['bar'] }}"></i> {{ $m['label'] }}</span>@endforeach
        </p>
    </section>

    {{-- เครือข่าย (เฉพาะระดับเขตพื้นที่) --}}
    @if(! $isNet)
        <section class="mb-5 rounded-3xl border border-slate-100 bg-white p-5 shadow-sm">
            <h2 class="mb-4 font-black text-slate-800"><i class="ri-building-line text-amber-500"></i> แยกตามเครือข่าย</h2>
            <div class="grid gap-3 md:grid-cols-2">
                @foreach($d['networks'] as $r)
                    <a href="{{ url('area/dashboard/'.$r['id']) }}" class="block rounded-2xl border border-slate-100 p-4 hover:bg-slate-50">
                        <div class="mb-1 flex items-start justify-between gap-2">
                            <b class="text-slate-800">{{ $r['name'] }}</b>
                            <span class="shrink-0 text-xs text-slate-500">{{ number_format($r['teams']) }} ทีม · {{ $r['school_count'] }}/{{ $r['school_total'] }} โรง</span>
                        </div>
                        {!! $stack($r) !!}
                        <p class="mt-1.5 text-xs text-slate-500"><span class="text-yellow-600">ทอง {{ $r['gold'] }}</span> · เงิน {{ $r['silver'] }} · <span class="text-orange-700">ทองแดง {{ $r['bronze'] }}</span> · รอประกาศ {{ $r['pending'] }}</p>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    {{-- โรงเรียนเหรียญสูงสุด --}}
    <section class="mb-5 rounded-3xl border border-slate-100 bg-white p-5 shadow-sm">
        <h2 class="mb-4 font-black text-slate-800"><i class="ri-award-line text-yellow-500"></i> 10 อันดับโรงเรียนที่ได้เหรียญสูงสุด</h2>
        @forelse($d['topSchools'] as $i => $r)
            <div class="flex items-center gap-3 border-b border-slate-100 py-2.5 last:border-0">
                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full text-sm font-black {{ $i === 0 ? 'bg-yellow-300 text-yellow-950' : ($i === 1 ? 'bg-slate-200 text-slate-800' : ($i === 2 ? 'bg-orange-300 text-orange-950' : 'bg-slate-100 text-slate-500')) }}">{{ $i + 1 }}</span>
                <span class="min-w-0 flex-1 text-sm font-bold leading-snug text-slate-800">{{ $r['name'] }}</span>
                <span class="shrink-0 text-right text-xs text-slate-500"><b class="text-yellow-600">{{ $r['gold'] }}</b> / {{ $r['silver'] }} / <b class="text-orange-700">{{ $r['bronze'] }}</b><br>รวม {{ $r['medals'] }}</span>
            </div>
        @empty
            <p class="py-6 text-center text-sm italic text-slate-400">- ยังไม่มีผลการแข่งขันที่ประกาศแล้ว -</p>
        @endforelse
    </section>
</div>
@endsection
