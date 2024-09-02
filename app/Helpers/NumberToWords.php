<?php

namespace App\Helpers;

class NumberToWords
{
    public static function convertToWords($number)
    {
        $formatter = new \NumberFormatter('fr_FR', \NumberFormatter::SPELLOUT);
        $dinars = intval($number);
        $dinarsText = $formatter->format($dinars);

        return ucfirst($dinarsText);
    }
}