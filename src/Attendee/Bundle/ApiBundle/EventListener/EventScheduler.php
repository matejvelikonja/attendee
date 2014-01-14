<?php

namespace Attendee\Bundle\ApiBundle\EventListener;

use Attendee\Bundle\ApiBundle\Entity\Event;
use Attendee\Bundle\ApiBundle\Entity\EventSchedule;
use Doctrine\ORM\Event\LifecycleEventArgs;

use JMS\DiExtraBundle\Annotation\DoctrineListener;

/**
 * Class EventScheduler
 *
 * @package Attendee\Bundle\ApiBundle\EventListener
 *
 * @DoctrineListener(
 *     events = {"prePersist"}
 * )
 */
class EventScheduler
{
    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        /** @var EventSchedule $schedule */
        $schedule = $args->getEntity();

        if (! $schedule instanceof EventSchedule) {
            return;
        }

        // TODO: needs to read rules about occurrences and create event
        $event = new Event();
        $event
            ->setStartsAt($schedule->getStartsAt())
            ->setEndsAt($schedule->getEndsAt())
            ->setSchedule($schedule);

        $args->getEntityManager()->persist($event);
    }
} 