<?php

namespace App\Taxe;

class CalculatorTaxe
{
    public function calculerTVA(float $prixHT): float
    {
        return $prixHT * 0.20;
    }

    public function calculerTTC(float $prixHT): float
    {
        return $prixHT * 1.20;
    }
}
