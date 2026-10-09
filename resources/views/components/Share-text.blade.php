{{--
  صندوق النص الجاهز للنشر + زرار النسخ (بيستخدمه المستخدم والأدمن).
  أي زراير إضافية (زي ماسنجر) بتتحط جوه الـ slot قبل زرار النسخ.
--}}
@props(['text', 'rows' => 12])

@php
    $textareaId = 'share-text-'.\Illuminate\Support\Str::random(6);
@endphp

<textarea id="{{ $textareaId }}" readonly rows="{{ $rows }}" aria-label="نص الملف الجاهز للنشر"
          class="mt-4 w-full rounded-lg border border-emerald-900/15 bg-white px-3 py-3 text-sm leading-relaxed text-ink/80">{{ $text }}</textarea>

<div class="mt-4 flex flex-col gap-3 sm:flex-row">
    {{ $slot }}
    <button type="button" data-copy-target="{{ $textareaId }}" data-label="نسخ الملف"
            class="{{ $slot->hasActualContent() ? 'flex-1' : '' }} flex items-center justify-center rounded-full border border-emerald-900/20 px-6 py-3 text-sm font-medium text-emerald-900 hover:bg-emerald-900/5">
        نسخ الملف
    </button>
</div>

@once
    @push('scripts')
        <script>
            document.addEventListener("click", async (event) => {
                const button = event.target.closest("[data-copy-target]");
                if (!button) return;

                const textarea = document.getElementById(button.dataset.copyTarget);
                const flash = (message) => {
                    button.textContent = message;
                    setTimeout(() => (button.textContent = button.dataset.label), 2000);
                };

                textarea.select();
                textarea.setSelectionRange(0, textarea.value.length); // لازم للموبايل (iOS)

                try {
                    await navigator.clipboard.writeText(textarea.value);
                } catch (e) {
                    // clipboard API مش متاح (مثلاً الموقع مش على HTTPS): نجرّب الطريقة القديمة.
                    if (!document.execCommand("copy")) {
                        flash("انسخ النص يدويًا");
                        return;
                    }
                }

                flash("تم النسخ ✓");
            });
        </script>
    @endpush
@endonce
