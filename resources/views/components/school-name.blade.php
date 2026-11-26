@props(['school' => null, 'id' => null, 'name' => null, 'size' => 'sm'])
<span {{ $attributes->merge(["class" => "inline-flex items-center gap-2 align-middle"]) }}><x-school-logo :school="$school" :id="$id ?? ($school->id ?? null)" :size="$size" /><span>{{ $name ?? ($school->school_name ?? '') }}</span></span>
