<?php 
namespace App\Controller\Dashboard;

use App\Entity\Project;
use App\Service\ProjectService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

#[Route('/dashboard/project', name: 'dashboard_project_')]
class ProjectController extends AbstractController
{

    public function __construct(
        private ProjectService $service
    )
    {}

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(Request $request):Response 
    {
        return $this->render('dashboard/project/index.html.twig', $this->service->search($request));
    }

    #[Route('/nouveau', name: 'new', methods: ['GET', 'POST'])]
    public function nouveau(Request $request):Response 
    {
        
        return $this->renderForm('');
    }

    #[Route('/editer/{id}', name: 'edit', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    public function edit(Project $project, Request $request):Response 
    {
        

        return $this->renderForm('dashboard/project/edit.html.twig', compact('project'));
    }

    #[Route('/delete/{id}', name: 'delete', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function delete(Project $project):Response 
    {   

        return $this->redirectToRoute('dashboard_project_index');
    }

}