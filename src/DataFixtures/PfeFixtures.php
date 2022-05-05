<?php

namespace App\DataFixtures;

use App\Entity\Entreprise;
use App\Entity\PFE;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Bridge\Doctrine\ManagerRegistry;

class PfeFixtures extends Fixture
{
    public function load(ObjectManager $manager ): void
    {
        $faker=Factory::create();
        for ($i=0;$i<50;$i++){
            $pfe=new PFE();
            $pfe->setTitle("PFE".$i);
            $pfe->setStudent($faker->firstName.' '.$faker->name);
            $entreprise=new Entreprise();
            $entreprise->setDesignation($faker->company);
            $manager->persist($entreprise);
            $pfe->setEntreprise($entreprise);
            $manager->persist($pfe);
        }

        $manager->flush();
    }
}
