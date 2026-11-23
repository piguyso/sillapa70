@props(['school' => null, 'id' => null, 'size' => 'md', 'icon' => 'ri-school-line'])
@php
    // ขนาดวงกลม: sm=24px md=40px lg=64px
    $dim = ['sm' => 'w-6 h-6 text-xs', 'md' => 'w-10 h-10 text-lg', 'lg' => 'w-16 h-16 text-3xl'][$size] ?? 'w-10 h-10 text-lg';
    $url = $school ? ($school->logo_url ?? null) : school_logo_url_by_id($id);
@endphp
@if($url)
    <img src="{{ $url }}" alt="" loading="lazy" onerror="this.outerHTML='<span class=&quot;{{ $dim }} rounded-full bg-gray-100 text-gray-300 inline-flex items-center justify-center shrink-0&quot;><i class=&quot;{{ $icon }}&quot;></i></span>'" {{ $attributes->merge(['class' => $dim.' rounded-full object-contain bg-white border border-gray-100 shrink-0 inline-block']) }}>
@else
    <span {{ $attributes->merge(['class' => $dim.' rounded-full bg-gray-100 text-gray-300 inline-flex items-center justify-center shrink-0']) }}><i class="{{ $icon }}"></i></span>
@endif
