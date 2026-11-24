{{-- ชิปบุคคล: มีรูปแสดงรูปวงกลมเล็ก (กดดูรูปเต็ม) ไม่มีรูปแสดงชื่ออย่างเดียว เพื่อประหยัดพื้นที่ --}}
@php
    $fullName = trim(($st->getstudenttitle->title ?? '').$st->name.' '.$st->lastname);
    $imageUrl = $st->student_image ? get_secure_file_url('picture', $st->student_image, $st->year) : null;
    $size = $teacher ? 'w-8 h-8' : 'w-10 h-10';
@endphp
<span class="inline-flex items-center gap-2 max-w-full {{ $teacher ? 'text-slate-500' : 'text-slate-700' }}">
    @if($imageUrl)
        <a href="{{ $imageUrl }}" target="_blank" rel="noopener" title="{{ $fullName }}" class="shrink-0">
            <img src="{{ $imageUrl }}" alt="{{ $fullName }}" loading="lazy"
                 class="{{ $size }} rounded-full object-cover bg-slate-100 border-2 {{ $teacher ? 'border-slate-200' : 'border-orange-200' }} shadow-sm">
        </a>
    @else
        <span class="{{ $teacher ? 'text-slate-300' : 'text-orange-300' }}">&bull;</span>
    @endif
    <span class="leading-tight text-xs {{ $teacher ? 'font-medium' : 'font-bold' }}">{{ $fullName }}</span>
</span>
