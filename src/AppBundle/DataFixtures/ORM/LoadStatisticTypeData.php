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
        $temp = new StatisticType();
        $temp->setName("temp");

        $hydro = new StatisticType();
        $hydro->setName("hydro");

        $valve = new StatisticType();
        $valve->setName("valve");

        $manager->persist($temp);
        $manager->persist($hydro);
        $manager->persist($valve);

        $manager->flush();

        $this->addReference('statistic-type-temp', $temp);
        $this->addReference('statistic-type-hydro', $hydro);
        $this->addReference('statistic-type-valve', $valve);
    }

    public function getOrder()
    {
        // the order in which fixtures will be loaded
        // the lower the number, the sooner that this fixture is loaded
        return 3;
    }

}