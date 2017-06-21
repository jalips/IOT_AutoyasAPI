<?php

namespace AppBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * Statistic
 *
 * @ORM\Table(name="statistic")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\StatisticRepository")
 */
class Statistic
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="data", type="integer")
     */
    private $data;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \AppBundle\Entity\StatisticType
     *
     * @ORM\ManyToOne(targetEntity="StatisticType", inversedBy="statistictypes")
     * @ORM\JoinColumn(name="statistic_type_id", referencedColumnName="id")
     */
    private $statisticType;

    /**
     * @var \AppBundle\Entity\Device
     *
     * @ORM\ManyToOne(targetEntity="Device", inversedBy="devices")
     * @ORM\JoinColumn(name="device_id", referencedColumnName="id")
     */
    private $device;


    /**
     * Device constructor.
     */
    public function __construct()
    {
        $this->setCreatedAt(new DateTime());
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param int $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $date
     */
    public function setCreatedAt($date)
    {
        $this->createdAt = $date;
    }

    /**
     * @return StatisticType
     */
    public function getStatisticType()
    {
        return $this->statisticType;
    }

    /**
     * @param StatisticType $statisticType
     */
    public function setStatisticType($statisticType)
    {
        $this->statisticType = $statisticType;
    }

    /**
     * @return Device
     */
    public function getDevice()
    {
        return $this->device;
    }

    /**
     * @param Device $device
     */
    public function setDevice($device)
    {
        $this->device = $device;
    }
}

