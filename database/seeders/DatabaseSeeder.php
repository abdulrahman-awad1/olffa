<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // شيلنا سطر إنشاء "Test User" الافتراضي بتاع Laravel لأنه
        // بيحاول يعمل مستخدم من غير phone وده بيفشل بعد ما ضفنا
        // العمود ده كـ required في جدول users.
        $this->call(DemoSeeder::class);
    }
}
