@props(['type' => 'button', 'variant' => 'primary'])
<button type="{{ $type }}" {{ $attributes->merge(['class' => 'ui-btn ui-btn-'.$variant]) }}>{{ $slot }}</button>

