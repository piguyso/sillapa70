{{--
  สรุปเหรียญรายโรงเรียน (TOP 10) แบบการ์ด สำหรับมือถือ/แท็บเล็ต (แสดงที่ < lg เท่านั้น)
  ตัวแปร: $dbrank, $selectschool, $mode ('area' | 'district')
--}}
@php
    $isArea = ($mode ?? 'district') === 'area';
    $rankStyle = [
        1 => 'bg-gradient-to-br from-yellow-300 to-yellow-500 text-yellow-950 ring-yellow-200',
        2 => 'bg-gradient-to-br from-slate-200 to-slate-400 text-slate-800 ring-slate-200',
        3 => 'bg-gradient-to-br from-orange-300 to-orange-500 text-orange-950 ring-orange-200',
    ];
    $i = 0;
@endphp
<div class="lg:hidden p-3 sm:p-4">
    <div class="grid gap-3 md:grid-cols-2">
        @forelse($dbrank as $l)
            @php $i++; $total = $l->Gold + $l->Silver + $l->Bronce; @endphp
            <a href="{{ url(($isArea ? 'area/' : '').'showschooldetail/'.$l->school_name.'/0') }}" class="block overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm active:bg-slate-50">
                <div class="flex items-center gap-3 p-3.5">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full text-base font-black shadow-inner ring-4 {{ $rankStyle[$i] ?? 'bg-slate-100 text-slate-600 ring-slate-50' }}">{{ $i }}</div>
                    <p class="min-w-0 flex-1 text-[15px] font-bold leading-snug text-slate-800"><x-school-name :id="$l->school_name" :name="$selectschool[$l->school_name] ?? ''" /></p>
                </div>
                <div class="grid {{ $isArea ? 'grid-cols-5' : 'grid-cols-4' }} divide-x divide-slate-100 border-t border-slate-100 bg-slate-50/70 text-center">
                    <div class="px-1 py-2"><p class="text-[10px] font-bold text-yellow-600"><i class="ri-medal-fill"></i> ทอง</p><p class="text-lg font-black leading-tight text-yellow-600">{{ $l->Gold }}</p></div>
                    <div class="px-1 py-2"><p class="text-[10px] font-bold text-slate-500"><i class="ri-medal-fill"></i> เงิน</p><p class="text-lg font-black leading-tight text-slate-500">{{ $l->Silver }}</p></div>
                    <div class="px-1 py-2"><p class="text-[10px] font-bold text-amber-800"><i class="ri-medal-fill"></i> ทองแดง</p><p class="text-lg font-black leading-tight text-amber-800">{{ $l->Bronce }}</p></div>
                    @if($isArea)
                        <div class="px-1 py-2"><p class="text-[10px] text-slate-400">ไม่แข่ง</p><p class="text-lg font-black leading-tight text-slate-400">{{ $l->Part ?? 0 }}</p></div>
                    @endif
                    <div class="px-1 py-2"><p class="text-[10px] text-slate-500">รวม</p><p class="text-lg font-black leading-tight text-slate-800">{{ $total }}</p></div>
                </div>
            </a>
        @empty
            <div class="rounded-2xl border border-slate-100 bg-white py-8 text-center text-sm italic text-slate-400 md:col-span-2">- ยังไม่มีข้อมูลเหรียญ -</div>
        @endforelse
    </div>
</div>
