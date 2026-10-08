<?php

namespace App\Services;

use App\Models\Profile;
use Illuminate\Support\Collection;

/**
 * خوارزمية التوافق الشخصي بين المسجلين.
 *
 * الفكرة: كل شخص بيحدد في استمارته "مهم ليا / مش مهم" لكل معيار
 * (السن، مكان الإقامة، الحالة الاجتماعية). النسبة بتتحسب من وجهة
 * نظر كل طرف على حدة بناءً على المعايير اللي هو شخصيًا حددها كمهمة.
 * الالتزام الديني وزنه ثابت دايمًا (40%) لأنه أساس المبادرة.
 *
 * عشان يظهر ترشيح معيّن، لازم نسبة رضا الطرفين عن بعض تكون فوق
 * الحد الأدنى (60% افتراضيًا) - مفيش معنى نرشّح حد راضي عنه طرف
 * بس مش راضي عليه الطرف التاني.
 */
class MatchService
{
    public const MATCH_THRESHOLD = 60;

    private const COMMITMENT_WEIGHT = 0.4;

    private const COMMITMENT_RANK = [
        'high' => 3,
        'medium' => 2,
        'practicing' => 1,
    ];

    public static function commitmentSubScore(Profile $a, Profile $b): float
    {
        $rankA = self::COMMITMENT_RANK[$a->commitment_level] ?? 1;
        $rankB = self::COMMITMENT_RANK[$b->commitment_level] ?? 1;
        $diff = abs($rankA - $rankB);

        return 100 - $diff * 50; // 0 -> 100, 1 -> 50, 2 -> 0
    }

    public static function maritalSubScore(Profile $a, Profile $b): float
    {
        $sameCategory = $a->marital_status === $b->marital_status
            || ($a->isMarriedBefore() && $b->isMarriedBefore());

        return $sameCategory ? 100 : 0;
    }

    /** نفس بلد الإقامة. لو أي طرف ما كتبش بلده، مفيش تطابق (قبل كده null == null كان بيتحسب تطابق). */
    public static function locationSubScore(Profile $a, Profile $b): float
    {
        $countryA = self::normalize($a->residence_country);
        $countryB = self::normalize($b->residence_country);

        return $countryA !== '' && $countryA === $countryB ? 100 : 0;
    }

    /** هل عمر $candidate واقع في المدى اللي حدده $viewer؟ */
    public static function ageSubScore(Profile $viewer, Profile $candidate): float
    {
        if (! $candidate->age || ! $viewer->age_range_min || ! $viewer->age_range_max) {
            return 0;
        }

        return ($candidate->age >= $viewer->age_range_min && $candidate->age <= $viewer->age_range_max) ? 100 : 0;
    }

    /**
     * نسبة رضا $viewer عن $candidate، بناءً على المعايير اللي
     * $viewer نفسه حددها كمهمة عنده فقط.
     */
    public static function directionalScore(Profile $viewer, Profile $candidate): int
    {
        $commitment = self::commitmentSubScore($viewer, $candidate);
        $criteria = self::activeCriteria($viewer);

        if ($criteria === []) {
            return (int) round($commitment);
        }

        $perCriterionWeight = (1 - self::COMMITMENT_WEIGHT) / count($criteria);
        $total = self::COMMITMENT_WEIGHT * $commitment;

        foreach ($criteria as $criterion) {
            $total += $perCriterionWeight * self::subScore($criterion, $viewer, $candidate);
        }

        return (int) round($total);
    }

    /**
     * يرجّع كل الترشيحات المتبادلة لشخص معيّن، مرتبة من الأعلى للأقل،
     * وفيها my_score (رضا الشخص عن المرشّح) و their_score (رضا المرشّح عنه).
     */
    public static function findMatches(Profile $person, ?Collection $pool = null): Collection
    {
        $oppositeGender = $person->isMale() ? 'female' : 'male';

        $candidates = $pool ?? Profile::query()
            ->completed()
            ->where('gender', $oppositeGender)
            ->with('user')
            ->get();

        return $candidates
            ->filter(fn (Profile $c) => $c->gender !== $person->gender)
            ->map(function (Profile $candidate) use ($person) {
                $candidate->my_score = self::directionalScore($person, $candidate);
                $candidate->their_score = self::directionalScore($candidate, $person);

                return $candidate;
            })
            ->filter(fn (Profile $c) => $c->my_score >= self::MATCH_THRESHOLD && $c->their_score >= self::MATCH_THRESHOLD)
            ->sortByDesc('my_score')
            ->values();
    }

    /**
     * المعايير اللي $viewer حددها كمهمة. السن ما بيتحسبش لو الشخص قال "مهم"
     * بس ما كتبش مدى (أقل/أعلى سن)، بدل ما نخصم منه نسبة على حاجة ما حددهاش.
     *
     * @return list<string>
     */
    private static function activeCriteria(Profile $viewer): array
    {
        return array_keys(array_filter([
            'marital' => $viewer->marital_important,
            'location' => $viewer->location_important,
            'age' => $viewer->age_important && $viewer->age_range_min && $viewer->age_range_max,
        ]));
    }

    private static function subScore(string $criterion, Profile $viewer, Profile $candidate): float
    {
        return match ($criterion) {
            'marital' => self::maritalSubScore($viewer, $candidate),
            'location' => self::locationSubScore($viewer, $candidate),
            'age' => self::ageSubScore($viewer, $candidate),
        };
    }

    private static function normalize(?string $value): string
    {
        return mb_strtolower(trim((string) $value));
    }
}
