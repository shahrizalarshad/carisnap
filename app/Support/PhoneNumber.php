<?php

namespace App\Support;

class PhoneNumber
{
    public static function normalize(?string $phone): ?string
    {
        if (blank($phone)) {
            return null;
        }

        $digits = preg_replace('/[^0-9]/', '', $phone);

        if ($digits === '') {
            return null;
        }

        if (str_starts_with($digits, '60')) {
            $digits = '0'.substr($digits, 2);
        }

        return $digits;
    }

    public static function matches(?string $first, ?string $second): bool
    {
        $left = self::normalize($first);
        $right = self::normalize($second);

        return $left !== null && $right !== null && $left === $right;
    }
}
