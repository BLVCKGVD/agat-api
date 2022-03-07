<?php

namespace App\Controller;

use App\Entity\Content;

use App\Form\ContentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContentController extends AbstractController
{

    public function __construct(EntityManagerInterface $em)
    {
        $this->entityManager = $em;
    }

    public function index(): Response
    {
        if ($_COOKIE['role'] != 'admin') {
            return $this->redirectToRoute('employees_page');
        }
        $content = $this->getDoctrine()->getRepository(Content::class)->findAll();
        return $this->render('content/index.html.twig', [
            'controller_name' => 'ContentController',
            'content' => $content,
        ]);
    }
    public function getContent($page)
    {
        $content = $this->getDoctrine()->getRepository(Content::class)->findOneBy(
            [
                'page' => $page
            ]
        );
        return $this->render('content/content.html.twig',
        [
            'content'=>$content,
        ]);
    }

    public function edit($page, Request $request,EntityManagerInterface $em)
    {
        if ($_COOKIE['role'] != 'admin') {
            return $this->redirectToRoute('employees_page');
        }
        $content = $this->getDoctrine()->getRepository(Content::class)->findOneBy(
            [
                'page' => $page
            ]
        );
        $form = $this->createForm(ContentType::class, $content);
        $form->handleRequest($request);

        if($form->isSubmitted())
        {
            $content = $form->getData();
            $em->persist($content);
            $em->flush();
            return $this->redirectToRoute('content');


        }
        return $this->render('content/edit.html.twig',[
            'form'=>$form->createView(),
            'content'=>$content,
        ]);
    }
}
