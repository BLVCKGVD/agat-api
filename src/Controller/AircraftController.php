<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Aircraft;
use App\Form\AircraftType;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\AircraftRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

use function PHPSTORM_META\elementType;

class AircraftController extends AbstractController
{
    private  $em;

    public function __construct(EntityManagerInterface $em) {
        $this->entityManager = $em;
    }
    
    #[Route('/aircraft', name: 'aircraft')]
    public function index(): Response
    {
        session_start();
        if($_COOKIE['role']=='admin')
        {
            $role = 'admin';
        } else{$role = 'user';}
        return $this->render('aircraft/index.html.twig', [
            'controller_name' => 'AircraftController',
            'aircrafts' => $this->getAircrafts(),
            'role' => $role,
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
            $entityManager->persist($aircraft);
            $entityManager->flush();

            return $this->redirectToRoute('aircrafts_page');
        }
        
        if(isset($_COOKIE['role']) && $_COOKIE['role']=='admin')
        {
            return $this->render('aircraft/create.html.twig', [
                'controller_name' => 'AircraftController',
                'form' => $form->createView(),
                
            ]);
                  
        }
        else{
            return $this->redirectToRoute('aircrafts_page'); 
        }
        
    }
    public function getAircrafts()
{
    $qb = $this->entityManager
              ->createQuery('SELECT a FROM App\Entity\Aircraft a');
        
            return $qb->execute();


}
}
