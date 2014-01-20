<?php

namespace Attendee\Bundle\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Attendee\Bundle\UserBundle\Entity\User as BaseUser;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as Serializer;

/**
 * User
 *
 * @ORM\Table(name="users")
 * @ORM\Entity()
 *
 * @Serializer\ExclusionPolicy("all")
 * @Serializer\AccessType("public_method")
 */
class User extends BaseUser
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->teams = new ArrayCollection();
    }

    /**
     * @var Attendance[]
     *
     * @ORM\OneToMany(targetEntity="Attendance", mappedBy="user")
     */
    private $attendances;

    /**
     * @var Team[] | ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Team", inversedBy="users")
     */
    private $teams;

    /**
     * @Serializer\VirtualProperty
     * @Serializer\SerializedName("name")
     *
     * @return string
     */
    public function getName()
    {
        return $this->getFirstName() . ' ' . $this->getLastName();
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
     * @param Team $team
     *
     * @return bool
     */
    public function belongsTo(Team $team)
    {
        foreach($this->teams as $existingTeam) {
            if ($team === $existingTeam) {
                return true;
            }
        }

        return false;
    }

}
