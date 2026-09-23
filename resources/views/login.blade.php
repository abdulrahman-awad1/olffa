@extends('layouts.app')

@section('title', 'تسجيل الدخول')

@section('content')
<div class="h-2 bg-emerald-900"></div>
<main class="flex min-h-[85vh] items-center justify-center px-6 py-12">
  <form method="POST" action="{{ route('login') }}" class="w-full max-w-sm rounded-2xl border border-emerald-900/10 bg-white p-8">
    @csrf
    <h1 class="font-display text-2xl text-emerald-900">تسجيل الدخول</h1>
    <p class="mt-1 text-sm text-ink/60">ارجع لمتابعة حالة طلبك</p>

    @if ($errors->any())
      <p class="mt-4 text-sm text-red-600">{{ $errors->first() }}</p>
    @endif

    <label class="mt-6 block">
      <span class="mb-1.5 block text-sm font-medium text-ink/80">البريد الإلكتروني أو رقم الهاتف</span>
      <input required name="identifier" value="{{ old('identifier') }}" class="field-input" type="text" />
    </label>

    <label class="mt-4 block">
      <span class="mb-1.5 block text-sm font-medium text-ink/80">كلمة المرور</span>
      <input required name="password" class="field-input" type="password" />
    </label>

    <button type="submit" class="mt-6 w-full rounded-full bg-emerald-900 px-6 py-3 font-medium text-sand-50 hover:bg-emerald-800">
      دخول
    </button>

    <p class="mt-4 text-center text-sm text-ink/60">
      لسه معملتش حساب؟
      <a href="{{ route('home') }}" class="text-bronze-600 hover:underline">سجّل الآن</a>
    </p>
  </form>
</main>
@endsection
