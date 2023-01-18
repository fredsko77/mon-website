<?php 
namespace App\Controller\Dashboard;

use App\Entity\Project;
use App\Service\ProjectService;
use App\Form\Dashboard\ProjectType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/dashboard/project', name: 'dashboard_project_')]
class ProjectController extends AbstractController
{

    public function __construct(
        private ProjectService $service
    ){}

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(Request $request):Response 
    {
        return $this->render('dashboard/project/index.html.twig', $this->service->search($request));
    }

    #[Route('/publish/{id}', name: 'publish', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function publish(Project $project):RedirectResponse 
    {
        $this->service->publish($project);

        return $this->redirectToRoute('dashboard_project_edit', ['id' => $project->getId()]);
    }

    #[Route('/nouveau', name: 'new', methods: ['GET', 'POST'])]
    public function nouveau(Request $request):Response 
    {
        $project = new Project;
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->service->store($form, $project);

            return $this->redirectToRoute('dashboard_project_edit', [
                'id' => $project->getId(),
            ]);
        }

        return $this->renderForm("dashboard/project/new.html.twig", [
            'form' => $form,
            'project' => $project,
        ]);
    }

    #[Route('/editer/{id}', name: 'edit', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    public function edit(Project $project, Request $request):Response 
    {
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->service->store($form, $project);

            return $this->redirectToRoute('dashboard_project_edit', [
                'id' => $project->getId(),
            ]);
        }

        return $this->renderForm("dashboard/project/edit.html.twig", [
            'form' => $form,
            'project' => $project,
        ]);
    }

    #[Route('/delete/{id}', name: 'delete', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function delete(Project $project):Response 
    {   

        return $this->redirectToRoute('dashboard_project_index');
    }

}