<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\StatisticType;
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
        $toto = new Statistic();
        $toto->setData(20);
        $toto->setStartDate("20/04/2017 00:00:00");
        $toto->setEndDate("20/04/2017 23:59:59");
        $toto->setStatisticType($manager->merge($this->getReference('statistic-type-temperature')));
        $toto->setDevice($manager->merge($this->getReference('device-a')));

        $titi  = new Statistic();
        $titi->setData(78);
        $titi->setStartDate("20/04/2017 00:00:00");
        $titi->setEndDate("20/04/2017 23:59:59");
        $titi->setStatisticType($manager->merge($this->getReference('statistic-type-humidite')));
        $titi->setDevice($manager->merge($this->getReference('device-a')));

        $tata  = new Statistic();
        $tata->setData(50);
        $tata->setStartDate("20/04/2017 00:00:00");
        $tata->setEndDate("20/04/2017 23:59:59");
        $tata->setStatisticType($manager->merge($this->getReference('statistic-type-remplissage-pot')));
        $tata->setDevice($manager->merge($this->getReference('device-a')));

        $tete = new Statistic();
        $tete->setData(15);
        $tete->setStartDate("20/04/2017 00:00:00");
        $tete->setEndDate("20/04/2017 23:59:59");
        $tete->setStatisticType($manager->merge($this->getReference('statistic-type-temperature')));
        $tete->setDevice($manager->merge($this->getReference('device-b')));

        $tutu  = new Statistic();
        $tutu->setData(28);
        $tutu->setStartDate("20/04/2017 00:00:00");
        $tutu->setEndDate("20/04/2017 23:59:59");
        $tutu->setStatisticType($manager->merge($this->getReference('statistic-type-humidite')));
        $tutu->setDevice($manager->merge($this->getReference('device-b')));

        $tyty  = new Statistic();
        $tyty->setData(90);
        $tyty->setStartDate("20/04/2017 00:00:00");
        $tyty->setEndDate("20/04/2017 23:59:59");
        $tyty->setStatisticType($manager->merge($this->getReference('statistic-type-remplissage-pot')));
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