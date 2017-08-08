<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table(name="fos_user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var \AppBundle\Entity\Device
     *
     * @ORM\OneToMany(targetEntity="Device", mappedBy="user")
     */
    private $devices;

    public function __construct()
    {
        parent::__construct();
        // your own logic

        $this->devices = new ArrayCollection();
    }


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Device
     */
    public function getDevices()
    {
        return $this->devices;
    }

    /**
     * @param Device $devices
     */
    public function setDevices($devices)
    {
        $this->devices = $devices;
    }
}

