<?php

namespace Andrmoel\AstronomyBundle\Parsers;

use Andrmoel\AstronomyBundle\Calculations\TimeCalc;
use Andrmoel\AstronomyBundle\Entities\TwoLineElements;
use Andrmoel\AstronomyBundle\TimeOfInterest;

class TLEParser extends AbstractParser
{
    private $tleData = [];

    public function getParsedData()
    {
        $lines = explode("\n", $this->data);

        $pattern = '/^([12]) /i';
        foreach ($lines as $line) {
            if (preg_match($pattern, $line, $matches)) {
                switch ($matches[1]) {
                    case 1:
                        $this->parseLineOne($line);
                        break;
                    case 2:
                        $this->parseLineTwo($line);
                        break;
                }
            }
        }

        return new TwoLineElements($this->tleData);
    }

    private function parseLineOne(string $line): void
    {
        $this->tleData['satelliteNo'] = substr($line, 2, 5);
        $this->tleData['classification'] = substr($line, 7, 1);
        $this->tleData['internationalDesignator'] = trim(substr($line, 9, 8));
        $this->tleData['epoch'] = $this->getEpoch(substr($line, 18, 2), substr($line, 20, 12));
        $this->tleData['td1MeanMotion'] = $this->addDecimalPoint(substr($line, 33, 10));
        $this->tleData['td2MeanMotion'] = $this->addDecimalPoint(substr($line, 44, 8));
        $this->tleData['BSTARDragTerm'] = $this->addDecimalPoint(substr($line, 53, 8));
        $this->tleData['setNumber'] = (int)substr($line, 64, 4);
    }

    private function parseLineTwo(string $line): void
    {
        $this->tleData['inclination'] = floatval(substr($line, 8, 8));
        $this->tleData['rightAscensionOfAscendingNode'] = floatval(substr($line, 17, 8));
        $this->tleData['eccentricity'] = $this->addDecimalPoint(substr($line, 26, 7));
        $this->tleData['argumentOfPerigee'] = floatval(substr($line, 34, 8));
        $this->tleData['meanAnomaly'] = floatval(substr($line, 43, 8));
        $this->tleData['meanMotion'] = floatval(substr($line, 52, 11));
        $this->tleData['revolutionNoAtEpoch'] = (int)substr($line, 63, 5);
    }

    private function getEpoch(string $year, string $dayOfYear): TimeOfInterest
    {
        $year = TimeCalc::yearTwoDigits2year((int)$year);
        $dayOfYear = floatval($dayOfYear);

        return TimeOfInterest::createFromDayOfYear($year, $dayOfYear);
    }

    private function addDecimalPoint(string $value): float
    {
        $value = trim($value);

        // -11606-4 -> -0.11606-4
        if (preg_match('/^\-([0-9-]+)$/', $value, $matches)) {
            $value = '-0.' . $matches[1];
        }

        // -0.11606-4 -> -0.11606e-4
        if (preg_match('/^([0-9.-]+)(\-[0-9]+)$/', $value, $matches)) {
            $value = $matches[1] . 'e' . $matches[2];
        }

        // 123456 -> 0.123456
        if (strpos($value, '.') === false) {
            $value = '0.' . $value;
        }

        return floatval($value);
    }
}
