<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class SendMailService
{
    private MailerInterface $mailer;
    private UserRepository $userRepository;

    public function __construct(MailerInterface $mailer, UserRepository $userRepository)
    {
        $this->mailer = $mailer;
        $this->userRepository = $userRepository;
    }

    /**
     * Envoie un email formaté avec un template Twig.
     *
     * @param string $from Expéditeur
     * @param string $to Destinataire
     * @param string $subject Sujet du mail
     * @param string $template Nom du fichier de template (sans l'extension .html.twig)
     * @param array $context Données à passer au template
     */
    public function send(
        string $from,
        string $to,
        string $subject,
        string $template,
        array $context
    ): void {
        $email = (new TemplatedEmail())
            ->from($from)
            ->to($to)
            ->subject($subject)
            ->htmlTemplate("emails/$template.html.twig")
            ->context($context);

        // On envoie le mail
        $this->mailer->send($email);
    }

    /**
     * Envoie un email de bienvenue à un utilisateur qui vient de se connecter.
     */
    public function dire_bonjour(User $user): void
    {
        $this->send(
            'no-reply@monsite.net',
            $user->getEmail(),
            'Connexion détectée sur votre compte',
            'connexion', // Fait référence à templates/emails/connexion.html.twig
            [
                'user' => $user,
            ]
        );
    }

    /**
     * Envoie un email à tous les utilisateurs.
     */
    public function dire_bonjour_a_tous(): void
    {
        $users = $this->userRepository->findAll();

        foreach ($users as $user) {
            $this->send(
                'no-reply@monsite.net',
                $user->getEmail(),
                'Message important à tous nos utilisateurs',
                'bonjour_a_tous', // Fait référence à templates/emails/bonjour_a_tous.html.twig
                [
                    'user' => $user,
                ]
            );
        }
    }
}
