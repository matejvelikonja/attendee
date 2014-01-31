<?php

namespace Attendee\Bundle\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * ScheduleManager
 *
 * @ORM\Entity
 *
 * @Serializer\ExclusionPolicy("all")
 */
class ScheduleManager extends Manager
{
    /**
     * @var Schedule
     *
     * @ORM\ManyToOne(targetEntity="Schedule", inversedBy="managers")
     */
    private $schedule;

    /**
     * @param \Attendee\Bundle\ApiBundle\Entity\Schedule $schedule
     *
     * @return $this
     */
    public function setSchedule($schedule)
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
}
