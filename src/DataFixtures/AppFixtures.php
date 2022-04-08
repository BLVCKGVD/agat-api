<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Users;
class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
	  
          $user = new Users();
          $user->setFio('Шульженко Антон Геннадьевич');
          $pass = password_hash("Anton75669", PASSWORD_DEFAULT);
          $user->setPassword($pass);
          $user->setLogin('angen');
          print($pass);
          $user->setRole("admin");
          $manager->persist($user);
$user = new Users();
          $user->setFio('Шульженко Геннадий Александрович');
          $pass = password_hash("Utyf52255", PASSWORD_DEFAULT);
          $user->setPassword($pass);
          $user->setLogin('genaas');
          print($pass);
          $user->setRole("admin");
          $manager->persist($user);
$user = new Users();
          $user->setFio('Админов Админ Админович');
          $pass = password_hash("admin2431", PASSWORD_DEFAULT);
          $user->setPassword($pass);
          $user->setLogin('admin');
          print($pass);
          $user->setRole("admin");
          $manager->persist($user);

     $manager->flush();
    }
}
