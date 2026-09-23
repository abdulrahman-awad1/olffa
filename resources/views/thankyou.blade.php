@extends('layouts.app')

@section('title', 'تم استلام طلبك')

@section('content')
<div class="h-2 bg-emerald-900"></div>
<main class="mx-auto max-w-lg px-6 py-16">

  <div class="text-center">
    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-emerald-900/10 text-3xl text-emerald-900">✓</div>
    <h1 class="font-display mt-4 text-3xl text-emerald-900">تم إرسال الاستمارة بنجاح</h1>
    <p class="mt-2 text-lg text-ink/80">{{ auth()->user()->name }}</p>
    <span class="mt-2 inline-block rounded-full bg-bronze-500/10 px-4 py-1.5 text-sm font-medium text-bronze-600">
      {{ $profile->statusLabel() }}
    </span>
    <p class="mt-3 text-xs text-ink/50">تم التقديم: {{ $profile->created_at->format('Y/m/d') }}</p>
  </div>

  <div class="mt-8 space-y-3">
    <div class="rounded-lg border border-emerald-900/10 bg-white p-4 text-sm text-ink/70">
      🔒 بياناتك سرّية تمامًا ولن تُشارك مع أي طرف إلا بعد موافقتك الصريحة.
    </div>
    <div class="rounded-lg border border-emerald-900/10 bg-white p-4 text-sm text-ink/70">
      ⏳ فريق "الفة" هيراجع طلبك ويتواصل معاك عند وجود ترشيح مناسب.
    </div>
  </div>

  {{-- بطاقة الملف المنشور --}}
  <div class="mt-8 rounded-2xl border border-emerald-900/15 bg-emerald-50/40 p-6">
    <p class="flex items-center gap-2 text-sm font-semibold text-emerald-900">✅ ملف منشور</p>

    <p class="mt-3 text-xs text-ink/60">كودك الخاص</p>
    <p class="font-display text-3xl tracking-widest text-emerald-900">{{ $profile->code }}</p>

    <p class="mt-4 text-sm text-ink/70">
      هل تريد نشر ملفك على صفحتنا في فيسبوك بهذا الشكل؟ ملفك هيظهر بالكود بس من غير اسمك،
      حفاظًا على خصوصيتك. انسخ النص أو ابعته لينا مباشرة على ماسنجر.
    </p>

    <textarea id="share-text" readonly rows="12"
      class="mt-4 w-full rounded-lg border border-emerald-900/15 bg-white px-3 py-3 text-sm leading-relaxed text-ink/80"
    >{{ $profile->shareText() }}</textarea>

    <div class="mt-4 flex flex-col gap-3 sm:flex-row">
      <a href="https://m.me/{{ config('alfa.facebook_page_username') }}"
         target="_blank" rel="noopener"
         class="flex flex-1 items-center justify-center gap-2 rounded-full bg-emerald-900 px-6 py-3 text-sm font-medium text-sand-50 hover:bg-emerald-800">
        أرسل لصفحتنا على ماسنجر
      </a>
      <button type="button" onclick="copyShareText()"
        class="flex flex-1 items-center justify-center gap-2 rounded-full border border-emerald-900/20 px-6 py-3 text-sm font-medium text-emerald-900 hover:bg-emerald-900/5">
        <span id="copy-label">نسخ الملف</span>
      </button>
    </div>
  </div>

  <div class="mt-8 text-center">
    <a href="{{ route('dashboard') }}" class="text-sm text-ink/60 hover:text-ink hover:underline">
      متابعة حالة طلبي
    </a>
  </div>
</main>

@push('scripts')
<script>
  function copyShareText() {
    const textarea = document.getElementById("share-text");
    textarea.select();
    navigator.clipboard.writeText(textarea.value).then(() => {
      const label = document.getElementById("copy-label");
      label.textContent = "تم النسخ ✓";
      setTimeout(() => (label.textContent = "نسخ الملف"), 2000);
    });
  }
</script>
@endpush
@endsection
