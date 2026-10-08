<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
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
            ['name' => 'أحمد محمد السيد', 'email' => 'ahmed.m@example.com', 'phone' => '01012345678', 'gender' => 'male', 'age' => 28, 'nationality' => 'مصري', 'residence_country' => 'مصر', 'commitment_level' => 'high', 'prays' => 'always', 'beard' => 'full', 'marital_status' => 'single', 'education' => 'بكالوريوس هندسة', 'occupation' => 'مهندس برمجيات', 'about_me' => 'شاب ملتزم يبحث عن الاستقرار والسكن الشرعي.', 'partner_preferences' => 'ملتزمة، متعلمة، من أسرة محافظة', 'age_range_min' => 22, 'age_range_max' => 28, 'age_important' => true, 'location_important' => true, 'marital_important' => true],
            ['name' => 'مريم أحمد', 'email' => 'mariam.a@example.com', 'phone' => '01098765432', 'gender' => 'female', 'age' => 27, 'nationality' => 'مصري', 'residence_country' => 'مصر', 'commitment_level' => 'high', 'prays' => 'always', 'hijab_type' => 'hijab', 'marital_status' => 'single', 'education' => 'بكالوريوس تجارة', 'occupation' => 'محاسبة', 'about_me' => 'فتاة ملتزمة تبحث عن شريك يخاف الله.', 'partner_preferences' => 'ملتزم، صاحب خلق، مستقر ماديًا', 'age_range_min' => 26, 'age_range_max' => 34, 'age_important' => true, 'location_important' => true, 'marital_important' => true],
            ['name' => 'هبة الله طارق', 'email' => 'heba.t@example.com', 'phone' => '01055512345', 'gender' => 'female', 'age' => 23, 'nationality' => 'أردني', 'residence_country' => 'الأردن', 'commitment_level' => 'medium', 'prays' => 'mostly', 'hijab_type' => 'hijab_normal', 'marital_status' => 'single', 'education' => 'بكالوريوس صيدلة', 'occupation' => 'صيدلانية', 'about_me' => 'أبحث عن شريك حياة متفاهم وطموح.', 'partner_preferences' => 'متعلم، طموح، حسن الخلق', 'age_range_min' => 25, 'age_range_max' => 31, 'age_important' => false, 'location_important' => true, 'marital_important' => true],
            ['name' => 'محمود خالد', 'email' => 'mahmoud.k@example.com', 'phone' => '01077712345', 'gender' => 'male', 'age' => 31, 'nationality' => 'إماراتي', 'residence_country' => 'الإمارات', 'commitment_level' => 'high', 'prays' => 'always', 'beard' => 'trimmed', 'marital_status' => 'divorced', 'children_count' => 1, 'education' => 'ماجستير إدارة أعمال', 'occupation' => 'مدير مشتريات', 'about_me' => 'أب لطفل واحد، أبحث عن أم صالحة له.', 'partner_preferences' => 'متفهمة، صبورة، سبق لها الزواج تفضيليًا', 'age_range_min' => 24, 'age_range_max' => 30, 'age_important' => true, 'location_important' => false, 'marital_important' => true],
            ['name' => 'سارة عبد الرحمن', 'email' => 'sara.a@example.com', 'phone' => '01033345678', 'gender' => 'female', 'age' => 25, 'nationality' => 'سعودي', 'residence_country' => 'السعودية', 'commitment_level' => 'high', 'prays' => 'always', 'hijab_type' => 'niqab', 'marital_status' => 'single', 'education' => 'بكالوريوس شريعة', 'occupation' => 'معلمة', 'about_me' => 'أبحث عن شريك ملتزم يخاف الله، الجنسية مش شرط عندي.', 'partner_preferences' => 'ملتزم بلحيته، طالب علم', 'age_range_min' => 26, 'age_range_max' => 33, 'age_important' => true, 'location_important' => false, 'marital_important' => true],
            ['name' => 'يوسف إبراهيم', 'email' => 'youssef.i@example.com', 'phone' => '01066645678', 'gender' => 'male', 'age' => 34, 'nationality' => 'كويتي', 'residence_country' => 'الكويت', 'commitment_level' => 'medium', 'prays' => 'mostly', 'beard' => 'none', 'marital_status' => 'widowed', 'education' => 'بكالوريوس محاسبة', 'occupation' => 'محاسب', 'about_me' => 'أبحث عن شريكة حياة مستقرة.', 'partner_preferences' => 'طيبة القلب، متدينة، السن مش شرط عندي', 'age_range_min' => 25, 'age_range_max' => 32, 'age_important' => false, 'location_important' => true, 'marital_important' => true],
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
                'commitment_level' => $p['commitment_level'],
                'prays' => $p['prays'],
                'beard' => $p['beard'] ?? null,
                'hijab_type' => $p['hijab_type'] ?? null,
                'education' => $p['education'],
                'occupation' => $p['occupation'],
                'marital_status' => $p['marital_status'],
                'children_count' => $p['children_count'] ?? null,
                'about_me' => $p['about_me'],
                'partner_preferences' => $p['partner_preferences'],
                'age_range_min' => $p['age_range_min'],
                'age_range_max' => $p['age_range_max'],
                'age_important' => $p['age_important'],
                'location_important' => $p['location_important'],
                'marital_important' => $p['marital_important'],
                'status' => 'new',
                'current_step' => 5,
                'completed' => true,
            ]);
        }

        $this->command->info('تم إنشاء حساب أدمن: admin@alfa.test / password123');
        $this->command->info('تم إنشاء 6 مسجّلين تجريبيين (كلمة مرور الجميع: password123)');
    }
}
