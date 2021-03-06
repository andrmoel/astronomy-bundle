## Table of Contents  
1. [Introduction](#introduction)
2. [Installation](#installation)
3. [Example data](#example)
4. [Angle Util](#angle)
5. [Time of Interest](#toi)
    1. [Create TOI](#toi-create)
    2. [Julian Day, Centuries & Millennia](#toi-julian-day)
    3. [GMST, GAST & Equation of Time](#toi-gmst)
6. [Location](#location)
7. [Coordinate Systems (and transformations)](#coordinates)
8. [Astronomical Objects](#objects)
    1. [Sun](#sun)
        1. [Position](#sun-position)
        2. [Distance to earth](#sun-distance)
        3. [Sunrise, Sunset & Culmination](#sunrise)
    2. [Moon](#moon)
        1. [Position](#moon-position)
        2. [Distance to earth](#moon-distance)
        3. [Moonrise, Moonset & Culmination](#moonrise)
        4. [Phases](#moon-phases)
    3. [Planets](#planets)
        1. [Heliocentric position of a planet](#planet-hel-pos)
        1. [Geocentric position of a planet](#planet-geo-pos)
        1. [Rise, Set & Culmination](#planet-rise)
9. [Events](#events)
    1. [Solar Eclipse](#solar-eclipse)
        1. [Create a Solar Eclipse](#solar-eclipse-create)
        1. [Type, Obscuration, Magnitude, Duration](#solar-eclipse-type)
        1. [Contacts (C1, C2, MAX, C3, C4)](#solar-eclipse-contact)
    2. [Lunar Eclipse](#lunar-eclipse)
10. [Other calculations](#other)
    1. [Distance between two locations](#distance)
    1. [Nutatation of earth](#nutation)

<a name="introduction"></a>
# Introduction

This library provides tools and methods for astronomical calculations.
With this bundle it is possible to calculate the position of moon, sun and planets and several coordinate systems.
For a higher accuracy, several corrections, like nutation and precision, were taken into account.
It is also possible to calculate rise, set and culmination events for celestial objects.
For a detailed see the table of contents.

Most of the calculations are based on Jean Meeus 'Astronomical Algorithms' book and the VSOP87 theory.

<a name="installation"></a>
# Installation

Use composer to install this package.

```console
composer require andrmoel/astronomy-bundle
```

<a name="example"></a>
# Example data

Some example calculations are provided inside the `/examples` folder of the project dir. Usage:

```
php examples/sun.php
```

<a name="angle"></a>
# Angle Util

The angle util provides helper methods to convert an angle into different formats.

**Example 1**: Convert decimal angle

```php
$angleDec = 132.6029282;

$angle = AngleUtil::dec2angle($angleDec);
$time = AngleUtil::dec2time($angleDec);
```

The result of the calculation should be:\
*Angle: 132°36'10.542"*\
*Time: 8h50m24.703s*

**Example 2**: Convert time into decimal format

```php
$time = '8h50m24.703s';

$angle = AngleUtil::time2dec($time);
```

The result of the calculation should be:\
*Angle: 132.60292916667°*

<a name="toi"></a>
# Time of Interest

The TimeOfInterest (TOI) object represents the time for which all of the astronomical calculations are done.
For that reason it is the **most important object** in this library.

:information_source: **Why can't we simply use PHP's DateTime object?**

The problem with PHP's own DateTime object is, that its supported range is '1000-01-01' to '9999-12-31'.
So we cannot process calculations for dates before year 1000.

<a name="toi-create"></a>
## Create the TimeOfInterest object

There are several ways how to initialize the TOi object.

**Example 1**: Initialize the TimeOfInterest object for the date 02 July 2017 at 15:30:00 UTC

```
// Create from time
$toi = TimeOfInterest::create(2017, 7, 2, 15, 30, 0);
$toi = TimeOfInterest::createFromTime(2017, 7, 2, 15, 30, 0);

// Create from string
$toi = TimeOfInterest::createFromString('2017-07-02 15:30:00');

// Create from PHPs DateTime object
$dateTime = new \DateTime('2017-07-02 15:30:00');
$toi = TimeOfInterest::createFromDateTime($dateTime);

// Create from Julian Day
$JD = 2457937.1458333;
$toi = TimeOfInterest::createFromJulianDay($JD);

// Create from Julian Centuries since J2000
$T = 0.17500741501255;
$toi = TimeOfInterest::createFromJulianCenturiesJ2000($T);

echo $toi;
```

The Result will be always: *2017-07-02 15:30:00*

**Example 2**: Create the TOI object for the **current date and time in UTC**

```
$toi = TimeOfInterest::createFromCurrentTime();

echo $toi;
```

The TimeOfInterest provides some methods to **modify** the time:
* `public function setDateTime(\DateTime $dateTime): void`
* `public function setString(string $dateTimeStr): void`
* `public function setJulianDay(float $JD): void`
* `public function setJulianCenturiesJ2000(float $T): void`

**Example 3**: Create the TOI object for the current time and change the time to 2017-07-02 15:30:00 UTC

```
$toi = TimeOfInterest::createFromCurrentTime();

$toi->setString('2017-07-02 15:30:00');

echo $toi;
```

The Result will be always: *2017-07-02 12:00:00*

<a name="toi-julian-day"></a>
### Julian Day, Julian Centuries from J2000 and Julian Millennia from J2000

**Example**: Create TOI for 02 July 2017 at 13:37 UTC

```php
$toi = TimeOfInterest::createFromString('2017-07-02 13:37:00');

$JD = $toi->getJulianDay();
$JD0 = $toi->getJulianDay0();
$T = $toi->getJulianCenturiesFromJ2000();
$t = $toi->getJulianMillenniaFromJ2000();
```

The result of the calculation should be:\
*Julian Day: 2457937.0673611*\
*Julian Day 0: 2457936.5*\
*Julian Centuries J2000: 0.1750052665602*\
*Julian Millennia J2000: 0.01750052665602*

<a name="toi-gmst"></a>
## Greenwich Mean Sidereal Time (GMST), Greenwich Apparent Sidereal Time (GAST) and Equation of Time

With the help of the TOI-Object it is possible to calculate the GMST, GAST and the equation in time (*units of all values are degrees*).
The following example explains how to get these values for 20 December 1992 at 00:00 UTC.

```php
$toi = TimeOfInterest::createFromString('1992-12-20 00:00:00');

$GMST = $toi->getGreenwichMeanSiderealTime();
$GAST = $toi->getGreenwichApparentSiderealTime();
$E = $toi->getEquationOfTime();
```

The result of the calculation should be:\
*GMST: 88.82536°*\
*GAST: 88.829629°*\
*Equation of Time: 0.619485°*

You may convert this values into the more common angle format by using `AngleUtil::dec2time($value)`.
The result will be:\
*GMST: 5h55m18.086s*\
*GAST: 5h55m19.111s*\
*Equation of Time: 0h2m28.676s*

<a name="location"></a>
# Location

The location object represents the location of the observer on the earth's surface.

```php
// Initialize Location object for Berlin
$location = Location::create(52.524, 13.411);

// Initialize Location with elevation (Mt. Everest)
$location = Location::create(27.98787, 86.92483, 8848);
```

<a name="coordinates"></a>
# Coordinate systems and transformations

The bundle provides the common astronomical coordinate systems for calculations.

* Geocentric Ecliptical Spherical (latitude, longitude)
* Geocentric Equatorial Spherical (rightAscension, declination)
* Geocentric Equatorial Rectangular (X, Y, Z)
* Heliocentric Ecliptical Spherical (latitude, longitude)
* Heliocentric Ecliptical Rectangular (X, Y, Z)
* Heliocentric Equatorial Rectangular (x, y, z)
* Local Horizontal (azimuth, altitude)

Each class provides methods to transform one coordinate system into another.

**Example 1**: Convert Geocentric Equatorial Spherical Coordinates into Geocentric Ecliptical Spherical Coordinates

```php
$T = -0.12727429842574; // Julian Centuries since J2000 (1987-04-10 19:21:00)
$rightAscension = 116.328942;
$declination = 28.026183;

$geoEquSphCoord = new GeocentricEquatorialSphericalCoordinates($rightAscension, $declination);
$geoEclSphCoord = $geoEquSphCoord->getGeocentricEclipticalSphericalCoordinates($T);

$lon = $geoEclSphCoord->getLongitude();
$lat = $geoEclSphCoord->getLatitude();
```

**Example 2**: Convert Geocentric Equatorial Spherical Coordinates to Local Horizontal Coordinates

```php
$location = Location::create(38.921389, -77.065556); // Washington DC
$T = -0.12727429842574; // Julian Centuries since J2000 (1987-04-10 19:21:00)
$rightAscension = 347.3193375;
$declination = -6.719891667;

$geoEquSphCoord = new GeocentricEquatorialSphericalCoordinates($rightAscension, $declination);
$locHorCoord = $geoEquSphCoord->getLocalHorizontalCoordinates($location, $T);

$altitude = $locHorCoord->getAltitude();
$azimuth = $locHorCoord->getAzimuth();
```

<a name="objects"></a>
# Astronomical Objects

An astronomical object **must** be initialized with the TOI. If you don't pass the TOI in the constructor, the
**current** time is chosen.

```php
$toi = TimeOfInterest::createFromString('2017-07-02 12:00:00');

$moon = Moon::create($toi);
```

<a name="sun"></a>
## Sun

<a name="sun-position"></a>
### Position of the sun

**Example 1**: Calculate the position of the sun for 17 May 2019 at 17:50 UTC

```php
$toi = TimeOfInterest::createFromString('2019-05-17 17:50');

$sun = Sun::create($toi);

$geoEclSphCoordinates = $sun->getGeocentricEclipticalSphericalCoordinates();
$lon = $geoEclSphCoordinates->getLongitude();
$lat = $geoEclSphCoordinates->getLatitude();
```

The result of the calculation should be:\
*Longitude: 56.544°*\
*Latitude: 0.0001°*

**Example 2**: Calculate azimuth and altitude of the sun observed in Berlin, Germany for 17 May 2019 at 17:50 UTC

```php
$toi = TimeOfInterest::createFromString('2019-05-17 17:50');

$location = Location::create(52.524, 13.411); // Berlin

$sun = Sun::create($toi);

$locHorCoord = $sun->getLocalHorizontalCoordinates($location);
$azimuth = $locHorCoord->getAzimuth();
$altitude = $locHorCoord->getAltitude();
```

The result of the calculation should be:\
*Azimuth: 291.0°*\
*Altitude: 8.49°*

The result of the altitude is **corrected by atmospheric refraction**.
To obtain the local horizontal coordinates **without correction of refraction**, pass `false` as second parameter:

`$locHorCoord = $sun->getLocalHorizontalCoordinates($location, false);`

<a name="sun-distance"></a>
### Distance of the sun to earth

**Example 1**: The current distance of the sun in kilometers can be calculated as follow:

```php
$sun = Sun::create();

$distance = $sun->getDistanceToEarth();
```

The result should be between 147.1 mio and 152.1 mio kilometers.

**Example 2**: Get the distance of the sun on 05 June 2017 at 20:50 UTC

```php
$toi = TimeOfInterest::createFromString('2017-06-05 20:50');

$sun = Sun::create($toi);

$distance = $sun->getDistanceToEarth();
```

The result should be 151797703km.

<a name="sunrise"></a>
### Sunrise, sunset and upper culmination

Calculate sunrise, sunset and upper culmination of the sun on 17 May 2019 in Berlin:

```php
$toi = TimeOfInterest::createFromString('2019-05-17');

$location = Location::create(52.524, 13.411); // Berlin

$sun = Sun::create($toi);

// Results are TimeOfInterest objects
$sunrise = $sun->getSunrise($location);
$sunset = $sun->getSunset($location);
$upperCulmination = $sun->getUpperCulmination($location);
```

The result of the calculation should be:\
*Sunrise: 03:08 UTC*\
*Sunset: 18:59 UTC*\
*Upper culmination: 13:03 UTC*

<a name="moon"></a>
## Moon

<a name="moon-position"></a>
### Position of the moon

**Example 1**: Calculate the geocentric position of the moon for 12 April 1992 at 00:00 UTC.

```php
$toi = TimeOfInterest::createFromString('1992-04-12 00:00:00');

$moon = Moon::create($toi);

$geoEquSphCoord = $moon->getGeocentricEquatorialSphericalCoordinates();
$rightAscension = $geoEquSphCoord->getRightAscension();
$declination = $geoEquSphCoord->getDeclination();
```

The result of the calculation should be:\
*Right ascension: 134.69°*\
*Declination: 13.77°*

**Example 2**: Calculate azimuth and altitude of the moon observed in Berlin, Germany for 20 May 2019 at 23:00 UTC

```php
$toi = TimeOfInterest::createFromString('2019-05-20 23:00:00');

$location = Location::create(52.524, 13.411); // Berlin

$moon = Moon::create($toi);

$locHorCoord = $moon->getLocalHorizontalCoordinates($location);
$azimuth = $locHorCoord->getAzimuth();
$altitude = $locHorCoord->getAltitude();
```

The result of the calculation should be:\
*Azimuth: 153.3°*\
*Altitude: 12.28°*

The result of the altitude is **corrected by atmospheric refraction**.
To obtain the local horizontal coordinates **without correction of refraction**, pass `false` as second parameter:

`$locHorCoord = $moon->getLocalHorizontalCoordinates($location, false);`

<a name="moon-distance"></a>
### Distance of the moon to earth

**Example 1**: The current distance of the moon in kimometers can be calculated as follow:

```php
$moon = Moon::create();

$distance = $moon->getDistanceToEarth();
```

The result should be between 363300km and 405500km.

**Example 2**: Get the distance of the moon on 05 June 2017 at 20:50 UTC

```php
$toi = TimeOfInterest::createFromString('2017-06-05 20:50');

$moon = Moon::create($toi);

$distance = $moon->getDistanceToEarth();
```

The result should be 402970km.

<a name="moonrise"></a>
### Moonrise, moonset and upper culmination

* ![#f03c15](https://placehold.it/15/f03c15/000000?text=+) **ATTENTION**: Feature not yet implemented

<a name="moon-phases"></a>
### Phases of the moon

The following code sniped explains how to calculate all important parameters which belong to the moons phase
for an specific date. In this example it is 13 May 2019 at 21:30 UTC.

```php
$toi = TimeOfInterest::createFromString('2019-05-13 21:30:00');

$moon = Moon::create($toi);

$isWaxing = $moon->isWaxingMoon();
$illumination = $moon->getIlluminatedFraction();
$positionAngle = $moon->getPositionAngleOfMoonsBrightLimb();
```

The result of the calculation should be:\
*Is waxing moon: yes*\
*Illumination: 0.709 (70.9%)*\
*Position angle of bright limb: 293.54°*

<a name="planets"></a>
## Planets

Like Sun and Moon-Objects, the Planets can be created by passing the TimeOfInterest.
If no TimeOfInteressed is passed, the **current date and time** are used for further calculations.

**Example**: Create some planets

```php
$toi = TimeOfInterest::createFromString('2018-06-03 19:00:00');

$mercury = Mercury::create();  // Time = now
$venus = Venus::create($toi); // Time = 2018-06-03 19:00:00
$earth = Earth::create($toi);
$mars = Mars::create($toi);
$jupiter = Jupiter::create($toi);
$saturn = Saturn::create($toi);
$uranus = Uranus::create($toi);
$neptune = Neptune::create($toi);
```

<a name="planet-hel-pos"></a>
### Heliocentric position of a planet

The calculations use the VSOP87 theory to obtain the heliocentric position of a planet.

**Example**: Calculate the heliocentric position of Venus for 20. December 1992 at 00:00 UTC.

```php
$toi = TimeOfInterest::createFromString('1992-12-20 00:00:00');

$venus = Venus::create($toi);

$helEclSphCoord = $venus->getHeliocentricEclipticalSphericalCoordinates();
$lon = $helEclSphCoord->getLongitude();
$lat = $helEclSphCoord->getLatitude();
$r = $helEclSphCoord->getRadiusVector();

$helEclRecCoord = $venus->getHeliocentricEclipticalRectangularCoordinates();
$x = $helEclRecCoord->getX();
$y = $helEclRecCoord->getY();
$z = $helEclRecCoord->getZ();
```
The result of the calculation should be:\
*Longitude: 26.11412°*\
*Latitude: -2.62063°*\
*Radius vector: 0.72460*\
*X: 0.64995327095595*\
*Y: 0.31860745636351*\
*Z: -0.033130385747949*

<a name="planet-geo-pos"></a>
### Geocentric position of a planet

All solutions for the geocentric calculations give the **apparent** position of a planet.
That means the position of the planet is corrected by light time and aberration.

**Example 1**: Calculate the apparent geocentric position of Venus on 25 October 2018 at 07:15 UTC

```php
$toi = TimeOfInterest::createFromString('2018-10-25 07:15:00');

$venus = Venus::create($toi);

$geoEclSphCoords = $venus->getGeocentricEclipticalSphericalCoordinates();
$lon = $geoEclSphCoords->getLongitude();
$lat = $geoEclSphCoords->getLatitude();

$geoEclSphCoords = $venus->getGeocentricEquatorialSphericalCoordinates();
$rightAscension = $geoEclSphCoords->getRightAscension();
$declination = $geoEclSphCoords->getDeclination();
```

The result of the calculation should be:\
*Longitude: 213.898092° (213°53'53.131")*\
*Latitude: -6.476359° (-6°28'34.891")*\
*Right ascension: 209.340427° (13h57m21.702s)*\
*Declination: -18.898191° (-18°53'53.487")*

**Example 2**: Calculate the azimuth and altitude of Venus on 25 October 2018 at 07:15 UTC in Berlin

```php
$location = Location::create(52.524, 13.411); // Berlin

$toi = TimeOfInterest::createFromString('2018-10-25 07:15:00');

$venus = Venus::create($toi);

$locHorCoords = $venus->getLocalHorizontalCoordinates($location);
$azimuth = $locHorCoords->getAzimuth();
$altitude = $locHorCoords->getAltitude();
```

The result of the calculation should be:\
*Azimuth: 130.202°*\
*Altitude: 5.038°*

The result of the altitude is **corrected by atmospheric refraction**.
To obtain the local horizontal coordinates **without correction of refraction**, pass `false` as second parameter:

`$locHorCoord = $venus->getLocalHorizontalCoordinates($location, false);`

<a name="planet-rise"></a>
### Rise, set and upper culmination

Calculate rise, set and upper culmination of Venus on 25 October 2018 at 07:15 UTC in Berlin:

```php
$location = Location::create(52.524, 13.411); // Berlin

$toi = TimeOfInterest::createFromString('2018-10-25 07:15:00');

$venus = Venus::create($toi);

// Results are TimeOfInterest objects
$rise = $venus->getRise($location);
$set = $venus->getSet($location);
$upperCulmination = $venus->getUpperCulmination($location);
```

The result of the calculation should be:\
*Rise: 06:31 UTC*\
*Set: 15:06 UTC*\
*Upper culmination: 10:49 UTC*

<a name="events"></a>
# Events

<a name="solar-eclipse"></a>
## Solar eclipse

<a name="solar-eclipse-create"></a>
### Create a Solar Eclipse object

**Example**: Create a solar eclipse for 21 August 2017 for the location Madrads in Oregon (USA)

```php
$location = Location::create(44.61040, -121.23848); // Madras, OR

$toi = TimeOfInterest::createFromString('2017-08-21'); // Date of the eclipse (UTC)

$solarEclipse = SolarEclipse::create($toi, $location);
```

*Note: If the date of the eclipse is invalid, an exception will be thrown.*

<a name="solar-eclipse-type"></a>
### Eclipse type, Obscuration, Magnitude, Duration, etc.

To obtain the eclipse circumstances of the **maximum eclipse** for a given location, see the following examples.

The **type of an eclipse** (for the given location) is expressed in a string. But it is better to use the following constants:
`SolarEclipse:TYPE_NONE`,
`SolarEclipse:TYPE_PARTIAL`,
`SolarEclipse:TYPE_ANNULAR` or
`SolarEclipse:TYPE_TOTAL`.

**Example 1**: Local circumstances for the total solar eclipse of 21 August 2017 for Madras, OR

```php
$location = Location::create(44.61040, -121.23848); // Madras, OR

$toi = TimeOfInterest::createFromString('2017-08-21'); // Date of the eclipse (UTC)

$solarEclipse = SolarEclipse::create($toi, $location);

$type = $solarEclipse->getEclipseType();
$duration = $solarEclipse->getEclipseDuration(); // in seconds
$durationTotality = $solarEclipse->getEclipseUmbraDuration(); // in seconds
$obscuration = $solarEclipse->getObscuration();
$magnitude = $solarEclipse->getMagnitude();
$moonSunRatio = $solarEclipse->getMoonSunRatio();
```

The result of the calculation should be:\
*Type: total*\
*Duration of eclipse: 9257s*\
*Duration of totality: 120s*\
*Obscuration: 1 (100%)*\
*Magnitude: 1.01*\
*Moon-sun-ratio: 1.03*

**Example 2**: Local circumstances for the partial solar eclipse of 20 March 2015 in Berlin

```php
$location = Location::create(52.52, 13.405); // Berlin

$toi = TimeOfInterest::createFromString('2015-03-20'); // Date of the eclipse (UTC)

$solarEclipse = SolarEclipse::create($toi, $location);

$type = $solarEclipse->getEclipseType();
$duration = $solarEclipse->getEclipseDuration(); // in seconds
$durationTotality = $solarEclipse->getEclipseUmbraDuration(); // in seconds
$obscuration = $solarEclipse->getObscuration();
$magnitude = $solarEclipse->getMagnitude();
$moonSunRatio = $solarEclipse->getMoonSunRatio();
```

The result of the calculation should be:\
*Type: partial*\
*Duration of eclipse: 8386s*\
*Duration of totality: 0s*\
*Obscuration: 0.74 (74%)*\
*Magnitude: 0.79*\
*Moon-sun-ratio: 1.05*

<a name="solar-eclipse-contact"></a>
### Contacts (C1, C2, MAX, C3, C4)

It is possible to obtains the current circumstances for each contact type (C1, C2, MAX, C3 and X4) for an eclipse.

* C1: First contact - Eclipse begins
* C2: Second contact - Begin of totality or annularity
* MAX: Maximum obscuration of eclipse
* C3: Third content - End of totality or annularity
* C4: Fourth contact - Eclipse ends

```php
$location = Location::create(44.61040, -121.23848); // Madras, OR

$toi = TimeOfInterest::createFromString('2017-08-21'); // Date of the eclipse (UTC)

$solarEclipse = SolarEclipse::create($toi, $location);

$c1 = $solarEclipse->getCircumstancesC1();
$c2 = $solarEclipse->getCircumstancesC2();
$max = $solarEclipse->getCircumstancesMax();
$c3 = $solarEclipse->getCircumstancesC3();
$c4 = $solarEclipse->getCircumstancesC4();
```

**Example**: Obtain the exact time of the second contact (C2) and the position of the sun.
The solar eclipse happens on 21 August 2017 in Madras, Oregon.

```php
$location = Location::create(44.61040, -121.23848); // Madras, OR

$toi = TimeOfInterest::createFromString('2017-08-21'); // Date of the eclipse (UTC)

$solarEclipse = SolarEclipse::create($toi, $location);

$c2 = $solarEclipse->getCircumstancesC2();

// Get time for C2
$toiC2 = $solarEclipse->getTimeOfInterest($c2);

// Get local horizontal coordinates (azimuth, altitude) of C2
$locHorCoord = $c2->getLocalHorizontalCoordinates();
$azimuth = $locHorCoord->getAzimuth();
$altitude = $locHorCoord->getAltitude();
```

The result of the calculation for the second contact (C2) should be:\
*Time of Interest: 2017-06-21 17:19:24 UTC*\
*Azimuth of sun: 118.9°*\
*Altitude of sun: 41.4°*

The result of the altitude is **corrected by atmospheric refraction**.
To obtain the local horizontal coordinates **without correction of refraction**, pass `false` as parameter:

`$locHorCoord = $c2->getLocalHorizontalCoordinates(false);`

<a name="lunar-eclipse"></a>
# Lunar eclipse

* ![#f03c15](https://placehold.it/15/f03c15/000000?text=+) **ATTENTION**: Feature not yet implemented

<a name="other"></a>
# Other calculations

<a name="distance"></a>
## Distance between two locations

```php
$location1 = Location::create(52.524, 13.411); // Berlin
$location2 = Location::create(40.697,-74.539); // New York

$distance = EarthCalc::getDistanceBetweenLocations($location1, $location2);
```

The result of the calculation should be 6436km.

<a name="nutation"></a>
## Nutation of earth

```php
$T = -0.127296372458;

$nutationLon = EarthCalc::getNutationInLongitude($T);
$nutationLon = AngleUtil::dec2angle($nutationLon);

$nutationObl = EarthCalc::getNutationInObliquity($T);
$nutationObl = AngleUtil::dec2angle($nutationObl);
```

The result of the calculation should be:\
*Nutation in longitude: -0°0'3.788"*\
*Nutation in obliquity: 0°0'9.442"*
