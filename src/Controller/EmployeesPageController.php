<?php

namespace App\Controller;

use App\Entity\Aircraft;
use App\Entity\AircraftOperating;
use App\Entity\EmailSubsription;
use App\Entity\Favourites;
use App\Entity\Parts;
use App\Entity\PartsOperating;
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
                $this->addFlash('success', "Вы успешно вошли в систему");
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

            $fav = $this->getDoctrine()->getRepository(Favourites::class)->findBy([
                'idUser'=>$found->getId()
            ]);
            foreach ($fav as $f)
            {
                $f->getIdAircraft()->setStatus($this->getAircraftStatus($f->getIdAircraft()));
            }
            if(password_verify($_COOKIE['password'], $pass_hash))
            {
                if ($request->query->get('mail') != null)
                {
                    $check_mail = $this->getDoctrine()->getRepository(EmailSubsription::class)->findOneBy([
                        'email'=> $request->query->get('mail')
                    ]);
                    if ($check_mail != null)
                    {
                        return $this->render('employees_page/index.html.twig', [
                            'controller_name' => 'EmployeesPageController',
                            'FIO' => $_COOKIE['FIO'],
                            'login' => $_COOKIE['login'],
                            'role' => $_COOKIE['role'],
                            'fav'=>$fav,
                            'mail'=>null,
                            'id_mail'=>null,
                            'error'=>"Данная почта уже привязана"
                        ]);
                    }
                    $email = new EmailSubsription();
                    $email->setEmail($request->query->get('mail'))
                        ->setSubUser($found);
                    $this->getDoctrine()->getManager()->persist($email);
                    $this->getDoctrine()->getManager()->flush();
                    $this->addFlash("success", "Почта успешно привязана");
                    if($request->query->get('test') == 'on')
                    {
                        $email = (new Email())
                            ->from('avia-agat@yandex.ru')
                            ->to($request->query->get('mail'))
                            ->subject('Добро пожаловать!');
                        $email->html('Добро пожаловать в систему рассылки информации по воздушным судам авиакомпании "Агат".<br> '.
                            $found->getFIO().', удачного использования системы. <br>Не забудьте прочитать руководство');
                        $mailer->send($email);
                        $this->addFlash("success", "Тестовое письмо отправлено");
                    }
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
                    'fav'=>$fav,
                    'error'=>''
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
                    ->from('avia-agat@yandex.ru')
                    ->to($mail->getEmail())
                    ->subject('Отписка от рассылки');
                $email->html('Вы отписались от рассылки');
                $mailer->send($email);
                $this->addFlash("success", "Вы успешно отвязались от почтовой рассылки");
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
        if (isset($_COOKIE['role']) && ($_COOKIE['role'] == 'admin' || $_COOKIE['role'] == 'superadmin'))
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

    public function getPartStatus(Parts $part)
    {
        $em = $this->getDoctrine()->getManager();
        $today = new \DateTime();
        $operating = $em->getRepository(PartsOperating::class)->getLastPartsOperating($part);
        if($operating->getOverhaulRes() >= $part->getOverhaulRes()*0.8 ||
            $operating->getOverhaulRes() >= $part->getOverhaulRes() ||
            $operating->getTotalRes() >= $part->getAssignedRes()*0.8 ||
            $operating->getTotalRes() >= $part->getAssignedRes() ||
            \DateTime::createFromInterface($part->getAssignedExpDate()) < $today||
            \DateTime::createFromInterface($part->getOverhaulExpDate()) < $today)
        {
            return "danger";
        } return null;
    }

    public function getAircraftStatus(Aircraft $aircraft)
    {
        $em = $this->getDoctrine()->getManager();
        $today = new \DateTime();
        $operating = $em->getRepository(AircraftOperating::class)->getLastAircraftOperating($aircraft);
        $overhaul_diff = $aircraft->getOverhaulExpDate()->diff(new \DateTime())->format("%a");
        $assigned_diff = $aircraft->getAssignedExpDate()->diff(new \DateTime())->format("%a");
        $lg_diff = $aircraft->getLgExpDate()->diff(new \DateTime())->format("%a");
        if($operating->getOverhaulRes() >= $aircraft->getOverhaulRes()*0.8 ||
            $operating->getOverhaulRes() >= $aircraft->getOverhaulRes() ||
            $operating->getTotalRes() >= $aircraft->getAssignedRes()*0.8 ||
            $operating->getTotalRes() >= $aircraft->getAssignedRes() ||
            $overhaul_diff <= 30 || $assigned_diff <= 30 || $lg_diff <= 30 ||
            \DateTime::createFromInterface($aircraft->getOverhaulExpDate()) < $today ||
            \DateTime::createFromInterface($aircraft->getAssignedExpDate()) < $today)
        {
            return "danger";
        }
        $parts = $aircraft->getParts();
        if ($parts != null)
        {
            foreach ($parts as $part)
            {
                $part->setStatus($this->getPartStatus($part));
                if ($part->getStatus()=='danger')
                {
                    return "danger";
                }
            }
            return null;
        }
        return null;


    }

}
