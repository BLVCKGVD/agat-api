<?php

namespace App\Controller;

use App\Entity\UserLogs;
use App\Entity\Users;

use App\Form\AddUserType;
use App\Form\ChangePassType;
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

    public function index(Request $request): Response
    {
        if (isset($_COOKIE['role']) && ($_COOKIE['role']!= 'admin' && $_COOKIE['role']!= 'superadmin')) {
            return $this->redirectToRoute('employees_page');
        }
        $searchfor = $request->query->get('searchfor');
        if ($searchfor)
        {
            $users = $this->getDoctrine()->getManager()
                ->getRepository(Users::class)
                ->createQueryBuilder('o')
                ->where('LOWER(o.FIO) LIKE LOWER(:searchfor)')
                ->orWhere('LOWER(o.login) LIKE LOWER(:searchfor)')
                ->orWhere('LOWER(o.role) LIKE LOWER(:searchfor)')
                ->setParameter('searchfor', '%'.$searchfor.'%')
                ->getQuery()
                ->getResult();
        } else {
            $users = $this->getDoctrine()->getRepository(Users::class)->findAll();
        }

        return $this->render('users/index.html.twig', [
            'controller_name' => 'UsersCntroller',
            'users' => $users,
            'login' => $_COOKIE['login'],
            'role' => $_COOKIE['role'],
            'searchfor' => $searchfor,
        ]);
    }
    public function profile(Request $request,$id)
    {
        if (isset($_COOKIE['role']) && ($_COOKIE['role']!= 'admin' && $_COOKIE['role']!= 'superadmin')) {
            return $this->redirectToRoute('employees_page');
        }
        $user = $this->getDoctrine()->getRepository(Users::class)->find($id);
        $logs = $this->entityManager->getRepository(UserLogs::class)->findBy([
            'employee' => $user->getId()
        ]);
        $form = $this->createForm(ChangePassType::class);
        $form->handleRequest($request);
        $errors = array();
        $error = false;
        if ($form->isSubmitted())
        {
            if ($form->isValid())
            {
                if ($_COOKIE['login'] == $user->getLogin()){
                    if (password_verify($form->get('pass_old')->getData(), $user->getPassword()))
                    {
                        if ($form->get('pass_new')->getData() == $form->get('pass_new_confirm')->getData())
                        {
                            $user->setPassword(password_hash($form->get('pass_new')->getData(),PASSWORD_DEFAULT));
                            $this->getDoctrine()->getManager()->persist($user);
                            $this->getDoctrine()->getManager()->flush();
                            $this->addFlash('success','Вы успешно сменили свой пароль');
                            return $this->redirectToRoute('exit');
                        } else {
                            array_push($errors,"Повторенный пароль не совпадает с новым");
                            $error = true;
                        }
                    } else{
                        array_push($errors,"Старый пароль введен неверно");
                        $error = true;
                    }
                } else {
                    if ($form->get('pass_new')->getData() == $form->get('pass_new_confirm')->getData())
                    {
                        $user->setPassword(password_hash($form->get('pass_new')->getData(),PASSWORD_DEFAULT));
                        $this->getDoctrine()->getManager()->persist($user);
                        $this->getDoctrine()->getManager()->flush();
                        $this->addFlash('success','Вы успешно сменили пароль пользователю '.$user->getLogin());
                    } else {
                        array_push($errors,"Повторенный пароль не совпадает с новым");
                        $error = true;
                    }
                }
            } else {
                $error = true;
            }
        }
        return $this->render('users/profile.html.twig', [
            'controller_name' => 'UsersController',
            'user' => $user,
            'login' => $_COOKIE['login'],
            'role' => $_COOKIE['role'],
            'logs' => $logs,
            'form'=>$form->createView(),
            'error'=>$error,
            'errors'=>$errors,
        ]);
    }
    public function delLogs($id)
    {
        if (isset($_COOKIE['role']) && ($_COOKIE['role']!= 'admin' && $_COOKIE['role']!= 'superadmin')) {
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
    public function create(Request $request)
    {
        if (isset($_COOKIE['role']) && ($_COOKIE['role']!= 'admin' && $_COOKIE['role']!= 'superadmin')) {
            return $this->redirectToRoute('employees_page');
        }
        $user = new Users();
        $form = $this->createForm(AddUserType::class,$user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            if ($this->getDoctrine()->getRepository(Users::class)->findBy([
                'login'=>$form->get('login')->getData(),
            ]) != null)
            {
                return $this->render('users/create.html.twig', [
                    'controller_name' => 'UsersController',
                    'form' => $form->createView(),
                    'login_error'=>"Пользователь с таким логином существует",
                    'error'=>"",
                    'login' => $_COOKIE['login'],
                    'role' => $_COOKIE['role'],
                ]);
            }
            if($form->get('password')->getData() != $form->get('password2')->getData())
            {
                return $this->render('users/create.html.twig', [
                    'controller_name' => 'UsersController',
                    'form' => $form->createView(),
                    'login_error'=>"",
                    'error'=>"Пароли не совпадают",
                    'login' => $_COOKIE['login'],
                    'role' => $_COOKIE['role'],
                    ]);
            }

            $user->setPassword(password_hash($form->get('password')->getData(), PASSWORD_DEFAULT))
                ->setFIO($form->get('FIO')->getData())
                ->setLogin($form->get('login')->getData());
            if ($form->get('roles')->getData()){
                $user->setRole($form->get('roles')->getData());
            } else {
                $user->setRole('user');
            }
            $this->getDoctrine()->getManager()->persist($user);
            $userLogs = new UserLogs();
            $userLogs->setEmployee(
                $this->getDoctrine()->getRepository(Users::class)->findOneBy([
                    'login'=>$_COOKIE['login']
                ])
            )
                ->setAction(
                    "Добавил пользователя")
                ->setEmployeeAdd($user)
                ->setFIO($user->getFIO())
                ->setDate(new \DateTime());
            $this->getDoctrine()->getManager()->persist($userLogs);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('users');


        }
        return $this->render('users/create.html.twig', [
            'controller_name' => 'UsersController',
            'form' => $form->createView(),
            'error'=>"",
            'login_error'=>"",
            'login' => $_COOKIE['login'],
            'role' => $_COOKIE['role'],

        ]);

    }
    public function delete($id)
    {
        $em = $this->getDoctrine()->getManager();
        if (isset($_COOKIE['role']) && ($_COOKIE['role'] == 'admin' || $_COOKIE['role']== 'superadmin') && isset($_COOKIE['login']))
        {
            $user = $em->getRepository(Users::class)->find($id);
            if ($user->getLogin() == $_COOKIE['login'])
            {
                return $this->redirectToRoute('users');
            }
            if($user->getRole() == 'admin' && ($_COOKIE['role'] == 'admin' || $_COOKIE['role']== 'superadmin'))
            {
                if ($_COOKIE['role'] == 'superadmin')
                {
                    $em->remove($user);
                    $userLogs = new UserLogs();
                    $userLogs->setEmployee(
                        $this->getDoctrine()->getRepository(Users::class)->findOneBy([
                            'login'=>$_COOKIE['login']
                        ])
                    )
                        ->setAction(
                            "Удалил пользователя ".$user->getFIO())
                        ->setDate(new \DateTime());
                    $this->getDoctrine()->getManager()->persist($userLogs);
                    $em->flush();
                    return $this->redirectToRoute('users');
                } else {return $this->redirectToRoute('users');}
            }
            if ($user->getRole() == 'user')
            {
                $em->remove($user);
                $userLogs = new UserLogs();
                $userLogs->setEmployee(
                    $this->getDoctrine()->getRepository(Users::class)->findOneBy([
                        'login'=>$_COOKIE['login']
                    ])
                )
                    ->setAction(
                        "Удалил пользователя ".$user->getFIO())
                    ->setDate(new \DateTime());
                $this->getDoctrine()->getManager()->persist($userLogs);
                $em->flush();
                return $this->redirectToRoute('users');
            } else {return $this->redirectToRoute('users');}
        } else {return $this->redirectToRoute('users');}
    }
}
