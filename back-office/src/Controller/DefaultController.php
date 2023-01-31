<?php

namespace App\Controller;

use App\Repository\ProjectRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('', name: 'app_default')]
class DefaultController extends AbstractController
{

    public function __construct(
        private ProjectRepository $repository
    ) {} 

    #[Route('', name: '', methods: ['GET'])]
    public function default(): Response
    {
        $projects = $this->repository->latest();

        return $this->render('home/index.html.twig', compact('projects'));
    }
}
