@props(['label' => null, 'name', 'type' => 'text', 'help' => null])
<div class="ui-form-group">
    @if($label)<label class="ui-label" for="{{ $attributes->get('id', $name) }}">{{ $label }}</label>@endif
    <input name="{{ $name }}" type="{{ $type }}" {{ $attributes->merge(['id' => $name, 'class' => 'ui-input']) }}>
    @if($help)<p class="ui-help">{{ $help }}</p>@endif
    @error($name)<p class="ui-error">{{ $message }}</p>@enderror
</div>

