<?php

namespace App\Controller;

use App\Entity\EmailSubsription;
use App\Entity\UserLogs;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Users;
use App\Repository\UsersRepository;
use App\Form\UsersType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;

class EmployeesPageController extends AbstractController
{
    public function auth(ManagerRegistry $doctrine,Request $request, MailerInterface $mailer): Response
    {

//          $entityManager = $this->getDoctrine()->getManager();
//          $user = new Users();
//          $user->setFio('Шульженко Антон');
//          $pass = password_hash("Anton75669", PASSWORD_DEFAULT);
//          $user->setPassword($pass);
//          $user->setLogin('angen');
//          print($pass);
//          $user->setRole("admin");
//          $entityManager->persist($user);
//
//          $entityManager->flush();
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
            setcookie("FIO", $found->getFIO(), time()+3600*24*14);
                $userLogs = new UserLogs();
                $userLogs->setEmployee(
                    $this->getDoctrine()->getRepository(Users::class)->findOneBy([
                        'login'=>$found->getLogin()
                    ])
                )
                    ->setAction(
                        "Вошел в систему")
                    ->setStatus('secondary')
                    ->setDate(new \DateTime());
                $this->getDoctrine()->getManager()->persist($userLogs);
                $this->getDoctrine()->getManager()->flush();
                if($request->get('ac')!=null)
                {
                    return $this->redirect('/aircrafts/'.$request->get('ac'));
                }
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
    public function index(ManagerRegistry $doctrine, Request $request, MailerInterface $mailer): Response
    {   
        if(isset($_COOKIE['login']) && $_COOKIE['login']!='' && isset($_COOKIE['password']) && $_COOKIE['password']!='')
        {   
            $user = new Users();
            $repository = $doctrine->getRepository(Users::class);
            
            $found = $repository->findOneBy([
                'login' => $_COOKIE['login'],
            ]);
            if ($found==null)
            {
                return $this->redirectToRoute('authtorization');
            }
            $pass_hash = $found->getPassword(); 

            
            
            if(password_verify($_COOKIE['password'], $pass_hash))
            {
                if ($request->query->get('mail') != null)
                {
                    $email = new EmailSubsription();
                    $email->setEmail($request->query->get('mail'))
                        ->setSubUser($found);
                    $this->getDoctrine()->getManager()->persist($email);
                    $this->getDoctrine()->getManager()->flush();
                    if($request->query->get('test') == 'on')
                    $email = (new Email())
                        ->from('agataviainfo@gmail.com')
                        ->to($request->query->get('mail'))
                        ->subject('Добро пожаловать!');
                    $email->html('Добро пожаловать в систему рассылки информации по воздушным судам авиакомпании "Агат".<br> '.
                    $found->getFIO().', удачного использования системы. <br>Не забудьте прочитать руководство');
                    $mailer->send($email);
                    return $this->redirect('/employees');
                }
                if ($found->getEmailSubsription() == null)
                {
                    $mail = null;
                    $mail_id = null;
                } else {
                    $mail = $found->getEmailSubsription()->getEmail();
                    $mail_id = $found->getEmailSubsription()->getId();
                }
                
                return $this->render('employees_page/index.html.twig', [
                    'controller_name' => 'EmployeesPageController',
                    'FIO' => $_COOKIE['FIO'],
                    'login' => $_COOKIE['login'],
                    'role' => $_COOKIE['role'],
                    'mail'=>$mail,
                    'mail_id'=>$mail_id,
                ]);
            }
            
    } return $this->redirectToRoute('authtorization');

    
    
}
    public function delete(MailerInterface $mailer)
    {
        $em = $this->getDoctrine()->getManager();
        if (isset($_COOKIE['login']))
        {
            $user = $em->getRepository(Users::class)->findOneBy([
                'login'=>$_COOKIE['login'],
            ]);
            $mail = $user->getEmailSubsription();
            if($mail!=null){
                $em->remove($mail);
                $em->flush();
                $email = (new Email())
                    ->from('agataviainfo@gmail.com')
                    ->to($mail->getEmail())
                    ->subject('Добро пожаловать!');
                $email->html('Вы отписались от рассылки');
                $mailer->send($email);
                return $this->redirect('/employees');
            }
            return $this->redirect('/employees');
        }
        return $this->redirect('/employees');


    }
    public function user()
    {
        if (isset($_COOKIE['role']))
        {
            return $this->render('instructions/user.html.twig',
            [
                'role' => $_COOKIE['role'],
                'login' => $_COOKIE['login'],
            ]);
        } else {
            return $this->redirectToRoute('employees_page');
        }

    }
    public function admin()
    {
        if (isset($_COOKIE['role']) && $_COOKIE['role'] == 'admin')
        {
            return $this->render('instructions/admin.html.twig',
                [
                    'role' => $_COOKIE['role'],
                    'login' => $_COOKIE['login'],
                ]);
        } else {
            return $this->redirectToRoute('employees_page');
        }
    }

}
