<?php
namespace App\Controller\Website;

use App\Entity\Project;
use App\Repository\ProjectRepository;
use App\Service\ProjectService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

#[Route('/mes-realisations', name: 'website_project_')]
class ProjectController extends AbstractController 
{

    public function __construct(
        private ProjectService $service,
        private ProjectRepository $repository
    ) {}

    #[Route('/{slug}-{id}', name: 'show', requirements: ['id' => '\d+', 'slug' => '[a-z0-9\-]*'], methods: ['GET'])]
    public function show(?int $id, ?string $slug):Response 
    {
        $project = $this->repository->find($id);

        if($project instanceof Project) {
            if ($slug !== $project->getSlug()) {
                return $this->redirectToRoute('website_project_show', [
                    'id' => $project->getId(),
                    'slug' => $project->getSlug()
                ]);
            }

            return $this->render('website/project/show.html.twig', compact('project'));
        }

        return new Response('Project not found', Response::HTTP_NOT_FOUND);
    }

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(Request $request): Response 
    {
        return $this->render('website/project/index.html.twig', $this->service->search($request));
    }

}