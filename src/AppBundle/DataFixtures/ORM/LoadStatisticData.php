<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\StatisticType;
use DateTime;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Statistic;

class LoadStatisticData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     *
     */
    public function load(ObjectManager $manager)
    {
        $date = new DateTime();

        $toto = new Statistic();
        $toto->setData(20);
        $toto->setCreatedAt($date);
        $toto->setStatisticType($manager->merge($this->getReference('statistic-type-temp')));
        $toto->setDevice($manager->merge($this->getReference('device-a')));

        $titi  = new Statistic();
        $titi->setData(78);
        $toto->setCreatedAt($date->modify("-1 day"));
        $titi->setStatisticType($manager->merge($this->getReference('statistic-type-hydro')));
        $titi->setDevice($manager->merge($this->getReference('device-a')));

        $tata  = new Statistic();
        $tata->setData(50);
        $toto->setCreatedAt($date->modify("-2 day"));
        $tata->setStatisticType($manager->merge($this->getReference('statistic-type-valve')));
        $tata->setDevice($manager->merge($this->getReference('device-a')));

        $tete = new Statistic();
        $tete->setData(15);
        $toto->setCreatedAt($date->modify("-3 day"));
        $tete->setStatisticType($manager->merge($this->getReference('statistic-type-temp')));
        $tete->setDevice($manager->merge($this->getReference('device-b')));

        $tutu  = new Statistic();
        $tutu->setData(28);
        $toto->setCreatedAt($date->modify("-4 day"));
        $tutu->setStatisticType($manager->merge($this->getReference('statistic-type-hydro')));
        $tutu->setDevice($manager->merge($this->getReference('device-b')));

        $tyty  = new Statistic();
        $tyty->setData(90);
        $toto->setCreatedAt($date->modify("-5 day"));
        $tyty->setStatisticType($manager->merge($this->getReference('statistic-type-valve')));
        $tyty->setDevice($manager->merge($this->getReference('device-b')));

        $manager->persist($toto);
        $manager->persist($titi);
        $manager->persist($tata);

        $manager->persist($tete);
        $manager->persist($tutu);
        $manager->persist($tyty);

        $manager->flush();
    }

    public function getOrder()
    {
        // the order in which fixtures will be loaded
        // the lower the number, the sooner that this fixture is loaded
        return 4;
    }

}