@extends('layouts.app')

@use('App\Models\Profile')

@php
    $isMale = $profile->isMale();

    $priorities = 'السن: '.($profile->age_important ? 'مهم' : 'مش مهم')
      .' · مكان الإقامة: '.($profile->location_important ? 'مهم' : 'مش مهم')
      .' · الحالة الاجتماعية: '.($profile->marital_important ? 'مهم' : 'مش مهم');

    $hasError = $errors->any();
    $flashMessage = $hasError ? $errors->first() : session('success');

    $ageRange = $profile->age_range_min && $profile->age_range_max
      ? $profile->age_range_min.' إلى '.$profile->age_range_max
      : 'غير محدد';
@endphp

@section('title', $profile->user->name)

@section('content')
    <main class="min-h-screen bg-sand-50 px-6 py-10">
        <div class="mx-auto max-w-4xl">
            <a href="{{ route('admin.dashboard') }}" class="text-sm text-ink/60 hover:underline">→ رجوع للوحة التحكم</a>

            {{-- رسائل الحفظ (بتتحدّث من الـ JS من غير reload) --}}
            <div id="flash" role="status" @class([
      'mt-4 rounded-lg p-3 text-sm',
      'hidden' => ! $flashMessage,
      'bg-red-50 text-red-700' => $hasError,
      'bg-emerald-50 text-emerald-800' => ! $hasError,
    ])>{{ $flashMessage }}</div>

            <div class="mt-4 grid grid-cols-1 gap-6 md:grid-cols-3">
                <div class="space-y-6 md:col-span-2">

                    <div class="rounded-xl border border-emerald-900/15 bg-emerald-50/40 p-6">
                        <p class="flex items-center gap-2 text-sm font-semibold text-emerald-900">📝 شكل الملف للنشر</p>
                        <p class="mt-1 text-xs text-ink/60">نفس النص اللي ظاهر عند المستخدم — راجعه قبل ما تنشره (النصوص الحرة ممكن يكون فيها بيانات تعريفية).</p>

                        <x-share-text :text="$profile->shareText()" rows="14" />
                    </div>

                    <div class="rounded-xl border border-emerald-900/10 bg-white p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h1 class="font-display text-2xl text-emerald-900">{{ $profile->user->name }}</h1>
                                <p class="text-xs text-ink/50">الكود: {{ $profile->code }}</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <span id="status-badge" class="rounded-full bg-bronze-500/10 px-3 py-1 text-xs text-bronze-600">{{ $profile->statusLabel() }}</span>
                                <span class="rounded-full bg-emerald-900/10 px-3 py-1 text-xs text-emerald-900">{{ $profile->genderLabel() }}</span>
                            </div>
                        </div>

                        <div class="mt-4">
                            <h3 class="mb-1 text-sm font-semibold text-emerald-900">البيانات الأساسية</h3>
                            <x-info-row label="السن" :value="$profile->age" />
                            <x-info-row label="الجنسية" :value="$profile->nationality" />
                        </div>

                        <div class="mt-6">
                            <h3 class="mb-1 text-sm font-semibold text-emerald-900">المظهر</h3>
                            <x-info-row label="الطول" :value="$profile->height ? $profile->height.' سم' : null" />
                            <x-info-row label="الوزن" :value="$profile->weight ? $profile->weight.' كجم' : null" />
                            <x-info-row label="لون البشرة" :value="$profile->skinColorLabel()" />
                        </div>

                        <div class="mt-6">
                            <h3 class="mb-1 text-sm font-semibold text-emerald-900">الإقامة والسكن</h3>
                            <x-info-row label="بلد الإقامة" :value="$profile->residence_country" />
                            <x-info-row label="المحافظة" :value="$profile->governorate" />
                            <x-info-row label="محل الإقامة الحالي" :value="$profile->current_residence" />
                            @if ($isMale)
                                <x-info-row label="طبيعة سكن الزوجية" :value="$profile->maritalHomeLabel()" />
                            @endif
                        </div>

                        <div class="mt-6">
                            <h3 class="mb-1 text-sm font-semibold text-emerald-900">الالتزام الديني</h3>
                            <x-info-row label="مستوى الالتزام" :value="$profile->commitmentLabel()" />
                            <x-info-row label="الصلاة" :value="$profile->praysLabel()" />
                            <x-info-row :label="$isMale ? 'اللحية' : 'الحجاب'" :value="$isMale ? $profile->beardLabel() : $profile->hijabLabel()" />
                            <x-info-row label="مدخن؟" :value="$profile->is_smoker ? 'أيوه' : 'لأ'" />
                            <x-info-row label="ملاحظات" :value="$profile->religious_notes" />
                        </div>

                        <div class="mt-6">
                            <h3 class="mb-1 text-sm font-semibold text-emerald-900">التعليم والحالة الاجتماعية</h3>
                            <x-info-row label="المؤهل" :value="$profile->education" />
                            <x-info-row label="المهنة" :value="$profile->occupation" />
                            <x-info-row label="الحالة الاجتماعية" :value="$profile->maritalStatusLabel()" />
                            @if ($profile->isMarriedBefore())
                                <x-info-row label="عدد الأبناء" :value="$profile->children_count" />
                            @endif
                            <x-info-row label="أمراض مزمنة؟" :value="$profile->has_chronic_disease ? ($profile->chronic_disease_details ?: 'أيوه') : 'لأ'" />
                        </div>

                        <div class="mt-6">
                            <h3 class="mb-1 text-sm font-semibold text-emerald-900">نبذة ومواصفات مطلوبة</h3>
                            <x-info-row label="نبذة شخصية" :value="$profile->about_me" />
                            <x-info-row label="المواصفات المطلوبة" :value="$profile->partner_preferences" />
                            @if ($isMale)
                                <x-info-row label="مؤهل العروسة المطلوب" :value="$profile->desired_bride_education" />
                                <x-info-row label="هل يريدها تعمل؟" :value="$profile->desiredBrideWorkLabel()" />
                                <x-info-row label="حجاب العروسة المقبول" :value="$profile->desiredBrideHijabLabel()" />
                            @endif
                            <x-info-row label="أولوياته في المطابقة" :value="$priorities" />
                            @if ($profile->age_important)
                                <x-info-row label="مدى السن المطلوب" :value="$ageRange" />
                            @endif
                        </div>

                        <div class="mt-6">
                            <h3 class="mb-1 text-sm font-semibold text-emerald-900">بيانات التواصل (سرّية)</h3>
                            <x-info-row label="البريد الإلكتروني" :value="$profile->user->email" />
                            <x-info-row label="الهاتف" :value="$profile->user->phone" />
                            <x-info-row label="واتساب" :value="$profile->contact_whatsapp" />
                            <x-info-row label="ملاحظات التواصل" :value="$profile->contact_notes" />
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
                    <form method="POST" id="registrant-form" action="{{ route('admin.registrants.update', $profile) }}" class="rounded-xl border border-emerald-900/10 bg-white p-6">
                        @csrf
                        @method('PATCH')
                        <h2 class="font-display text-xl text-emerald-900">إدارة الطلب</h2>

                        <label class="mt-4 block">
                            <span class="mb-1.5 block text-sm font-medium text-ink/80">الحالة</span>
                            <select name="status" class="w-full rounded-lg border border-emerald-900/15 px-3 py-2 text-sm">
                                @foreach (Profile::STATUS_LABELS as $value => $label)
                                    <option value="{{ $value }}" @selected(old('status', $profile->status) === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </label>

                        <label class="mt-4 block">
                            <span class="mb-1.5 block text-sm font-medium text-ink/80">ملاحظات داخلية (لا تظهر للمستخدم)</span>
                            <textarea name="admin_notes" rows="4" class="w-full rounded-lg border border-emerald-900/15 px-3 py-2 text-sm">{{ old('admin_notes', $profile->admin_notes) }}</textarea>
                        </label>

                        <button type="submit" class="mt-4 w-full rounded-full bg-emerald-900 px-6 py-2.5 text-sm font-medium text-sand-50 hover:bg-emerald-800">
                            حفظ التغييرات
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </main>

    @push('scripts')
        <script>
            // حفظ الحالة والملاحظات من غير reload. لو الـ JS مش شغال الفورم بيشتغل عادي.
            const form = document.getElementById("registrant-form");
            const flash = document.getElementById("flash");
            const badge = document.getElementById("status-badge");
            const submitButton = form.querySelector("button[type=submit]");

            function showFlash(message, isError) {
                flash.textContent = message;
                flash.classList.remove("hidden", "bg-emerald-50", "text-emerald-800", "bg-red-50", "text-red-700");
                flash.classList.add(...(isError ? ["bg-red-50", "text-red-700"] : ["bg-emerald-50", "text-emerald-800"]));
            }

            form.addEventListener("submit", async (event) => {
                event.preventDefault();
                submitButton.disabled = true;

                try {
                    const response = await fetch(form.action, {
                        method: "POST", // الفورم فيه _method=PATCH
                        headers: { Accept: "application/json", "X-Requested-With": "XMLHttpRequest" },
                        body: new FormData(form),
                    });
                    const data = await response.json();

                    if (!response.ok) {
                        const firstError = data.errors ? Object.values(data.errors)[0][0] : data.message;
                        showFlash(firstError ?? "حصل خطأ، حاول تاني", true);
                        return;
                    }

                    badge.textContent = data.status_label;
                    showFlash(data.message, false);
                } catch (error) {
                    showFlash("تعذّر الحفظ، اتأكد من الاتصال وحاول تاني", true);
                } finally {
                    submitButton.disabled = false;
                }
            });
        </script>
    @endpush
@endsection
