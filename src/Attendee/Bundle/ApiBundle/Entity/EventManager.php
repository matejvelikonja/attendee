<?php

namespace Attendee\Bundle\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * EventManager
 *
 * @ORM\Entity
 *
 * @Serializer\ExclusionPolicy("all")
 */
class EventManager extends Manager
{
    /**
     * @var Event
     *
     * @ORM\ManyToOne(targetEntity="Event", inversedBy="managers")
     */
    private $event;

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
