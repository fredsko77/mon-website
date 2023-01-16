<?php

namespace App\Controller\Auth;

use App\Entity\Utilisateur;
use App\Form\Auth\InscriptionType;
use App\Mailing\AuthMailing;
use App\Service\UtilisateurService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/auth/inscription', name: 'auth_inscription')]
class InscriptionController extends AbstractController
{

    public function __construct(
        private UtilisateurService $service,
        private AuthMailing $mailing
    ) {
    }

    #[Route('', name: '', methods: ['POST', 'GET'])]
    public function inscription(Request $request): Response
    {
        if ($this->getUser()) {

            return $this->redirectToRoute('admin');
        }

        $user = new Utilisateur;
        $form = $this->createForm(InscriptionType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setRoles(['ROLE_UTILISATEUR']);
            $this->service->store($user);
            $this->mailing->confirmEmail($user);

            $this->addFlash(
                'success',
                'Votre compte a bien été crée'
            );

            return $this->redirectToRoute('auth_inscription');
        }

        return $this->renderForm('auth/inscription.html.twig', compact('form', 'user'));
    }

    #[Route('/{token}', name: '_confirm', methods: ['GET'])]
    public function inscriptionConfirm(): Response
    {
        
        return $this->render('', compact(''));
    }
}