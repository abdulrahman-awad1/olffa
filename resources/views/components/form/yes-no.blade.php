{{--
  سؤال اختيار بين "أيوه" و"لأ".
  - target: id عنصر (جوه الـ slot غالبًا) بيتخفى لما الإجابة تبقى "لأ".
  - default: القيمة الافتراضية (yes/no)، سيبها فاضية للأسئلة الحساسة عشان اليوزر يختار بنفسه.
  - boxed: بيحط الإطار حوالين السؤال.
--}}
@props([
  'name',
  'label',
  'default' => null,
  'required' => false,
  'boxed' => false,
  'target' => null,
  'yes' => 'أيوه',
  'no' => 'لأ',
])

@php($current = old($name, $default))

<fieldset @class(['rounded-lg border border-emerald-900/10 p-4' => $boxed])>
    <legend class="mb-2 block text-sm font-medium text-ink/80">
        {{ $label }}
        @if ($required)<span class="text-bronze-600">*</span>@endif
    </legend>

    <div class="flex gap-3">
        @foreach (['yes' => $yes, 'no' => $no] as $value => $text)
            <label class="relative flex flex-1 cursor-pointer items-center justify-center rounded-lg border border-emerald-900/15 py-2 text-sm has-[:checked]:bg-emerald-900 has-[:checked]:text-sand-50 has-[:focus-visible]:ring-2 has-[:focus-visible]:ring-bronze-500">
                <input
                    type="radio"
                    name="{{ $name }}"
                    value="{{ $value }}"
                    class="sr-only {{ $target ? 'pref-toggle' : '' }}"
                    @if ($target) data-target="{{ $target }}" @endif
                    @required($required)
                    @checked($current === $value)
                />
                {{ $text }}
            </label>
        @endforeach
    </div>

    {{ $slot }}
</fieldset>
