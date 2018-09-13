<?php

namespace Andrmoel\AstronomyBundle\Tests\AstronomicalObjects;

use Andrmoel\AstronomyBundle\AstronomicalObjects\Earth;
use Andrmoel\AstronomyBundle\Location;
use Andrmoel\AstronomyBundle\TimeOfInterest;
use Andrmoel\AstronomyBundle\Util;
use PHPUnit\Framework\TestCase;

class EarthTest extends TestCase
{
    /**
     * Meeus 22.a
     */
    public function testGetMeanAnomaly()
    {
        $toi = new TimeOfInterest();
        $toi->setTime(1987, 4, 10, 0, 0, 0);

        $earth = new Earth($toi);
        $M = $earth->getMeanAnomaly();

        $this->assertEquals(94.9792, round($M, 4));
    }

    /**
     * Meeus 22.a
     */
    public function testGetNutation()
    {
        $toi = new TimeOfInterest();
        $toi->setTime(1987, 4, 10, 0, 0, 0);

        $earth = new Earth($toi);
        $phi = $earth->getNutation();

        $this->assertStringStartsWith('0°0\'-3.7879', Util::dec2angle($phi));
    }

    /**
     * Meeus 22.a
     */
    public function testGetNutationInObliquity()
    {
        $toi = new TimeOfInterest();
        $toi->setTime(1987, 4, 10, 0, 0, 0);

        $earth = new Earth($toi);
        $eps = $earth->getNutationInObliquity();

        $this->assertStringStartsWith('0°0\'9.4425', Util::dec2angle($eps));
    }

    /**
     * Meeus 22.a
     */
    public function testGetObliquityOfEcliptic()
    {
        $toi = new TimeOfInterest();
        $toi->setTime(1987, 4, 10, 0, 0, 0);

        $earth = new Earth($toi);
        $e0 = $earth->getObliquityOfEcliptic();

        $this->assertEquals(23.44094, round($e0, 5));
    }

    /**
     * Meeus 22.a
     */
    public function testGetTrueObliquityOfEcliptic()
    {
        $toi = new TimeOfInterest();
        $toi->setTime(1987, 4, 10, 0, 0, 0);

        $earth = new Earth($toi);
        $e = $earth->getTrueObliquityOfEcliptic();

        $this->assertEquals(23.44356, round($e, 5));
    }

    public function testGetDistance()
    {
        // Berlin
        $lat = 52.518611;
        $lon = 13.408333;
        $location1 = new Location($lat, $lon);

        // Munich
        $lat = 48.137222;
        $lon = 11.575556;
        $location2 = new Location($lat, $lon);

        $distance = Earth::getDistance($location1, $location2);

        $this->assertEquals(504.50, round($distance, 2));
    }
}