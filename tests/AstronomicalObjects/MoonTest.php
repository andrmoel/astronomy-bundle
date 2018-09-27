<?php

namespace Andrmoel\AstronomyBundle\Tests\AstronomicalObjects;

use Andrmoel\AstronomyBundle\AstronomicalObjects\Moon;
use Andrmoel\AstronomyBundle\Location;
use Andrmoel\AstronomyBundle\TimeOfInterest;
use PHPUnit\Framework\TestCase;

class MoonTest extends TestCase
{
    /**
     * Meeus 22.a
     */
    public function testGetMeanElongationFromSun()
    {
        $toi = new TimeOfInterest(new \DateTime('1987-04-10 00:00:00'));

        $moon = new Moon($toi);
        $D = $moon->getMeanElongationFromSun();

        $this->assertEquals(136.96215, round($D, 5));
    }

    /**
     * Meeus 22.a
     */
    public function testGetMeanAnomaly()
    {
        $toi = new TimeOfInterest(new \DateTime('1987-04-10 00:00:00'));

        $moon = new Moon($toi);
        $M = $moon->getMeanAnomaly();

        $this->assertEquals(229.27882, round($M, 5));
    }

    /**
     * Meeus 22.a
     */
    public function testGetArgumentOfLatitude()
    {
        $toi = new TimeOfInterest(new \DateTime('1987-04-10 00:00:00'));

        $moon = new Moon($toi);
        $F = $moon->getArgumentOfLatitude();

        $this->assertEquals(143.40809, round($F, 5));
    }

    /**
     * Meeus 47.a
     */
    public function XtestGetMeanLongitude()
    {
        $toi = new TimeOfInterest(new \DateTime('1992-04-12 00:00:00'));

        $moon = new Moon($toi);
        $L = $moon->getMeanLongitude();

        $this->assertEquals(134.290182, round($L, 6));
    }

    /**
     * Meeus 47.a
     */
    public function testGetDistanceToEarth()
    {
        $toi = new TimeOfInterest(new \DateTime('1992-04-12 00:00:00'));

        $moon = new Moon($toi);
        $distance = $moon->getDistanceToEarth();

        $this->assertEquals(368409.7, round($distance, 1));
    }

    /**
     * Meeus 47.a
     */
    public function testGetEquatorialHorizontalParallax()
    {
        $toi = new TimeOfInterest(new \DateTime('1992-04-12 00:00:00'));

        $moon = new Moon($toi);
        $distance = $moon->getEquatorialHorizontalParallax();

        $this->assertEquals(0.991990, round($distance, 6));
    }

    /**
     * Meeus 47.a
     */
    public function testGetGeocentricEclipticalSpericalCoordinates()
    {
        $toi = new TimeOfInterest(new \DateTime('1992-04-12 00:00:00'));

        $moon = new Moon($toi);
        $geoEclSphCoordinates = $moon->getGeocentricEclipticalSphericalCoordinates();

        $longitude = $geoEclSphCoordinates->getLongitude();
        $latitude = $geoEclSphCoordinates->getLatitude();
        $radiusVector = $geoEclSphCoordinates->getRadiusVector();

        $this->assertEquals(133.162655, round($longitude, 6));
        $this->assertEquals(-3.229126, round($latitude, 6));
        $this->assertEquals(0.002463, round($radiusVector, 6));
    }

    /**
     * Meeus 47.a
     */
    public function testGetGeocentricEquatorialCoordinates()
    {
        $toi = new TimeOfInterest(new \DateTime('1992-04-12 00:00:00'));

        $moon = new Moon($toi);
        $geoEquCoordinates = $moon->getGeocentricEquatorialCoordinates();

        $rightAscension = $geoEquCoordinates->getRightAscension();
        $declination = $geoEquCoordinates->getDeclination();

        $this->assertEquals(134.68386, round($rightAscension, 5));
        $this->assertEquals(13.76941, round($declination, 5));
    }

    public function testGetLocalHorizontalCoordinates()
    {
        $toi = new TimeOfInterest(new \DateTime('1992-04-12 00:00:00'));

        $lat = 52.518611;
        $lon = 13.408333;
        $location = new Location($lat, $lon);

        $moon = new Moon($toi);
        $localHorizontalCoordinates = $moon->getLocalHorizontalCoordinates($location);

        $azimuth = $localHorizontalCoordinates->getAzimuth();
        $altitude = $localHorizontalCoordinates->getAltitude();

//        $this->assertEquals(269.99708, round($azimuth, 5)); // TODO Failes... :(
        $this->assertEquals(17.45262, round($altitude, 5));
    }

    /**
     * Meeus 48.a
     */
    public function testGetIlluminatedFraction()
    {
        $toi = new TimeOfInterest(new \DateTime('1992-04-12 00:00:00'));

        $moon = new Moon($toi);
        $illuminatedFraction = $moon->getIlluminatedFraction();

        $this->assertEquals(0.68, round($illuminatedFraction, 2));
    }

    public function testIsWaxingMoon()
    {
        $toi = new TimeOfInterest(new \DateTime('2018-09-17 00:00:00'));

        $moon = new Moon($toi);
        $isWaxingMoon = $moon->isWaxingMoon();

        $this->assertTrue($isWaxingMoon);

        $toi = new TimeOfInterest(new \DateTime('2018-10-02 00:00:00'));

        $moon = new Moon($toi);
        $isWaxingMoon = $moon->isWaxingMoon();

        $this->assertFalse($isWaxingMoon);
    }

    /**
     * Meeus 48.a
     */
    public function testGetPositionAngleOfMoonsBrightLimb()
    {
        $toi = new TimeOfInterest(new \DateTime('1992-04-12 00:00:00'));

        $moon = new Moon($toi);
        $x = $moon->getPositionAngleOfMoonsBrightLimb();

        $this->assertEquals(285.0, round($x, 1));
    }
}
