{{-- การ์ดบุคคลสำหรับ grid: รูปใหญ่ + ชื่อใต้รูป (ไม่มีรูปแสดงวงกลมไอคอนเพื่อให้ grid เรียงตรงกัน) --}}
@php
    $fullName = trim(($st->getstudenttitle->title ?? '').$st->name.' '.$st->lastname);
    $imageUrl = $st->student_image ? get_secure_file_url('picture', $st->student_image, $st->year) : null;
    $tier = $tier ?? 'md'; // md = คนน้อย (รูปใหญ่), sm = คนมาก, xs = คนมากมาก
    $size = ['md' => 'w-20 h-20', 'sm' => 'w-14 h-14', 'xs' => 'w-10 h-10'][$tier]; // นักเรียนและครูขนาดเท่ากัน
    $textSize = $tier === 'xs' ? 'text-[9px]' : 'text-[11px]';
@endphp
<div class="flex flex-col items-center text-center gap-1.5 min-w-0">
    @if($imageUrl)
        <a href="{{ $imageUrl }}" target="_blank" rel="noopener" title="{{ $fullName }}">
            <img src="{{ $imageUrl }}" alt="{{ $fullName }}" loading="lazy"
                 class="{{ $size }} rounded-full object-cover bg-slate-100 border-[3px] {{ !empty($teacher) ? 'border-slate-300' : 'border-emerald-400' }} shadow-md hover:scale-105 transition-transform">
        </a>
    @else
        <span class="{{ $size }} rounded-full bg-slate-100 border-[3px] border-dashed border-slate-200 text-slate-300 flex items-center justify-center">
            <i class="ri-user-3-line {{ $tier === 'md' ? 'text-2xl' : 'text-base' }}"></i>
        </span>
    @endif
    <span class="{{ $textSize }} leading-tight break-words w-full {{ !empty($teacher) ? 'text-slate-500 font-medium' : 'text-slate-800 font-bold' }}">
        @if(!empty($no))<span class="text-slate-400">{{ $no }}.</span> @endif{{ $fullName }} <span class="{{ $tier === 'xs' ? 'text-[8px]' : 'text-[10px]' }} font-bold {{ !empty($teacher) ? 'text-slate-400' : 'text-emerald-600' }}">({{ !empty($teacher) ? 'ครู' : 'นักเรียน' }})</span>
    </span>
</div>
