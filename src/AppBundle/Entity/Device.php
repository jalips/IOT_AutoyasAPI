<?php

namespace AppBundle\Entity;

use JMS\Serializer\Annotation\Type;
use Doctrine\ORM\Mapping as ORM;

/**
 * Device
 *
 * @ORM\Table(name="device", uniqueConstraints={@ORM\UniqueConstraint(name="id_UNIQUE", columns={"id"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DeviceRepository")
 */
class Device
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Type("integer")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="mac_adress", type="string", unique=true)
     * @Type("string")
     */
    private $macAdress;

    /**
     * @var bool
     *
     * @ORM\Column(name="status", type="boolean")
     * @Type("boolean")
     */
    private $status;

    /**
     * @var \AppBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="devices")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * @Type("AppBundle\Entity\User")
     */
    private $user;

    /**
     * Get id
     *
     * @return integer
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
     * Set mac adress
     *
     * @param string $macAdress
     *
     * @return Device
     */
    public function setMacAdress($macAdress)
    {
        $this->macAdress = $macAdress;

        return $this;
    }

    /**
     * Get mac adress
     *
     * @return string
     */
    public function getMacAdress()
    {
        return $this->macAdress;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return Device
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    public function __toString()
    {
        return strval("device mac adress :" . $this->getMacAdress());
    }
}

