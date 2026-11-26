{{--
  รายชื่อโรงเรียนแบบการ์ด สำหรับมือถือ/แท็บเล็ต (แสดงที่ < lg เท่านั้น) กดการ์ดเพื่อดูรายละเอียดโรงเรียน
  ตัวแปร: $schoolname (paginator), $schoolcount, $activitycount, $mode ('area' | 'district')
--}}
@php
    $isArea = ($mode ?? 'district') === 'area';
    $n = ($schoolname->currentPage() - 1) * $schoolname->perPage();
    $accent = $isArea ? 'text-orange-600' : 'text-emerald-600';
@endphp
<div class="lg:hidden">
    <div class="grid gap-3 md:grid-cols-2">
        @forelse($schoolname as $l)
            @php
                $n++;
                $acts = (int) ($activitycount[$l->id] ?? 0);
                $teams = (int) ($schoolcount[$l->id] ?? 0);
                $url = url(($isArea ? 'area/' : '').'showschooldetail/'.$l->id.'/'.$l->school_group);
            @endphp
            <a href="{{ $url }}" class="block overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm transition active:bg-slate-50">
                <div class="flex items-center gap-3 p-4">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-slate-100 text-sm font-black text-slate-600">{{ $n }}</div>
                    <p class="min-w-0 flex-1 text-base font-bold leading-snug text-slate-800"><x-school-name :school="$l" /></p>
                    <i class="ri-arrow-right-s-line shrink-0 text-2xl {{ $accent }}"></i>
                </div>
                <div class="grid grid-cols-2 divide-x divide-slate-100 border-t border-slate-100 bg-slate-50/70 text-center">
                    <div class="px-3 py-2.5">
                        <p class="text-[11px] text-slate-400">รายการ</p>
                        <p class="text-lg font-black leading-tight {{ $acts > 0 ? 'text-slate-800' : 'text-slate-300' }}">{{ $acts }}</p>
                    </div>
                    <div class="px-3 py-2.5">
                        <p class="text-[11px] text-slate-400">ทีม</p>
                        <p class="text-lg font-black leading-tight {{ $teams > 0 ? 'text-slate-800' : 'text-slate-300' }}">{{ $teams }}</p>
                    </div>
                </div>
            </a>
        @empty
            <div class="rounded-2xl border border-slate-100 bg-white py-10 text-center text-sm italic text-slate-400 md:col-span-2">ไม่พบข้อมูลโรงเรียน</div>
        @endforelse
    </div>
</div>
