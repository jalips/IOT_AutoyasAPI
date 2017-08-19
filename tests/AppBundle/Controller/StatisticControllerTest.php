<?php

namespace AppBundle\Tests\Controller;

use DateTime;
use AppBundle\Entity\Statistic;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class StatisticRepositoryTest extends KernelTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        self::bootKernel();

        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->em->beginTransaction();
    }

    /**
     * @return Statistic
     */
    public function createElem(){
        $newStatistic = new Statistic();
        $newStatistic->setCreatedAt(new DateTime('2017-08-16'));
        $newStatistic->setData(30);
        $newStatistic->setStatisticType($this->em->getRepository('AppBundle:StatisticType')->findOneBy(
            array('name' => 'temp')
        ));
        $newStatistic->setDevice($this->em->getRepository('AppBundle:Device')->findOneBy(
            array('macAdress' => 'temp')
        ));

        $this->em->persist($newStatistic);
        $this->em->flush();

        return $newStatistic;
    }

    public function testCreate()
    {
        $Statistics = $this->em->getRepository(Statistic::class)->findAll();
        $total = count($Statistics);

        $this->createElem();

        $newStatistics = $this->em->getRepository(Statistic::class)->findAll();
        $newTotal = count($newStatistics);

        $this->assertEquals($total + 1, $newTotal);

        $this->em->rollback();
    }

    public function testSearchAll()
    {
        $Statistics = $this->em->getRepository(Statistic::class)->findAll();

        $this->assertGreaterThan(1, $Statistics);
    }

    public function testSearchById()
    {
        $newStatistic = $this->createElem();

        $Statistic = $this->em->getRepository(Statistic::class)->find($newStatistic->getId());

        $this->assertInstanceOf(Statistic::class, $Statistic);
        $this->assertEquals($newStatistic->getId(), $Statistic->getId());
        $this->assertEquals($newStatistic->getCreatedAt(), $Statistic->getCreatedAt());
        $this->assertEquals($newStatistic->getData(), $Statistic->getData());
        $this->assertEquals($newStatistic->getDevice(), $Statistic->getDevice());
        $this->assertEquals($newStatistic->getStatisticType(), $Statistic->getStatisticType());

        $this->em->rollback();
    }

    public function testDeleteById()
    {
        $newStatistic = $this->createElem();

        $Statistic = $this->em->getRepository(Statistic::class)->find($newStatistic->getId());
        $lastId = $Statistic->getId();

        $this->em->remove($Statistic);
        $this->em->flush();

        $lastStatistic = $this->em->getRepository(Statistic::class)->find($lastId);
        $this->assertNull($lastStatistic);

        $this->em->rollback();
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown()
    {
        parent::tearDown();

        $this->em->close();
        $this->em = null; // avoid memory leaks
    }
}