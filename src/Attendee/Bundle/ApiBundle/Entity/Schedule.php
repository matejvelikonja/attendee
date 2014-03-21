<?php

namespace Attendee\Bundle\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Recurr\RecurrenceRule;

/**
 * Schedule
 *
 * @ORM\Table(name="schedules")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Schedule extends AbstractEntity
{
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var Event[]
     *
     * @ORM\OneToMany(targetEntity="Event", mappedBy="schedule", cascade={"persist"})
     */
    private $events;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="starts_at", type="datetimetz")
     */
    private $startsAt;

    /**
     * @var string
     *
     * @ORM\Column(name="r_rule", type="string", length=255)
     */
    private $rRuleText;

    /**
     * @var RecurrenceRule
     */
    private $rRule;

    /**
     * @var \DateInterval
     *
     * @ORM\Column(name="duration", type="dateinterval")
     */
    private $duration;

    /**
     * @var Location
     *
     * @ORM\ManyToOne(targetEntity="Location")
     * @ORM\JoinColumn(name="location_id", referencedColumnName="id")
     */
    private $defaultLocation;

    /**
     * @var Team[]
     *
     * @ORM\ManyToMany(targetEntity="Team", mappedBy="schedules", cascade={"persist"})
     */
    private $teams;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->teams    = new ArrayCollection();
        $this->events   = new ArrayCollection();
        $this->duration = \DateInterval::createFromDateString('0 seconds');
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
     * @param RecurrenceRule $rRule
     *
     * @return $this
     */
    public function setRRule(RecurrenceRule $rRule)
    {
        $this->rRule     = $rRule;
        $this->rRuleText = $this->rRule->getString();
        $this->startsAt  = $rRule->getStartDate();

        return $this;
    }

    /**
     * @return RecurrenceRule
     */
    public function getRRule()
    {
        if (! $this->rRule) {
            $this->rRule = new RecurrenceRule($this->rRuleText);
            $this->rRule->setStartDate($this->startsAt);
        }

        return $this->rRule;
    }

    /**
     * @param \DateInterval $duration
     *
     * @return $this
     */
    public function setDuration(\DateInterval $duration)
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * @return \DateInterval
     */
    public function getDuration()
    {
        return $this->duration;
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
     * @param Event $event
     *
     * @return $this
     */
    public function addEvent(Event $event)
    {
        if (! $this->hasEvent($event)) {
            $this->events[] = $event;

            if ($event->getSchedule() !== $this) {
                $event->setSchedule($this);
            }
        }

        return $this;
    }


    /**
     * @param Event $event
     *
     * @return bool
     */
    public function hasEvent(Event $event)
    {
        foreach($this->events as $existingEvent) {
            if ($event === $existingEvent) {
                return true;
            }
        }

        return false;
    }


    /**
     * @param \Attendee\Bundle\ApiBundle\Entity\Location $defaultLocation
     *
     * @return $this
     */
    public function setDefaultLocation(Location $defaultLocation = null)
    {
        $this->defaultLocation = $defaultLocation;

        return $this;
    }

    /**
     * @return \Attendee\Bundle\ApiBundle\Entity\Location
     */
    public function getDefaultLocation()
    {
        return $this->defaultLocation;
    }

    /**
     * @param \Attendee\Bundle\ApiBundle\Entity\Team[] $teams
     *
     * @return $this
     */
    public function setTeams($teams)
    {
        foreach ($teams as $team) {
            $this->addTeam($team);
        }

        return $this;
    }

    /**
     * @return \Attendee\Bundle\ApiBundle\Entity\Team[] | ArrayCollection
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
        $team->addSchedule($this);
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
        foreach ($this->teams as $existingTeam) {
            if ($team === $existingTeam) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }
}
