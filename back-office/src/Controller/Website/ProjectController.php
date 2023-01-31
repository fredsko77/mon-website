<?php
namespace App\Controller\Website;

use App\Service\ProjectService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

#[Route('/mes-realisations', name: 'website_project_')]
class ProjectController extends AbstractController 
{

    public function __construct(
        private ProjectService $service
    ) {}

    #[Route('/{slug}-{id}', name: 'show', requirements: ['id' => '\d+', 'slug' => '[a-z0-9\-]*'], methods: ['GET'])]
    public function show():Response 
    {
        return $this->render('');
    }

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(Request $request): Response 
    {
        return $this->render('website/project/index.html.twig', $this->service->search($request));
    }

}