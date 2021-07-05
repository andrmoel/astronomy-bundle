<?php

namespace Andrmoel\AstronomyBundle\AstronomicalObjects;

class Satellite extends AstronomicalObject
{
    /** @var string two line element */
    private $tle = '';

    // Satellite data
    private $satelliteNo = 0;
    private $classification = 0;
    private $internationalId = 0;
    private $revolutionNoAtEpoch = 0;

    // Epoch of TLE data set
    private $epoch = 0;

    // Kepler parameters
    private $inclination = 0;
    private $raan = 0; // Right ascension of ascending node
    private $eccentricity = 0;
    private $aop = 0; // Argument of perigee
    private $meanAnomaly = 0;
    private $meanMotion = 0;

    public function __construct($tle)
    {
        parent::__construct();

        $this->tle = $tle;
        $this->parseTLE();
    }


    /**
     * Parse two line element
     */
    private function parseTLE()
    {
        $valid = array(
            1 => false, // Validate line 1
            2 => false, // Validate line 2
        );

        $lines = explode("\n", $this->tle);
        $lines = str_replace("\r", '', $lines);
        foreach ($lines as $line) {
            if (strlen($line) == 69) {
                $lineNo = substr($line, 0, 1);
var_dump($lineNo); //TODO
                switch ($lineNo) {
                    case '1':
                        $this->satelliteNo = trim(substr($line, 2, 5));
                        $this->classification = substr($line, 7, 1);
                        $this->internationalId = trim(substr($line, 9, 7));
                        $this->epoch = new TimeOfInterest();
                        $this->epoch->setTleEpoch(substr($line, 18, 14));

                        var_dump($this->epoch->getTimeString());
                        die();
                        $ftdmm = substr($line, 33, 9); //TODO
                        // TODO
                        $valid[1] = true;
                        break;
                    case '2':
                        $this->satelliteNo = trim(substr($line, 2, 5));
                        $this->inclination = doubleval(substr($line, 8, 8));
                        $this->raan = doubleval(substr($line, 17, 8));
                        $this->eccentricity = doubleval('0.' . substr($line, 26, 7));
                        $this->aop = doubleval(substr($line, 34, 8));
                        $this->meanAnomaly = doubleval(substr($line, 43, 8));
                        $this->meanMotion = doubleval(substr($line, 52, 11));
                        $this->revolutionNoAtEpoch = intval(substr($line, 63, 5));
                        $valid[2] = true;
                        break;
                    default:
                        break;
                }
            }
        }
    }
}
