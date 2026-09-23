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

    public static function locationSubScore(Profile $a, Profile $b): float
    {
        return $a->residence_country === $b->residence_country ? 100 : 0;
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
        $active = [];
        if ($viewer->marital_important) {
            $active[] = 'marital';
        }
        if ($viewer->location_important) {
            $active[] = 'location';
        }
        if ($viewer->age_important) {
            $active[] = 'age';
        }

        if (empty($active)) {
            return (int) round(self::commitmentSubScore($viewer, $candidate));
        }

        $remainingWeight = 1 - self::COMMITMENT_WEIGHT;
        $perCriterionWeight = $remainingWeight / count($active);

        $total = self::COMMITMENT_WEIGHT * self::commitmentSubScore($viewer, $candidate);

        foreach ($active as $criterion) {
            $total += $perCriterionWeight * match ($criterion) {
                'marital' => self::maritalSubScore($viewer, $candidate),
                'location' => self::locationSubScore($viewer, $candidate),
                'age' => self::ageSubScore($viewer, $candidate),
            };
        }

        return (int) round($total);
    }

    /**
     * يرجّع كل الترشيحات المتبادلة لشخص معيّن، مرتبة من الأعلى للأقل،
     * وفيها myScore (رضا الشخص عن المرشّح) و theirScore (رضا المرشّح عنه).
     */
    public static function findMatches(Profile $person, ?Collection $pool = null): Collection
    {
        $oppositeGender = $person->gender === 'male' ? 'female' : 'male';

        $candidates = $pool ?? Profile::query()
            ->where('gender', $oppositeGender)
            ->where('completed', true)
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
}
