<?php

namespace Attendee\Bundle\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Attendee\Bundle\UserBundle\Entity\User as BaseUser;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as Serializer;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * User
 *
 * @ORM\Table(name="users")
 * @ORM\Entity()
 *
 * @Serializer\ExclusionPolicy("all")
 * Serializer\AccessType("public_method")
 */
class User extends BaseUser
{
    /**
     * @var \Datetime $created
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    protected $created;

    /**
     * @var \Datetime $updated
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    protected $updated;

    /**
     * @var Attendance[]
     *
     * @ORM\OneToMany(targetEntity="Attendance", mappedBy="user")
     */
    private $attendances;

    /**
     * @var TeamManager[]
     *
     * @ORM\OneToMany(targetEntity="TeamManager", mappedBy="user")
     */
    private $teamManagers;

    /**
     * @var Team[] | ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Team", inversedBy="users")
     */
    private $teams;

    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->teams = new ArrayCollection();
    }

    /**
     * @param \Attendee\Bundle\ApiBundle\Entity\Attendance[] $attendances
     */
    public function setAttendances($attendances)
    {
        $this->attendances = $attendances;
    }

    /**
     * @return \Attendee\Bundle\ApiBundle\Entity\Attendance[]
     */
    public function getAttendances()
    {
        return $this->attendances;
    }

    /**
     * @param \Attendee\Bundle\ApiBundle\Entity\TeamManager[] $teamManagers
     */
    public function setTeamManagers($teamManagers)
    {
        $this->teamManagers = $teamManagers;
    }

    /**
     * @return \Attendee\Bundle\ApiBundle\Entity\TeamManager[]
     */
    public function getTeamManagers()
    {
        return $this->teamManagers;
    }

    /**
     * @param \Attendee\Bundle\ApiBundle\Entity\Team[] $teams
     *
     * @return $this
     */
    public function setTeams($teams)
    {
        $this->teams = $teams;

        return $this;
    }

    /**
     * @return \Attendee\Bundle\ApiBundle\Entity\Team[]
     */
    public function getTeams()
    {
        return $this->teams;
    }

    /**
     * @param Team $team
     *
     * @return $this
     */
    public function addTeam(Team $team)
    {
        $this->teams->add($team);

        return $this;
    }

    /**
     * @param Team $existingTeam
     *
     * @return $this
     */
    public function removeTeam(Team $existingTeam)
    {
        foreach ($this->teams as $key => $team) {
            if ($team === $existingTeam) {
                $this->teams->remove($key);
                if ($existingTeam->hasUser($this)) {
                    $existingTeam->removeUser($this);
                }
                break;
            }
        }

        return $this;
    }

    /**
     * @param Team $team
     *
     * @return bool
     */
    public function belongsTo(Team $team)
    {
        foreach ($this->teams as $existingTeam) {
            if ($team === $existingTeam) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return \Datetime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @return \Datetime
     */
    public function getUpdated()
    {
        return $this->updated;
    }
}
