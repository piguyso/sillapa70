{{--
  สถิติการแข่งขัน: การ์ดตัวเลข + แถบสัดส่วน (ใช้ CSS ล้วน ไม่พึ่ง Chart.js)
  ตัวแปร: $countstudent, $countteacher, $countjow, $countjow2, $mode ('area' | 'district')
--}}
@php
    $isArea = ($mode ?? 'district') === 'area';
    $items = [
        ['label' => 'นักเรียน', 'value' => (int) $countstudent, 'icon' => 'ri-graduation-cap-line', 'bar' => 'bg-sky-500', 'soft' => 'bg-sky-50 text-sky-600', 'num' => 'text-sky-700'],
        ['label' => 'ครูผู้ควบคุม', 'value' => (int) $countteacher, 'icon' => 'ri-user-star-line', 'bar' => 'bg-emerald-500', 'soft' => 'bg-emerald-50 text-emerald-600', 'num' => 'text-emerald-700'],
        ['label' => 'กรรมการตัดสิน', 'value' => (int) $countjow, 'icon' => 'ri-scales-3-line', 'bar' => 'bg-amber-500', 'soft' => 'bg-amber-50 text-amber-600', 'num' => 'text-amber-700'],
        ['label' => 'กรรมการดำเนินงาน', 'value' => (int) $countjow2, 'icon' => 'ri-team-line', 'bar' => 'bg-rose-500', 'soft' => 'bg-rose-50 text-rose-600', 'num' => 'text-rose-700'],
    ];
    $total = array_sum(array_column($items, 'value'));
    $head = $isArea ? 'from-amber-900 to-orange-700' : 'from-primary to-emerald-600';
@endphp
<section class="mb-8 overflow-hidden rounded-3xl border border-slate-100 bg-white shadow-sm">
    <div class="flex flex-wrap items-center justify-between gap-3 bg-gradient-to-r {{ $head }} px-5 py-4 text-white sm:px-8">
        <h3 class="flex items-center gap-2 text-base font-bold sm:text-lg"><i class="ri-bar-chart-2-line text-xl"></i> สถิติการแข่งขัน</h3>
        <p class="text-sm text-white/90">ผู้เกี่ยวข้องทั้งหมด <span class="text-xl font-black text-white">{{ number_format($total) }}</span> คน</p>
    </div>

    <div class="p-4 sm:p-8">
        {{-- แถบสัดส่วนรวม --}}
        @if($total > 0)
            <div class="mb-6 flex h-3 w-full overflow-hidden rounded-full bg-slate-100" role="img" aria-label="สัดส่วนผู้เกี่ยวข้อง">
                @foreach($items as $it)
                    @if($it['value'] > 0)
                        <div class="{{ $it['bar'] }}" style="width: {{ $it['value'] / $total * 100 }}%" title="{{ $it['label'] }} {{ number_format($it['value']) }} คน"></div>
                    @endif
                @endforeach
            </div>
        @endif

        <div class="grid grid-cols-2 gap-3 sm:gap-4 lg:grid-cols-4">
            @foreach($items as $it)
                @php $pct = $total > 0 ? round($it['value'] / $total * 100) : 0; @endphp
                <div class="rounded-2xl border border-slate-100 bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md sm:p-5">
                    <div class="mb-3 flex items-center justify-between">
                        <span class="flex h-11 w-11 items-center justify-center rounded-xl text-2xl {{ $it['soft'] }}"><i class="{{ $it['icon'] }}"></i></span>
                        <span class="text-xs font-bold text-slate-400">{{ $pct }}%</span>
                    </div>
                    <p class="text-3xl font-black leading-none tracking-tight sm:text-4xl {{ $it['num'] }}">{{ number_format($it['value']) }}</p>
                    <p class="mt-1.5 text-sm font-medium text-slate-500">{{ $it['label'] }} <span class="text-xs text-slate-400">คน</span></p>
                    <div class="mt-3 h-1.5 w-full overflow-hidden rounded-full bg-slate-100">
                        <div class="h-full rounded-full {{ $it['bar'] }}" style="width: {{ $pct }}%"></div>
                    </div>
                </div>
            @endforeach
        </div>

        @if($total === 0)
            <p class="mt-5 rounded-xl bg-slate-50 py-3 text-center text-sm text-slate-400">ยังไม่มีข้อมูลการลงทะเบียน ตัวเลขจะแสดงเมื่อมีการบันทึกข้อมูล</p>
        @endif
    </div>
</section>
