<?php

namespace App\Controller;

use App\Entity\UserLogs;
use App\Entity\Users;
use App\Entity\Favourites;
use App\Entity\Aircraft;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FavouritesController extends AbstractController
{

    public function add($id)
    {
        if (isset($_COOKIE['login']))
        {

            $user = $this->getDoctrine()->getRepository(Users::class)->findOneBy([
                'login'=>$_COOKIE['login']
            ]);
            $aircraft = $this->getDoctrine()->getRepository(Aircraft::class)->find($id);
            if($aircraft == null)
            {
                return $this->redirect("/aircrafts");
            }
            if ($this->getFav($aircraft) != 'added')
            {
                $fav= new Favourites();
                $fav->setIdUser($user)
                    ->setIdAircraft($aircraft);
                $this->getDoctrine()->getManager()->persist($fav);
                $this->getDoctrine()->getManager()->flush();

                return $this->redirect("/aircrafts/".$aircraft->getId());
            } else
            {
                return $this->redirect("/aircrafts/".$aircraft->getId());
            }

        }
            return $this->redirect("/login");
    }
    public function remove($id)
    {
        if (isset($_COOKIE['login']))
        {

            $user = $this->getDoctrine()->getRepository(Users::class)->findOneBy([
                'login'=>$_COOKIE['login']
            ]);
            $aircraft = $this->getDoctrine()->getRepository(Aircraft::class)->find($id);
            $fav= $this->getDoctrine()->getRepository(Favourites::class)->findOneBy([
                'idUser'=>$user->getId(),
                'idAircraft'=>$id,
            ]);
            if($aircraft == null)
            {
                return $this->redirect("/aircrafts");
            }
            if ($fav)
            {
                $this->getDoctrine()->getManager()->remove($fav);
                $this->getDoctrine()->getManager()->flush();
            }



            return $this->redirect("/aircrafts/".$aircraft->getId());
        }
        return $this->redirect("/login");
    }

    public function getFav($aircraft)
    {
        if(isset($_COOKIE['login']))
        {
            $user = $this->getDoctrine()->getRepository(Users::class)->findOneBy([
                'login'=>$_COOKIE['login']
            ]);
            if ($this->getDoctrine()->getRepository(Favourites::class)->findOneBy([
                'idUser'=>$user->getId(),
                'idAircraft'=>$aircraft->getId()
            ])){
                return "added";
            } else{
                return "not added";
            }
        }
    }
}
