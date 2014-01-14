<?php

namespace Attendee\Bundle\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EventOccurrence
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class EventOccurrence
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="starts_at", type="datetimetz")
     */
    private $startsAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="ends_at", type="datetimetz")
     */
    private $endsAt;

    /**
     * @var Event
     *
     * @ORM\ManyToOne(targetEntity="Event", inversedBy="occurrences")
     */
    private $event;

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
     * Set startsAt
     *
     * @param \DateTime $startsAt
     * @return EventOccurrence
     */
    public function setStartsAt($startsAt)
    {
        $this->startsAt = $startsAt;

        return $this;
    }

    /**
     * Get startsAt
     *
     * @return \DateTime 
     */
    public function getStartsAt()
    {
        return $this->startsAt;
    }

    /**
     * Set endsAt
     *
     * @param \DateTime $endsAt
     * @return EventOccurrence
     */
    public function setEndsAt($endsAt)
    {
        $this->endsAt = $endsAt;

        return $this;
    }

    /**
     * Get endsAt
     *
     * @return \DateTime 
     */
    public function getEndsAt()
    {
        return $this->endsAt;
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
}
