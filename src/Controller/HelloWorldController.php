<?php

namespace App\Controller;

use App\Taxe\CalculatorTaxe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HelloWorldController extends AbstractController
{
    #[Route('/taxe', name: 'taxe')]
    public function index(CalculatorTaxe $calculator): Response
    {
        $prixHT = 360;
        $tva = $calculator->calculerTVA($prixHT);
        $ttc = $calculator->calculerTTC($prixHT);

        return $this->render('hello_world/helloWorld.html.twig', [
            'prixHT' => $prixHT,
            'tva' => $tva,
            'ttc' => $ttc,
        ]);
    }
}
