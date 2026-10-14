@props(['name', 'label', 'rows' => 3, 'required' => false])

<label class="block">
  <span class="mb-1.5 block text-sm font-medium text-ink/80">
    {{ $label }}
    @if ($required)<span class="text-bronze-600">*</span>@endif
  </span>
  <textarea
    name="{{ $name }}"
    rows="{{ $rows }}"
    @required($required)
    {{ $attributes->merge(['class' => 'field-input']) }}
  >{{ old($name) }}</textarea>
</label>
