<?php

namespace AppBundle\Entity;

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
     * @ORM\Column(name="start_date", type="string")
     */
    private $startDate;

    /**
     * @var string
     *
     * @ORM\Column(name="end_date", type="string")
     */
    private $endDate;

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
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
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
     * @return string
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @param string $startDate
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;
    }

    /**
     * @return string
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * @param string $endDate
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;
    }

    /**
     * @return \AppBundle\Entity\StatisticType
     */
    public function getStatisticType()
    {
        return $this->statisticType;
    }

    /**
     * @param \AppBundle\Entity\StatisticType $statisticType
     */
    public function setStatisticType($statisticType)
    {
        $this->statisticType = $statisticType;
    }

    /**
     * @return \AppBundle\Entity\Device
     */
    public function getDevice()
    {
        return $this->device;
    }

    /**
     * @param \AppBundle\Entity\Device $deviceId
     */
    public function setDevice($device)
    {
        $this->device = $device;
    }
}

