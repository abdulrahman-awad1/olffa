<?php

namespace App\Enums;

enum Gender: string
{
    case Male = 'male';
    case Female = 'female';

    public function isMale(): bool
    {
        return $this === self::Male;
    }

    public function isFemale(): bool
    {
        return $this === self::Female;
    }
}
