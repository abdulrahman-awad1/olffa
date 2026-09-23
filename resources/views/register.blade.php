@extends('layouts.app')

@section('title', $gender === 'male' ? 'استمارة عريس' : 'استمارة عروس')

@section('content')
<div class="h-2 bg-emerald-900"></div>

<main class="px-6 py-12">
  <div class="mx-auto max-w-xl">

    <header class="mb-8">
      <p class="text-sm text-ink/60">
        <a href="{{ route('home') }}" class="hover:underline">الفة</a>
        · {{ $gender === 'male' ? 'استمارة عريس' : 'استمارة عروس' }}
      </p>
      <div class="mt-4 flex items-center gap-2" id="progress-bar">
        @for ($i = 1; $i <= 6; $i++)
          <div class="h-1.5 flex-1 rounded-full step-dot {{ $i === 1 ? 'bg-emerald-900' : 'bg-emerald-900/15' }}" data-step="{{ $i }}"></div>
        @endfor
      </div>
      <p class="mt-2 text-xs text-ink/50">الخطوة <span id="step-label">1</span> من 6</p>
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

        <label class="block">
          <span class="mb-1.5 block text-sm font-medium text-ink/80">الاسم الثلاثي <span class="text-bronze-600">*</span></span>
          <input required name="full_name" value="{{ old('full_name') }}" class="field-input" type="text" />
        </label>

        <label class="block">
          <span class="mb-1.5 block text-sm font-medium text-ink/80">البريد الإلكتروني <span class="text-bronze-600">*</span></span>
          <input required name="email" value="{{ old('email') }}" class="field-input" type="email" />
        </label>

        <label class="block">
          <span class="mb-1.5 block text-sm font-medium text-ink/80">رقم الهاتف <span class="text-bronze-600">*</span></span>
          <input required name="phone" value="{{ old('phone') }}" class="field-input" type="tel" />
        </label>

        <label class="block">
          <span class="mb-1.5 block text-sm font-medium text-ink/80">كلمة المرور <span class="text-bronze-600">*</span></span>
          <input required minlength="8" name="password" class="field-input" type="password" />
        </label>

        <label class="block">
          <span class="mb-1.5 block text-sm font-medium text-ink/80">تاريخ الميلاد <span class="text-bronze-600">*</span></span>
          <input required name="birth_date" value="{{ old('birth_date') }}" class="field-input" type="date" />
        </label>

        <label class="block">
          <span class="mb-1.5 block text-sm font-medium text-ink/80">الجنسية <span class="text-bronze-600">*</span></span>
          <select required name="nationality" class="field-input">
            <option value="">اختر الجنسية</option>
            @foreach (['مصري','سعودي','إماراتي','كويتي','قطري','بحريني','عماني','أردني','فلسطيني','سوري','عراقي','لبناني','يمني','سوداني','ليبي','تونسي','جزائري','مغربي','أخرى'] as $n)
              <option value="{{ $n }}" @selected(old('nationality') === $n)>{{ $n }}</option>
            @endforeach
          </select>
        </label>
      </section>

      {{-- الخطوة 2: المظهر والإقامة --}}
      <section class="wizard-step hidden space-y-5" data-step="2">
        <h2 class="font-display text-2xl text-emerald-900">المظهر والإقامة</h2>

        <div class="grid grid-cols-2 gap-4">
          <label class="block">
            <span class="mb-1.5 block text-sm font-medium text-ink/80">الطول (سم)</span>
            <input name="height" value="{{ old('height') }}" class="field-input" type="number" min="120" max="230" />
          </label>
          <label class="block">
            <span class="mb-1.5 block text-sm font-medium text-ink/80">الوزن (كجم)</span>
            <input name="weight" value="{{ old('weight') }}" class="field-input" type="number" min="30" max="250" />
          </label>
        </div>

        <label class="block">
          <span class="mb-1.5 block text-sm font-medium text-ink/80">لون البشرة</span>
          <select name="skin_color" class="field-input">
            <option value="">اختر</option>
            <option value="fair" @selected(old('skin_color') === 'fair')>فاتحة</option>
            <option value="wheatish" @selected(old('skin_color') === 'wheatish')>قمحية</option>
            <option value="dark" @selected(old('skin_color') === 'dark')>أسمر</option>
          </select>
        </label>

        <label class="block">
          <span class="mb-1.5 block text-sm font-medium text-ink/80">بلد الإقامة</span>
          <input name="residence_country" value="{{ old('residence_country') }}" class="field-input" type="text" />
        </label>

        <label class="block">
          <span class="mb-1.5 block text-sm font-medium text-ink/80">المحافظة</span>
          <input name="governorate" value="{{ old('governorate') }}" class="field-input" type="text" />
        </label>

        <label class="block">
          <span class="mb-1.5 block text-sm font-medium text-ink/80">محل الإقامة الحالي (المدينة/الحي)</span>
          <input name="current_residence" value="{{ old('current_residence') }}" class="field-input" type="text" />
        </label>

        @if ($gender === 'male')
          <label class="block">
            <span class="mb-1.5 block text-sm font-medium text-ink/80">طبيعة سكن الزوجية <span class="text-bronze-600">*</span></span>
            <select required name="marital_home_type" class="field-input">
              <option value="">اختر</option>
              <option value="rent" @selected(old('marital_home_type') === 'rent')>إيجار</option>
              <option value="owned" @selected(old('marital_home_type') === 'owned')>ملك</option>
            </select>
          </label>
        @endif
      </section>

      {{-- الخطوة 3: الالتزام الديني --}}
      <section class="wizard-step hidden space-y-5" data-step="3">
        <h2 class="font-display text-2xl text-emerald-900">الالتزام الديني</h2>

        <label class="block">
          <span class="mb-1.5 block text-sm font-medium text-ink/80">مستوى الالتزام <span class="text-bronze-600">*</span></span>
          <select required name="commitment_level" class="field-input">
            <option value="">اختر</option>
              {{-- في select مستوى الالتزام --}}
              <option value="high" @selected(old('commitment_level') === 'high')>{{ $gender === 'female' ? 'ملتزمة بدرجة عالية' : 'ملتزم بدرجة عالية' }}</option>
              <option value="medium" @selected(old('commitment_level') === 'medium')>{{ $gender === 'female' ? 'ملتزمة بشكل عام' : 'ملتزم بشكل عام' }}</option>
            <option value="practicing" @selected(old('commitment_level') === 'practicing')>أحاول الالتزام</option>
          </select>
        </label>

        <label class="block">
          <span class="mb-1.5 block text-sm font-medium text-ink/80">المحافظة على الصلاة <span class="text-bronze-600">*</span></span>
          <select required name="prays" class="field-input">
            <option value="">اختر</option>
            <option value="always" @selected(old('prays') === 'always')>دائمًا في وقتها</option>
            <option value="mostly" @selected(old('prays') === 'mostly')>في الغالب</option>
            <option value="sometimes" @selected(old('prays') === 'sometimes')>أحيانًا</option>
          </select>
        </label>

        @if ($gender === 'male')
          <label class="block">
            <span class="mb-1.5 block text-sm font-medium text-ink/80">اللحية <span class="text-bronze-600">*</span></span>
            <select required name="beard" class="field-input">
              <option value="">اختر</option>
              <option value="full" @selected(old('beard') === 'full')>مطلقة</option>
              <option value="trimmed" @selected(old('beard') === 'trimmed')>مهذّبة</option>
              <option value="none" @selected(old('beard') === 'none')>لا</option>
            </select>
          </label>
        @else
          <label class="block">
            <span class="mb-1.5 block text-sm font-medium text-ink/80">الحجاب <span class="text-bronze-600">*</span></span>
            <select required name="hijab_type" class="field-input">
              <option value="">اختر</option>
              <option value="niqab" @selected(old('hijab_type') === 'niqab')>نقاب</option>
              <option value="hijab" @selected(old('hijab_type') === 'hijab')>حجاب شرعي</option>
              <option value="hijab_normal" @selected(old('hijab_type') === 'hijab_normal')>حجاب عادي</option>
              <option value="none" @selected(old('hijab_type') === 'none')>بدون</option>
            </select>
          </label>
        @endif

        <label class="block">
            {{-- سؤال التدخين --}}
            <span class="mb-1.5 block text-sm font-medium text-ink/80">{{ $gender === 'female' ? 'هل مدخنة؟' : 'هل مدخن؟' }} <span class="text-bronze-600">*</span></span>          <div class="flex gap-3">
            <label class="flex flex-1 cursor-pointer items-center justify-center rounded-lg border border-emerald-900/15 py-2 text-sm has-[:checked]:bg-emerald-900 has-[:checked]:text-sand-50">
              <input type="radio" required name="is_smoker" value="yes" class="hidden" {{ old('is_smoker') === 'yes' ? 'checked' : '' }} /> أيوه
            </label>
            <label class="flex flex-1 cursor-pointer items-center justify-center rounded-lg border border-emerald-900/15 py-2 text-sm has-[:checked]:bg-emerald-900 has-[:checked]:text-sand-50">
              <input type="radio" required name="is_smoker" value="no" class="hidden" {{ old('is_smoker', 'no') === 'no' ? 'checked' : '' }} /> لأ
            </label>
          </div>
        </label>

        <label class="block">
          <span class="mb-1.5 block text-sm font-medium text-ink/80">ملاحظات دينية إضافية (اختياري)</span>
          <textarea name="religious_notes" rows="3" class="field-input">{{ old('religious_notes') }}</textarea>
        </label>
      </section>

      {{-- الخطوة 4: التعليم والحالة الاجتماعية --}}
      <section class="wizard-step hidden space-y-5" data-step="4">
        <h2 class="font-display text-2xl text-emerald-900">التعليم والحالة الاجتماعية</h2>

        <label class="block">
          <span class="mb-1.5 block text-sm font-medium text-ink/80">المؤهل الدراسي <span class="text-bronze-600">*</span></span>
          <input required name="education" value="{{ old('education') }}" class="field-input" type="text" />
        </label>

        <label class="block">
          <span class="mb-1.5 block text-sm font-medium text-ink/80">المهنة</span>
          <input name="occupation" value="{{ old('occupation') }}" class="field-input" type="text" />
        </label>

        <label class="block">
          <span class="mb-1.5 block text-sm font-medium text-ink/80">الحالة الاجتماعية <span class="text-bronze-600">*</span></span>
          <select required name="marital_status" id="marital-status" class="field-input">
            <option value="">اختر</option>
              {{-- في select الحالة الاجتماعية --}}
              <option value="single" @selected(old('marital_status') === 'single')>{{ $gender === 'female' ? 'عزباء / لم يسبق الزواج' : 'أعزب / لم يسبق الزواج' }}</option>
              <option value="divorced" @selected(old('marital_status') === 'divorced')>{{ $gender === 'female' ? 'مطلّقة' : 'مطلّق' }}</option>
              <option value="widowed" @selected(old('marital_status') === 'widowed')>{{ $gender === 'female' ? 'أرملة' : 'أرمل' }}</option>
              @if ($gender === 'male')
                  <option value="married" @selected(old('marital_status') === 'married')>متزوج وأريد التعدد</option>
              @endif          </select>
        </label>

        <label class="block {{ old('marital_status', 'single') === 'single' ? 'hidden' : '' }}" id="children-count-field">
          <span class="mb-1.5 block text-sm font-medium text-ink/80">عدد الأبناء</span>
          <input name="children_count" value="{{ old('children_count') }}" class="field-input" type="number" min="0" />
        </label>

        <div class="rounded-lg border border-emerald-900/10 p-4">
          <span class="mb-2 block text-sm font-medium text-ink/80">هل تعاني من أي أمراض مزمنة؟ <span class="text-bronze-600">*</span></span>
          <div class="flex gap-3">
            <label class="flex flex-1 cursor-pointer items-center justify-center rounded-lg border border-emerald-900/15 py-2 text-sm has-[:checked]:bg-emerald-900 has-[:checked]:text-sand-50">
              <input type="radio" required name="has_chronic_disease" value="yes" class="hidden pref-toggle" data-target="chronic-details-field" {{ old('has_chronic_disease') === 'yes' ? 'checked' : '' }} /> أيوه
            </label>
            <label class="flex flex-1 cursor-pointer items-center justify-center rounded-lg border border-emerald-900/15 py-2 text-sm has-[:checked]:bg-emerald-900 has-[:checked]:text-sand-50">
              <input type="radio" required name="has_chronic_disease" value="no" class="hidden pref-toggle" data-target="chronic-details-field" {{ old('has_chronic_disease', 'no') === 'no' ? 'checked' : '' }} /> لأ
            </label>
          </div>
          <label class="mt-4 block {{ old('has_chronic_disease') === 'yes' ? '' : 'hidden' }}" id="chronic-details-field">
            <span class="mb-1.5 block text-sm font-medium text-ink/80">تفاصيل المرض</span>
            <textarea name="chronic_disease_details" rows="2" class="field-input">{{ old('chronic_disease_details') }}</textarea>
          </label>
        </div>
      </section>

      {{-- الخطوة 5: نبذة ومواصفات مطلوبة --}}
      <section class="wizard-step hidden space-y-5" data-step="5">
        <h2 class="font-display text-2xl text-emerald-900">
          نبذة عنك وعن {{ $gender === 'male' ? 'شريكة' : 'شريك' }} المستقبل
        </h2>

        <label class="block">
          <span class="mb-1.5 block text-sm font-medium text-ink/80">نبذة مختصرة عن نفسك <span class="text-bronze-600">*</span></span>
          <textarea required rows="4" name="about_me" class="field-input">{{ old('about_me') }}</textarea>
        </label>

        <label class="block">
          <span class="mb-1.5 block text-sm font-medium text-ink/80">
            المواصفات المطلوبة في {{ $gender === 'male' ? 'شريكة الحياة' : 'شريك الحياة' }} <span class="text-bronze-600">*</span>
          </span>
          <textarea required rows="4" name="partner_preferences" class="field-input">{{ old('partner_preferences') }}</textarea>
        </label>

        @if ($gender === 'male')
          <div class="rounded-lg border border-emerald-900/10 p-4 space-y-4">
            <p class="text-sm font-medium text-ink/80">مواصفات محددة تطلبها في العروسة</p>

            <label class="block">
              <span class="mb-1.5 block text-sm font-medium text-ink/80">المؤهل الدراسي المطلوب <span class="text-bronze-600">*</span></span>
              <input required name="desired_bride_education" value="{{ old('desired_bride_education') }}" class="field-input" type="text" placeholder="مثال: خريجة جامعة، لا يشترط..." />
            </label>

            <label class="block">
              <span class="mb-1.5 block text-sm font-medium text-ink/80">هل تريدها تعمل؟ <span class="text-bronze-600">*</span></span>
              <select required name="desired_bride_work" class="field-input">
                <option value="">اختر</option>
                <option value="yes" @selected(old('desired_bride_work') === 'yes')>نعم، تعمل</option>
                <option value="no" @selected(old('desired_bride_work') === 'no')>لا تعمل</option>
                <option value="negotiable" @selected(old('desired_bride_work') === 'negotiable')>حسب الاتفاق</option>
              </select>
            </label>

            <label class="block">
              <span class="mb-1.5 block text-sm font-medium text-ink/80">حجاب العروسة المقبول <span class="text-bronze-600">*</span></span>
              <select required name="desired_bride_hijab" class="field-input">
                <option value="">اختر</option>
                <option value="niqab" @selected(old('desired_bride_hijab') === 'niqab')>نقاب</option>
                <option value="hijab" @selected(old('desired_bride_hijab') === 'hijab')>حجاب شرعي</option>
                <option value="hijab_normal" @selected(old('desired_bride_hijab') === 'hijab_normal')>حجاب عادي</option>
                <option value="no_preference" @selected(old('desired_bride_hijab') === 'no_preference')>مش شرط عندي</option>
              </select>
            </label>
          </div>
        @endif

        <div class="rounded-lg border border-emerald-900/10 p-4">
          <span class="mb-2 block text-sm font-medium text-ink/80">
            هل السن مهم بالنسبة لك في اختيار {{ $gender === 'male' ? 'شريكة الحياة' : 'شريك الحياة' }}؟
          </span>
          <div class="flex gap-3">
            <label class="flex flex-1 cursor-pointer items-center justify-center rounded-lg border border-emerald-900/15 py-2 text-sm has-[:checked]:bg-emerald-900 has-[:checked]:text-sand-50">
              <input type="radio" name="age_important" value="yes" class="hidden pref-toggle" data-target="age-range-fields" {{ old('age_important', 'yes') === 'yes' ? 'checked' : '' }} />
              أيوه، مهم بالنسبة لي
            </label>
            <label class="flex flex-1 cursor-pointer items-center justify-center rounded-lg border border-emerald-900/15 py-2 text-sm has-[:checked]:bg-emerald-900 has-[:checked]:text-sand-50">
              <input type="radio" name="age_important" value="no" class="hidden pref-toggle" data-target="age-range-fields" {{ old('age_important') === 'no' ? 'checked' : '' }} />
              مش شرط عندي
            </label>
          </div>
          <div id="age-range-fields" class="mt-4 grid grid-cols-2 gap-4 {{ old('age_important') === 'no' ? 'hidden' : '' }}">
            <label class="block">
              <span class="mb-1.5 block text-sm font-medium text-ink/80">أقل سن مناسب</span>
              <input name="age_range_min" value="{{ old('age_range_min') }}" class="field-input" type="number" min="18" />
            </label>
            <label class="block">
              <span class="mb-1.5 block text-sm font-medium text-ink/80">أعلى سن مناسب</span>
              <input name="age_range_max" value="{{ old('age_range_max') }}" class="field-input" type="number" min="18" />
            </label>
          </div>
        </div>

        <div class="rounded-lg border border-emerald-900/10 p-4">
          <span class="mb-2 block text-sm font-medium text-ink/80">هل مهم يكون من نفس بلد إقامتك؟</span>
          <div class="flex gap-3">
            <label class="flex flex-1 cursor-pointer items-center justify-center rounded-lg border border-emerald-900/15 py-2 text-sm has-[:checked]:bg-emerald-900 has-[:checked]:text-sand-50">
              <input type="radio" name="location_important" value="yes" class="hidden" {{ old('location_important') === 'yes' ? 'checked' : '' }} />
              أيوه، مهم بالنسبة لي
            </label>
            <label class="flex flex-1 cursor-pointer items-center justify-center rounded-lg border border-emerald-900/15 py-2 text-sm has-[:checked]:bg-emerald-900 has-[:checked]:text-sand-50">
              <input type="radio" name="location_important" value="no" class="hidden" {{ old('location_important', 'no') === 'no' ? 'checked' : '' }} />
              مش شرط عندي
            </label>
          </div>
        </div>

        <div class="rounded-lg border border-emerald-900/10 p-4">
          <span class="mb-2 block text-sm font-medium text-ink/80">
{{-- سؤال أهمية الحالة الاجتماعية --}}
هل مهم يكون في نفس حالتك الاجتماعية (مثلاً لو كنت {{ $gender === 'female' ? 'مطلّقة/أرملة' : 'مطلّق/أرمل' }}، يكون هو كمان سبق له الزواج)؟          </span>
          <div class="flex gap-3">
            <label class="flex flex-1 cursor-pointer items-center justify-center rounded-lg border border-emerald-900/15 py-2 text-sm has-[:checked]:bg-emerald-900 has-[:checked]:text-sand-50">
              <input type="radio" name="marital_important" value="yes" class="hidden" {{ old('marital_important') === 'yes' ? 'checked' : '' }} />
              أيوه، مهم بالنسبة لي
            </label>
            <label class="flex flex-1 cursor-pointer items-center justify-center rounded-lg border border-emerald-900/15 py-2 text-sm has-[:checked]:bg-emerald-900 has-[:checked]:text-sand-50">
              <input type="radio" name="marital_important" value="no" class="hidden" {{ old('marital_important', 'no') === 'no' ? 'checked' : '' }} />
              مش شرط عندي
            </label>
          </div>
        </div>
      </section>

      {{-- الخطوة 6: بيانات التواصل --}}
      <section class="wizard-step hidden space-y-5" data-step="6">
        <h2 class="font-display text-2xl text-emerald-900">بيانات التواصل</h2>

        <p class="rounded-lg bg-bronze-500/10 p-4 text-sm text-ink/70">
          هذه البيانات سرّية تمامًا، ولن تُشارك مع أي شخص إلا بعد موافقتك الصريحة على ترشيح معيّن.
        </p>

        <label class="block">
          <span class="mb-1.5 block text-sm font-medium text-ink/80">رقم واتساب (إن وُجد)</span>
          <input name="contact_whatsapp" value="{{ old('contact_whatsapp') }}" class="field-input" type="tel" />
        </label>

        <label class="block">
          <span class="mb-1.5 block text-sm font-medium text-ink/80">أي ملاحظات أخيرة (اختياري)</span>
          <textarea name="contact_notes" rows="3" class="field-input">{{ old('contact_notes') }}</textarea>
        </label>
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
  const TOTAL_STEPS = 6;
  let currentStep = 1;

  const steps = document.querySelectorAll(".wizard-step");
  const dots = document.querySelectorAll(".step-dot");
  const stepLabel = document.getElementById("step-label");
  const backBtn = document.getElementById("back-btn");
  const nextBtn = document.getElementById("next-btn");

  const maritalSelect = document.getElementById("marital-status");
  const childrenField = document.getElementById("children-count-field");
  maritalSelect.addEventListener("change", () => {
    childrenField.classList.toggle("hidden", maritalSelect.value === "single" || maritalSelect.value === "");
  });

  document.querySelectorAll(".pref-toggle").forEach((radio) => {
    radio.addEventListener("change", () => {
      const target = document.getElementById(radio.dataset.target);
      target.classList.toggle("hidden", radio.value === "no" && radio.checked);
    });
  });

  function showStep(step) {
    steps.forEach((s) => s.classList.toggle("hidden", s.dataset.step != step));
    dots.forEach((d) => {
      d.classList.toggle("bg-emerald-900", Number(d.dataset.step) <= step);
      d.classList.toggle("bg-emerald-900/15", Number(d.dataset.step) > step);
    });
    stepLabel.textContent = step;
    nextBtn.textContent = step === TOTAL_STEPS ? "إرسال الطلب" : "التالي ←";
    nextBtn.type = step === TOTAL_STEPS ? "submit" : "button";
  }

  function currentStepEl() {
    return document.querySelector(`.wizard-step[data-step="${currentStep}"]`);
  }

  nextBtn.addEventListener("click", (e) => {
    if (currentStep === TOTAL_STEPS) return;

    e.preventDefault();
    const visibleInputs = Array.from(currentStepEl().querySelectorAll("input, select, textarea"))
      .filter((el) => el.offsetParent !== null);
    for (const el of visibleInputs) {
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
