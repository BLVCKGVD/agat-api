<?php

namespace App\Controller;

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
}
