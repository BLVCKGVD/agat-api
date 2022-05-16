<?php

namespace App\Controller;

use App\Controller\CookiesController;
use App\Entity\AcTypes;
use App\Entity\Aircraft;
use App\Entity\Parts;
use App\Entity\PartsOperating;
use App\Entity\UserLogs;
use App\Entity\Users;
use App\Form\AddRepType;
use App\Form\PartsType;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;


class PartsController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->entityManager = $em;
    }

    public function info($id, Request $request)
    {
        if (!isset($_COOKIE['role']))
        {
            $this->redirectToRoute('employees_page');
        }
        if ($_COOKIE['role'] == 'admin') {
            $role = 'admin';
        } else {
            $role = 'user';
        }
        if ($_COOKIE['role'] == 'superadmin') {
            $role = 'superadmin';
        }

        $part = $this->entityManager->getRepository(Parts::class)->find($id);
        if($part==null)
        {
            return $this->redirectToRoute('aircrafts_page');
        }
        $aircraft = $part->getAircraft();
        if($aircraft==null)
        {
            return $this->redirectToRoute('aircrafts_page');
        }
        $operatings = $part->getPartsOperatings();

        $operating = $this->entityManager->getRepository(PartsOperating::class)
            ->getLastPartsOperating($part);
        $formRep = $this->createForm(AddRepType::class);
        $formRep->handleRequest($request);

        if ($formRep->isSubmitted() && $formRep->isValid()) {
            $new_operating = new PartsOperating();
            $new_operating
                ->setTotalRes($operating->getTotalRes())
                ->setOverhaulRes(0)
                ->setPart($part)
                ->setCreateDate(new \DateTime())
                ->setAddedBy($_COOKIE['FIO']);
            $repair_date = new \DateTime;
            $repair_date->format('YYYY-MM-DD');
            $repair_date = $formRep->get('repair_date')->getData();
            $overhaul_exp_date = new \DateTime();
            $overhaul_exp_date->format('YYYY-MM-DD');
            $overhaul_exp_date->setTimestamp($repair_date->getTimestamp());
            $overhaul_exp_date->modify('+' . $part->getOverhaulYears() . 'years');
            $part
                ->setOverhaulRes($part->getOverhaulRes())
                ->setOverhaulExpDate($overhaul_exp_date)
                ->setRepairPlace($formRep->get('repair_place')->getData())
                ->setRepairDate($repair_date);

            $this->entityManager->persist($part);
            $this->entityManager->persist($new_operating);
            $this->entityManager->flush();
            return $this->redirect($request->getUri());
        }

        return $this->render('parts/profile.html.twig', [
            'controller_name' => 'AircraftController',
            'lastOperating' => $this->entityManager->getRepository(PartsOperating::class)->getLastPartsOperating($part),
            'part' => $part,
            'partOperating' => $operatings,
            'role' => $role,
            'addRep' => $formRep->createView(),
            'login' => $_COOKIE['login'],


        ]);
    }

    public function delete($id)
    {
        $part = $this->entityManager->getRepository(Parts::class)->find($id);
        if ($part != null && isset($_COOKIE['role']) && ($_COOKIE['role'] == 'admin' || $_COOKIE['role']== 'superadmin'))
        {
            $aircraft = $part->getAircraft();
            $this->entityManager->remove($part);
            $this->entityManager->flush();
            return $this->redirect('/aircrafts/'.$aircraft->getId());
        }

    }

    public function create(Request $request, $id)
    {
        $part = new Parts();
        $form = $this->createForm(PartsType::class,$part);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            if($this->getDoctrine()->getManager()->getRepository(Aircraft::class)->find($id))
            {
                $part->setAircraft($this->getDoctrine()->getManager()->getRepository(Aircraft::class)->find($id))
                    ->setOverhaulRes($form->get('overhaul_res')->getData())
                    ->setAssignedRes($form->get('assigned_res')->getData())
                    ->setFactoryNum($form->get('factory_num')->getData())
                    ->setName($form->get('name')->getData())
                    ->setMarking($form->get('marking')->getData())
                    ->setDocument($form->get('document')->getData())
                    ->setOverhaulYears($form->get('overhaul_exp')->getData())
                    ->setReleaseDate($form->get('release_date')->getData());
                if($form->get('repair_date')->getData()==null)
                {
                    $overhaul_exp_date = new \DateTime();
                    $overhaul_exp_date->setTimestamp($part->getReleaseDate()->getTimestamp());
                    $overhaul_exp_date->modify('+'.$form->get('overhaul_exp')->getData().'years');
                } else {
                    $repair_date = $form->get('repair_date')->getData();
                    $overhaul_exp_date = new \DateTime();
                    $overhaul_exp_date->setTimestamp($repair_date->getTimestamp());
                    $overhaul_exp_date->modify('+'.$form->get('overhaul_exp')->getData().'years');
                }
                $part->setOverhaulExpDate($overhaul_exp_date);
                $assigned_exp_date = new \DateTime();
                $assigned_exp_date->setTimestamp($part->getReleaseDate()->getTimestamp());
                $assigned_exp_date->modify('+'.$form->get('assigned_exp')->getData().'years');
                $part->setAssignedExpDate($assigned_exp_date);
                $operating = new PartsOperating();
                $operating->setAddedBy($_COOKIE['FIO'])
                    ->setCreateDate(new \DateTime())
                    ->setOverhaulRes(0)
                    ->setTotalRes(0);
                $part->addPartsOperating($operating);
                $this->getDoctrine()->getManager()->persist($part);
                $this->getDoctrine()->getManager()->flush();
                $this->addFlash('success', "Комплектующее успешно создано");
                return $this->redirect('/aircrafts/'.$id);
            }
        }
        return $this->render('parts/create.html.twig',
        [
            'login'=>$_COOKIE['login'],
            'role'=>$_COOKIE['role'],
            'form'=>$form->createView(),
        ]);
    }
    public function createEng(Request $request, $id)
    {
        $part = new Parts();
        $form = $this->createForm(PartsType::class,$part);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            if($this->getDoctrine()->getManager()->getRepository(Aircraft::class)->find($id))
            {
                $part->setAircraft($this->getDoctrine()->getManager()->getRepository(Aircraft::class)->find($id))
                    ->setOverhaulRes($form->get('overhaul_res')->getData())
                    ->setAssignedRes($form->get('assigned_res')->getData())
                    ->setFactoryNum($form->get('factory_num')->getData())
                    ->setName($form->get('name')->getData())
                    ->setMarking($form->get('marking')->getData())
                    ->setDocument($form->get('document')->getData())
                    ->setType('engine')
                    ->setOverhaulYears($form->get('overhaul_exp')->getData())
                    ->setReleaseDate($form->get('release_date')->getData());
                if($form->get('repair_date')->getData()==null)
                {
                    $overhaul_exp_date = new \DateTime();
                    $overhaul_exp_date->setTimestamp($part->getReleaseDate()->getTimestamp());
                    $overhaul_exp_date->modify('+'.$form->get('overhaul_exp')->getData().'years');
                    $part->setOverhaulExpDate($overhaul_exp_date);
                } else {
                    $repair_date = $form->get('repair_date')->getData();
                    $overhaul_exp_date = new \DateTime();
                    $overhaul_exp_date->setTimestamp($repair_date->getTimestamp());
                    $overhaul_exp_date->modify('+'.$form->get('overhaul_exp')->getData().'years');
                }
                $assigned_exp_date = new \DateTime();
                $assigned_exp_date->setTimestamp($part->getReleaseDate()->getTimestamp());
                $assigned_exp_date->modify('+'.$form->get('assigned_exp')->getData().'years');
                $part->setAssignedExpDate($assigned_exp_date);
                $operating = new PartsOperating();
                $operating->setAddedBy($_COOKIE['FIO'])
                    ->setCreateDate(new \DateTime())
                    ->setOverhaulRes(0)
                    ->setTotalRes(0);
                $part->addPartsOperating($operating);
                $this->getDoctrine()->getManager()->persist($part);
                $this->getDoctrine()->getManager()->flush();
                return $this->redirect('/aircrafts/'.$id);
            }
        }
        return $this->render('parts/create_eng.html.twig',
            [
                'login'=>$_COOKIE['login'],
                'role'=>$_COOKIE['role'],
                'form'=>$form->createView(),
            ]);
    }




}
