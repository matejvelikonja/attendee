<?php

namespace Attendee\Bundle\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * TeamManager
 *
 * @ORM\Table(name="team_managers")
 * @ORM\Entity
 *
 * @Serializer\ExclusionPolicy("all")
 */
class TeamManager extends AbstractEntity
{
    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="teamManagers")
     */
    private $user;

    /**
     * @var Team
     *
     * @ORM\ManyToOne(targetEntity="Team", inversedBy="teamManagers")
     */
    private $team;

    /**
     * @param \Attendee\Bundle\ApiBundle\Entity\User $user
     *
     * @return $this
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return \Attendee\Bundle\ApiBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param \Attendee\Bundle\ApiBundle\Entity\Team $team
     *
     * @return $this
     */
    public function setTeam(Team $team)
    {
        $team->addTeamManager($this);
        $this->team = $team;

        return $this;
    }

    /**
     * @return \Attendee\Bundle\ApiBundle\Entity\Team
     */
    public function getTeam()
    {
        return $this->team;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getId();
    }
}
