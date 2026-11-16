@props(['label' => null, 'name', 'help' => null, 'value' => null])
<div class="ui-form-group">
    @if($label)<label class="ui-label" for="{{ $attributes->get('id', $name) }}">{{ $label }}</label>@endif
    <textarea name="{{ $name }}" {{ $attributes->merge(['id' => $name, 'class' => 'ui-textarea']) }}>{{ old($name, $value ?? $slot) }}</textarea>
    @if($help)<p class="ui-help">{{ $help }}</p>@endif
    @error($name)<p class="ui-error">{{ $message }}</p>@enderror
</div>

