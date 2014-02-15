<?php

namespace Attendee\Bundle\ApiBundle\EventListener;

use Attendee\Bundle\ApiBundle\Entity\Event;
use Attendee\Bundle\ApiBundle\Entity\Schedule;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Recurr\RecurrenceRuleTransformer;
use Recurr\TransformerConfig;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * Class EventScheduler
 *
 * @package Attendee\Bundle\ApiBundle\EventListener
 *
 * @DI\DoctrineListener(events = { "prePersist" })
 * @DI\Service("attendee.event_scheduler")
 */
class EventScheduler
{
    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        /** @var Schedule $schedule */
        $schedule = $args->getEntity();

        if (! $schedule instanceof Schedule) {
            return;
        }

        $events = $this->calculateEvents($schedule);

        foreach ($events as $event) {
            $args->getEntityManager()->persist($event);
        }
    }

    /**
     * @param Schedule $schedule
     *
     * @return Event[]
     */
    public function calculateEvents(Schedule $schedule)
    {
        $transformer = new RecurrenceRuleTransformer($schedule->getRRule());

        $transformerConfig = new TransformerConfig();
        $transformerConfig->enableLastDayOfMonthFix();

        $transformer->setTransformerConfig($transformerConfig);

        $events = array();
        foreach ($transformer->getComputedArray() as $eventDate) {
            $event = new Event();
            $event
                ->setStartsAt($eventDate)
                ->setEndsAt($eventDate)
                ->setSchedule($schedule)
                ->setLocation($schedule->getDefaultLocation());

            $events[] = $event;
        }

        return $events;
    }
} 