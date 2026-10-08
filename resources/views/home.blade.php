@extends('layouts.app')

@section('title', 'أُلفة')

@section('content')
<div class="h-2 bg-emerald-900"></div>
<nav class="mx-auto flex max-w-6xl items-center justify-between px-6 py-4">
  <a href="{{ route('home') }}" class="font-display text-2xl text-emerald-900">أُلفة</a>
  @auth
    <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('dashboard') }}" class="text-sm text-ink/60 hover:text-ink">حسابي</a>
  @else
    <a href="{{ route('login') }}" class="text-sm text-ink/60 hover:text-ink">تسجيل الدخول</a>
  @endauth
</nav>

<div class="geo-pattern">
  <div class="mx-auto max-w-3xl px-6 py-16 text-center">
    <h1 class="font-display text-6xl leading-tight text-emerald-900 md:text-7xl">أُلفة</h1>
    <p class="mx-auto mt-6 max-w-xl text-lg text-ink/80 md:text-xl">
      نسأل الله أن يرزقك شريك حياة صالح يكون لك سكنًا. مبادرة شرعية
      لمساعدة الشباب والشابات على إيجاد شريك الحياة وفق ضوابط الشريعة الإسلامية.
    </p>
    <div class="mt-10 inline-flex items-baseline gap-2 rounded-full border border-bronze-500/40 bg-white/60 px-6 py-3">
      <span class="font-display text-3xl text-bronze-600">{{ number_format($count) }}</span>
      <span class="text-sm text-ink/70">شخص انضموا إلى المبادرة</span>
    </div>
  </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2">
  <a href="{{ route('register.create', 'male') }}"
     class="group flex flex-col justify-between gap-8 bg-emerald-900 px-10 py-16 text-sand-50 transition-colors hover:bg-emerald-800">
    <div>
      <span class="text-sm text-sand-100/70">للشباب</span>
      <h2 class="font-display mt-2 text-4xl">استمارة عريس</h2>
      <p class="mt-4 max-w-sm text-sand-100/85">
        ابدأ رحلتك للبحث عن شريكة حياة تشاركك الطريق إلى الله، من خلال
        استمارة مختصرة وسرية تمامًا.
      </p>
    </div>
    <span class="inline-flex w-fit items-center gap-2 rounded-full bg-sand-50 px-6 py-3 font-medium text-emerald-900 transition-transform group-hover:-translate-x-1">
      ابدأ التسجيل
    </span>
  </a>

  <a href="{{ route('register.create', 'female') }}"
     class="group flex flex-col justify-between gap-8 bg-bronze-500 px-10 py-16 text-emerald-950 transition-colors hover:bg-bronze-400">
    <div>
      <span class="text-sm text-emerald-950/70">للشابات</span>
      <h2 class="font-display mt-2 text-4xl">استمارة عروس</h2>
      <p class="mt-4 max-w-sm text-emerald-950/85">
        سجّلي بياناتك بثقة واطمئنان، فبياناتك لا تُعرض لأحد إلا بعد
        موافقتك الشخصية الصريحة.
      </p>
    </div>
    <span class="inline-flex w-fit items-center gap-2 rounded-full bg-emerald-950 px-6 py-3 font-medium text-sand-50 transition-transform group-hover:-translate-x-1">
      ابدئي التسجيل
    </span>
  </a>
</div>

<div class="mx-auto grid max-w-4xl grid-cols-1 gap-8 px-6 py-16 text-center md:grid-cols-3">
  <div>
    <p class="font-display text-2xl text-emerald-900">سرية تامة</p>
    <p class="mt-2 text-sm text-ink/70">جميع المعلومات محفوظة بخصوصية ولا تُعرض إلا بموافقتك.</p>
  </div>
  <div>
    <p class="font-display text-2xl text-emerald-900">10 دقائق فقط</p>
    <p class="mt-2 text-sm text-ink/70">استمارة مختصرة ومباشرة، وبياناتك تُحفظ تلقائيًا أثناء التعبئة.</p>
  </div>
  <div>
    <p class="font-display text-2xl text-emerald-900">مراجعة بشرية</p>
    <p class="mt-2 text-sm text-ink/70">فريق المبادرة يراجع الطلبات ويقترح الترشيحات المناسبة.</p>
  </div>
</div>

<footer class="border-t border-emerald-900/10 py-8 text-center text-xs text-ink/50">
  أُلفة — جميع الحقوق محفوظة
</footer>
@endsection
