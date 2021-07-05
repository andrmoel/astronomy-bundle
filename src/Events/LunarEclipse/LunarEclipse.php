<?php

namespace Andrmoel\AstronomyBundle\Events\SolarEclipse;

class LunarEclipse
{
    const TYPE_TOTAL = 1;
    const TYPE_PARTIAL = 2;
    const TYPE_PENUMBRAL_TOTAL = 3;
    const TYPE_PENUMBRAL = 4;

    const EVENT_C1 = -3;
    const EVENT_C2 = -2;
    const EVENT_C3 = -1;
    const EVENT_MAX = 0;
    const EVENT_C4 = 1;
    const EVENT_C5 = 2;
    const EVENT_C6 = 3;

    // Observer
    private $lat = 0.0;
    private $lon = 0.0;
    private $latRad = 0.0;
    private $lonRad = 0.0;
    private $elevation = 0.0;


    public function __construct(BesselianElements $besselianElements)
    {
        $this->besselianElements = $besselianElements;
        $this->timeZone = new \DateTimeZone('UTC');
        $this->dT = $besselianElements->getDeltaT();
    }


    public function setLocation($lat, $lon, $elevation = 0.0)
    {
        $this->lat = $lat;
        $this->lon = $lon;
        $this->latRad = deg2rad($lat);
        $this->lonRad = -1 * deg2rad($lon);
        $this->elevation = $elevation >= 0.0 ? $elevation : $elevation;

        // Get the observer's geocentric position
        $tmp = atan(0.996647189335 * tan($this->latRad));
        $this->rhoSinOs = 0.996647189335 * sin($tmp) + $this->elevation * sin($this->latRad) / 6378137.0;
        $this->rhoCosOs = cos($tmp) + $this->elevation * sin($this->latRad) / 6378137.0;
    }


    public function getCircumstancesMax()
    {

    }


    private function getTimeDependentCircumstances(int $eventType, float $t): LunarEclipseCircumstances
    {
        // Set circumstances
        $circumstances = new LunarEclipseCircumstances();

        $rightAscension =

        // TODO ...
        $circumstances->t = $t;

        return $circumstances;
    }
}
