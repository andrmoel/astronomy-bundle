<?php
/**
 * Created by PhpStorm.
 * User: Andreas
 * Date: 04.01.15
 * Time: 17:00
 */

namespace App\Util\Astro;

/**
 * Class Util
 * @package Astro
 */
class Util
{
    /**
     * Transform angles to decimal system
     * @param $deg
     * @param $min
     * @param $sec
     * @return double
     */
    public static function angle2dec($deg, $min, $sec)
    {
        $angle = $deg + $min / 60 + $sec / 3600;

        // TODO
//        echo $min / 60;
//        echo "<br>";
//        echo $sec / 3600;
//        echo "<br>";
//        die("D ". $angle);

        return $angle;
    }


    /**
     * Transform angles from decimal system
     * @param $dec
     * @return string
     */
    public static function dec2angle($dec)
    {
        $deg = (int) $dec;
        $x = ($dec - $deg) * 60;
        $min = (int) $x;
        $sec = ($x - $min) * 60;

        $angle = $deg . '°' . $min . '\'' . $sec . '"';

        return $angle;
    }


    /**
     * Normalize angle
     * @param double $angle
     * @param double $nAngle
     * @return int
     */
    public static function normalizeAngle($angle, $nAngle = 360.0)
    {
        $angle = fmod($angle, $nAngle);
        if ($angle < 0) {
            $angle = $angle + $nAngle;
        }

        return $angle;
    }
}
