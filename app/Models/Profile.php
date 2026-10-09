<?php

namespace App\Models;

use App\Enums\Gender;
use App\Support\ProfileShareText;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Profile extends Model
{
    use HasFactory;

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
        'married' => 'متزوج واريد التعدد',
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

    public const NATIONALITIES = [
        'مصري', 'سعودي', 'إماراتي', 'كويتي', 'قطري', 'بحريني', 'عماني', 'أردني',
        'فلسطيني', 'سوري', 'عراقي', 'لبناني', 'يمني', 'سوداني', 'ليبي', 'تونسي',
        'جزائري', 'مغربي', 'أخرى',
    ];

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

    // ---------- Relations ----------

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ---------- Scopes ----------

    /** الاستمارات اللي المستخدم خلّصها كاملة. */
    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('completed', true);
    }

    /**
     * فلاتر لوحة الأدمن. القيم لازم تتعمل لها validation قبل ما توصل هنا.
     *
     * @param  array{gender?: ?string, status?: ?string, nationality?: ?string, code?: ?string}  $filters
     */
    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query
            ->when($filters['gender'] ?? null, fn (Builder $q, string $gender) => $q->where('gender', $gender))
            ->when($filters['status'] ?? null, fn (Builder $q, string $status) => $q->where('status', $status))
            ->when($filters['nationality'] ?? null, fn (Builder $q, string $nationality) => $q->where('nationality', $nationality))
            // بحث بالكود: بيطابق بداية الكود، فالكود الكامل (8 أرقام) بيرجّع صاحبه بالظبط.
            ->when($filters['code'] ?? null, fn (Builder $q, string $code) => $q->where('code', 'like', $code.'%'));
    }

    // ---------- Accessors ----------

    protected function age(): Attribute
    {
        return Attribute::get(fn () => $this->birth_date?->age);
    }

    // ---------- Gender / marital helpers ----------

    public function isMale(): bool
    {
        return $this->gender === Gender::Male->value;
    }

    public function isFemale(): bool
    {
        return $this->gender === Gender::Female->value;
    }

    public function genderLabel(): string
    {
        return $this->isMale() ? 'عريس' : 'عروس';
    }

    /** سبق له/لها الزواج (مطلّق، أرمل، أو متزوج حاليًا ويريد التعدد). */
    public function isMarriedBefore(): bool
    {
        return in_array($this->marital_status, ['divorced', 'widowed', 'married'], true);
    }

    /** @return array<string, string> */
    public static function maritalLabelsFor(string $gender): array
    {
        return $gender === Gender::Female->value ? self::MARITAL_LABELS_FEMALE : self::MARITAL_LABELS_MALE;
    }

    /** @return array<string, string> */
    public static function commitmentLabelsFor(string $gender): array
    {
        return $gender === Gender::Female->value ? self::COMMITMENT_LABELS_FEMALE : self::COMMITMENT_LABELS_MALE;
    }

    // ---------- Labels ----------

    public function statusLabel(): string
    {
        return (string) $this->labelFor(self::STATUS_LABELS, $this->status);
    }

    public function maritalStatusLabel(): string
    {
        return (string) $this->labelFor(self::maritalLabelsFor((string) $this->gender), $this->marital_status);
    }

    public function commitmentLabel(): string
    {
        return (string) $this->labelFor(self::commitmentLabelsFor((string) $this->gender), $this->commitment_level);
    }

    public function praysLabel(): string
    {
        return (string) $this->labelFor(self::PRAYS_LABELS, $this->prays);
    }

    public function beardLabel(): ?string
    {
        return $this->labelFor(self::BEARD_LABELS, $this->beard);
    }

    public function hijabLabel(): ?string
    {
        return $this->labelFor(self::HIJAB_LABELS, $this->hijab_type);
    }

    public function desiredBrideHijabLabel(): ?string
    {
        return $this->labelFor(self::DESIRED_HIJAB_LABELS, $this->desired_bride_hijab);
    }

    public function skinColorLabel(): ?string
    {
        return $this->labelFor(self::SKIN_COLOR_LABELS, $this->skin_color);
    }

    public function maritalHomeLabel(): ?string
    {
        return $this->labelFor(self::MARITAL_HOME_LABELS, $this->marital_home_type);
    }

    public function desiredBrideWorkLabel(): ?string
    {
        return $this->labelFor(self::WORK_PREFERENCE_LABELS, $this->desired_bride_work);
    }

    /** بيرجّع التسمية العربية للقيمة، أو القيمة نفسها لو مالهاش تسمية. */
    private function labelFor(array $labels, ?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        return $labels[$value] ?? $value;
    }

    // ---------- Sharing ----------

    public function shareText(): string
    {
        return (new ProfileShareText($this))->build();
    }
}
