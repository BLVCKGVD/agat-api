<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Users;
use App\Repository\UsersRepository;
use App\Form\UsersType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;

class EmployeesPageController extends AbstractController
{
    public function auth(ManagerRegistry $doctrine,Request $request): Response
    {
        //  $entityManager = $this->getDoctrine()->getManager();
        //  $user = new Users();
        //  $user->setFio('Шульженко Антон');
        //  $pass = password_hash("Anton75669", PASSWORD_DEFAULT);
        //  $user->setPassword($pass);
        //  $user->setLogin('anton');
        //  print($pass);
        //  $user->setRole("user");
        //  $entityManager->persist($user);

        //  $entityManager->flush();
        $user = new Users();
        $form = $this->createForm(UsersType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            $repository = $doctrine->getRepository(Users::class);
            
            $found = $repository->findOneBy([
                'login' => $user->getLogin(),
            ]);
            if($found!=null){
            $pass_hash = $found->getPassword(); 
            if(password_verify($user->getPassword(), $pass_hash))
            {
            print 'Password verified';
            
            setcookie("login", $user->getLogin(), time()+3600*24*14);
            setcookie("password", $user->getPassword(), time()+3600*24*14);
            setcookie("role", $found->getRole(), time()+3600*24*14);
            return $this->redirect('/employees');
            } else{
                return $this->render('employees_page/authtorization.html.twig', [
                    'controller_name' => 'EmployeesPageController',
                    'form' => $form->createView(),
                    'error' => 'Неверный логин или пароль'
                ]);
            }
            } else{
                return $this->render('employees_page/authtorization.html.twig', [
                    'controller_name' => 'EmployeesPageController',
                    'form' => $form->createView(),
                    'error' => 'Неверный логин или пароль'
                ]);
            }
            }
            
            
             
    
    return $this->render('employees_page/authtorization.html.twig', [
        'controller_name' => 'EmployeesPageController',
        'form' => $form->createView(),
        'error' => '',
        'auth' => true,
    ]);
    
    }
    public function index(ManagerRegistry $doctrine): Response
    {   
        if(isset($_COOKIE['login']) && $_COOKIE['login']!='' && isset($_COOKIE['password']) && $_COOKIE['password']!='')
        {   
            $user = new Users();
            $repository = $doctrine->getRepository(Users::class);
            
            $found = $repository->findOneBy([
                'login' => $_COOKIE['login'],
            ]);
            $pass_hash = $found->getPassword(); 

            
            
            if(password_verify($_COOKIE['password'], $pass_hash))
            {
                
                return $this->render('employees_page/index.html.twig', [
                    'controller_name' => 'EmployeesPageController',
                ]);
            }
            
    } return $this->redirect('/auth');
    
    
}

}