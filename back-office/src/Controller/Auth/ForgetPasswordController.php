<?php

namespace App\Controller\Auth;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ForgetPasswordController extends AbstractController
{


    public function forgotPassword(): Response
    {
        return $this->render('');
    }
}
