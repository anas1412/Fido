<?php

namespace App\Helpers;

class NumberToWords
{
    public static function convertToWords($number)
    {
        $formatter = new \NumberFormatter('fr_FR', \NumberFormatter::SPELLOUT);
        $dinars = intval($number);
        $millimes = round(($number - $dinars) * 1000);

        $dinarsText = $formatter->format($dinars) . ' dinars';
        $millimesText = $formatter->format($millimes) . ' millimes';

        return ucfirst($dinarsText) . ' et ' . $millimesText;
    }
}
