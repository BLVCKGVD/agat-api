<?php

namespace App\Controller;

use App\Entity\UserLogs;
use App\Entity\Users;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExitController extends AbstractController
{
    #[Route('/exit', name: 'exit')]
    public function index(): Response
    {
        $userLogs = new UserLogs();
        $userLogs->setEmployee(
            $this->getDoctrine()->getRepository(Users::class)->findOneBy([
                'login'=>$_COOKIE['login']
            ])
        )
            ->setAction(
                "Вышел из системы")
            ->setStatus('secondary')
            ->setDate(new \DateTime());
        $this->getDoctrine()->getManager()->persist($userLogs);
        $this->getDoctrine()->getManager()->flush();
        setcookie('login',null,time()-3600);
        setcookie('password',null,time()-3600);
        setcookie('role',null,time()-3600);
        $this->addFlash('success', "Вы успешно вышли из системы");
        return $this->redirectToRoute('authtorization');
    }
}
