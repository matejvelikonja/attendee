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
     * @var Schedule
     *
     * @ORM\ManyToOne(targetEntity="Schedule", inversedBy="events")
     */
    private $schedule;

    /**
     * @var Attendance[]
     *
     * @ORM\OneToMany(targetEntity="Attendance", mappedBy="event")
     */
    private $attendances;

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
     * @param \Attendee\Bundle\ApiBundle\Entity\Schedule $schedule
     *
     * @return $this
     */
    public function setSchedule(Schedule $schedule)
    {
        $this->schedule = $schedule;

        return $this;
    }

    /**
     * @return \Attendee\Bundle\ApiBundle\Entity\Schedule
     */
    public function getSchedule()
    {
        return $this->schedule;
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
}
