<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Device;

class LoadDeviceData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     *
     */
    public function load(ObjectManager $manager)
    {
        $toto = new Device();
        $toto->setMacAdress("5E:FF:56:A2:AF:15");
        $toto->setStatus(1);

        $titi  = new Device();
        $titi->setMacAdress("6E:FF:58:A3:AF:16");
        $titi->setStatus(1);

        $manager->persist($toto);
        $manager->persist($titi);

        $manager->flush();

        $this->addReference('device-a', $toto);
        $this->addReference('device-b', $titi);
    }

    public function getOrder()
    {
        // the order in which fixtures will be loaded
        // the lower the number, the sooner that this fixture is loaded
        return 2;
    }
}