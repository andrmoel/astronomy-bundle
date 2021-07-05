<?php

include __DIR__ . '/../vendor/autoload.php';

use Andrmoel\AstronomyBundle\Events\SolarEclipse\SolarEclipse;
use Andrmoel\AstronomyBundle\Location;
use Andrmoel\AstronomyBundle\TimeOfInterest;

date_default_timezone_set('UTC');

$string = <<<END
ISS             109. 73.0 27.5 -2.0 v  402
1 25544U 98067A   19155.51091253  .00000344  00000-0  13620-4 0  9994
2 25544  51.6453  64.2093 0007540  13.2790 173.5287 15.51165870173281
END;

$TLEParser = \Andrmoel\AstronomyBundle\Parsers\ParserFactory::get(\Andrmoel\AstronomyBundle\Parsers\TLEParser::class, $string);
$tle = $TLEParser->getParsedData();

$T = TimeOfInterest::createFromString('2019-06-04 00:00:00')->getJulianCenturiesFromJ2000();
$kep = \Andrmoel\AstronomyBundle\Calculations\SatelliteCalc::twoLineElements2keplerianElements($tle, $T);

var_dump($kep);die();