<?php

namespace App\Controller;

use App\Entity\UserLogs;
use App\Entity\Users;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UsersController extends AbstractController
{

    public function __construct(EntityManagerInterface $em)
    {
        $this->entityManager = $em;
    }

    public function index(): Response
    {
        if (isset($_COOKIE['role']) && $_COOKIE['role']!= 'admin') {
            return $this->redirectToRoute('employees_page');
        }
        $users = $this->getDoctrine()->getRepository(Users::class)->findAll();
        return $this->render('users/index.html.twig', [
            'controller_name' => 'UsersCntroller',
            'users' => $users,
            'login' => $_COOKIE['login'],
            'role' => $_COOKIE['role'],
        ]);
    }
    public function get($id): Response
    {
        if (isset($_COOKIE['role']) && $_COOKIE['role']!= 'admin') {
            return $this->redirectToRoute('employees_page');
        }
        $user = $this->getDoctrine()->getRepository(Users::class)->find($id);
        $logs = $this->entityManager->getRepository(UserLogs::class)->findBy([
            'employee' => $user->getId()
        ]);
        return $this->render('users/profile.html.twig', [
            'controller_name' => 'UsersController',
            'user' => $user,
            'login' => $_COOKIE['login'],
            'role' => $_COOKIE['role'],
            'logs' => $logs,
        ]);
    }
    public function delLogs($id)
    {
        if (isset($_COOKIE['role']) && $_COOKIE['role']!= 'admin') {
            return $this->redirectToRoute('employees_page');
        }
        $user = $this->getDoctrine()->getRepository(Users::class)->find($id);
        $logs = $this->entityManager->getRepository(UserLogs::class)->findBy([
            'employee' => $user->getId()
        ]);
        for ($i = 0; $i<count($logs); $i++)
        {
            $this->entityManager->remove($logs[$i]);
        }
        $this->entityManager->flush();
        return $this->redirect('/users/'.$user->getId());
    }
}
