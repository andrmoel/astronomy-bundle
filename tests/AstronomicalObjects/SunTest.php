<?php

namespace Andrmoel\AstronomyBundle\Tests\AstronomicalObgetGeocentricEclipticalSphericalCoordinatesjects;

use Andrmoel\AstronomyBundle\AstronomicalObjects\Sun;
use Andrmoel\AstronomyBundle\Location;
use Andrmoel\AstronomyBundle\TimeOfInterest;
use PHPUnit\Framework\TestCase;

class SunTest extends TestCase
{
    /**
     * @test
     * Meeus 25.b
     */
    public function getGeocentricEclipticalSphericalCoordinatesTest()
    {
        $toi = TimeOfInterest::createFromString('1992-10-13 00:00:00');

        $sun = Sun::create($toi);
        $geoEclSphCoord = $sun->getGeocentricEclipticalSphericalCoordinates();

        $lon = $geoEclSphCoord->getLongitude();
        $lat = $geoEclSphCoord->getLatitude();

        $this->assertEquals(199.90599, round($lon, 5));
        $this->assertEquals(0.0002, round($lat, 5));
    }

    /**
     * @test
     * Meeus 26.a
     */
    public function getGeocentricEquatorialRectangularCoordinatesTest()
    {
        $toi = TimeOfInterest::createFromString('1992-10-13 00:00:00');

        $sun = Sun::create($toi);
        $geoEquRecCoordinates = $sun->getGeocentricEquatorialRectangularCoordinates();

        $X = $geoEquRecCoordinates->getX();
        $Y = $geoEquRecCoordinates->getY();
        $Z = $geoEquRecCoordinates->getZ();

        $this->assertEquals(-0.9379962, round($X, 7));
        $this->assertEquals(-0.3116541, round($Y, 7));
        $this->assertEquals(-0.1351207, round($Z, 7));
    }

    /**
     * @test
     * Meeus 25.a
     */
    public function getGeocentricEquatorialSphericalCoordinatesTest()
    {
        $toi = TimeOfInterest::createFromString('1992-10-13 00:00:00');

        $sun = Sun::create($toi);
        $geoEquCoordinates = $sun->getGeocentricEquatorialSphericalCoordinates();

        $rightAscension = $geoEquCoordinates->getRightAscension();
        $declination = $geoEquCoordinates->getDeclination();
        $radiusVector = $geoEquCoordinates->getRadiusVector();

        $this->assertEquals(198.37812, round($rightAscension, 5));
        $this->assertEquals(-7.78382, round($declination, 5));
        $this->assertEquals(0.99761, round($radiusVector, 5));
    }

    /**
     * @test
     * @throws \Exception
     */
    public function getTwilightTest()
    {
        $data = array(
            ['2018-09-18 12:00:00', Sun::TWILIGHT_DAY],
            ['2018-09-18 17:30:00', Sun::TWILIGHT_CIVIL],
            ['2018-09-18 18:00:00', Sun::TWILIGHT_NAUTICAL],
            ['2018-09-18 18:30:00', Sun::TWILIGHT_ASTRONOMICAL],
            ['2018-09-18 19:30:00', Sun::TWILIGHT_NIGHT],
        );

        $lat = 52.518611;
        $lon = 13.408333;
        $location = Location::create($lat, $lon);

        foreach ($data as $t) {
            $toi = TimeOfInterest::createFromString($t[0]);

            $sun = Sun::create($toi);
            $this->assertEquals($t[1], $sun->getTwilight($location));
        }
    }
}
