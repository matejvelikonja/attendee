<?php

namespace Attendee\Bundle\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EventOccurrence
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Event
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
     * @var EventSchedule
     *
     * @ORM\ManyToOne(targetEntity="EventSchedule", inversedBy="events")
     */
    private $schedule;

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
     * @return string
     */
    public function getName()
    {
        return $this->getSchedule()->getName();
    }

    /**
     * Set startsAt
     *
     * @param \DateTime $startsAt
     *
     * @return $this
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
     *
     * @return $this
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
     * @param \Attendee\Bundle\ApiBundle\Entity\EventSchedule $schedule
     *
     * @return $this
     */
    public function setSchedule(EventSchedule $schedule)
    {
        $this->schedule = $schedule;

        return $this;
    }

    /**
     * @return \Attendee\Bundle\ApiBundle\Entity\EventSchedule
     */
    public function getSchedule()
    {
        return $this->schedule;
    }
}
