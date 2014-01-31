<?php

namespace Attendee\Bundle\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * Event
 *
 * @ORM\Table(name="events")
 * @ORM\Entity
 *
 * @Serializer\ExclusionPolicy("all")
 */
class Event extends AbstractEntity
{
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="starts_at", type="datetimetz")
     *
     * @Serializer\Expose
     */
    private $startsAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="ends_at", type="datetimetz")
     *
     * @Serializer\Expose
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
     * @var EventManager[]
     *
     * @ORM\OneToMany(targetEntity="EventManager", mappedBy="event")
     */
    private $managers;

    /**
     * @var Location
     *
     * @ORM\ManyToOne(targetEntity="Location")
     * @ORM\JoinColumn(name="location_id", referencedColumnName="id")
     */
    private $location;

    /**
     * @Serializer\VirtualProperty
     * @Serializer\SerializedName("attendances")
     *
     * @return int[]
     */
    public function getAttendancesIds()
    {
        $ids = array();

        foreach ($this->attendances as $attendance) {
            $ids[] = $attendance->getId();
        }

        return $ids;
    }

    /**
     * @Serializer\VirtualProperty
     * @Serializer\SerializedName("location")
     *
     * @return int
     */
    public function getLocationId()
    {
        return $this->getLocation()->getId();
    }

    /**
     * @Serializer\VirtualProperty
     * @Serializer\SerializedName("name")
     *
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
    public function setStartsAt(\DateTime $startsAt)
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
    public function setEndsAt(\DateTime $endsAt)
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

    /**
     * @param \Attendee\Bundle\ApiBundle\Entity\Location $location
     */
    public function setLocation(Location $location = null)
    {
        $this->location = $location;
    }

    /**
     * @return \Attendee\Bundle\ApiBundle\Entity\Location
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param \Attendee\Bundle\ApiBundle\Entity\EventManager[] $managers
     */
    public function setManagers($managers)
    {
        $this->managers = $managers;
    }

    /**
     * @return \Attendee\Bundle\ApiBundle\Entity\EventManager[]
     */
    public function getManagers()
    {
        return $this->managers;
    }
}
