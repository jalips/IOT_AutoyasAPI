<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\AppBundle;
use AppBundle\Entity\Device;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     *
     */
    public function load(ObjectManager $manager)
    {
        $toto = new User();
        $toto->setUsername('toto');
        $toto->setEmail('toto@toto.org');
        $toto->setPlainPassword('toto');
        $toto->addDevices($this->getReference('device-a'));
        $deviceA = $this->getReference('device-a');
        $deviceA->setUser($toto);

        $titi  = new User();
        $titi->setUsername('titi');
        $titi->setEmail('titi@titi.org');
        $titi->setPlainPassword('titi');
        $titi->addDevices($this->getReference('device-b'));

        $deviceB = $this->getReference('device-b');
        $deviceB->setUser($titi);

        $manager->persist($toto);
        $manager->persist($titi);
        $manager->persist($deviceA);
        $manager->persist($deviceB);

        $this->addReference('user-toto',  $toto);
        $this->addReference('user-titi',  $titi);

        $manager->flush();
    }

    public function getOrder()
    {
        // the order in which fixtures will be loaded
        // the lower the number, the sooner that this fixture is loaded
        return 2;
    }

}