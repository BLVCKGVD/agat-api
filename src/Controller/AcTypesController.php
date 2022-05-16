<?php

namespace App\Controller;

use App\Entity\AcTypes;
use App\Entity\Aircraft;
use App\Entity\UserLogs;
use App\Entity\Users;
use App\Form\addAcType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AcTypesController extends AbstractController
{

    public function create(Request $request): Response
    {
        if (!isset($_COOKIE['role']) || ($_COOKIE['role'] != 'admin' && $_COOKIE['role']!= 'superadmin'))
        {
            return $this->redirectToRoute('employees_page');
        }
        $all_types = new AcTypes();
        $all_types = $this->getDoctrine()->getManager()->getRepository(AcTypes::class)->findAll();
        $ac_type = new AcTypes();
        $form = $this->createForm(addAcType::class,$ac_type);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $new_type = new AcTypes();
            $new_type->setType($form->get('type')->getData())
                ->setCategory($form->get('category')->getData())
                ->setEngCount($form->get('eng_count')->getData())
                ->setMrRes($form->get('mr_res')->getData())
                ->setMrMonth($form->get('mr_month')->getData());
            $this->getDoctrine()->getManager()->persist($new_type);
            $userLogs = new UserLogs();
            $userLogs->setEmployee(
                $this->getDoctrine()->getRepository(Users::class)->findOneBy([
                    'login'=>$_COOKIE['login']
                ])
            )
                ->setAction(
                    "Создал новый тип судна: ".$form->get('type')->getData())
                ->setDate(new \DateTime());
            $this->getDoctrine()->getManager()->persist($userLogs);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', "Тип ".$new_type->getType()." успешно создан");
            return $this->redirectToRoute('employees_page');
        }
        return $this->render('aircraft/addType.html.twig',[
            'form' => $form->createView(),
            'login' => $_COOKIE['login'],
            'role' => $_COOKIE['role'],
            'types' => $all_types,
        ]);

    }
    public function delete($id){
        if (!isset($_COOKIE['role']) || $_COOKIE['role'] != 'admin' && $_COOKIE['role']!='superadmin')
        {
            $ac_type = $this->getDoctrine()->getManager()->getRepository(AcTypes::class)->find($id);
            $aircrafts = $this->getDoctrine()->getManager()->getRepository(Aircraft::class)->findBy([
                'ac_type'=>$ac_type->getType(),
            ]);
            $this->getDoctrine()->getManager()->remove($ac_type);
            foreach ($aircrafts as $a)
            {
                $this->getDoctrine()->getManager()->remove($a);
            }
            $userLogs = new UserLogs();
            $userLogs->setEmployee(
                $this->getDoctrine()->getRepository(Users::class)->findOneBy([
                    'login'=>$_COOKIE['login']
                ])
            )
                ->setAction(
                    "Создал новый тип судна: ".$ac_type->getType())
                ->setDate(new \DateTime());
            $this->getDoctrine()->getManager()->persist($userLogs);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('add_ac_type');
        }

        return 0;
    }
}
