@props(['name', 'label', 'type' => 'text', 'required' => false])

<label class="block">
  <span class="mb-1.5 block text-sm font-medium text-ink/80">
    {{ $label }}
      @if ($required)<span class="text-bronze-600">*</span>@endif
  </span>
    <input
        name="{{ $name }}"
        type="{{ $type }}"
        @if ($type !== 'password') value="{{ old($name) }}" @endif
        @required($required)
        {{ $attributes->merge(['class' => 'field-input']) }}
    />
</label>
