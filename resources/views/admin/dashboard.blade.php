@extends('layouts.app')

@use('App\Models\Profile')

@section('title', 'لوحة تحكم الفة')

@section('content')
    <main class="min-h-screen bg-sand-50 px-6 py-10">
        <div class="mx-auto max-w-6xl">

            <div class="mb-8 flex items-center justify-between">
                <div>
                    <h1 class="font-display text-3xl text-emerald-900">لوحة تحكم الفة</h1>
                    <p class="text-sm text-ink/60">{{ $registrants->total() }} نتيجة معروضة</p>
                </div>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="text-sm text-ink/60 hover:text-ink">تسجيل الخروج</button>
                </form>
            </div>

            <form method="GET" class="mb-6 flex flex-wrap gap-3 rounded-xl border border-emerald-900/10 bg-white p-4">
                <input type="search" name="code" value="{{ request('code') }}" placeholder="ابحث بالكود"
                       inputmode="numeric" autocomplete="off" aria-label="بحث بالكود"
                       class="w-40 rounded-lg border border-emerald-900/15 px-3 py-2 text-sm" />
                <select name="gender" class="rounded-lg border border-emerald-900/15 px-3 py-2 text-sm">
                    <option value="">كل الأنواع</option>
                    <option value="male" @selected(request('gender') === 'male')>عرسان</option>
                    <option value="female" @selected(request('gender') === 'female')>عرائس</option>
                </select>
                <select name="status" class="rounded-lg border border-emerald-900/15 px-3 py-2 text-sm">
                    <option value="">كل الحالات</option>
                    @foreach (Profile::STATUS_LABELS as $value => $label)
                        <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                <select name="nationality" class="rounded-lg border border-emerald-900/15 px-3 py-2 text-sm">
                    <option value="">كل الجنسيات</option>
                    @foreach ($nationalities as $n)
                        <option value="{{ $n }}" @selected(request('nationality') === $n)>{{ $n }}</option>
                    @endforeach
                </select>
                <button type="submit" class="rounded-lg bg-emerald-900 px-5 py-2 text-sm font-medium text-sand-50">تطبيق الفلاتر</button>
                @if (request()->anyFilled(['code', 'gender', 'status', 'nationality']))
                    <a href="{{ route('admin.dashboard') }}" class="rounded-lg px-5 py-2 text-sm text-ink/60 hover:text-ink">إلغاء الفلاتر</a>
                @endif
            </form>

            @if ($searchError)
                <p class="mb-4 text-sm text-red-600">{{ $searchError }}</p>
            @endif

            <div class="overflow-x-auto rounded-xl border border-emerald-900/10 bg-white">
                <table class="w-full text-right text-sm">
                    <thead class="border-b border-emerald-900/10 bg-sand-100/60 text-ink/70">
                    <tr>
                        <th class="px-4 py-3">الاسم</th>
                        <th class="px-4 py-3">الكود</th>
                        <th class="px-4 py-3">النوع</th>
                        <th class="px-4 py-3">السن</th>
                        <th class="px-4 py-3">الجنسية</th>
                        <th class="px-4 py-3">الحالة الاجتماعية</th>
                        <th class="px-4 py-3">الحالة</th>
                        <th class="px-4 py-3">تاريخ التسجيل</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($registrants as $r)
                        <tr class="border-b border-emerald-900/5 last:border-0">
                            <td class="px-4 py-3 font-medium">{{ $r->user->name }}</td>
                            <td class="px-4 py-3 font-mono text-ink/60">{{ $r->code }}</td>
                            <td class="px-4 py-3">{{ $r->genderLabel() }}</td>
                            <td class="px-4 py-3">{{ $r->age ?? '—' }}</td>
                            <td class="px-4 py-3">{{ $r->nationality ?? '—' }}</td>
                            <td class="px-4 py-3">{{ $r->maritalStatusLabel() }}</td>
                            <td class="px-4 py-3">
                                <span class="rounded-full bg-bronze-500/10 px-3 py-1 text-xs text-bronze-600">{{ $r->statusLabel() }}</span>
                            </td>
                            <td class="px-4 py-3 text-ink/60">{{ $r->created_at->format('Y/m/d') }}</td>
                            <td class="px-4 py-3">
                                <a href="{{ route('admin.registrants.show', $r) }}" class="text-emerald-800 hover:underline">عرض</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="9" class="px-4 py-10 text-center text-ink/50">لا توجد نتائج مطابقة</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">{{ $registrants->links() }}</div>
        </div>
    </main>
@endsection
