@extends('layouts.app')

@section('title', 'دخول لوحة التحكم')

@section('content')
<main class="flex min-h-screen items-center justify-center bg-sand-50 px-6">
  <form method="POST" action="{{ route('admin.login') }}" class="w-full max-w-sm rounded-2xl border border-emerald-900/10 bg-white p-8">
    @csrf
    <h1 class="font-display text-2xl text-emerald-900">لوحة تحكم أُلفة</h1>
    <p class="mt-1 text-sm text-ink/60">دخول فريق المبادرة فقط</p>

    @if ($errors->any())
      <p class="mt-4 text-sm text-red-600">{{ $errors->first() }}</p>
    @endif
    @if (session('error'))
      <p class="mt-4 text-sm text-red-600">{{ session('error') }}</p>
    @endif

    <label class="mt-6 block">
      <span class="mb-1.5 block text-sm font-medium text-ink/80">البريد الإلكتروني</span>
      <input required name="email" value="{{ old('email') }}" type="email"
             class="w-full rounded-lg border border-emerald-900/15 px-4 py-2.5 outline-none focus:border-bronze-500" />
    </label>

    <label class="mt-4 block">
      <span class="mb-1.5 block text-sm font-medium text-ink/80">كلمة المرور</span>
      <input required name="password" type="password"
             class="w-full rounded-lg border border-emerald-900/15 px-4 py-2.5 outline-none focus:border-bronze-500" />
    </label>

    <button type="submit" class="mt-6 w-full rounded-full bg-emerald-900 px-6 py-3 font-medium text-sand-50 hover:bg-emerald-800">
      دخول
    </button>
  </form>
</main>
@endsection
