<?php

namespace Attendee\Bundle\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * Attendance
 *
 * @ORM\Table(name="attendances")
 * @ORM\Entity
 *
 * @Serializer\ExclusionPolicy("all")
 */
class Attendance extends AbstractEntity
{
    const STATUS_PRESENT = 1;
    const STATUS_ABSENT  = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="status", type="smallint")
     *
     * @Serializer\Expose
     */
    private $status;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="attendances")
     */
    private $user;

    /**
     * @var Event
     *
     * @ORM\ManyToOne(targetEntity="Event", inversedBy="attendances")
     */
    private $event;

    /**
     * @Serializer\VirtualProperty
     * @Serializer\SerializedName("user")
     *
     * @return int
     */
    public function getUserId()
    {
        return $this->getUser()->getId();
    }

    /**
     * @Serializer\VirtualProperty
     * @Serializer\SerializedName("user_name")
     *
     * @return string
     */
    public function getUserName()
    {
        return $this->getUser()->getName();
    }

    /**
     * Set status
     *
     * @param int $status
     *
     * @return Attendance
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return integer[]
     */
    public static function getStatuses()
    {
        return array(
            self::STATUS_PRESENT,
            self::STATUS_ABSENT
        );
    }

    /**
     * @param \Attendee\Bundle\ApiBundle\Entity\Event $event
     *
     * @return $this
     */
    public function setEvent(Event $event)
    {
        $this->event = $event;

        return $this;
    }

    /**
     * @return \Attendee\Bundle\ApiBundle\Entity\Event
     */
    public function getEvent()
    {
        return $this->event;
    }

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
