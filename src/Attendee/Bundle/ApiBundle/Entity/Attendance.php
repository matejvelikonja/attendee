<?php

namespace Attendee\Bundle\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * Attendance
 *
 * @ORM\Table(name="attendances", uniqueConstraints={@ORM\UniqueConstraint(name="user_event_idx", columns={"user_id", "event_id"})})
 * @ORM\Entity
 *
 * @Serializer\ExclusionPolicy("all")
 */
class Attendance extends AbstractEntity
{
    const STATUS_PRESENT = 'present';
    const STATUS_ABSENT  = 'absent';
    const STATUS_EMPTY   = '';

    /**
     * @var int
     *
     * @ORM\Column(name="status", type="string")
     *
     * @Serializer\Expose
     */
    private $status = self::STATUS_EMPTY;

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
     * @Serializer\VirtualProperty
     * @Serializer\SerializedName("event")
     *
     * @return int
     */
    public function getEventId()
    {
        return $this->getEvent()->getId();
    }

    /**
     * Set status
     *
     * @param string $status
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
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return string[]
     */
    public static function getStatuses()
    {
        return array(
            self::STATUS_PRESENT,
            self::STATUS_ABSENT,
            self::STATUS_EMPTY
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
        $event->addAttendance($this);

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

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getId() . ' ' . $this->getStatus();
    }
}
