@props(['name', 'label', 'options', 'required' => false, 'placeholder' => 'اختر'])

<label class="block">
  <span class="mb-1.5 block text-sm font-medium text-ink/80">
    {{ $label }}
      @if ($required)<span class="text-bronze-600">*</span>@endif
  </span>
    <select name="{{ $name }}" @required($required) {{ $attributes->merge(['class' => 'field-input']) }}>
        <option value="">{{ $placeholder }}</option>
        @foreach ($options as $value => $text)
            <option value="{{ $value }}" @selected(old($name) === (string) $value)>{{ $text }}</option>
        @endforeach
    </select>
</label>
