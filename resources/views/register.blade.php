@extends('layouts.app')

@use('App\Models\Profile')

@php
    $isMale = $gender === 'male';
    $isFemale = ! $isMale;

    $formTitle = $isMale ? 'استمارة عريس' : 'استمارة عروس';
    $partnerWord = $isMale ? 'شريكة الحياة' : 'شريك الحياة';
    $totalSteps = 6;

    $nationalities = array_combine(Profile::NATIONALITIES, Profile::NATIONALITIES);
    $maritalExample = $isFemale ? 'مطلّقة/أرملة' : 'مطلّق/أرمل';
    $partnerMaritalNote = $isFemale ? 'هو كمان سبق له الزواج' : 'هي كمان سبق لها الزواج';

    $yesImportant = 'أيوه، مهم بالنسبة لي';
    $noImportant = 'مش شرط عندي';
@endphp

@section('title', $formTitle)

@section('content')
    <div class="h-2 bg-emerald-900"></div>

    <main class="px-6 py-12">
        <div class="mx-auto max-w-xl">

            <header class="mb-8">
                <p class="text-sm text-ink/60">
                    <a href="{{ route('home') }}" class="hover:underline">أُلفة</a>
                    · {{ $formTitle }}
                </p>
                <div class="mt-4 flex items-center gap-2" id="progress-bar">
                    @for ($i = 1; $i <= $totalSteps; $i++)
                        <div class="h-1.5 flex-1 rounded-full step-dot bg-emerald-900/15" data-step="{{ $i }}"></div>
                    @endfor
                </div>
                <p class="mt-2 text-xs text-ink/50">الخطوة <span id="step-label">1</span> من {{ $totalSteps }}</p>
            </header>

            @if ($errors->any())
                <div class="mb-4 rounded-lg bg-red-50 p-4 text-sm text-red-700">
                    <p class="font-medium">في أخطاء لازم تتصلح:</p>
                    <ul class="mt-2 list-inside list-disc">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register.store', $gender) }}" id="wizard-form" class="space-y-6 rounded-2xl border border-emerald-900/10 bg-white p-8">
                @csrf

                {{-- الخطوة 1: بيانات الحساب الأساسية --}}
                <section class="wizard-step space-y-5" data-step="1">
                    <h2 class="font-display text-2xl text-emerald-900">بيانات الحساب الأساسية</h2>

                    <x-form.input name="full_name" label="الاسم الثلاثي" required autocomplete="name" />
                    <x-form.input name="email" type="email" label="البريد الإلكتروني" required autocomplete="email" />
                    <x-form.input name="phone" type="tel" label="رقم الهاتف" required autocomplete="tel" />
                    <x-form.input name="password" type="password" label="كلمة المرور" required minlength="8" autocomplete="new-password" />
                    <x-form.input name="password_confirmation" type="password" label="تأكيد كلمة المرور" required minlength="8" autocomplete="new-password" />
                    <x-form.input name="birth_date" type="date" label="تاريخ الميلاد" required max="{{ now()->subYears(18)->toDateString() }}" />
                    <x-form.select name="nationality" label="الجنسية" :options="$nationalities" placeholder="اختر الجنسية" required />
                </section>

                {{-- الخطوة 2: المظهر والإقامة --}}
                <section class="wizard-step hidden space-y-5" data-step="2">
                    <h2 class="font-display text-2xl text-emerald-900">المظهر والإقامة</h2>

                    <div class="grid grid-cols-2 gap-4">
                        <x-form.input name="height" type="number" label="الطول (سم)" min="120" max="230" />
                        <x-form.input name="weight" type="number" label="الوزن (كجم)" min="30" max="250" />
                    </div>

                    <x-form.select name="skin_color" label="لون البشرة" :options="Profile::SKIN_COLOR_LABELS" />
                    <x-form.input name="residence_country" label="بلد الإقامة" />
                    <x-form.input name="governorate" label="المحافظة" />
                    <x-form.input name="current_residence" label="محل الإقامة الحالي (المدينة/الحي)" />

                    @if ($isMale)
                        <x-form.select name="marital_home_type" label="طبيعة سكن الزوجية" :options="Profile::MARITAL_HOME_LABELS" required />
                    @endif
                </section>

                {{-- الخطوة 3: الالتزام الديني --}}
                <section class="wizard-step hidden space-y-5" data-step="3">
                    <h2 class="font-display text-2xl text-emerald-900">الالتزام الديني</h2>

                    <x-form.select name="commitment_level" label="مستوى الالتزام" :options="Profile::commitmentLabelsFor($gender)" required />
                    <x-form.select name="prays" label="المحافظة على الصلاة" :options="Profile::PRAYS_LABELS" required />

                    @if ($isMale)
                        <x-form.select name="beard" label="اللحية" :options="Profile::BEARD_LABELS" required />
                    @else
                        <x-form.select name="hijab_type" label="الحجاب" :options="Profile::HIJAB_LABELS" required />
                    @endif

                    <x-form.yes-no name="is_smoker" :label="$isFemale ? 'هل مدخنة؟' : 'هل مدخن؟'" required />

                    <x-form.textarea name="religious_notes" label="ملاحظات دينية إضافية (اختياري)" />
                </section>

                {{-- الخطوة 4: التعليم والحالة الاجتماعية --}}
                <section class="wizard-step hidden space-y-5" data-step="4">
                    <h2 class="font-display text-2xl text-emerald-900">التعليم والحالة الاجتماعية</h2>

                    <x-form.input name="education" label="المؤهل الدراسي" required />
                    <x-form.input name="occupation" label="المهنة" />

                    <x-form.select name="marital_status" id="marital-status" label="الحالة الاجتماعية" :options="Profile::maritalLabelsFor($gender)" required />

                    <div class="{{ in_array(old('marital_status', 'single'), ['single', ''], true) ? 'hidden' : '' }}" id="children-count-field">
                        <x-form.input name="children_count" type="number" label="عدد الأبناء" min="0" />
                    </div>

                    <x-form.yes-no name="has_chronic_disease" label="هل تعاني من أي أمراض مزمنة؟" required boxed target="chronic-details-field">
                        <div class="mt-4 {{ old('has_chronic_disease') === 'yes' ? '' : 'hidden' }}" id="chronic-details-field">
                            <x-form.textarea name="chronic_disease_details" label="تفاصيل المرض" rows="2" />
                        </div>
                    </x-form.yes-no>
                </section>

                {{-- الخطوة 5: نبذة ومواصفات مطلوبة --}}
                <section class="wizard-step hidden space-y-5" data-step="5">
                    <h2 class="font-display text-2xl text-emerald-900">
                        نبذة عنك وعن {{ $isMale ? 'شريكة' : 'شريك' }} المستقبل
                    </h2>

                    <x-form.textarea name="about_me" label="نبذة مختصرة عن نفسك" rows="4" required />
                    <x-form.textarea name="partner_preferences" :label="'المواصفات المطلوبة في '.$partnerWord" rows="4" required />

                    @if ($isMale)
                        <div class="space-y-4 rounded-lg border border-emerald-900/10 p-4">
                            <p class="text-sm font-medium text-ink/80">مواصفات محددة تطلبها في العروسة</p>

                            <x-form.input name="desired_bride_education" label="المؤهل الدراسي المطلوب" required placeholder="مثال: خريجة جامعة، لا يشترط..." />
                            <x-form.select name="desired_bride_work" label="هل تريدها تعمل؟" :options="Profile::WORK_PREFERENCE_LABELS" required />
                            <x-form.select name="desired_bride_hijab" label="حجاب العروسة المقبول" :options="Profile::DESIRED_HIJAB_LABELS" required />
                        </div>
                    @endif

                    <x-form.yes-no
                        name="age_important"
                        :label="'هل السن مهم بالنسبة لك في اختيار '.$partnerWord.'؟'"
                        default="yes"
                        boxed
                        target="age-range-fields"
                        :yes="$yesImportant"
                        :no="$noImportant"
                    >
                        <div id="age-range-fields" class="mt-4 grid grid-cols-2 gap-4 {{ old('age_important') === 'no' ? 'hidden' : '' }}">
                            <x-form.input name="age_range_min" type="number" label="أقل سن مناسب" min="18" />
                            <x-form.input name="age_range_max" type="number" label="أعلى سن مناسب" min="18" />
                        </div>
                    </x-form.yes-no>

                    <x-form.yes-no
                        name="location_important"
                        label="هل مهم يكون من نفس بلد إقامتك؟"
                        default="no"
                        boxed
                        :yes="$yesImportant"
                        :no="$noImportant"
                    />

                    <x-form.yes-no
                        name="marital_important"
                        :label="'هل مهم يكون في نفس حالتك الاجتماعية (مثلاً لو كنت '.$maritalExample.'، '.$partnerMaritalNote.')؟'"
                        default="no"
                        boxed
                        :yes="$yesImportant"
                        :no="$noImportant"
                    />
                </section>

                {{-- الخطوة 6: بيانات التواصل --}}
                <section class="wizard-step hidden space-y-5" data-step="6">
                    <h2 class="font-display text-2xl text-emerald-900">بيانات التواصل</h2>

                    <p class="rounded-lg bg-bronze-500/10 p-4 text-sm text-ink/70">
                        هذه البيانات سرّية تمامًا، ولن تُشارك مع أي شخص إلا بعد موافقتك الصريحة على ترشيح معيّن.
                    </p>

                    <x-form.input name="contact_whatsapp" type="tel" label="رقم واتساب (إن وُجد)" />
                    <x-form.textarea name="contact_notes" label="أي ملاحظات أخيرة (اختياري)" />
                </section>

                <div class="flex items-center justify-between pt-4" id="nav-buttons">
                    <button type="button" id="back-btn" class="text-sm text-ink/60 hover:text-ink">→ رجوع</button>
                    <button type="submit" id="next-btn" class="rounded-full bg-emerald-900 px-8 py-3 font-medium text-sand-50 transition-colors hover:bg-emerald-800">
                        التالي ←
                    </button>
                </div>
            </form>
        </div>
    </main>

    @push('scripts')
        <script>
            const form = document.getElementById("wizard-form");
            const steps = Array.from(document.querySelectorAll(".wizard-step"));
            const dots = document.querySelectorAll(".step-dot");
            const stepLabel = document.getElementById("step-label");
            const backBtn = document.getElementById("back-btn");
            const nextBtn = document.getElementById("next-btn");
            const TOTAL_STEPS = steps.length;

            // لو السيرفر رجّع أخطاء، نفتح أول خطوة فيها حقل غلط بدل ما نرجع لأول الفورم.
            const errorFields = @json(array_keys($errors->messages()));

            function firstErrorStep() {
                const stepNumbers = errorFields
                    .map((name) => form.querySelector(`[name="${name}"]`)?.closest(".wizard-step")?.dataset.step)
                    .filter(Boolean)
                    .map(Number);

                return stepNumbers.length ? Math.min(...stepNumbers) : 1;
            }

            let currentStep = firstErrorStep();

            // عدد الأبناء بيظهر لو الحالة الاجتماعية غير "أعزب/عزباء".
            const maritalSelect = document.getElementById("marital-status");
            const childrenField = document.getElementById("children-count-field");
            maritalSelect.addEventListener("change", () => {
                childrenField.classList.toggle("hidden", ["", "single"].includes(maritalSelect.value));
            });

            // الحقول المرتبطة بإجابة yes/no (تفاصيل المرض، نطاق السن).
            document.querySelectorAll(".pref-toggle").forEach((radio) => {
                radio.addEventListener("change", () => {
                    const target = document.getElementById(radio.dataset.target);
                    target.classList.toggle("hidden", radio.value === "no" && radio.checked);
                });
            });

            function showStep(step) {
                steps.forEach((s) => s.classList.toggle("hidden", Number(s.dataset.step) !== step));
                dots.forEach((d) => {
                    const active = Number(d.dataset.step) <= step;
                    d.classList.toggle("bg-emerald-900", active);
                    d.classList.toggle("bg-emerald-900/15", !active);
                });
                stepLabel.textContent = step;
                nextBtn.textContent = step === TOTAL_STEPS ? "إرسال الطلب" : "التالي ←";
                nextBtn.type = step === TOTAL_STEPS ? "submit" : "button";
            }

            function currentStepEl() {
                return steps.find((s) => Number(s.dataset.step) === currentStep);
            }

            nextBtn.addEventListener("click", (e) => {
                if (currentStep === TOTAL_STEPS) return;

                e.preventDefault();

                const inputs = Array.from(currentStepEl().querySelectorAll("input, select, textarea"))
                    .filter((el) => el.offsetParent !== null || el.type === "radio");

                for (const el of inputs) {
                    if (!el.checkValidity()) {
                        el.reportValidity();
                        return;
                    }
                }

                currentStep++;
                showStep(currentStep);
            });

            backBtn.addEventListener("click", () => {
                if (currentStep === 1) {
                    window.location.href = "{{ route('home') }}";
                    return;
                }

                currentStep--;
                showStep(currentStep);
            });

            showStep(currentStep);
        </script>
    @endpush
@endsection
