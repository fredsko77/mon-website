<?php
namespace App\Controller\Website;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/mes-realisations', name: 'website_project_')]
class ProjectController extends AbstractController 
{

    #[Route('/{slug}-{id}', name: 'show', requirements: ['id' => '\d+', 'slug' => '[a-z0-9\-]*'], methods: ['GET'])]
    public function show():Response 
    {
        return $this->render('');
    }

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(): Response 
    {
        return $this->render('');
    }

}