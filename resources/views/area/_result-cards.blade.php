{{--
  ผลการแข่งขันแบบการ์ด สำหรับมือถือ/แท็บเล็ต (แสดงที่ < lg เท่านั้น)
  ออกแบบให้เห็นข้อมูลครบในจอเดียว ไม่ต้องเลื่อนซ้าย-ขวา และแคปภาพไปใช้ต่อได้
  ตัวแปร: $addschool, $student, $activity, $mode ('area' | 'district'), $heading
--}}
@php
    $isArea = ($mode ?? 'district') === 'area';
    $allPeople = $student->pluck('getstudentname')->flatten(1);
    $cardMedal = function ($score) {
        $s = (float) $score;
        if ($s >= 80) return ['เหรียญทอง', 'bg-yellow-400 text-yellow-950'];
        if ($s >= 70) return ['เหรียญเงิน', 'bg-slate-300 text-slate-800'];
        if ($s >= 60) return ['เหรียญทองแดง', 'bg-orange-300 text-orange-950'];
        if ($s > 0) return ['เข้าร่วม', 'bg-blue-100 text-blue-700'];
        return null;
    };
    $rankStyle = [
        1 => 'bg-gradient-to-br from-yellow-300 to-yellow-500 text-yellow-950 ring-yellow-200',
        2 => 'bg-gradient-to-br from-slate-200 to-slate-400 text-slate-800 ring-slate-200',
        3 => 'bg-gradient-to-br from-orange-300 to-orange-500 text-orange-950 ring-orange-200',
    ];
    $accent = $isArea ? 'text-orange-600' : 'text-emerald-600';

    // โลโก้งาน: เครือข่ายใช้ของเครือข่ายก่อน ไม่มีค่อยใช้ของเขตพื้นที่ (แถวหลัก schoolgroup = 0)
    $logoCfg = null;
    try {
        $cfgBase = \App\Configs::withoutGlobalScopes()->where('event_id', active_event_id())->whereNotNull('app_logo')->where('app_logo', '<>', '');
        if ($isArea && session('area')) {
            $logoCfg = (clone $cfgBase)->where('schoolgroup', session('area'))->first();
        }
        $logoCfg = $logoCfg ?: (clone $cfgBase)->where('schoolgroup', 0)->first();
    } catch (\Throwable $e) {
        $logoCfg = null;
    }
    $logoUrl = $logoCfg ? get_secure_file_url('upload', $logoCfg->app_logo, $logoCfg->app_year) : null;

    $avatar = function ($p, $teacher) {
        $url = !empty($p->student_image) ? get_secure_file_url('picture', $p->student_image, $p->year) : null;
        $ring = $teacher ? 'border-slate-200' : 'border-emerald-200';
        return [$url, $ring];
    };
@endphp
<div class="lg:hidden" id="result-cards">
    {{-- หัวสรุปรายการ (อยู่ในกรอบเดียวกับผล เพื่อให้แคปภาพแล้วอ่านรู้เรื่อง) --}}
    <div class="mb-4 rounded-2xl border border-slate-100 bg-slate-50 px-4 py-3 text-center">
        @if($logoUrl)
            <img src="{{ $logoUrl }}" alt="โลโก้งาน" class="mx-auto mb-2 block h-16 w-auto max-w-full object-contain">
        @endif
        <p class="text-[11px] text-slate-500">{{ $heading }} ปีการศึกษา {{ session('app_year') }}</p>
        <p class="mt-1 text-base font-bold leading-snug text-slate-800">{{ $activity->activity_name ?? '' }}</p>
        @if($activity->getclass?->class_name)
            <p class="mt-0.5 text-sm text-slate-600">{{ $activity->getclass->class_name }}</p>
        @endif
    </div>

    <div class="grid gap-4 md:grid-cols-2 md:items-start">
        @forelse($addschool as $l)
            @php
                $published = $isArea
                    ? in_array((int) $l->activity_status, [1, 98, 99], true)
                    : ((int) $activity->activity_status === 1);
                $special = $isArea && in_array((int) $l->activity_status, [98, 99], true);
                $hasRank = $published && ($special || (float) $l->student_score > 0);
                $medal = $special ? ['เข้าร่วม', 'bg-blue-100 text-blue-700'] : ($published ? $cardMedal($l->student_score) : null);
                $rank = (int) $l->activity_rank;
                $people = $allPeople->where('school', $l->id);
                $students = $people->where('type', 0)->values();
                $teachers = $people->where('type', 1)->values();
            @endphp
            <div class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm">
                {{-- แถวบน: ชื่อโรงเรียนเต็มความกว้าง --}}
                <div class="border-b border-slate-100 bg-slate-50 px-4 py-3">
                    <p class="text-base font-bold leading-snug text-slate-800"><x-school-name :school="$l->getschoolname" /></p>
                    @if($l->getschoolgroup?->schoolgroup_name)
                        <p class="mt-0.5 text-xs text-slate-500">เครือข่าย: {{ $l->getschoolgroup->schoolgroup_name }}</p>
                    @endif
                </div>

                {{-- แถวผล: อันดับ | เหรียญ | คะแนน --}}
                <div class="flex items-center justify-between gap-3 px-4 py-3">
                    <div class="flex items-center gap-3">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full text-lg font-black shadow-inner ring-4 {{ $hasRank && isset($rankStyle[$rank]) ? $rankStyle[$rank] : 'bg-slate-100 text-slate-700 ring-slate-50' }}">
                            {{ $hasRank ? $rank : '-' }}
                        </div>
                        <div>
                            <p class="text-[10px] text-slate-400">อันดับ</p>
                            @if($medal)
                                <span class="inline-block rounded-lg px-2.5 py-0.5 text-xs font-bold shadow-sm {{ $medal[1] }}">{{ $medal[0] }}</span>
                            @else
                                <span class="text-xs text-slate-400">{{ $published ? '-' : 'รอประกาศผล' }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] text-slate-400">คะแนน</p>
                        <p class="text-3xl font-black leading-none text-slate-800">{{ $published ? $l->student_score : '-' }}</p>
                    </div>
                </div>

                <div class="grid gap-3 border-t border-slate-100 px-4 py-3 sm:grid-cols-2">
                    <div>
                        <p class="mb-1.5 text-[11px] font-bold {{ $accent }}">นักเรียน ({{ $students->count() }})</p>
                        <ol class="space-y-1.5">
                            @forelse($students as $i => $p)
                                <li class="flex items-center gap-2 text-[13px] leading-snug text-slate-700">
                                    <span class="w-4 shrink-0 text-right text-[11px] text-slate-400">{{ $i + 1 }}.</span>
                                    @php [$pu, $pr] = $avatar($p, false); @endphp
                                    @if($pu)
                                        <a href="{{ $pu }}" target="_blank" rel="noopener" class="shrink-0"><img src="{{ $pu }}" alt="" loading="lazy" class="h-12 w-12 rounded-full border-2 {{ $pr }} bg-slate-100 object-cover shadow-sm"></a>
                                    @endif
                                    <span class="min-w-0">{{ trim(($p->getstudenttitle->title ?? '').$p->name.' '.$p->lastname) }}</span>
                                </li>
                            @empty
                                <li class="text-xs italic text-slate-400">ไม่พบรายชื่อ</li>
                            @endforelse
                        </ol>
                    </div>
                    <div>
                        <p class="mb-1.5 text-[11px] font-bold text-slate-500">ครูผู้ควบคุม ({{ $teachers->count() }})</p>
                        <ol class="space-y-1.5">
                            @forelse($teachers as $i => $p)
                                <li class="flex items-center gap-2 text-[13px] leading-snug text-slate-600">
                                    <span class="w-4 shrink-0 text-right text-[11px] text-slate-400">{{ $i + 1 }}.</span>
                                    @php [$pu, $pr] = $avatar($p, true); @endphp
                                    @if($pu)
                                        <a href="{{ $pu }}" target="_blank" rel="noopener" class="shrink-0"><img src="{{ $pu }}" alt="" loading="lazy" class="h-12 w-12 rounded-full border-2 {{ $pr }} bg-slate-100 object-cover shadow-sm"></a>
                                    @endif
                                    <span class="min-w-0">{{ trim(($p->getstudenttitle->title ?? '').$p->name.' '.$p->lastname) }}</span>
                                </li>
                            @empty
                                <li class="text-xs italic text-slate-400">ไม่พบรายชื่อ</li>
                            @endforelse
                        </ol>
                    </div>
                </div>
            </div>
        @empty
            <div class="rounded-2xl border border-slate-100 bg-white py-10 text-center text-sm italic text-slate-400 md:col-span-2">ไม่พบข้อมูลผลการแข่งขัน</div>
        @endforelse
    </div>
</div>
