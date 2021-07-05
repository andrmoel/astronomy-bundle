<?php

namespace Andrmoel\AstronomyBundle\Events\SolarEclipse;

class LunarEclipseElements
{
    private $tMax = 0.0;
    private $t0 = 0.0;
    private $dT = 0.0; // Delta T

    private $ra = array(); // Right ascension
    private $d = array(); // Declination


    public function __construct(array $data)
    {
        $this->tMax = $data['tMax'];
        $this->t0 = $data['t0'];

        $this->dT = $data['dT'];

        $this->ra = $data['ra'];
        $this->d = $data['d'];
    }


    /**
     * Load besselian elements from string
     * @param $string
     */
    public function loadBesselianElementsFromString($string)
    {
        // TODO ...
    }


    /**
     * Get time of greatest eclipse
     * @return float
     */
    public function getTMax()
    {
        return $this->tMax;
    }


    /**
     * Get t0
     * @return float
     */
    public function getT0()
    {
        return $this->t0;
    }


    public function getDeltaT(): float
    {
        return $this->dT;
    }


    /**
     * Get d
     * @param $t
     * @return float
     */
    public function getD($t): float
    {
        return $this->evaluate($this->d, $t);
    }


    /**
     * Get dd
     * @param $t
     * @return float
     */
    public function getDD($t)
    {
        return $this->evaluateD($this->d, $t);
    }


    /**
     * Get m
     * @param $t
     * @return float
     */
    public function getMu($t)
    {
        return $this->evaluate($this->mu, $t);
    }


    /**
     * Get dd
     * @param $t
     * @return float
     */
    public function getDMu($t)
    {
        return $this->evaluateD($this->mu, $t);
    }


    /**
     * Get l1
     * @param $t
     * @return float
     */
    public function getL1($t)
    {

        return $this->evaluate($this->l1, $t);
    }


    /**
     * Get dl1
     * @param $t
     * @return float
     */
    public function getDL1($t)
    {
        return $this->evaluateD($this->l1, $t);
    }


    /**
     * Get l2
     * @param $t
     * @return float
     */
    public function getL2($t)
    {

        return $this->evaluate($this->l2, $t);
    }


    /**
     * Get dl2
     * @param $t
     * @return float
     */
    public function getDL2($t)
    {
        return $this->evaluateD($this->l2, $t);
    }


    /**
     * Evaluate
     * @param $dataArray
     * @param $t
     * @return float
     */
    private function evaluate($dataArray, $t): float
    {
        $result = 0.0;

        foreach ($dataArray as $key => $n) {
            $result += $n * pow($t, $key);
        }

        return $result;
    }


    /**
     * Evaluate D
     * @param $dataArray
     * @param $t
     * @return float
     */
    private function evaluateD($dataArray, $t)
    {
        $result = 0.0;

        foreach ($dataArray as $key => $n) {
            if ($key >= 1) {
                $result += $key * $n * pow($t, $key - 1);
            }
        }

        return $result;
    }
}
