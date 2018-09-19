<?php

namespace Andrmoel\AstronomyBundle\Coordinates;

use Andrmoel\AstronomyBundle\AstronomicalObjects\Planets\Earth;
use Andrmoel\AstronomyBundle\TimeOfInterest;

class HeliocentricEclipticalCoordinates
{
    private $L = 0;
    private $B = 0;
    private $R = 0;

    public function __construct(float $eclipticalLongitude, float $eclipticalLatitude, float $radialVector)
    {
        $this->L = $eclipticalLongitude;
        $this->B = $eclipticalLatitude;
        $this->R = $radialVector;
    }

    public function getEclipticalLongitude(): float
    {
        return $this->L;
    }

    public function getEclipticalLatitude(): float
    {
        return $this->B;
    }

    public function getRadiusVector(): float
    {
        return $this->R;
    }

    public function getEquatorialRectangularCoordinates(TimeOfInterest $toi): EquatorialRectangularCoordinates
    {
        $LRad = deg2rad($this->L);
        $BRad = deg2rad($this->B);
        $R = $this->R;

        // Heliocentric coordinates of earth
        $earth = new Earth($toi);
        $heliocentricCoordinatesEarth = $earth->getHeliocentricEclipticalCoordinates();
        $L0 = $heliocentricCoordinatesEarth->getEclipticalLongitude();
        $L0Rad = deg2rad($L0);
        $B0 = $heliocentricCoordinatesEarth->getEclipticalLatitude();
        $B0Rad = deg2rad($B0);
        $R0 = $heliocentricCoordinatesEarth->getRadiusVector();

        // Meeus 33.1
        $X = $R * cos($BRad) * cos($LRad) - $R0 * cos($B0Rad) * cos($L0Rad);
        $Y = $R * cos($BRad) * sin($LRad) - $R0 * cos($B0Rad) * sin($L0Rad);
        $Z = $R * sin($BRad) - $R0 * sin($B0Rad);

        return new EquatorialRectangularCoordinates($X, $Y, $Z);
    }

    public function getEclipticalCoordinates(TimeOfInterest $toi): EclipticalCoordinates
    {
        return $this
            ->getEquatorialRectangularCoordinates($toi)
            ->getEclipticalCoordinates();
    }
}
