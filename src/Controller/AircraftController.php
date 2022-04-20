<?php

namespace App\Controller;

use App\Controller\CookiesController;
use App\Entity\AcTypes;
use App\Entity\AircraftOperating;
use App\Entity\Aircraft;
use App\Entity\Favourites;
use App\Entity\Parts;
use App\Entity\PartsOperating;
use App\Entity\UserLogs;
use App\Entity\Users;
use App\Form\AddRepAcType;
use App\Form\AddRepType;
use App\Form\AddResType;
use App\Form\TableBuilderType;
use App\Repository\PartsOperatingRepository;
use Cassandra\Map;
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
        $filter = $request->get('filter');
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
            foreach ($aircrafts as $aircraft)
            {
                $aircraft->setStatus($this->getAircraftStatus($aircraft));
            }
        }else{
            $aircrafts = $this->getDoctrine()->getRepository(Aircraft::class)->findAll();
            foreach ($aircrafts as $aircraft)
            {
                $aircraft->setStatus($this->getAircraftStatus($aircraft));
            }
        }

        return $this->render('aircraft/index.html.twig', [
            'controller_name' => 'AircraftController',
            'aircrafts' => $aircrafts,
            'searchfor' => $searchfor,
            'filter'=>$filter,
            'aircraftOperating' => $this->getDoctrine()->getRepository(AircraftOperating::class)->findAll(),
            'role' => $role,
            'login' => $_COOKIE['login'],
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

            $ac_type = $form->get('add_type')->getData();
            $aircraft->setAcType($ac_type->getType())
                ->setOverhaulYears($form->get('overhaul_exp_date')->getData())
                ->setAcCategory($ac_type->getCategory());
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
                'role' => $_COOKIE['role'],
                'login'=> $_COOKIE['login'],

            ]);

        } else {
            return $this->redirectToRoute('aircrafts_page');
        }

    }



    public function info(Request $request,$id)
    {
        if (!isset($_COOKIE['role']) && $request->get('ac') != null)
        {
            return $this->redirect('/login?ac='.$request->get('ac'));
        }
        if($request->get('ac') != null)
        {
            return $this->redirect('/aircrafts/'.$request->get('ac'));
        }
        if ($_COOKIE['role'] == 'admin') {
            $role = 'admin';
        } else {
            $role = 'user';
        }
        $aircraft = $this->getDoctrine()->getRepository(Aircraft::class)->find($id);
        if($aircraft==null)
        {
            return $this->redirectToRoute('aircrafts_page');
        }
        $operating = $this->entityManager->getRepository(AircraftOperating::class)
            ->getLastAircraftOperating($aircraft);

        $parts = $aircraft->getParts();
        $type = $this->entityManager->getRepository(AcTypes::class)->findOneBy([
            'type'=>$aircraft->getAcType(),
        ]);
        $eng_limit = $type->getEngCount();
        $eng_count = 0;
        foreach ($parts as $part)
        {
            $part->setStatus($this->getPartStatus($part));
            if ($part->getType()=='engine')
            {
                $eng_count++;
            }
        }

        $form = $this->createForm(AddResType::class);
        $form->handleRequest($request);
        $formRep = $this->createForm(AddRepAcType::class);
        $formRep->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($parts as $p)
            {
                $partOp = $this->entityManager->getRepository(PartsOperating::class)
                    ->getLastPartsOperating($p);
                $partOperating = new PartsOperating();
                $partOperating
                    ->setTotalRes($partOp->getTotalRes()+$form->get('add')->getData())
                    ->setOverhaulRes($partOp->getOverhaulRes()+$form->get('add')->getData())
                    ->setPart($p)
                    ->setCreateDate(new \DateTime())
                    ->setAddedBy($_COOKIE['FIO']);
                $this->entityManager->persist($partOperating);
            }
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
            $overhaul_exp_date->modify('+' . $aircraft->getOverhaulYears() . 'years');
            $aircraft
                ->setOverhaulRes($aircraft->getOverhaulRes())
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
            'eng_count'=>$eng_count,
            'eng_limit'=>$eng_limit,
            'aircraft' => $aircraft,
            'lastOperating' => $operating,
            'parts' => $parts,
            'login' => $_COOKIE['login'],
            'addRes' => $form->createView(),
            'addRep' => $formRep->createView(),
            'aircraftOperating' => $aircraft->getAircraftOperating(),
            'role' => $role,
            'added' => $this->getFav($aircraft),


        ]);

    }

    public function getFav($aircraft)
    {
        if(isset($_COOKIE['login']))
        {
            $user = $this->getDoctrine()->getRepository(Users::class)->findOneBy([
                'login'=>$_COOKIE['login']
            ]);
            if ($this->entityManager->getRepository(Favourites::class)->findOneBy([
                'idUser'=>$user->getId(),
                'idAircraft'=>$aircraft->getId()
            ])){
                return "added";
            } else{
                return "not added";
            }
        }
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
            if($form->get('last_repair_date')->getData() == null)
            {
                $overhaul_exp_date->setTimestamp($release_date->getTimestamp());
            } else {
                $overhaul_exp_date=$form->get('last_repair_date')->getData();
            }
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
            $userLogs = new UserLogs();
            $userLogs->setEmployee(
                $this->getDoctrine()->getRepository(Users::class)->findOneBy([
                    'login'=>$_COOKIE['login']
                ])
            )
                ->setAction(
                    "Удалил воздушное судно ".$entite->getBoardNum())
                ->setDate(new \DateTime());
            $this->getDoctrine()->getManager()->persist($userLogs);
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
