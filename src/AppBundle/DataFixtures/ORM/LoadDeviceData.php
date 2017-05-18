<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Device;

class LoadDeviceData implements FixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     *
     */
    public function load(ObjectManager $manager)
    {
        $toto = new Device();
        $toto->setGuid(123);
        $toto->setStatus(1);

        $titi  = new Device();
        $titi->setGuid(456789);
        $titi->setStatus(1);

        $manager->persist($toto);
        $manager->persist($titi);

        $manager->flush();
    }

}