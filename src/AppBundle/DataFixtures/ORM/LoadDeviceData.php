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
        $a = new Device();
        $a->setMacAdress("5E:FF:56:A2:AF:15");
        $a->setStatus(1);

        $b  = new Device();
        $b->setMacAdress("6E:FF:58:A3:AF:16");
        $b->setStatus(1);

        $manager->persist($a);
        $manager->persist($b);

        $manager->flush();

        $this->addReference('device-a', $a);
        $this->addReference('device-b', $b);
    }

    public function getOrder()
    {
        // the order in which fixtures will be loaded
        // the lower the number, the sooner that this fixture is loaded
        return 1;
    }
}