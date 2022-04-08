<?php
namespace App\DataFixtures;

use App\Entity\Content;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ContentFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // create 20 products! Bam!
        
            $content = new Content();
            $content->setPage('main_page');
            $manager->persist($content);

	    $content = new Content();
            $content->setPage('contacts');
            $manager->persist($content);


        

        $manager->flush();
    }
}
