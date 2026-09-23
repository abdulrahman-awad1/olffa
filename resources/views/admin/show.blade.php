@extends('layouts.app')

@section('title', $profile->user->name)

@section('content')
    <main class="min-h-screen bg-sand-50 px-6 py-10">
        <div class="mx-auto max-w-4xl">
            <a href="{{ route('admin.dashboard') }}" class="text-sm text-ink/60 hover:underline">→ رجوع للوحة التحكم</a>

            @if (session('success'))
                <div class="mt-4 rounded-lg bg-emerald-50 p-3 text-sm text-emerald-800">{{ session('success') }}</div>
            @endif

            <div class="mt-4 grid grid-cols-1 gap-6 md:grid-cols-3">
                <div class="space-y-6 md:col-span-2">

                    <div class="rounded-xl border border-emerald-900/15 bg-emerald-50/40 p-6">
                        <p class="flex items-center gap-2 text-sm font-semibold text-emerald-900">✅ شكل الملف المنشور</p>
                        <p class="mt-1 text-xs text-ink/60">نفس النص اللي ظاهر عند المستخدم — انسخه لو هتنشره بنفسك للطرف التاني.</p>

                        <textarea id="admin-share-text" readonly rows="14"
                                  class="mt-4 w-full rounded-lg border border-emerald-900/15 bg-white px-3 py-3 text-sm leading-relaxed text-ink/80"
                        >{{ $profile->shareText() }}</textarea>

                        <button type="button" onclick="copyAdminShareText()"
                                class="mt-3 rounded-full border border-emerald-900/20 px-6 py-2 text-sm font-medium text-emerald-900 hover:bg-emerald-900/5">
                            <span id="admin-copy-label">نسخ الملف</span>
                        </button>
                    </div>

                    <div class="rounded-xl border border-emerald-900/10 bg-white p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h1 class="font-display text-2xl text-emerald-900">{{ $profile->user->name }}</h1>
                                <p class="text-xs text-ink/50">الكود: {{ $profile->code }}</p>
                            </div>
                            <span class="rounded-full bg-emerald-900/10 px-3 py-1 text-xs text-emerald-900">
              {{ $profile->gender === 'male' ? 'عريس' : 'عروس' }}
            </span>
                        </div>

                        <div class="mt-4">
                            <h3 class="mb-1 text-sm font-semibold text-emerald-900">البيانات الأساسية</h3>
                            <div class="info-row"><span class="text-ink/50">البريد الإلكتروني</span><span>{{ $profile->user->email }}</span></div>
                            <div class="info-row"><span class="text-ink/50">الهاتف</span><span>{{ $profile->user->phone }}</span></div>
                            <div class="info-row"><span class="text-ink/50">السن</span><span>{{ $profile->age ?? '—' }}</span></div>
                            <div class="info-row"><span class="text-ink/50">الجنسية</span><span>{{ $profile->nationality ?? '—' }}</span></div>
                        </div>

                        <div class="mt-6">
                            <h3 class="mb-1 text-sm font-semibold text-emerald-900">المظهر</h3>
                            <div class="info-row"><span class="text-ink/50">الطول</span><span>{{ $profile->height ? $profile->height.' سم' : '—' }}</span></div>
                            <div class="info-row"><span class="text-ink/50">الوزن</span><span>{{ $profile->weight ? $profile->weight.' كجم' : '—' }}</span></div>
                            <div class="info-row"><span class="text-ink/50">لون البشرة</span><span>{{ $profile->skinColorLabel() ?? '—' }}</span></div>
                        </div>

                        <div class="mt-6">
                            <h3 class="mb-1 text-sm font-semibold text-emerald-900">الإقامة والسكن</h3>
                            <div class="info-row"><span class="text-ink/50">بلد الإقامة</span><span>{{ $profile->residence_country ?? '—' }}</span></div>
                            <div class="info-row"><span class="text-ink/50">المحافظة</span><span>{{ $profile->governorate ?? '—' }}</span></div>
                            <div class="info-row"><span class="text-ink/50">محل الإقامة الحالي</span><span>{{ $profile->current_residence ?? '—' }}</span></div>
                            @if ($profile->gender === 'male')
                                <div class="info-row"><span class="text-ink/50">طبيعة سكن الزوجية</span><span>{{ $profile->maritalHomeLabel() ?? '—' }}</span></div>
                            @endif
                        </div>

                        <div class="mt-6">
                            <h3 class="mb-1 text-sm font-semibold text-emerald-900">الالتزام الديني</h3>
                            <div class="info-row"><span class="text-ink/50">مستوى الالتزام</span><span>{{ $profile->commitmentLabel() }}</span></div>
                            <div class="info-row"><span class="text-ink/50">الصلاة</span><span>{{ $profile->praysLabel() }}</span></div>
                            <div class="info-row">
                                <span class="text-ink/50">{{ $profile->gender === 'male' ? 'اللحية' : 'الحجاب' }}</span>
                                <span>{{ $profile->gender === 'male' ? $profile->beardLabel() : $profile->hijabLabel() }}</span>
                            </div>
                            <div class="info-row"><span class="text-ink/50">مدخن؟</span><span>{{ $profile->is_smoker ? 'أيوه' : 'لأ' }}</span></div>
                            <div class="info-row"><span class="text-ink/50">ملاحظات</span><span>{{ $profile->religious_notes ?? '—' }}</span></div>
                        </div>

                        <div class="mt-6">
                            <h3 class="mb-1 text-sm font-semibold text-emerald-900">التعليم والحالة الاجتماعية</h3>
                            <div class="info-row"><span class="text-ink/50">المؤهل</span><span>{{ $profile->education ?? '—' }}</span></div>
                            <div class="info-row"><span class="text-ink/50">المهنة</span><span>{{ $profile->occupation ?? '—' }}</span></div>
                            <div class="info-row"><span class="text-ink/50">الحالة الاجتماعية</span><span>{{ $profile->maritalStatusLabel() }}</span></div>
                            <div class="info-row">
                                <span class="text-ink/50">أمراض مزمنة؟</span>
                                <span>{{ $profile->has_chronic_disease ? ($profile->chronic_disease_details ?: 'أيوه') : 'لأ' }}</span>
                            </div>
                        </div>

                        <div class="mt-6">
                            <h3 class="mb-1 text-sm font-semibold text-emerald-900">نبذة ومواصفات مطلوبة</h3>
                            <div class="info-row"><span class="text-ink/50">نبذة شخصية</span><span>{{ $profile->about_me }}</span></div>
                            <div class="info-row"><span class="text-ink/50">المواصفات المطلوبة</span><span>{{ $profile->partner_preferences }}</span></div>
                            @if ($profile->gender === 'male')
                                <div class="info-row"><span class="text-ink/50">مؤهل العروسة المطلوب</span><span>{{ $profile->desired_bride_education ?? '—' }}</span></div>
                                <div class="info-row"><span class="text-ink/50">هل يريدها تعمل؟</span><span>{{ $profile->desiredBrideWorkLabel() ?? '—' }}</span></div>
                                <div class="info-row"><span class="text-ink/50">حجاب العروسة المقبول</span><span>{{ $profile->desiredBrideHijabLabel() ?? '—' }}</span></div>
                            @endif
                            <div class="info-row">
                                <span class="text-ink/50">أولوياته في المطابقة</span>
                                <span>
                السن: {{ $profile->age_important ? 'مهم' : 'مش مهم' }} ·
                مكان الإقامة: {{ $profile->location_important ? 'مهم' : 'مش مهم' }} ·
                الحالة الاجتماعية: {{ $profile->marital_important ? 'مهم' : 'مش مهم' }}
              </span>
                            </div>
                            @if ($profile->age_important)
                                <div class="info-row"><span class="text-ink/50">مدى السن المطلوب</span><span>{{ $profile->age_range_min }} إلى {{ $profile->age_range_max }}</span></div>
                            @endif
                        </div>

                        <div class="mt-6">
                            <h3 class="mb-1 text-sm font-semibold text-emerald-900">بيانات التواصل (سرّية)</h3>
                            <div class="info-row"><span class="text-ink/50">الهاتف</span><span>{{ $profile->user->phone }}</span></div>
                            <div class="info-row"><span class="text-ink/50">واتساب</span><span>{{ $profile->contact_whatsapp ?? '—' }}</span></div>
                        </div>
                    </div>

                    <div class="rounded-xl border border-emerald-900/10 bg-white p-6">
                        <h3 class="font-display text-xl text-emerald-900">ترشيحات محتملة</h3>
                        <p class="mt-1 text-xs text-ink/50">
                            محسوبة بناءً على أولويات كل طرف الخاصة به. الترشيح ميظهرش إلا لو نسبة رضا الطرفين عن بعض 60% فأكتر.
                        </p>
                        <div class="mt-4 space-y-3">
                            @forelse ($matches as $m)
                                <a href="{{ route('admin.registrants.show', $m) }}" class="flex items-center justify-between rounded-lg border border-emerald-900/10 px-4 py-3 text-sm hover:bg-sand-100/60">
                                    <div class="flex items-center gap-3">
                                        <span class="rounded-full bg-emerald-900 px-2.5 py-1 text-xs font-semibold text-sand-50">{{ $m->my_score }}%</span>
                                        <span class="rounded-full bg-bronze-500 px-2.5 py-1 text-xs font-semibold text-emerald-950">{{ $m->their_score }}%</span>
                                        <span class="font-medium">{{ $m->user->name }}</span>
                                    </div>
                                    <span class="text-ink/50">{{ $m->age }} سنة · {{ $m->nationality }}</span>
                                </a>
                            @empty
                                <p class="text-sm text-ink/50">لا توجد ترشيحات بنسبة توافق 60% أو أكتر حاليًا.</p>
                            @endforelse
                        </div>
                        @if ($matches->isNotEmpty())
                            <p class="mt-3 text-xs text-ink/40">الدائرة الخضراء = رضا {{ $profile->user->name }} عن المرشّح · الدائرة الذهبية = رضا المرشّح عنه</p>
                        @endif
                    </div>
                </div>

                <div>
                    <form method="POST" action="{{ route('admin.registrants.update', $profile) }}" class="rounded-xl border border-emerald-900/10 bg-white p-6">
                        @csrf
                        @method('PATCH')
                        <h2 class="font-display text-xl text-emerald-900">إدارة الطلب</h2>

                        <label class="mt-4 block">
                            <span class="mb-1.5 block text-sm font-medium text-ink/80">الحالة</span>
                            <select name="status" class="w-full rounded-lg border border-emerald-900/15 px-3 py-2 text-sm">
                                @foreach (\App\Models\Profile::STATUS_LABELS as $value => $label)
                                    <option value="{{ $value }}" @selected($profile->status === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </label>

                        <label class="mt-4 block">
                            <span class="mb-1.5 block text-sm font-medium text-ink/80">ملاحظات داخلية (لا تظهر للمستخدم)</span>
                            <textarea name="admin_notes" rows="4" class="w-full rounded-lg border border-emerald-900/15 px-3 py-2 text-sm">{{ $profile->admin_notes }}</textarea>
                        </label>

                        <button type="submit" class="mt-4 w-full rounded-full bg-emerald-900 px-6 py-2.5 text-sm font-medium text-sand-50 hover:bg-emerald-800">
                            حفظ التغييرات
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </main>

    @push('styles')
        <style>
            .info-row { display: grid; grid-template-columns: 1fr 2fr; gap: 1rem; padding: 0.625rem 0; font-size: 0.875rem; border-bottom: 1px solid rgba(18,61,46,0.05); }
            .info-row:last-child { border-bottom: none; }
        </style>
    @endpush

    @push('scripts')
        <script>
            function copyAdminShareText() {
                const textarea = document.getElementById("admin-share-text");
                textarea.select();
                navigator.clipboard.writeText(textarea.value).then(() => {
                    const label = document.getElementById("admin-copy-label");
                    label.textContent = "تم النسخ ✓";
                    setTimeout(() => (label.textContent = "نسخ الملف"), 2000);
                });
            }
        </script>
    @endpush
@endsection
