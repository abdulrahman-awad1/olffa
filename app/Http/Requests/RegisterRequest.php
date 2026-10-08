<?php

namespace App\Http\Requests;

use App\Enums\Gender;
use App\Models\Profile;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * توحيد الشكل قبل الـ validation، عشان unique ما يتخطاش بفرق في الحروف أو المسافات:
     * Ali@x.com و ali@x.com، و "0100 123 4567" و "01001234567".
     */
    protected function prepareForValidation(): void
    {
        $normalized = [];

        if (is_string($this->input('email'))) {
            $normalized['email'] = Str::lower(trim($this->input('email')));
        }

        if (is_string($this->input('phone'))) {
            $normalized['phone'] = preg_replace('/[\s\-().]/', '', $this->input('phone'));
        }

        $this->merge($normalized);
    }

    /**
     * الحقول الخاصة بجنس معين موجودة في القواعد للجنس ده بس،
     * عشان كده validated() مش بترجّع غير الحقول المناسبة.
     */
    public function rules(): array
    {
        /** @var Gender $gender */
        $gender = $this->route('gender');

        return [
            ...$this->commonRules(),
            ...($gender->isMale() ? $this->maleRules() : $this->femaleRules()),
        ];
    }

    public function messages(): array
    {
        return [
            'phone.regex' => 'رقم الهاتف غير صحيح، اكتب الأرقام فقط (ممكن تبدأ بـ +)',
            'password.confirmed' => 'تأكيد كلمة المرور غير مطابق',
            'beard.required' => 'من فضلك اختر حالة اللحية',
            'hijab_type.required' => 'من فضلك اختري حالة الحجاب',
            'marital_home_type.required' => 'من فضلك أكمل هذا الحقل',
            'desired_bride_education.required' => 'من فضلك أكمل هذا الحقل',
            'desired_bride_work.required' => 'من فضلك أكمل هذا الحقل',
            'desired_bride_hijab.required' => 'من فضلك أكمل هذا الحقل',
        ];
    }

    private function commonRules(): array
    {
        $yesNo = Rule::in(['yes', 'no']);

        return [
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'regex:/^\+?[0-9]{7,15}$/', 'unique:users,phone'],
            'password' => ['required', 'string', 'confirmed', Password::min(8)],
            'birth_date' => ['required', 'date', 'before_or_equal:'.now()->subYears(18)->toDateString()],
            'nationality' => ['required', Rule::in(Profile::NATIONALITIES)],
            'residence_country' => ['nullable', 'string', 'max:100'],
            'governorate' => ['nullable', 'string', 'max:100'],
            'current_residence' => ['nullable', 'string', 'max:255'],

            'height' => ['nullable', 'integer', 'min:120', 'max:230'],
            'weight' => ['nullable', 'integer', 'min:30', 'max:250'],
            'skin_color' => ['nullable', Rule::in(array_keys(Profile::SKIN_COLOR_LABELS))],

            'commitment_level' => ['required', Rule::in(array_keys(Profile::COMMITMENT_LABELS_MALE))],
            'prays' => ['required', Rule::in(array_keys(Profile::PRAYS_LABELS))],
            'religious_notes' => ['nullable', 'string', 'max:2000'],
            'is_smoker' => ['required', $yesNo],

            'education' => ['required', 'string', 'max:255'],
            'occupation' => ['nullable', 'string', 'max:255'],
            'children_count' => ['nullable', 'integer', 'min:0', 'max:30'],
            'has_chronic_disease' => ['required', $yesNo],
            'chronic_disease_details' => ['nullable', 'string', 'max:2000', 'required_if:has_chronic_disease,yes'],

            'about_me' => ['required', 'string', 'max:3000'],
            'partner_preferences' => ['required', 'string', 'max:3000'],

            'age_important' => ['required', $yesNo],
            'age_range_min' => ['nullable', 'integer', 'min:18', 'max:99'],
            'age_range_max' => ['nullable', 'integer', 'min:18', 'max:99', 'gte:age_range_min'],
            'location_important' => ['required', $yesNo],
            'marital_important' => ['required', $yesNo],

            'contact_whatsapp' => ['nullable', 'string', 'max:30'],
            'contact_notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    private function maleRules(): array
    {
        return [
            // للرجال: بيشمل "married" (متزوج ويريد التعدد)
            'marital_status' => ['required', Rule::in(array_keys(Profile::MARITAL_LABELS_MALE))],
            'beard' => ['required', Rule::in(array_keys(Profile::BEARD_LABELS))],
            'marital_home_type' => ['required', Rule::in(array_keys(Profile::MARITAL_HOME_LABELS))],
            'desired_bride_education' => ['required', 'string', 'max:255'],
            'desired_bride_work' => ['required', Rule::in(array_keys(Profile::WORK_PREFERENCE_LABELS))],
            'desired_bride_hijab' => ['required', Rule::in(array_keys(Profile::DESIRED_HIJAB_LABELS))],
        ];
    }

    private function femaleRules(): array
    {
        return [
            'marital_status' => ['required', Rule::in(array_keys(Profile::MARITAL_LABELS_FEMALE))],
            'hijab_type' => ['required', Rule::in(array_keys(Profile::HIJAB_LABELS))],
        ];
    }
}
