{{--
  รายการกิจกรรมแบบการ์ด สำหรับมือถือ/แท็บเล็ต (แสดงที่ < lg เท่านั้น) กดการ์ดเพื่อดูผลการแข่งขัน
  ตัวแปร: $activity (paginator), $schoolcountactivity, $mode ('area' | 'district'), $start (เลขลำดับเริ่มต้น)
--}}
@php
    $isArea = ($mode ?? 'district') === 'area';
    $n = $start ?? 0;
    $statusBadge = [
        1  => ['ประกาศผลแล้ว', 'ri-checkbox-circle-fill', 'bg-emerald-50 text-emerald-700 border-emerald-200'],
        98 => ['ไม่มีการแข่งขัน', 'ri-close-circle-fill', 'bg-red-50 text-red-700 border-red-200'],
        99 => ['มอบเกียรติบัตร', 'ri-award-fill', 'bg-amber-50 text-amber-700 border-amber-200'],
        2  => ['ยกเลิกผล', 'ri-spam-2-fill', 'bg-slate-100 text-slate-500 border-slate-200'],
    ];
    $waiting = ['รอประกาศ', 'ri-time-line', 'bg-slate-50 text-slate-500 border-slate-200'];
    $accent = $isArea ? 'text-orange-600' : 'text-emerald-600';
@endphp
<div class="lg:hidden">
    <div class="grid gap-3 md:grid-cols-2">
        @forelse($activity as $l)
            @php
                $n++;
                $st = (int) ($isArea ? ($l->getactivitystatus->activity_status ?? 0) : $l->activity_status);
                $badge = $statusBadge[$st] ?? $waiting;
                $teams = (int) ($schoolcountactivity[$l->id] ?? 0);
                $url = url(($isArea ? 'area/' : '').'viewactivitydetail/'.$l->id);
            @endphp
            <a href="{{ $url }}" class="block overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm transition active:bg-slate-50">
                <div class="flex items-start gap-3 p-4">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-slate-100 text-sm font-black text-slate-600">{{ $n }}</div>
                    <div class="min-w-0 flex-1">
                        <p class="text-base font-bold leading-snug text-slate-800">{{ $l->activity_name }}</p>
                        <div class="mt-1.5 flex flex-wrap items-center gap-1.5">
                            @if($l->getclass?->class_name)
                                <span class="rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-600">{{ $l->getclass->class_name }}</span>
                            @endif
                            <span class="rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-bold text-slate-700">{{ $teams }} ทีม</span>
                        </div>
                    </div>
                    <i class="ri-arrow-right-s-line shrink-0 self-center text-2xl {{ $accent }}"></i>
                </div>

                <div class="flex flex-wrap items-center justify-between gap-2 border-t border-slate-100 bg-slate-50/70 px-4 py-2.5">
                    <span class="inline-flex items-center gap-1 rounded-lg border px-2.5 py-1 text-xs font-bold {{ $badge[2] }}"><i class="{{ $badge[1] }}"></i> {{ $badge[0] }}</span>
                    <span class="text-xs text-slate-500">นักเรียน {{ $l->activity_studennumber }} · ครู {{ $l->activity_teachernumber }} คน/ทีม</span>
                </div>

                @if(!$isArea && ($l->activity_branchroom || $l->activity_day || $l->activity_time))
                    <div class="space-y-1 border-t border-slate-100 px-4 py-2.5 text-xs text-slate-600">
                        @if($l->activity_branchroom)<div class="flex items-start gap-1.5"><i class="ri-map-pin-2-line {{ $accent }}"></i><span>{{ $l->activity_branchroom }}</span></div>@endif
                        @if($l->activity_day)<div class="flex items-start gap-1.5"><i class="ri-calendar-line {{ $accent }}"></i><span>{{ Datethai($l->activity_day) }}</span></div>@endif
                        @if($l->activity_time)<div class="flex items-start gap-1.5"><i class="ri-time-line {{ $accent }}"></i><span>{{ $l->activity_time }}</span></div>@endif
                    </div>
                @endif
            </a>
        @empty
            <div class="rounded-2xl border border-slate-100 bg-white py-10 text-center text-sm italic text-slate-400 md:col-span-2">ไม่พบกิจกรรม</div>
        @endforelse
    </div>
</div>
