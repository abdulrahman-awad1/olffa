<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'code', 'gender', 'birth_date', 'nationality', 'residence_country',
        'height', 'weight', 'skin_color',
        'governorate', 'current_residence', 'marital_home_type',
        'commitment_level', 'prays', 'beard', 'hijab_type', 'religious_notes', 'is_smoker',
        'education', 'occupation', 'marital_status', 'children_count',
        'has_chronic_disease', 'chronic_disease_details',
        'about_me', 'partner_preferences', 'age_range_min', 'age_range_max',
        'desired_bride_education', 'desired_bride_work', 'desired_bride_hijab',
        'age_important', 'location_important', 'marital_important',
        'contact_whatsapp', 'contact_notes',
        'status', 'admin_notes', 'current_step', 'completed',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'age_important' => 'boolean',
            'location_important' => 'boolean',
            'marital_important' => 'boolean',
            'is_smoker' => 'boolean',
            'has_chronic_disease' => 'boolean',
            'completed' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted(): void
    {
        static::creating(function (Profile $profile) {
            if (empty($profile->code)) {
                $profile->code = self::generateUniqueCode();
            }
        });
    }

    public static function generateUniqueCode(): string
    {
        do {
            $code = (string) random_int(10000000, 99999999);
        } while (self::where('code', $code)->exists());

        return $code;
    }

    public function getAgeAttribute(): ?int
    {
        return $this->birth_date?->age;
    }

    // ---------- تسميات عربية للعرض في الواجهات ----------

    public const STATUS_LABELS = [
        'new' => 'جديد',
        'reviewing' => 'تحت المراجعة',
        'matched' => 'تم الترشيح',
        'completed' => 'مكتمل',
    ];

    public const MARITAL_LABELS_MALE = [
        'single' => 'أعزب / لم يسبق الزواج',
        'divorced' => 'مطلّق',
        'widowed' => 'أرمل',
        'married'=>'متزوج واريد التعدد'
    ];

    public const MARITAL_LABELS_FEMALE = [
        'single' => 'عزباء / لم يسبق الزواج',
        'divorced' => 'مطلّقة',
        'widowed' => 'أرملة',
    ];

    public const COMMITMENT_LABELS_MALE = [
        'high' => 'ملتزم بدرجة عالية',
        'medium' => 'ملتزم بشكل عام',
        'practicing' => 'أحاول الالتزام',
    ];

    public const COMMITMENT_LABELS_FEMALE = [
        'high' => 'ملتزمة بدرجة عالية',
        'medium' => 'ملتزمة بشكل عام',
        'practicing' => 'أحاول الالتزام',
    ];

    public const PRAYS_LABELS = [
        'always' => 'دائمًا في وقتها',
        'mostly' => 'في الغالب',
        'sometimes' => 'أحيانًا',
    ];

    public const BEARD_LABELS = [
        'full' => 'مطلقة',
        'trimmed' => 'مهذّبة',
        'none' => 'لا',
    ];

    public const HIJAB_LABELS = [
        'niqab' => 'نقاب',
        'hijab' => 'حجاب شرعي',
        'hijab_normal' => 'حجاب عادي',
        'none' => 'بدون',
    ];

    public const DESIRED_HIJAB_LABELS = [
        'niqab' => 'نقاب',
        'hijab' => 'حجاب شرعي',
        'hijab_normal' => 'حجاب عادي',
        'no_preference' => 'مش شرط عندي',
    ];

    public const SKIN_COLOR_LABELS = [
        'fair' => 'فاتحة',
        'wheatish' => 'قمحية',
        'dark' => 'أسمر',
    ];

    public const MARITAL_HOME_LABELS = [
        'rent' => 'إيجار',
        'owned' => 'ملك',
    ];

    public const WORK_PREFERENCE_LABELS = [
        'yes' => 'نعم، تعمل',
        'no' => 'لا تعمل',
        'negotiable' => 'حسب الاتفاق',
    ];

    public function statusLabel(): string
    {
        return self::STATUS_LABELS[$this->status] ?? $this->status;
    }

    public function maritalStatusLabel(): string
    {
        $labels = $this->gender === 'female' ? self::MARITAL_LABELS_FEMALE : self::MARITAL_LABELS_MALE;

        return $labels[$this->marital_status] ?? (string) $this->marital_status;
    }

    public function commitmentLabel(): string
    {
        $labels = $this->gender === 'female' ? self::COMMITMENT_LABELS_FEMALE : self::COMMITMENT_LABELS_MALE;

        return $labels[$this->commitment_level] ?? (string) $this->commitment_level;
    }

    public function praysLabel(): string
    {
        return self::PRAYS_LABELS[$this->prays] ?? (string) $this->prays;
    }

    public function beardLabel(): ?string
    {
        return self::BEARD_LABELS[$this->beard] ?? $this->beard;
    }

    public function hijabLabel(): ?string
    {
        return self::HIJAB_LABELS[$this->hijab_type] ?? $this->hijab_type;
    }

    public function desiredBrideHijabLabel(): ?string
    {
        return self::DESIRED_HIJAB_LABELS[$this->desired_bride_hijab] ?? $this->desired_bride_hijab;
    }

    public function skinColorLabel(): ?string
    {
        return self::SKIN_COLOR_LABELS[$this->skin_color] ?? $this->skin_color;
    }

    public function maritalHomeLabel(): ?string
    {
        return self::MARITAL_HOME_LABELS[$this->marital_home_type] ?? $this->marital_home_type;
    }

    public function desiredBrideWorkLabel(): ?string
    {
        return self::WORK_PREFERENCE_LABELS[$this->desired_bride_work] ?? $this->desired_bride_work;
    }

    public function isMarriedBefore(): bool
    {
        return in_array($this->marital_status, ['divorced', 'widowed'], true);
    }

    /**
     * نص جاهز للنشر (مثلاً على صفحة فيسبوك)، بيعرّف صاحبه بالكود بس
     * من غير أي اسم أو بيانات تواصل، حفاظًا على الخصوصية. الشكل هنا
     * مبني على قالب صفحات الفيسبوك المعروفة، لكن بالبيانات الموجودة
     * عندنا فعلًا بس - من غير إضافة أي حقول جديدة.
     */
    public function shareText(): string
    {
        $isMale = $this->gender === 'male';
        $roleWord = $isMale ? 'عريس' : 'عروسة';
        $roleEmoji = $isMale ? '👨' : '👩';
        $titleEmoji = $isMale ? '💍' : '👰';
        $partnerWord = $isMale ? 'الزوجة' : 'الزوج';

        $lines = [];
        $lines[] = "{$titleEmoji} استمارة بيانات {$roleWord}";
        $lines[] = '';
        $lines[] = "{$roleWord} كود: {$this->code}";
        $lines[] = '';

        // أولًا: المعلومات الشخصية
        $lines[] = "أولًا: المعلومات الشخصية {$roleEmoji}";
        $lines[] = '';
        if ($this->age) {
            $lines[] = "• العمر: {$this->age}";
        }
        if ($this->nationality) {
            $lines[] = "• الجنسية: {$this->nationality}";
        }
        if ($this->governorate) {
            $lines[] = "• المحافظة: {$this->governorate}";
        }
        if ($this->current_residence) {
            $lines[] = "• مكان الإقامة: {$this->current_residence}";
        }
        if ($this->weight) {
            $lines[] = "• الوزن: {$this->weight}";
        }
        if ($this->height) {
            $lines[] = "• الطول: {$this->height}";
        }
        if ($this->skin_color) {
            $lines[] = '• لون البشرة: '.$this->skinColorLabel();
        }
        $lines[] = '• الحالة الاجتماعية: '.$this->maritalStatusLabel();
        $lines[] = ($isMale ? '• هل أنت مدخن؟ ' : '• هل أنتِ مدخنة؟ ').($this->is_smoker
                ? ($isMale ? 'مدخن' : 'مدخنة')
                : ($isMale ? 'غير مدخن' : 'غير مدخنة'));
        $lines[] = '';

        // ثانيًا: التعليم والعمل
        if ($this->education || $this->occupation) {
            $lines[] = 'ثانيًا: التعليم والعمل 🎓';
            $lines[] = '';
            if ($this->education) {
                $lines[] = "• المؤهل الدراسي: {$this->education}";
            }
            if ($this->occupation) {
                $lines[] = "• المهنة: {$this->occupation}";
            }
            $lines[] = '';
        }

        // ثالثًا: الالتزام الديني
        $lines[] = 'ثالثًا: الالتزام الديني 🕌';
        $lines[] = '';
        $lines[] = '• مستوى الالتزام: '.$this->commitmentLabel();
        $lines[] = '• هل تحافظ على الصلوات الخمس؟ '.$this->praysLabel();
        if ($isMale && $this->beard) {
            $lines[] = '• اللحية: '.$this->beardLabel();
        }
        if (! $isMale && $this->hijab_type) {
            $lines[] = '• الحجاب: '.$this->hijabLabel();
        }
        $lines[] = '';

        // رابعًا: السكن (للعريس فقط، لأنها بياناته هو)
        if ($isMale && $this->marital_home_type) {
            $lines[] = 'رابعًا: السكن 🏠';
            $lines[] = '';
            $lines[] = '• نوع سكن الزوجية: '.$this->maritalHomeLabel();
            $lines[] = '';
        }

        // خامسًا: نبذة عنه/عنها
        if ($this->about_me) {
            $lines[] = 'خامسًا: نبذة شخصية 📝';
            $lines[] = '';
            $lines[] = $this->about_me;
            $lines[] = '';
        }

        // سادسًا: المواصفات المطلوبة في الطرف الآخر
        $lines[] = "سادسًا: مواصفات {$partnerWord} المطلوبة ❤️";
        $lines[] = '';
        if ($this->partner_preferences) {
            $lines[] = "• أهم الصفات المطلوبة: {$this->partner_preferences}";
        }
        if ($this->age_important && $this->age_range_min && $this->age_range_max) {
            $lines[] = "• السن المناسب: {$this->age_range_min} إلى {$this->age_range_max}";
        }
        $lines[] = '• أهمية نفس بلد الإقامة: '.($this->location_important ? 'مهم' : 'مش شرط');
        $lines[] = '• أهمية نفس الحالة الاجتماعية: '.($this->marital_important ? 'مهم' : 'مش شرط');
        if ($isMale) {
            if ($this->desired_bride_education) {
                $lines[] = "• المؤهل المطلوب: {$this->desired_bride_education}";
            }
            if ($this->desired_bride_work) {
                $lines[] = '• هل تقبل أن تعمل الزوجة؟ '.$this->desiredBrideWorkLabel();
            }
            if ($this->desired_bride_hijab) {
                $lines[] = '• حجاب الزوجة المطلوب: '.$this->desiredBrideHijabLabel();
            }
        }
        $lines[] = '';

        // سابعًا: الزواج السابق
        $lines[] = 'سابعًا: الحالة الاجتماعية السابقة 👶';
        $lines[] = '';
        $lines[] = '• هل سبق لك الزواج؟ '.($this->isMarriedBefore() ? 'نعم' : 'لا');
        if ($this->isMarriedBefore() && $this->children_count) {
            $lines[] = "• عدد الأبناء: {$this->children_count}";
        }
        $lines[] = '';

        $lines[] = '---';
        $lines[] = '';
        $lines[] = '📢 ملحوظة هامة';
        $lines[] = "جميع البيانات المذكورة تحت مسؤولية {$roleWord} نفسه، ومبادرة \"الفة\" غير مسؤولة عن أي تعارض في المعلومات.";

        return implode("\n", $lines);
    }
}
