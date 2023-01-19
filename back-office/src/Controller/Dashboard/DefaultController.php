<?php 
namespace App\Controller\Dashboard;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/dashboard', name: 'dashboard_default')]
class DefaultController extends AbstractController
{

    #[Route('', name:'', methods: ['GET'])]
    public function default(): Response
    {
        return $this->render('dashboard/index.html.twig', []);
    }

}