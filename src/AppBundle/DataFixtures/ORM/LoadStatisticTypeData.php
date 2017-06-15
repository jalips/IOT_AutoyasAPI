<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\StatisticType;

class LoadStatisticTypeData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     *
     */
    public function load(ObjectManager $manager)
    {
        $toto = new StatisticType();
        $toto->setName("Temperature");

        $titi  = new StatisticType();
        $titi->setName("Humidité");

        $tata  = new StatisticType();
        $tata->setName("Remplissage pot");

        $manager->persist($toto);
        $manager->persist($titi);
        $manager->persist($tata);

        $manager->flush();

        $this->addReference('statistic-type-temperature', $toto);
        $this->addReference('statistic-type-humidite', $titi);
        $this->addReference('statistic-type-remplissage-pot', $tata);
    }

    public function getOrder()
    {
        // the order in which fixtures will be loaded
        // the lower the number, the sooner that this fixture is loaded
        return 3;
    }

}