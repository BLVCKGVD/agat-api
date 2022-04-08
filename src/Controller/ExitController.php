<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExitController extends AbstractController
{
    #[Route('/exit', name: 'exit')]
    public function index(): Response
    {
        setcookie('login',null,time()-3600);
        setcookie('password',null,time()-3600);
        setcookie('role',null,time()-3600);
        return $this->redirect('/login');
    }
}
