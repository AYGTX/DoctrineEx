<?php

namespace App\DataFixtures;

use App\Entity\Annonce;
use App\Entity\Category;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{

  /*  public function load(ObjectManager $manager)
    
    {
        
    }*/
    
    public function load(ObjectManager $manager)
    {

        $user = new User();
        $user->setEmail("Aymen@gmail.com");
        $user->setPassword('123456');
        $user->setRoles(['ROLE_ADMIN']);
        $this->setReference('users', $user);
        $manager->persist($user);
        $manager->flush();
        $this->setReference('users', $user);

        for ($i = 0; $i < 2; $i++) {
            $categ = new Category();
            $categ->setNom('category '.$i);
            $manager->persist($categ);
            for ($j = 0; $j < 5; $j++) {
                $user =  $this->getReference('users');
                $annonce = new Annonce();
                $annonce->setTitre('titre '.$i);
                $annonce->setDescription('description '.$i);
                $annonce->setCreatedAt($this->createdAt = new \DateTime());
                $annonce->setUser($user);
                $annonce->setCategory($categ);
                $manager->persist($annonce);
            }
        $manager->flush();

        }
        $manager->flush();

    }
}

