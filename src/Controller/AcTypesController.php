<?php

namespace App\Controller;

use App\Entity\AcTypes;
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
        if (!isset($_COOKIE['role']) || $_COOKIE['role'] != 'admin')
        {
            return $this->redirectToRoute('employees_page');
        }
        $ac_type = new AcTypes();
        $form = $this->createForm(addAcType::class,$ac_type);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $new_type = new AcTypes();
            $new_type->setType($form->get('type')->getData())
                ->setCategory($form->get('category')->getData())
                ->setEngCount($form->get('eng_count')->getData());
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
            return $this->redirectToRoute('employees_page');
        }
        return $this->render('aircraft/addType.html.twig',[
            'form' => $form->createView(),
            'login' => $_COOKIE['login'],
        ]);

    }
}
