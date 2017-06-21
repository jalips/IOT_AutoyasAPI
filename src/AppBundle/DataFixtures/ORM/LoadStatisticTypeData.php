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
        $toto->setName("sensor/temp");

        $titi  = new StatisticType();
        $titi->setName("sensor/hydro");

        $tata  = new StatisticType();
        $tata->setName("sensor/valve");

        $manager->persist($toto);
        $manager->persist($titi);
        $manager->persist($tata);

        $manager->flush();

        $this->addReference('statistic-type-temp', $toto);
        $this->addReference('statistic-type-hydro', $titi);
        $this->addReference('statistic-type-valve', $tata);
    }

    public function getOrder()
    {
        // the order in which fixtures will be loaded
        // the lower the number, the sooner that this fixture is loaded
        return 3;
    }

}