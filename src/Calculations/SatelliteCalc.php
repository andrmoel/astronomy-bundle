<?php

namespace Andrmoel\AstronomyBundle\Calculations;

use Andrmoel\AstronomyBundle\AstronomicalObjects\Planets\Earth;
use Andrmoel\AstronomyBundle\Constants;
use Andrmoel\AstronomyBundle\Entities\TwoLineElements;
use Andrmoel\AstronomyBundle\Utils\AngleUtil;
use Andrmoel\AstronomyBundle\Utils\GeneralUtil;

class SatelliteCalc
{
    public static function twoLineElements2keplerianElements(TwoLineElements $tle, float $T): array
    {
        $T0 = $tle->getEpoch()->getJulianCenturiesFromJ2000();
        $M0 = $tle->getMeanAnomaly();
        $n = $tle->getMeanMotion();
        $d1mm = $tle->get1thDerivativeOfMeanMotion();
        $d2mm = $tle->get2ndDerivativeOfMeanMotion();
        $e = $tle->getEccentricity();

        $dT = $T - $T0;

        $M = self::getMeanAnomaly($tle, $dT);

        $a = self::getSemiMajorAxis($tle, $dT);

        var_dump($a);
        die();

        $E = self::getEccentricAnomaly($M0, $e);


        var_dump("ECCC", $E);
        die();
        // TODO ...
        return [];
    }

    private static function getMeanAnomaly(TwoLineElements $tle, float $dT): float
    {
        $M0 = $tle->getMeanAnomaly();
        $n = $tle->getMeanMotion();
        $d1mm = $tle->get1thDerivativeOfMeanMotion();
        $d2mm = $tle->get2ndDerivativeOfMeanMotion();

        $M = $M0
            + $n * $dT
            + $d1mm * pow($dT, 2)
            + $d2mm * pow($dT, 3);

        return $M;
    }

    public static function getTrueAnomaly(TwoLineElements $tle): float
    {
        $e = $tle->getEccentricity();
        $E = self::getEccentricAnomaly($tle);

        $ERad = deg2rad($E);

        $ThetaRad = 2 * atan(
                sqrt((1 + $e) / (1 - $e)) * tan($ERad / 2)
            );

        $Theta = rad2deg($ThetaRad);

        return $Theta;
    }

    public static function getEccentricAnomaly(TwoLineElements $tle): float
    {
        $M = $tle->getMeanAnomaly();
        $e = $tle->getEccentricity();
        $MRad = deg2rad($M);

        $Ei = $M + 0.85 * $e * GeneralUtil::sign(sin($MRad));

        do {
            $EiRad = deg2rad($Ei);

            $Ej = $Ei - ($Ei - $e * sin($EiRad) - $M) / (1 - $e * cos($EiRad));
            $Ei = $Ej;
        } while (abs($Ei - $Ej) > 1e-8);

        $Ei = AngleUtil::normalizeAngle($Ei);

        return $Ei;
    }

    private static function getSemiMajorAxis(TwoLineElements $tle, float $dT): float
    {
        $n = $tle->getMeanMotion();
        $nRad = deg2rad($n);
        $d1mm = $tle->get1thDerivativeOfMeanMotion();
        $d2mm = $tle->get2ndDerivativeOfMeanMotion();

        $mu0 = sqrt(Constants::GRAVITATIONAL_PARAMETER);

        $a0 = pow($mu0 / $n, 2 / 3);

        var_dump($a1);die();

        $a = pow($GM, 1 / 3) / pow((2 * $n * pi()) / 86400, 2 / 3);

        return $a;
    }

    public static function getRightAscensionOfAscendingNode(TwoLineElements $tle, float $dT): float
    {
        $iRad = deg2rad($i);

        $dOmega = -9.9641 * pow(Earth::RADIUS / $a, 3.5) * (cos($iRad) / (pow(1 - pow($e, 2), 2)));
        $Omega = $Omega0 + $dOmega * $dT;

        return $Omega;
    }

    public static function getArgumentOfPerigee(TwoLineElements $tle, float $dT): float
    {
        $iRad = deg2rad($i);

        $dOmega = 4.98 * pow(Earth::RADIUS / $a, 3.5) * ((5 * pow(cos($iRad), 2) - 1) / pow(1 - pow($e, 2), 2));
        $omega = $omega0 + $dOmega * $dT;

        return $omega;
    }

    public static function getPerifocalCoordinateSystem(TwoLineElements $tle): array
    {
        $TRad = deg2rad($trueAnomaly);

        $k = ($a * (1 - pow($e, 2))) / (1 + $e * cos($TRad));

        $x = $k * cos($TRad);
        $y = $k * sin($TRad);
        $z = 0;

        return [$x, $y, $z];
    }

    public static function fofo(): array
    {
        $x0 = 0;
        $y0 = 0;

        $aopRad = deg2rad($aop);
        $raanRad = deg2rad($raan);
        $iRad = deg2rad($i);

        // Rotation matrix
        $Px = cos($aopRad) * cos($raanRad) - sin($aopRad) * sin($raanRad) * cos($iRad);
        $Py = cos($aopRad) * sin($raanRad) + sin($aopRad) * cos($raanRad) * cos($iRad);
        $Pz = sin($aopRad) * sin($iRad);

        $Qx = -sin($aopRad) * cos($raanRad) - cos($aopRad) * sin($raanRad) * cos($iRad);
        $Qy = -sin($aopRad) * sin($raanRad) + cos($aopRad) * cos($raanRad) * cos($iRad);
        $Qz = cos($aopRad) * sin($iRad);

        $x = $Px * $x0 + $Qx * $y0;
        $y = $Py * $x0 + $Qy * $y0;
        $z = $Pz * $x0 + $Qz * $y0;

        return [$x, $y, $z];
    }
}
