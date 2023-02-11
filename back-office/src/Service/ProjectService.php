<?php 
namespace App\Service;

use DateTimeImmutable;
use App\Entity\Project;
use Cocur\Slugify\Slugify;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Symfony\Component\Form\FormInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class ProjectService 
{

    private mixed $slugger;
    private mixed $session;

    public function __construct(
        private EntityManagerInterface $manager, 
        private PaginatorInterface $paginator,
        private ProjectRepository $repository,
        private ParameterBagInterface $parameterBag,
        private Filesystem $filesystem, 
        private UrlGeneratorInterface $router
    ){
        $this->slugger = new Slugify;
        $this->session = new Session;
    }

        
    /**
     * search projects in project index page 
     *
     * @param  mixed $request
     * @return array
     */
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

    /**
     * store project in database
     * 
     * @param FormInterface $form
     * @param Project $project
     *
     * @return void
     */    
    public function store(FormInterface $form, Project $project): void
    {
        $now = new DateTimeImmutable;
        $image = $form->get('uploadedFile')->getData();
        $project->getId() !== null ? $project->setUpdatedAt($now) : $project->setCreatedAt($now);

        $project->setSlug(
            $this->slugger->slugify(
                $project->getSlug() ?? $project->getName(),
                '-'
            )
        );

        if ($image instanceof UploadedFile) {

            $filename = md5(uniqid()) . '.' . $image->guessExtension();

            $image->move(
                $this->parameterBag->get('project_directory'),
                $filename
            );

            $this->deleteImage($project);

            $project->setImage('/uploads/project/' . $filename);
        }
        
        try {
            $this->session->getFlashBag()->add(
                'info',
                'L\'article a été enregistré'
            );

            $this->manager->persist($project);
            $this->manager->flush();
        } catch (ORMException $e) {
            $this->session->getFlashBag()->add(
                'danger',
                $e->getMessage()
            );
        }
    }

    /**
     * delete project image
     * 
     * @param Project $project
     *
     * @return void
     */
    private function deleteImage(Project $project): void
    {
        if ($project->getImage() !== null) {
            $file = $this->parameterBag->get('root_directory') . $project->getImage();
            if ($this->filesystem->exists($file)) {
                $this->filesystem->remove($file);
            }
        }
    }
    
    /**
     * publish project
     *
     * @param  mixed $project
     * @return void
     */
    public function publish(Project $project):void 
    {
        $now = new DateTimeImmutable;
        $project->setPublishedAt($now)
            ->setUpdatedAt($now)
            ->setState('published');
            
        try {
            $this->session->getFlashBag()->add(
                'info',
                'L\'article a été enregistré'
            );

            $this->manager->persist($project);
            $this->manager->flush();
        } catch (ORMException $e) {
            $this->session->getFlashBag()->add(
                'danger',
                $e->getMessage()
            );
        }

        return;         
    }

    
    /**
     * delete project
     *
     * @param  mixed $project
     * @param  mixed $request
     * 
     * @return string
     */
    public function delete(Project $project, Request $request):string 
    {
        $referer = $request->headers->get('referer', $this->router->generate('dashboard_project_index'));
        
        $this->deleteImage($project);
        
        $this->manager->remove($project);
        $this->manager->flush();

        $this->session->getFlashBag()->add(
            'info',
            'Le projet a bien été supprimé'
        );

        return $referer;
    }

}