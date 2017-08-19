<?php

namespace ApiBundle\Tests\Controller;

use DateTime;
use JMS\Serializer\SerializerBuilder;
use PHPUnit\Runner\Exception;
use AppBundle\Entity\Statistic;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class StatisticRepositoryTest extends WebTestCase
{
    private $serializer;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        self::bootKernel();

        $this->serializer = SerializerBuilder::create()->build();
    }

    /**
     * Test route /statistics
     */
    public function testGetStatistics()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/statistics');
        $content = json_decode($client->getResponse()->getContent(), true);
        $statistics = $content["Statistics"];

        $this->assertGreaterThan(1, $statistics);
    }

    /**
     * Test route /statistics/{id}
     */
    public function testGetStatisticById()
    {
        $content = $this->getStatisticById(1);
        $statistic = json_decode($content, true);
//        $statistic = $this->serializer->deserialize($content, 'AppBundle\Entity\Statistic', 'json');

        $this->assertEquals(1, $statistic['id']);
    }

    /**
     * Test route /statistics/{statisticType}/{device}/{createdAt}
     */
    public function testGetStatisticByTypeAndDeviceAndDate()
    {
        $content = $this->getStatisticByTypeAndDeviceAndDate("temp", "5E:FF:56:A2:AF:15", "2017-08-04 19:35:10");
        $statistic = json_decode($content, true);

        $this->assertEquals(1, $statistic['id']);
    }

    /**
     * Test routes /statistics/{data}/{statisticType}/new && /statistic/{id}/delete
     */
    public function testCreateDeleteStatistic()
    {
        // Create entity
        $newStatistic = new Statistic();
        $newStatistic->setData(100);

        $createdDate = new DateTime();
        $newStatistic->setCreatedAt($createdDate);

        // Send entity
        $client = static::createClient();
        $crawler = $client->request('POST', '/statistics/'
            . "5E:FF:56:A2:AF:15***" . $newStatistic->getData() .'/hydro/new'
        );


        // Test creation's result is an entity
        $contentCreation = $client->getResponse()->getContent();
        $statisticCreation = json_decode($contentCreation, true);
        $this->assertEquals(100, $statisticCreation['data']);

        // Delete Entity
        $contentDelete = $this->deleteStatisticById($statisticCreation['id']);

        // Test deletion
        $statisticDeletion = json_decode($contentDelete, true);
        $this->assertEquals(100, $statisticDeletion['data']);
    }




    /**
     * Sends requests to route /statistics/{id}
     *
     * @param $id
     * @return string json
     */
    public function getStatisticById($id)
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/statistics/' . $id);
        $content = $client->getResponse()->getContent();

        return $content;
    }

    /**
     * Sends requests to route /statistics/{statisticType}/{device}/{createdAt}
     *
     * @param $statisticTypeId
     * @param $deviceId
     * @param $createdAt
     * @return string json
     */
    public function getStatisticByTypeAndDeviceAndDate($statisticTypeId, $deviceId, $createdAt)
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/statistics/' . $statisticTypeId . '/' . $deviceId . "/" . $createdAt);
        $content = $client->getResponse()->getContent();

        return $content;
    }

    /**
     * Test route /deleteStatistic/{id}
     */
    public function deleteStatisticById($id)
    {
        $client = static::createClient();

        $crawler = $client->request('DELETE', '/statistic/' . $id . '/delete');
        $content = $client->getResponse()->getContent();

        return $content;
    }
}