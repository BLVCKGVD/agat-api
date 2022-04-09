<?php

namespace App\Controller;

use App\Controller\CookiesController;
use App\Entity\AircraftOperating;
use App\Entity\Aircraft;
use App\Entity\UserLogs;
use App\Entity\Users;
use App\Form\AddRepType;
use App\Form\AddResType;
use App\Form\TableBuilderType;
use Doctrine\Common\Collections\ArrayCollection;
use PhpOffice\PhpWord\IOFactory;
use App\Repository\AircraftOperatingRepository;
use PhpOffice\PhpWord\TemplateProcessor;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\Stream;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\AircraftType;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\AircraftRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Validator\Constraints\DateTime;
use function PHPSTORM_META\elementType;
use function Symfony\Component\String\b;

class AircraftController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->entityManager = $em;
    }

    #[Route('/aircraft', name: 'aircraft')]
    public function index(Request $request): Response
    {
        if (!isset($_COOKIE['role'])) {
            return $this->redirectToRoute('employees_page');
        }
        if ($_COOKIE['role'] == 'admin') {
            $role = 'admin';
        } else {
            $role = 'user';
        }

        $form = $this->createForm(TableBuilderType::class);
        $form->handleRequest($request);
        $data = CookiesController::getCooks();
        if ($form->isSubmitted()) {
            $data = $form->getData();
            $data = CookiesController::setCooks($data);

        }
        $searchfor = $request->query->get('searchfor');
        if($searchfor)
        {
            $aircrafts = $this->getDoctrine()->getManager()
                ->getRepository(Aircraft::class)
                ->createQueryBuilder('o')
                ->where('LOWER(o.board_num) LIKE LOWER(:searchfor)')
                ->orWhere('LOWER(o.ac_type) LIKE LOWER(:searchfor)')
                ->orWhere('LOWER(o.lg_sert) LIKE LOWER(:searchfor)')
                ->orWhere('LOWER(o.factory_num) LIKE LOWER(:searchfor)')
                ->orWhere('LOWER(o.reg_sert) LIKE LOWER(:searchfor)')
                ->setParameter('searchfor', '%'.$searchfor.'%')
                ->getQuery()
                ->getResult();
        }else{
            $aircrafts = $this->getDoctrine()->getRepository(Aircraft::class)->findAll();
        }


        //echo $_COOKIE['board_num'];

        return $this->render('aircraft/index.html.twig', [
            'controller_name' => 'AircraftController',
            'aircrafts' => $aircrafts,
            'searchfor' => $searchfor,
            'aircraftOperating' => $this->getDoctrine()->getRepository(AircraftOperating::class)->findAll(),
            'role' => $role,
            'tableBuilderForm' => $form->createView(),
            'data' => $data


        ]);

    }


    public function create(Request $request): Response
    {

        $aircraft = new Aircraft();

        $form = $this->createForm(AircraftType::class, $aircraft);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $aircraft = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();


            $release_date = new \DateTime;
            $release_date->format('YYYY-MM-DD');
            $release_date = $aircraft->getReleaseDate();
            $assigned_exp_date = new \DateTime();
            $assigned_exp_date->format('YYYY-MM-DD');
            $assigned_exp_date->setTimestamp($release_date->getTimestamp());
            $assigned_exp_date->modify('+' . $form->get('assigned_exp_date')->getData() . 'years');
            $overhaul_exp_date = new \DateTime();
            $overhaul_exp_date->format('YYYY-MM-DD');
            $overhaul_exp_date->setTimestamp($release_date->getTimestamp());
            $overhaul_exp_date->modify('+' . $form->get('overhaul_exp_date')->getData() . 'years');
            $aircraft
                ->setFinPeriodicMt
                ($form->get('fin_form')->getData()
                    . " " . $form->get('fin_res')->getData()
                    . " " . $form->get('fin_term')->getData());
            $aircraft->setAssignedExpDate($assigned_exp_date);
            $aircraft->setOverhaulExpDate($overhaul_exp_date);
            $operating = new AircraftOperating();
            $operating->setAddedBy($_COOKIE['FIO'])
                ->setCreateDate(new \DateTime())
                ->setOverhaulRes(0)
                ->setTotalRes(0);
            $aircraft->addAircraftOperating($operating);
            $entityManager->persist($aircraft);
            $entityManager->flush();
            $userLogs = new UserLogs();
            $userLogs->setEmployee(
                $this->getDoctrine()->getRepository(Users::class)->findOneBy([
                    'login'=>$_COOKIE['login']
                ])
            )
                ->setAction(
                    "Создал воздушное судно")
                ->setBoardNum($aircraft->getBoardNum())
                ->setAircraft($aircraft)
                ->setDate(new \DateTime());
            $this->getDoctrine()->getManager()->persist($userLogs);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('aircraft_info',[
                'id'=>$aircraft->getId(),
            ]);
        }

        if (isset($_COOKIE['role']) && $_COOKIE['role'] == 'admin') {
            return $this->render('aircraft/create.html.twig', [
                'controller_name' => 'AircraftController',
                'form' => $form->createView(),

            ]);

        } else {
            return $this->redirectToRoute('aircrafts_page');
        }

    }



    public function info(Request $request,$id)
    {
        if ($_COOKIE['role'] == 'admin') {
            $role = 'admin';
        } else {
            $role = 'user';
        }
        $aircraft = $this->getDoctrine()->getRepository(Aircraft::class)->find($id);
        $operating = $this->entityManager->getRepository(AircraftOperating::class)
            ->getLastAircraftOperating($aircraft);

        $form = $this->createForm(AddResType::class);
        $form->handleRequest($request);
        $formRep = $this->createForm(AddRepType::class);
        $formRep->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $new_operating = new AircraftOperating();
            $new_operating
                ->setTotalRes($operating->getTotalRes()+$form->get('add')->getData())
                ->setOverhaulRes($operating->getOverhaulRes()+$form->get('add')->getData())
                ->setAircraft($aircraft)
                ->setCreateDate(new \DateTime())
                ->setAddedBy($_COOKIE['FIO']);
            $userLogs = new UserLogs();
            $userLogs->setEmployee(
                $this->getDoctrine()->getRepository(Users::class)->findOneBy([
                    'login'=>$_COOKIE['login']
                ])
            )
                ->setAction(
                    "Добавил наработку в количестве ".$form->get('add')->getData().
                    " ч. к воздушному судну ")
                ->setDate(new \DateTime())
                ->setAircraft($aircraft)
                ->setBoardNum($aircraft->getBoardNum());
            $this->entityManager->persist($userLogs);
            $this->entityManager->persist($new_operating);
            $this->entityManager->flush();
            return $this->redirect($request->getUri());

        }
        if ($formRep->isSubmitted() && $formRep->isValid()) {
            $new_operating = new AircraftOperating();
            $new_operating
                ->setTotalRes($operating->getTotalRes())
                ->setOverhaulRes(0)
                ->setAircraft($aircraft)
                ->setCreateDate(new \DateTime())
                ->setAddedBy($_COOKIE['FIO']);
            $repair_date = new \DateTime;
            $repair_date->format('YYYY-MM-DD');
            $repair_date = $formRep->get('repair_date')->getData();
            $overhaul_exp_date = new \DateTime();
            $overhaul_exp_date->format('YYYY-MM-DD');
            $overhaul_exp_date->setTimestamp($repair_date->getTimestamp());
            $overhaul_exp_date->modify('+' . $formRep->get('overhaul_exp_date')->getData() . 'years');
            $aircraft
                ->setOverhaulRes($formRep->get('add')->getData())
                ->setOverhaulExpDate($overhaul_exp_date)
                ->setRepairsCount($aircraft->getRepairsCount() + 1)
                ->setLastRepairDate($repair_date);
            $userLogs = new UserLogs();
            $userLogs->setEmployee(
                $this->getDoctrine()->getRepository(Users::class)->findOneBy([
                    'login'=>$_COOKIE['login']
                ])
            )
                ->setAction(
                    "Добавил ремонт с датой ремонта: ".date_format($repair_date,'d.m.Y')." к воздушному судну ")
                ->setDate(new \DateTime())
                ->setAircraft($aircraft)
                ->setBoardNum($aircraft->getBoardNum());
            $this->entityManager->persist($userLogs);
            $this->entityManager->persist($aircraft);
            $this->entityManager->persist($new_operating);
            $this->entityManager->flush();
            return $this->redirect($request->getUri());

        }
        return $this->render('aircraft/info.html.twig', [
            'controller_name' => 'AircraftController',
            'aircraft' => $aircraft,
            'lastOperating' => $operating,
            'addRes' => $form->createView(),
            'addRep' => $formRep->createView(),
            'aircraftOperating' => $aircraft->getAircraftOperating(),
            'role' => $role,


        ]);

    }

    public function edit($id, Request $request)
    {
        $aircraft = $this->entityManager->getRepository(Aircraft::class)->find($id);
        $form = $this->createForm(AircraftType::class, $aircraft);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $aircraft = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();


            $release_date = new \DateTime;
            $release_date->format('YYYY-MM-DD');
            $release_date = $aircraft->getReleaseDate();
            $assigned_exp_date = new \DateTime();
            $assigned_exp_date->format('YYYY-MM-DD');
            $assigned_exp_date->setTimestamp($release_date->getTimestamp());
            $assigned_exp_date->modify('+' . $form->get('assigned_exp_date')->getData() . 'years');
            $overhaul_exp_date = new \DateTime();
            $overhaul_exp_date->format('YYYY-MM-DD');
            $overhaul_exp_date->setTimestamp($release_date->getTimestamp());
            $overhaul_exp_date->modify('+' . $form->get('overhaul_exp_date')->getData() . 'years');
            $aircraft
                ->setFinPeriodicMt
                ($form->get('fin_form')->getData()
                    . " " . $form->get('fin_res')->getData()
                    . " " . $form->get('fin_term')->getData());
            $aircraft->setAssignedExpDate($assigned_exp_date);
            $aircraft->setOverhaulExpDate($overhaul_exp_date);
            $entityManager->persist($aircraft);
            $entityManager->flush();
            return $this->redirect('aircrafts/'.$id);
        }
        return $this->render('aircraft/create.html.twig',
        [
            'form'=>$form->createView(),
        ]);
    }
    public function delete(int $id)
    {
        session_start();
        if ($_COOKIE['role'] == 'admin') {
            $em = $this->getDoctrine()->getManager();
            $entite = $em->getRepository(Aircraft::class)->find($id);
            $em->remove($entite);
            $em->flush();
            return $this->redirectToRoute('aircrafts_page');
        } else {
            return $this->redirectToRoute('aircrafts_page');
        }


    }
    public function testWord($fileName, $id)
    {

        $aircraft = $this->entityManager->getRepository(Aircraft::class)->find($id);
        $templateProcessor = new TemplateProcessor($this->getParameter('app.templates').$fileName.'.docx');
        $templateProcessor->setValue('board_num', $aircraft->getBoardNum());
        $templateProcessor->setValue('factory_num', $aircraft->getFactoryNum());
        $templateProcessor->setValue('ac_type', $aircraft->getAcType());
        $templateProcessor->saveAs($this->getParameter('app.results').$aircraft->getBoardNum().'-test'.'.docx');

        header("Pragma: public"); // required
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: private",false);
        header("Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document");
        header("Content-Disposition: attachment; filename=\"".$aircraft->getBoardNum().'-test'.'.docx'."\";" );
        header("Content-Transfer-Encoding: binary");
        header("Content-Length: ".filesize($this->getParameter('app.results').$aircraft->getBoardNum().'-test'.'.docx'));
        ob_clean();
        flush();
        readfile($this->getParameter('app.results').$aircraft->getBoardNum().'-test'.'.docx');
        unlink($this->getParameter('app.results').$aircraft->getBoardNum().'-test'.'.docx');

    }



}
