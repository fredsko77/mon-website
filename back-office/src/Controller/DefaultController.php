<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('', name: 'app_default')]
class DefaultController extends AbstractController
{

    #[Route('', name: '', methods: ['GET'])]
    public function default(): Response
    {   
        // if() {
            
        // }
        return $this->render('home/index.html.twig');
    }
}
