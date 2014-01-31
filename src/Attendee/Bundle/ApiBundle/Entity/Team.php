<?php

namespace Attendee\Bundle\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Team
 *
 * @ORM\Table(name="teams")
 * @ORM\Entity
 */
class Team extends AbstractEntity
{
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var User[]
     *
     * @ORM\ManyToMany(targetEntity="User", mappedBy="teams")
     */
    private $users;

    /**
     * @var Schedule[]
     *
     * @ORM\ManyToMany(targetEntity="Schedule", mappedBy="teams")
     */
    private $schedules;

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param \Attendee\Bundle\ApiBundle\Entity\User[] $users
     *
     * @return $this
     */
    public function setUsers($users)
    {
        $this->users = $users;

        return $this;
    }

    /**
     * @return \Attendee\Bundle\ApiBundle\Entity\User[]
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @param \Attendee\Bundle\ApiBundle\Entity\Schedule[] $schedules
     *
     * @return $this
     */
    public function setSchedules($schedules)
    {
        $this->schedules = $schedules;

        return $this;
    }

    /**
     * @return \Attendee\Bundle\ApiBundle\Entity\Schedule[]
     */
    public function getSchedules()
    {
        return $this->schedules;
    }

}
