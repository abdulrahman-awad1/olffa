<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller
{
    public function create(string $gender)
    {
        $this->assertValidGender($gender);

        return view('register', ['gender' => $gender]);
    }

    public function store(Request $request, string $gender)
    {
        $this->assertValidGender($gender);

        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:30', 'unique:users,phone'],
            'password' => ['required', 'string', 'min:8'],
            'birth_date' => ['required', 'date', 'before:-18 years'],
            'nationality' => ['required', 'string'],
            'residence_country' => ['nullable', 'string'],
            'governorate' => ['nullable', 'string'],
            'current_residence' => ['nullable', 'string'],

            'height' => ['nullable', 'integer', 'min:120', 'max:230'],
            'weight' => ['nullable', 'integer', 'min:30', 'max:250'],
            'skin_color' => ['nullable', Rule::in(['fair', 'wheatish', 'dark'])],

            'marital_home_type' => ['nullable', Rule::in(['rent', 'owned'])],

            'commitment_level' => ['required', Rule::in(['high', 'medium', 'practicing'])],
            'prays' => ['required', Rule::in(['always', 'mostly', 'sometimes'])],
            'beard' => ['nullable', 'string'],
            'hijab_type' => ['nullable', 'string'],
            'religious_notes' => ['nullable', 'string'],
            'is_smoker' => ['required', Rule::in(['yes', 'no'])],

            'education' => ['required', 'string'],
            'occupation' => ['nullable', 'string'],
            'marital_status' => ['required', Rule::in(['single', 'divorced', 'widowed'])],
            'children_count' => ['nullable', 'integer', 'min:0'],
            'has_chronic_disease' => ['required', Rule::in(['yes', 'no'])],
            'chronic_disease_details' => ['nullable', 'string', 'required_if:has_chronic_disease,yes'],

            'about_me' => ['required', 'string'],
            'partner_preferences' => ['required', 'string'],
            'desired_bride_education' => ['nullable', 'string'],
            'desired_bride_work' => ['nullable', Rule::in(['yes', 'no', 'negotiable'])],
            'desired_bride_hijab' => ['nullable', Rule::in(['niqab', 'hijab', 'hijab_normal', 'no_preference'])],

            'age_important' => ['required', Rule::in(['yes', 'no'])],
            'age_range_min' => ['nullable', 'integer', 'min:18'],
            'age_range_max' => ['nullable', 'integer', 'min:18', 'gte:age_range_min'],
            'location_important' => ['required', Rule::in(['yes', 'no'])],
            'marital_important' => ['required', Rule::in(['yes', 'no'])],

            'contact_whatsapp' => ['nullable', 'string'],
            'contact_notes' => ['nullable', 'string'],
        ]);

        if ($gender === 'male' && empty($data['beard'])) {
            throw ValidationException::withMessages(['beard' => 'من فضلك اختر حالة اللحية']);
        }
        if ($gender === 'female' && empty($data['hijab_type'])) {
            throw ValidationException::withMessages(['hijab_type' => 'من فضلك اختري حالة الحجاب']);
        }
        if ($gender === 'male') {
            foreach (['marital_home_type', 'desired_bride_education', 'desired_bride_work', 'desired_bride_hijab'] as $field) {
                if (empty($data[$field])) {
                    throw ValidationException::withMessages([$field => 'من فضلك أكمل هذا الحقل']);
                }
            }
        }

        $user = DB::transaction(function () use ($data, $gender) {
            $user = User::create([
                'name' => $data['full_name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'password' => Hash::make($data['password']),
                'role' => 'user',
            ]);

            Profile::create([
                'user_id' => $user->id,
                'gender' => $gender,
                'birth_date' => $data['birth_date'],
                'nationality' => $data['nationality'],
                'residence_country' => $data['residence_country'] ?? null,
                'governorate' => $data['governorate'] ?? null,
                'current_residence' => $data['current_residence'] ?? null,

                'height' => $data['height'] ?? null,
                'weight' => $data['weight'] ?? null,
                'skin_color' => $data['skin_color'] ?? null,

                'marital_home_type' => $gender === 'male' ? ($data['marital_home_type'] ?? null) : null,

                'commitment_level' => $data['commitment_level'],
                'prays' => $data['prays'],
                'beard' => $gender === 'male' ? ($data['beard'] ?? null) : null,
                'hijab_type' => $gender === 'female' ? ($data['hijab_type'] ?? null) : null,
                'religious_notes' => $data['religious_notes'] ?? null,
                'is_smoker' => $data['is_smoker'] === 'yes',

                'education' => $data['education'],
                'occupation' => $data['occupation'] ?? null,
                'marital_status' => $data['marital_status'],
                'children_count' => $data['children_count'] ?? null,
                'has_chronic_disease' => $data['has_chronic_disease'] === 'yes',
                'chronic_disease_details' => $data['has_chronic_disease'] === 'yes' ? ($data['chronic_disease_details'] ?? null) : null,

                'about_me' => $data['about_me'],
                'partner_preferences' => $data['partner_preferences'],
                'age_range_min' => $data['age_range_min'] ?? null,
                'age_range_max' => $data['age_range_max'] ?? null,

                'desired_bride_education' => $gender === 'male' ? ($data['desired_bride_education'] ?? null) : null,
                'desired_bride_work' => $gender === 'male' ? ($data['desired_bride_work'] ?? null) : null,
                'desired_bride_hijab' => $gender === 'male' ? ($data['desired_bride_hijab'] ?? null) : null,

                'age_important' => $data['age_important'] === 'yes',
                'location_important' => $data['location_important'] === 'yes',
                'marital_important' => $data['marital_important'] === 'yes',

                'contact_whatsapp' => $data['contact_whatsapp'] ?? null,
                'contact_notes' => $data['contact_notes'] ?? null,

                'status' => 'new',
                'current_step' => 6,
                'completed' => true,
            ]);

            return $user;
        });

        Auth::login($user);

        return redirect()->route('thankyou');
    }

    private function assertValidGender(string $gender): void
    {
        abort_unless(in_array($gender, ['male', 'female'], true), 404);
    }
}
