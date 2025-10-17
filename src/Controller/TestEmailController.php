<?php

namespace App\Controller;

use App\Service\SendMailService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestEmailController extends AbstractController
{
    #[Route('/test/email', name: 'app_test_email')]
    public function index(SendMailService $mailer): Response
    {
        $prenom = 'John';
        $nom = 'Doe';

        $mailer->send(
            'no-reply@monsite.net',
            'destinataire-test@monsite.net', // Mettez une adresse email de votre choix
            'Titre de mon message de test',
            'test',
            [
                'prenom' => $prenom,
                'nom' => $nom
            ]
        );

        $this->addFlash('success', 'Email de test envoyé ! Vérifiez votre interface MailHog.');

        return $this->redirectToRoute('app_recette_index');
    }
}
