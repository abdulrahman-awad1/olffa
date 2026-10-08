<?php

namespace App\Actions;

use App\Enums\Gender;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class RegisterUser
{
    /** آخر خطوة في فورم التسجيل متعدد الخطوات. */
    private const FINAL_STEP = 6;

    /** حقول بتتخزن زي ما هي من الـ validated data. */
    private const PROFILE_FIELDS = [
        'birth_date', 'nationality', 'residence_country', 'governorate', 'current_residence',
        'height', 'weight', 'skin_color',
        'commitment_level', 'prays', 'religious_notes',
        'education', 'occupation', 'marital_status', 'children_count',
        'about_me', 'partner_preferences', 'age_range_min', 'age_range_max',
        'contact_whatsapp', 'contact_notes',
        // خاصة بالرجال (موجودة في الـ validated data للرجال بس)
        'marital_home_type', 'beard',
        'desired_bride_education', 'desired_bride_work', 'desired_bride_hijab',
        // خاصة بالنساء
        'hijab_type',
    ];

    /** حقول yes/no بتتحول لـ boolean. */
    private const YES_NO_FIELDS = [
        'is_smoker', 'has_chronic_disease',
        'age_important', 'location_important', 'marital_important',
    ];

    /**
     * @param  array<string, mixed>  $data  بيانات الـ RegisterRequest::validated()
     */
    public function handle(Gender $gender, array $data): User
    {
        return DB::transaction(function () use ($gender, $data) {
            $user = User::create([
                'name' => $data['full_name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'password' => $data['password'], // بيتعمله hash تلقائي من الـ cast في User
                'role' => User::ROLE_USER,
            ]);

            $user->profile()->create([
                ...Arr::only($data, self::PROFILE_FIELDS),
                ...$this->booleans($data),
                'gender' => $gender->value,
                'chronic_disease_details' => $this->chronicDiseaseDetails($data),
                'status' => 'new',
                'current_step' => self::FINAL_STEP,
                'completed' => true,
            ]);

            return $user;
        });
    }

    /**
     * @return array<string, bool>
     */
    private function booleans(array $data): array
    {
        $result = [];

        foreach (self::YES_NO_FIELDS as $field) {
            $result[$field] = ($data[$field] ?? null) === 'yes';
        }

        return $result;
    }

    private function chronicDiseaseDetails(array $data): ?string
    {
        return ($data['has_chronic_disease'] ?? null) === 'yes'
            ? ($data['chronic_disease_details'] ?? null)
            : null;
    }
}
