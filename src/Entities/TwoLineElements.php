<?php

namespace Andrmoel\AstronomyBundle\Entities;

use Andrmoel\AstronomyBundle\TimeOfInterest;

class TwoLineElements
{
    const EPHEMERIS_TYPE_SGP4 = 0;
    const EPHEMERIS_TYPE_SGP = 1;
//    const EPHEMERIS_TYPE_SGP4 = 2;
    const EPHEMERIS_TYPE_SDP4 = 3;
    const EPHEMERIS_TYPE_SGP8 = 4;
    const EPHEMERIS_TYPE_SDP8 = 5;

    private $satelliteNo = '';
    private $classification = '';
    private $internationalDesignator = '';
    private $epoch = null;
    private $d1MeanMotion = 0.0;
    private $d2MeanMotion = 0.0;
    private $BSTARDragTerm = 0.0;
    private $ephemerisType = '';
    private $setNumber = 0;
    private $inclination = 0.0;
    private $rightAscensionOfAscendingNode = 0.0;
    private $eccentricity = 0.0;
    private $argumentOfPerigee = 0.0;
    private $meanAnomaly = 0.0;
    private $meanMotion = 0.0;
    private $revolutionNoAtEpoch = 0;

    public function __construct(array $tleData)
    {
        foreach ($tleData as $var => $value) {
            $this->$var = $value;
        }
    }

    public static function createFromTLEData(array $tleData)
    {

    }

    public function getEpoch(): TimeOfInterest
    {
        return $this->epoch;
    }

    public function get1thDerivativeOfMeanMotion(): float
    {
        return $this->d1MeanMotion;
    }

    public function get2ndDerivativeOfMeanMotion(): float
    {
        return $this->d2MeanMotion;
    }

    public function getEccentricity(): float
    {
        return $this->eccentricity;
    }

    public function getMeanAnomaly(): float
    {
        return $this->meanAnomaly;
    }

    public function getMeanMotion(): float
    {
        return $this->meanMotion;
    }
}
