<?php 
namespace App\Service;

use App\Entity\Project;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

final class ProjectService 
{

    public function __construct(
        private EntityManagerInterface $manager, 
        private PaginatorInterface $paginator,
        private ProjectRepository $repository 
    ){}


    public function store (Project $project):void 
    {
        return;
    }

    public function search (Request $request) :?array
    {
        // Init request values 
        $query = $request->query->get('q', null);
        $page = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('nbItem', 15);

        // Data to paginate
        $data = $this->repository->search($query);

        // List of projects
        $projects = $this->paginator->paginate(
            $data, 
            $page,
            $limit
        );

        return compact('projects');
    }


}