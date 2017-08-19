<?php

namespace AppBundle\Entity;

use JMS\Serializer\Annotation\Type;
use Doctrine\ORM\Mapping as ORM;

/**
 * StatisticType
 *
 * @ORM\Table(name="statistic_type", uniqueConstraints={@ORM\UniqueConstraint(name="id_UNIQUE", columns={"id"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\StatisticTypeRepository")
 */
class StatisticType
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
     * @ORM\Column(name="name", type="string")
     * @Type("string")
     */
    private $name;

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
     * Set name
     *
     * @param string $name
     *
     * @return StatisticType
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    public function __toString()
    {
        return $this->getName();
    }
}

