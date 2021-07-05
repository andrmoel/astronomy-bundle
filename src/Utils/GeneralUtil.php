<?php

namespace Andrmoel\AstronomyBundle\Utils;

class GeneralUtil
{
    public static function year2string(float $year): string
    {
        $yearStr = str_pad(abs($year), 4, '0', STR_PAD_LEFT);
        $yearStr = $year < 0 ? '-' . $yearStr : $yearStr;

        return $yearStr;
    }

    public static function sign(float $value): int
    {
        if ($value > 0) {
            return 1;
        }

        if ($value < 0) {
            return -1;
        }

        return 0;
    }
}
