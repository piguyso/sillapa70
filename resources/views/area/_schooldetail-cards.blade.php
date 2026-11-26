{{--
  ผลการแข่งขันของโรงเรียนแบบการ์ด สำหรับมือถือ/แท็บเล็ต (แสดงที่ < lg เท่านั้น)
  ตัวแปร: $addschool (paginator), $student, $mode ('area' | 'district')
--}}
@php
    $isArea = ($mode ?? 'district') === 'area';
    $n = ($addschool->currentPage() - 1) * $addschool->perPage();
    $allPeople = $student->pluck('getstudentname')->flatten(1);
    $accent = $isArea ? 'text-orange-600' : 'text-emerald-600';
    $medalOf = function ($score) {
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
@endphp
<div class="lg:hidden">
    <div class="grid gap-4 md:grid-cols-2 md:items-start">
        @forelse($addschool as $l)
            @php
                $n++;
                $act = $l->getactivityname;
                $status = $isArea ? (int) $l->activity_status : (int) ($act->activity_status ?? 0);
                $published = $isArea ? in_array($status, [1, 98, 99], true) : $status === 1;
                $special = $isArea && in_array($status, [98, 99], true);
                $hasRank = $published && ($special || (float) $l->student_score > 0);
                $medal = $special ? ['เข้าร่วม', 'bg-blue-100 text-blue-700'] : ($published ? $medalOf($l->student_score) : null);
                $rank = (int) $l->activity_rank;
                $people = $allPeople->where('school', $l->id);
                $students = $people->where('type', 0)->values();
                $teachers = $people->where('type', 1)->values();
                $detailUrl = $act ? url(($isArea ? 'area/' : '').'viewactivitydetail/'.$act->id) : '#';
            @endphp
            <div class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm">
                <a href="{{ $detailUrl }}" class="block border-b border-slate-100 bg-slate-50 px-4 py-3">
                    <p class="text-base font-bold leading-snug text-slate-800"><span class="mr-1 text-slate-400">{{ $n }}.</span>{{ $act?->activity_name }}</p>
                    @if($act?->getclass?->class_name)
                        <p class="mt-0.5 text-xs text-slate-500">{{ $act->getclass->class_name }}</p>
                    @endif
                </a>

                <div class="flex items-center justify-between gap-3 px-4 py-3">
                    <div class="flex items-center gap-3">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full text-lg font-black shadow-inner ring-4 {{ $hasRank && isset($rankStyle[$rank]) ? $rankStyle[$rank] : 'bg-slate-100 text-slate-700 ring-slate-50' }}">
                            {{ $hasRank && !$special ? $rank : '-' }}
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
                                    @if(!empty($p->student_image))
                                        @php $pu = get_secure_file_url('picture', $p->student_image, $p->year); @endphp
                                        <a href="{{ $pu }}" target="_blank" rel="noopener" class="shrink-0"><img src="{{ $pu }}" alt="" loading="lazy" class="h-12 w-12 rounded-full border-2 border-emerald-200 bg-slate-100 object-cover shadow-sm"></a>
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
                                    @if(!empty($p->student_image))
                                        @php $pu = get_secure_file_url('picture', $p->student_image, $p->year); @endphp
                                        <a href="{{ $pu }}" target="_blank" rel="noopener" class="shrink-0"><img src="{{ $pu }}" alt="" loading="lazy" class="h-12 w-12 rounded-full border-2 border-slate-200 bg-slate-100 object-cover shadow-sm"></a>
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
            <div class="rounded-2xl border border-slate-100 bg-white py-10 text-center text-sm italic text-slate-400 md:col-span-2">ยังไม่มีข้อมูลการสรุปผล</div>
        @endforelse
    </div>
</div>
