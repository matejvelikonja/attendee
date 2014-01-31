<?php

namespace Attendee\Bundle\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * Manager
 *
 * @ORM\Table(name="managers")
 * @ORM\Entity
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="manager_for", type="string")
 * @ORM\DiscriminatorMap({"event" = "EventManager", "schedule" = "ScheduleManager"})
 *
 * @Serializer\ExclusionPolicy("all")
 */
class Manager extends AbstractEntity
{
    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="eventManagers")
     */
    private $user;

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
}
