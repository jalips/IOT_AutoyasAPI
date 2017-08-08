<?php

namespace AppBundle\DataFixtures\ORM;

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

        $titi  = new User();
        $titi->setUsername('titi');
        $titi->setEmail('titi@titi.org');
        $titi->setPlainPassword('titi');

        $manager->persist($toto);
        $manager->persist($titi);

        $manager->flush();
    }

    public function getOrder()
    {
        // the order in which fixtures will be loaded
        // the lower the number, the sooner that this fixture is loaded
        return 1;
    }

}