<?php

namespace Andrmoel\AstronomyBundle\Events\SolarEclipse;

/**
 * TODO
//  (0) Event type (P1=-3, U1=-2, U2=-1, Mid=0, U3=1, U4=2, P4=3)
//  (1) t
//  (2) hour angle
//  (3) declination
//  (4) altitude
//  (5) azimuth
//  (6) visibility (0 = above horizon, 1 = no event, 2 = below horizon)
 */
class LunarEclipseCircumstances
{
    public $eclipseType;
    public $t;

    public $hourAngle;
    public $declination;

    public $moonAltitude;
    public $moonAzimuth;
}
