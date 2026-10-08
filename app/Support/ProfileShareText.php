<?php

namespace App\Support;

use App\Models\Profile;

/**
 * بيبني نص جاهز للنشر (مثلاً على صفحة فيسبوك) بيعرّف صاحب البروفايل بالكود بس،
 * من غير أي اسم أو بيانات تواصل، حفاظًا على الخصوصية.
 */
class ProfileShareText
{
    private const ORDINALS = [
        'أولًا', 'ثانيًا', 'ثالثًا', 'رابعًا', 'خامسًا', 'سادسًا', 'سابعًا', 'ثامنًا',
    ];

    private readonly bool $isMale;

    public function __construct(private readonly Profile $profile)
    {
        $this->isMale = $profile->isMale();
    }

    public function build(): string
    {
        $blocks = [$this->header()];

        // الترقيم تلقائي، فلو قسم اتشال الأقسام اللي بعده مش بتتخطى رقم.
        $number = 0;
        foreach ($this->sections() as $title => $lines) {
            if ($lines === []) {
                continue;
            }

            $blocks[] = self::ORDINALS[$number++].": {$title}\n\n".implode("\n", $lines);
        }

        $blocks[] = $this->footer();

        return implode("\n\n", $blocks);
    }

    private function header(): string
    {
        $titleEmoji = $this->isMale ? '💍' : '👰';

        return "{$titleEmoji} استمارة بيانات {$this->roleWord()}\n\n"
            ."{$this->roleWord()} كود: {$this->profile->code}";
    }

    /**
     * @return array<string, list<string>> عنوان القسم => سطوره (القسم الفاضي بيتشال)
     */
    private function sections(): array
    {
        $roleEmoji = $this->isMale ? '👨' : '👩';
        $partnerWord = $this->isMale ? 'الزوجة' : 'الزوج';

        return [
            "المعلومات الشخصية {$roleEmoji}" => $this->personalInfo(),
            'التعليم والعمل 🎓' => $this->educationAndWork(),
            'الالتزام الديني 🕌' => $this->religiousCommitment(),
            'السكن 🏠' => $this->housing(),
            'نبذة شخصية 📝' => $this->about(),
            "مواصفات {$partnerWord} المطلوبة ❤️" => $this->partnerPreferences(),
            'الحالة الاجتماعية السابقة 👶' => $this->previousMarriage(),
        ];
    }

    private function personalInfo(): array
    {
        $p = $this->profile;

        return $this->lines([
            $this->line('العمر', $p->age),
            $this->line('الجنسية', $p->nationality),
            $this->line('المحافظة', $p->governorate),
            $this->line('مكان الإقامة', $p->current_residence),
            $this->line('الوزن', $p->weight),
            $this->line('الطول', $p->height),
            $this->line('لون البشرة', $p->skin_color ? $p->skinColorLabel() : null),
            '• الحالة الاجتماعية: '.$p->maritalStatusLabel(),
            $this->smokerLine(),
        ]);
    }

    private function educationAndWork(): array
    {
        return $this->lines([
            $this->line('المؤهل الدراسي', $this->profile->education),
            $this->line('المهنة', $this->profile->occupation),
        ]);
    }

    private function religiousCommitment(): array
    {
        $p = $this->profile;

        return $this->lines([
            '• مستوى الالتزام: '.$p->commitmentLabel(),
            '• هل تحافظ على الصلوات الخمس؟ '.$p->praysLabel(),
            $this->isMale && $p->beard ? '• اللحية: '.$p->beardLabel() : null,
            ! $this->isMale && $p->hijab_type ? '• الحجاب: '.$p->hijabLabel() : null,
        ]);
    }

    // للعريس فقط، لأنها بياناته هو.
    private function housing(): array
    {
        if (! $this->isMale || ! $this->profile->marital_home_type) {
            return [];
        }

        return ['• نوع سكن الزوجية: '.$this->profile->maritalHomeLabel()];
    }

    private function about(): array
    {
        return $this->profile->about_me ? [$this->profile->about_me] : [];
    }

    private function partnerPreferences(): array
    {
        $p = $this->profile;

        $lines = [
            $this->line('أهم الصفات المطلوبة', $p->partner_preferences),
            $p->age_important && $p->age_range_min && $p->age_range_max
                ? "• السن المناسب: {$p->age_range_min} إلى {$p->age_range_max}"
                : null,
            '• أهمية نفس بلد الإقامة: '.($p->location_important ? 'مهم' : 'مش شرط'),
            '• أهمية نفس الحالة الاجتماعية: '.($p->marital_important ? 'مهم' : 'مش شرط'),
        ];

        if ($this->isMale) {
            $lines[] = $this->line('المؤهل المطلوب', $p->desired_bride_education);
            $lines[] = $p->desired_bride_work
                ? '• هل تقبل أن تعمل الزوجة؟ '.$p->desiredBrideWorkLabel()
                : null;
            $lines[] = $p->desired_bride_hijab
                ? '• حجاب الزوجة المطلوب: '.$p->desiredBrideHijabLabel()
                : null;
        }

        return $this->lines($lines);
    }

    private function previousMarriage(): array
    {
        $p = $this->profile;

        return $this->lines([
            '• هل سبق لك الزواج؟ '.($p->isMarriedBefore() ? 'نعم' : 'لا'),
            $p->isMarriedBefore() ? $this->line('عدد الأبناء', $p->children_count) : null,
        ]);
    }

    private function footer(): string
    {
        return "---\n\n📢 ملحوظة هامة\n"
            ."جميع البيانات المذكورة تحت مسؤولية {$this->roleWord()} نفسه، "
            .'ومبادرة "أُلفة" غير مسؤولة عن أي تعارض في المعلومات.';
    }

    private function smokerLine(): string
    {
        $question = $this->isMale ? 'هل أنت مدخن؟' : 'هل أنتِ مدخنة؟';

        $answer = match (true) {
            $this->isMale && $this->profile->is_smoker => 'مدخن',
            $this->isMale => 'غير مدخن',
            $this->profile->is_smoker => 'مدخنة',
            default => 'غير مدخنة',
        };

        return "• {$question} {$answer}";
    }

    private function roleWord(): string
    {
        return $this->isMale ? 'عريس' : 'عروسة';
    }

    /** سطر "• label: value" أو null لو القيمة فاضية. */
    private function line(string $label, mixed $value): ?string
    {
        return $value ? "• {$label}: {$value}" : null;
    }

    /**
     * @param  array<int, ?string>  $lines
     * @return list<string>
     */
    private function lines(array $lines): array
    {
        return array_values(array_filter($lines));
    }
}
