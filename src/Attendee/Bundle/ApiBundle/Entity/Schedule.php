<?php

namespace Attendee\Bundle\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;

/**
 * Event
 *
 * @ORM\Table()
 * @ORM\Entity
 * @HasLifecycleCallbacks
 */
class Schedule
{
    const WEEKLY  = 'weekly';
    const MONTHLY = 'monthly';
    const YEARLY  = 'yearly';

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

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
     * @var Event[]
     *
     * @ORM\OneToMany(targetEntity="Event", mappedBy="schedule")
     */
    private $events;

    /**
     * @var string
     *
     * @ORM\Column(name="r_rule", type="string", length=255)
     */
    private $rRule;

    /**
     * @var Location
     *
     * @ORM\ManyToOne(targetEntity="Location")
     * @ORM\JoinColumn(name="location_id", referencedColumnName="id")
     */
    private $defaultLocation;

    /**
     * @var string
     */
    private $frequency;

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
     * Set name
     *
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
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
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function setRRule()
    {
        $this->rRule = sprintf('freq=%s;until=%s',
            $this->frequency,
            $this->endsAt->format('c')
        );
    }

    /**
     * @return string
     */
    public function getRRule()
    {
        if (! $this->rRule) {
            $this->setRRule();
        }

        return $this->rRule;
    }

    /**
     * @param \Attendee\Bundle\ApiBundle\Entity\Event[] $events
     *
     * @return $this
     */
    public function setEvents($events)
    {
        $this->events = $events;

        return $this;
    }

    /**
     * @return \Attendee\Bundle\ApiBundle\Entity\Event[]
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * @param string $frequency
     *
     * @return $this
     */
    public function setFrequency($frequency)
    {
        $this->frequency = $frequency;

        return $this;
    }

    /**
     * @param \Attendee\Bundle\ApiBundle\Entity\Location $defaultLocation
     */
    public function setDefaultLocation(Location $defaultLocation = null)
    {
        $this->defaultLocation = $defaultLocation;
    }

    /**
     * @return \Attendee\Bundle\ApiBundle\Entity\Location
     */
    public function getDefaultLocation()
    {
        return $this->defaultLocation;
    }
}
