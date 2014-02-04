<?php

namespace Attendee\Bundle\ApiBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
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
     * @var TeamManager[]
     *
     * @ORM\OneToMany(targetEntity="TeamManager", mappedBy="team")
     */
    private $teamManagers;

    /**
     * @var Schedule[] | ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Schedule", inversedBy="teams", cascade={"persist"})
     */
    private $schedules;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->schedules    = new ArrayCollection();
        $this->teamManagers = new ArrayCollection();
        $this->users        = new ArrayCollection();
    }

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
     * @param \Attendee\Bundle\ApiBundle\Entity\TeamManager[] $teamManagers
     *
     * @return $this
     */
    public function setTeamManagers($teamManagers)
    {
        $this->teamManagers = $teamManagers;

        return $this;
    }

    /**
     * @return \Attendee\Bundle\ApiBundle\Entity\TeamManager[]
     */
    public function getTeamManagers()
    {
        return $this->teamManagers;
    }

    /**
     * @param Schedule $schedule
     *
     * @return $this
     */
    public function addSchedule(Schedule $schedule)
    {
        $this->schedules->add($schedule);

        return $this;
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

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }
}
