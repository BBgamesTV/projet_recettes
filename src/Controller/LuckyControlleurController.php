<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class LuckyControlleurController extends AbstractController
{
    #[Route('/lucky', name: 'app_lucky_controlleur')]
    // public function index(): Response
    // {
    //     return $this->render('lucky_controlleur/index.html.twig', [
    //         'controller_name' => 'LuckyControlleurController',
    //     ]);
    // }

    public function show_number(): Response
    {
        $number = random_int(0, 100);
        return $this->render('lucky_controlleur/index.html.twig', [
            'number' => $number,
        ]);
    }

    #[Route('/lucky/v3', name: 'app_lucky_number_v3')]
    public function show_number_v3(Request $request): Response
    {
        $username = $request->query->get('username', 'Visiteur');

        // Génération de 10 nombres aléatoires
        $numbers = [];
        for ($i = 0; $i < 10; $i++) {
            $numbers[] = random_int(0, 100);
        }

        // Rendu de la vue
        return $this->render('lucky_controlleur/number.html.twig', [
            'username' => $username,
            'numbers' => $numbers,
        ]);
    }

    #[Route('/lucky/username', name: 'app_lucky_number_for_username')]
    public function show_number_v2(Request $request): Response
    {
        // Récupération du paramètre GET "username"
        $username = $request->query->get('username', 'inconnu');

        // Génération d’un nombre aléatoire
        $number = random_int(0, 100);

        // Rendu Twig avec passage des variables
        return $this->render('lucky_controlleur/username.html.twig', [
            'username' => $username,
            'number' => $number,
        ]);
    }
}
