<?php

namespace App\Mailing;

use App\Entity\User;
use App\Entity\Utilisateur;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class AuthMailing
{

    public function __construct(
        private MailerInterface $mailer
    ) {
    }

    public function confirmEmail(Utilisateur $user): void
    {
        $email = (new TemplatedEmail)
            ->from('contact@agathefrederick.fr')
            ->to($user->getEmail())
            ->subject('Confirmation de votre compte')
            ->htmlTemplate('emails/auth/confirm.html.twig')
            ->context(compact('user'));

        try {
            $this->mailer->send($email);
            return;
        } catch (TransportExceptionInterface $e) {
            throw new TransportException("Something went wrong while sending this email : {$e->getMessage()}");
        }

        return;
    }
}
