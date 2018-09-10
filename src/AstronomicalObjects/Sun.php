<?php

namespace Andrmoel\AstronomyBundle\AstronomicalObjects;

use Andrmoel\AstronomyBundle\Coordinates\EclipticalCoordinates;
use Andrmoel\AstronomyBundle\Coordinates\EquatorialCoordinates;
use Andrmoel\AstronomyBundle\Coordinates\HorizontalCoordinates;
use Andrmoel\AstronomyBundle\TimeOfInterest;
use Andrmoel\AstronomyBundle\Util;

class Sun extends AstronomicalObject
{
    const TWILIGHT_DAY = 0;
    const TWILIGHT_CIVIL = 1;
    const TWILIGHT_NAUTICAL = 2;
    const TWILIGHT_ASTRONOMICAL = 3;
    const TWILIGHT_NIGHT = 4;


    public function __construct()
    {
        parent::__construct();
    }


    public function setTimeOfInterest(TimeOfInterest $toi): void
    {
        parent::setTimeOfInterest($toi);
    }


    public function getEquatorialCoordinates(): EquatorialCoordinates
    {
        $T = $this->T;

        // Get obliquity of ecliptic
        $earth = new Earth();
        $earth->setTimeOfInterest($this->toi);
        $eps = $earth->getTrueObliquityOfEcliptic();
        $eps = deg2rad($eps);

        // Geometric mean longitude of the sun
        $L = 280.46646 + 36000.76983 * $T + 0.0003032 * pow($T, 2);

        // Mean anomaly of the sun
        $M = 357.52911 + 35999.05029 * $T - 0.0001537 * pow($T, 2);

        // Eccentricity of earth's orbit
        // TODO needed?
//        $e = 0.016708634 - 0.000042037 * $T - 0.0000001267 * pow($T, 2);

        $C = (1.914602 - 0.004817 * $T - 0.000014 * pow($T, 2)) * sin(deg2rad($M));
        $C += (0.019993 - 0.000101 * $T) * sin(2 * deg2rad($M));
        $C += 0.000289 * sin(3 * deg2rad($M));

        $o = $L + $C;
        $o_rad = deg2rad($o);

        $O = 125.04 - 1934.136 * $T;
        $O_rad = deg2rad($O);
        $lon = $o - 0.00569 - 0.00478 * sin($O_rad);
        $lon_rad = deg2rad($lon);

        // Corrections
        $eps += 0.00256 * cos($O_rad);

        $a = atan2(cos($eps) * sin($lon_rad), cos($lon_rad));
        $a = Util::normalizeAngle(rad2deg($a));

        $d = asin(sin($eps) * sin($o_rad));
        $d = rad2deg($d);

        return new EquatorialCoordinates($a, $d, $this->toi);
    }


    public function getEclipticalCoordinates(): EclipticalCoordinates
    {
        $equatorialCoordinates = $this->getEquatorialCoordinates();

        return $equatorialCoordinates->getEclipticalCoordinates();
    }


    public function getHorizontalCoordinates(Earth $earth): HorizontalCoordinates
    {
        $equatorialCoordinates = $this->getEquatorialCoordinates();

        return $equatorialCoordinates->getHorizontalCoordinates($earth);
    }


    /**
     * Get distance to earth [km]
     * @return float
     */
    public function getDistanceToEarth()
    {
        return 149971520; // TODO
    }


    public function getTwilight(Earth $earth): int
    {
        $horizontalCoordinates = $this->getHorizontalCoordinates($earth);
        $alt = $horizontalCoordinates->getAltitude();

        if ($alt > 0) {
            return self::TWILIGHT_DAY;
        }

        if ($alt > -6) {
            return self::TWILIGHT_CIVIL;
        }

        if ($alt > -12) {
            return self::TWILIGHT_NAUTICAL;
        }

        if ($alt > -18) {
            return self::TWILIGHT_ASTRONOMICAL;
        }

        return self::TWILIGHT_NIGHT;
    }
}