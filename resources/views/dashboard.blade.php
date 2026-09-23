@extends('layouts.app')

@section('title', 'حسابي')

@section('content')
<div class="h-2 bg-emerald-900"></div>
<main class="mx-auto max-w-xl px-6 py-16">
  <div class="rounded-2xl border border-emerald-900/10 bg-white p-8 text-center">
    <span class="inline-block rounded-full bg-bronze-500/10 px-4 py-1.5 text-sm font-medium text-bronze-600">
      {{ $profile?->statusLabel() ?? 'لا يوجد طلب مسجّل' }}
    </span>
    <h1 class="font-display mt-4 text-3xl text-emerald-900">أهلًا بك من جديد، {{ auth()->user()->name }}</h1>
    <p class="mt-3 text-sm text-ink/70">
      فريق "الفة" بيراجع طلبك دلوقتي. هنبلغك فور وجود ترشيح مناسب أو أي تحديث على حالتك.
    </p>

    <form method="POST" action="{{ route('logout') }}" class="mt-8">
      @csrf
      <button type="submit" class="text-sm text-ink/60 hover:text-ink hover:underline">تسجيل الخروج</button>
    </form>
  </div>

  @if ($profile)
    <div class="mt-6 rounded-2xl border border-emerald-900/15 bg-emerald-50/40 p-6">
      <p class="flex items-center gap-2 text-sm font-semibold text-emerald-900">✅ ملف منشور</p>
      <p class="mt-3 text-xs text-ink/60">كودك الخاص</p>
      <p class="font-display text-3xl tracking-widest text-emerald-900">{{ $profile->code }}</p>

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
  @endif
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

