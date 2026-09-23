<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('code', 8)->unique()->nullable();

            $table->enum('gender', ['male', 'female']);
            $table->date('birth_date')->nullable();
            $table->unsignedSmallInteger('height')->nullable(); // بالسم
            $table->unsignedSmallInteger('weight')->nullable(); // بالكجم
            $table->string('skin_color')->nullable(); // fair | wheatish | dark

            $table->string('nationality')->nullable();
            $table->string('residence_country')->nullable();
            $table->string('governorate')->nullable();
            $table->string('current_residence')->nullable(); // المدينة/الحي الحالي
            $table->string('marital_home_type')->nullable(); // خاص بالعريس: rent | owned

            // الالتزام الديني
            $table->enum('commitment_level', ['high', 'medium', 'practicing'])->nullable();
            $table->enum('prays', ['always', 'mostly', 'sometimes'])->nullable();
            $table->string('beard')->nullable();       // للذكور فقط: full | trimmed | none
            $table->string('hijab_type')->nullable();  // للإناث فقط: niqab | hijab | hijab_normal | none
            $table->text('religious_notes')->nullable();
            $table->boolean('is_smoker')->default(false);

            // التعليم والحالة الاجتماعية
            $table->string('education')->nullable();
            $table->string('occupation')->nullable();
            $table->enum('marital_status', ['single', 'divorced', 'widowed'])->nullable();
            $table->unsignedTinyInteger('children_count')->nullable();
            $table->boolean('has_chronic_disease')->default(false);
            $table->text('chronic_disease_details')->nullable();

            // نبذة ومواصفات
            $table->text('about_me')->nullable();
            $table->text('partner_preferences')->nullable();
            $table->unsignedTinyInteger('age_range_min')->nullable();
            $table->unsignedTinyInteger('age_range_max')->nullable();

            // مواصفات العروسة المطلوبة (خاص بالعريس فقط)
            $table->string('desired_bride_education')->nullable();
            $table->string('desired_bride_work')->nullable(); // yes | no | negotiable
            $table->string('desired_bride_hijab')->nullable();

            // أهمية كل معيار بالنسبة لصاحب الملف (تُستخدم في خوارزمية التوافق)
            $table->boolean('age_important')->default(true);
            $table->boolean('location_important')->default(false);
            $table->boolean('marital_important')->default(false);

            // بيانات تواصل إضافية (رقم الهاتف الأساسي موجود في users)
            $table->string('contact_whatsapp')->nullable();
            $table->text('contact_notes')->nullable();

            // إدارة الطلب
            $table->enum('status', ['new', 'reviewing', 'matched', 'completed'])->default('new');
            $table->text('admin_notes')->nullable();
            $table->unsignedTinyInteger('current_step')->default(1);
            $table->boolean('completed')->default(false);

            $table->timestamps();

            $table->index('gender');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
