<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        // حساب الأدمن - غيّر كلمة المرور فورًا بعد أول تسجيل دخول
        $admin = User::create([
            'name' => 'أدمن أُلفة',
            'email' => 'admin@alfa.test',
            'phone' => '01000000000',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        $people = [
            ['name' => 'أحمد محمد السيد', 'email' => 'ahmed.m@example.com', 'phone' => '01012345678', 'gender' => 'male', 'age' => 28, 'nationality' => 'مصري', 'residence_country' => 'مصر', 'governorate' => 'القاهرة', 'current_residence' => 'مدينة نصر', 'height' => 178, 'weight' => 78, 'skin_color' => 'wheatish', 'marital_home_type' => 'rent', 'commitment_level' => 'high', 'prays' => 'always', 'beard' => 'full', 'is_smoker' => false, 'marital_status' => 'single', 'has_chronic_disease' => false, 'education' => 'بكالوريوس هندسة', 'occupation' => 'مهندس برمجيات', 'about_me' => 'شاب ملتزم يبحث عن الاستقرار والسكن الشرعي.', 'partner_preferences' => 'ملتزمة، متعلمة، من أسرة محافظة', 'desired_bride_education' => 'خريجة جامعة', 'desired_bride_work' => 'negotiable', 'desired_bride_hijab' => 'hijab', 'age_range_min' => 22, 'age_range_max' => 28, 'age_important' => true, 'location_important' => true, 'marital_important' => true],
            ['name' => 'مريم أحمد', 'email' => 'mariam.a@example.com', 'phone' => '01098765432', 'gender' => 'female', 'age' => 27, 'nationality' => 'مصري', 'residence_country' => 'مصر', 'governorate' => 'القاهرة', 'current_residence' => 'مدينة نصر', 'height' => 162, 'weight' => 58, 'skin_color' => 'fair', 'commitment_level' => 'high', 'prays' => 'always', 'hijab_type' => 'hijab', 'is_smoker' => false, 'marital_status' => 'single', 'has_chronic_disease' => false, 'education' => 'بكالوريوس تجارة', 'occupation' => 'محاسبة', 'about_me' => 'فتاة ملتزمة تبحث عن شريك يخاف الله.', 'partner_preferences' => 'ملتزم، صاحب خلق، مستقر ماديًا', 'age_range_min' => 26, 'age_range_max' => 34, 'age_important' => true, 'location_important' => true, 'marital_important' => true],
            ['name' => 'هبة الله طارق', 'email' => 'heba.t@example.com', 'phone' => '01055512345', 'gender' => 'female', 'age' => 23, 'nationality' => 'أردني', 'residence_country' => 'الأردن', 'governorate' => 'عمّان', 'current_residence' => 'الجبيهة', 'height' => 165, 'weight' => 55, 'skin_color' => 'wheatish', 'commitment_level' => 'medium', 'prays' => 'mostly', 'hijab_type' => 'hijab_normal', 'is_smoker' => false, 'marital_status' => 'single', 'has_chronic_disease' => false, 'education' => 'بكالوريوس صيدلة', 'occupation' => 'صيدلانية', 'about_me' => 'أبحث عن شريك حياة متفاهم وطموح.', 'partner_preferences' => 'متعلم، طموح، حسن الخلق', 'age_range_min' => 25, 'age_range_max' => 31, 'age_important' => false, 'location_important' => true, 'marital_important' => true],
            ['name' => 'محمود خالد', 'email' => 'mahmoud.k@example.com', 'phone' => '01077712345', 'gender' => 'male', 'age' => 31, 'nationality' => 'إماراتي', 'residence_country' => 'الإمارات', 'governorate' => 'دبي', 'current_residence' => 'الجميرا', 'height' => 182, 'weight' => 85, 'skin_color' => 'dark', 'marital_home_type' => 'owned', 'commitment_level' => 'high', 'prays' => 'always', 'beard' => 'trimmed', 'is_smoker' => false, 'marital_status' => 'divorced', 'children_count' => 1, 'has_chronic_disease' => false, 'education' => 'ماجستير إدارة أعمال', 'occupation' => 'مدير مشتريات', 'about_me' => 'أب لطفل واحد، أبحث عن أم صالحة له.', 'partner_preferences' => 'متفهمة، صبورة، سبق لها الزواج تفضيليًا', 'desired_bride_education' => 'لا يشترط', 'desired_bride_work' => 'no', 'desired_bride_hijab' => 'hijab', 'age_range_min' => 24, 'age_range_max' => 30, 'age_important' => true, 'location_important' => false, 'marital_important' => true],
            ['name' => 'سارة عبد الرحمن', 'email' => 'sara.a@example.com', 'phone' => '01033345678', 'gender' => 'female', 'age' => 25, 'nationality' => 'سعودي', 'residence_country' => 'السعودية', 'governorate' => 'الرياض', 'current_residence' => 'حي النرجس', 'height' => 160, 'weight' => 60, 'skin_color' => 'fair', 'commitment_level' => 'high', 'prays' => 'always', 'hijab_type' => 'niqab', 'is_smoker' => false, 'marital_status' => 'single', 'has_chronic_disease' => false, 'education' => 'بكالوريوس شريعة', 'occupation' => 'معلمة', 'about_me' => 'أبحث عن شريك ملتزم يخاف الله، الجنسية مش شرط عندي.', 'partner_preferences' => 'ملتزم بلحيته، طالب علم', 'age_range_min' => 26, 'age_range_max' => 33, 'age_important' => true, 'location_important' => false, 'marital_important' => true],
            ['name' => 'يوسف إبراهيم', 'email' => 'youssef.i@example.com', 'phone' => '01066645678', 'gender' => 'male', 'age' => 34, 'nationality' => 'كويتي', 'residence_country' => 'الكويت', 'governorate' => 'العاصمة', 'current_residence' => 'السالمية', 'height' => 175, 'weight' => 90, 'skin_color' => 'wheatish', 'marital_home_type' => 'owned', 'commitment_level' => 'medium', 'prays' => 'mostly', 'beard' => 'none', 'is_smoker' => true, 'marital_status' => 'widowed', 'has_chronic_disease' => true, 'chronic_disease_details' => 'ضغط دم مرتفع تحت السيطرة بالعلاج', 'education' => 'بكالوريوس محاسبة', 'occupation' => 'محاسب', 'about_me' => 'أبحث عن شريكة حياة مستقرة.', 'partner_preferences' => 'طيبة القلب، متدينة، السن مش شرط عندي', 'desired_bride_education' => 'لا يشترط', 'desired_bride_work' => 'negotiable', 'desired_bride_hijab' => 'no_preference', 'age_range_min' => 25, 'age_range_max' => 32, 'age_important' => false, 'location_important' => true, 'marital_important' => true],
        ];

        foreach ($people as $p) {
            $birthDate = now()->subYears($p['age'])->subDays(10);

            $user = User::create([
                'name' => $p['name'],
                'email' => $p['email'],
                'phone' => $p['phone'],
                'password' => Hash::make('password123'),
                'role' => 'user',
            ]);

            Profile::create([
                'user_id' => $user->id,
                'gender' => $p['gender'],
                'birth_date' => $birthDate,
                'nationality' => $p['nationality'],
                'residence_country' => $p['residence_country'],
                'governorate' => $p['governorate'] ?? null,
                'current_residence' => $p['current_residence'] ?? null,
                'height' => $p['height'] ?? null,
                'weight' => $p['weight'] ?? null,
                'skin_color' => $p['skin_color'] ?? null,
                'marital_home_type' => $p['marital_home_type'] ?? null,
                'commitment_level' => $p['commitment_level'],
                'prays' => $p['prays'],
                'beard' => $p['beard'] ?? null,
                'hijab_type' => $p['hijab_type'] ?? null,
                'is_smoker' => $p['is_smoker'] ?? false,
                'education' => $p['education'],
                'occupation' => $p['occupation'],
                'marital_status' => $p['marital_status'],
                'children_count' => $p['children_count'] ?? null,
                'has_chronic_disease' => $p['has_chronic_disease'] ?? false,
                'chronic_disease_details' => $p['chronic_disease_details'] ?? null,
                'about_me' => $p['about_me'],
                'partner_preferences' => $p['partner_preferences'],
                'desired_bride_education' => $p['desired_bride_education'] ?? null,
                'desired_bride_work' => $p['desired_bride_work'] ?? null,
                'desired_bride_hijab' => $p['desired_bride_hijab'] ?? null,
                'age_range_min' => $p['age_range_min'],
                'age_range_max' => $p['age_range_max'],
                'age_important' => $p['age_important'],
                'location_important' => $p['location_important'],
                'marital_important' => $p['marital_important'],
                'status' => 'new',
                'current_step' => 6,
                'completed' => true,
            ]);
        }

        $this->command->info('تم إنشاء حساب أدمن: admin@alfa.test / password123');
        $this->command->info('تم إنشاء 6 مسجّلين تجريبيين (كلمة مرور الجميع: password123)');
    }
}
