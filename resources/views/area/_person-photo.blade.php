{{-- รูปวงกลมเล็กของนักเรียน/ครู (ไม่มีรูปไม่แสดงอะไร) กดเพื่อดูรูปเต็ม --}}
@if(!empty($st->student_image))
    @php
        $photoUrl = get_secure_file_url('picture', $st->student_image, $st->year);
        $photoName = trim(($st->getstudenttitle->title ?? '').$st->name.' '.$st->lastname);
        $photoSize = 'w-8 h-8';
    @endphp
    <a href="{{ $photoUrl }}" target="_blank" rel="noopener" title="{{ $photoName }}" class="inline-block shrink-0 align-middle">
        <img src="{{ $photoUrl }}" alt="{{ $photoName }}" loading="lazy"
             class="{{ $photoSize }} rounded-full object-cover bg-slate-100 border-2 {{ !empty($teacher) ? 'border-slate-200' : 'border-emerald-200' }} shadow-sm">
    </a>
@endif
