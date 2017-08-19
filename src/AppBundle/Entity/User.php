<?php

namespace AppBundle\Entity;

use JMS\Serializer\Annotation\Type;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table(name="fos_user", uniqueConstraints={@ORM\UniqueConstraint(name="id_UNIQUE", columns={"id"})})
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
     * @Type("integer")
     */
    protected $id;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Device", mappedBy="user")
     * @Type("AppBundle\Entity\Device")
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
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return ArrayCollection
     */
    public function getDevices()
    {
        return $this->devices;
    }

    /**
     * @param ArrayCollection $devices
     */
    public function setDevices($devices)
    {
        $this->devices = $devices;
    }

    /**
     * Add devices
     *
     * @param \AppBundle\Entity\Device $devices
     * @return User
     */
    public function addDevices($devices)
    {
        if (!$this->devices->contains($devices))
        {
            $this->devices[] = $devices;
        }

        return $this;
    }
}

