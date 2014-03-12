<?php

namespace Attendee\Bundle\ApiBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * Team
 *
 * @ORM\Table(name="teams")
 * @ORM\Entity
 *
 * @Serializer\ExclusionPolicy("all")
 */
class Team extends AbstractEntity
{
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     *
     * @Serializer\Expose
     */
    private $name;

    /**
     * @var User[]
     *
     * @ORM\ManyToMany(targetEntity="User", mappedBy="teams", cascade={"persist"})
     */
    private $users;

    /**
     * @var TeamManager[]
     *
     * @ORM\OneToMany(targetEntity="TeamManager", mappedBy="team", cascade={"persist"})
     */
    private $teamManagers;

    /**
     * @var Schedule[] | ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Schedule", inversedBy="teams", cascade={"persist"})
     */
    private $schedules;

    /**
     * @Serializer\VirtualProperty
     * @Serializer\SerializedName("users")
     *
     * @return int[]
     */
    public function getUsersIds()
    {
        $ids = array();

        foreach ($this->users as $user) {
            $ids[] = $user->getId();
        }

        return $ids;
    }


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
     * @param User $user
     *
     * @return $this
     */
    public function addUser(User $user)
    {
        $user->addTeam($this);
        $this->users->add($user);

        return $this;
    }

    /**
     * @param User $userToRemove
     *
     * @return $this
     */
    public function removeUser(User $userToRemove)
    {
        foreach ($this->users as $key => $user) {
            if ($user === $userToRemove) {
                $this->users->remove($key);

                if ($userToRemove->belongsTo($this)) {
                    $userToRemove->removeTeam($this);
                }

                break;
            }
        }

        return $this;
    }

    /**
     * @param User $user
     *
     * @return bool
     */
    public function hasUser(User $user)
    {
        foreach ($this->users as $existingUser) {
            if ($user === $existingUser) {
                return true;
            }
        }

        return false;
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
     * @param TeamManager $manager
     *
     * @return $this
     */
    public function addTeamManager(TeamManager $manager)
    {
        $this->teamManagers->add($manager);

        return $this;
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