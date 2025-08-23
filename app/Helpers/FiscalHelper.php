<?php

namespace App\Helpers;

class FiscalHelper
{
    public static function parseMfNumber(string $mfNumber): array
    {
        // Assuming format like XXXXXXXX-X-X-XXX
        $noEtSecondaire = substr($mfNumber, -3);
        $codeCategorie = substr($mfNumber, -5, 1);
        $codeTva = substr($mfNumber, -7, 1);
        $matriculeFiscal = substr($mfNumber, 0, -8);

        return [
            'matricule_fiscal' => $matriculeFiscal,
            'code_tva' => $codeTva,
            'code_categorie' => $codeCategorie,
            'no_et_secondaire' => $noEtSecondaire,
        ];
    }
}
