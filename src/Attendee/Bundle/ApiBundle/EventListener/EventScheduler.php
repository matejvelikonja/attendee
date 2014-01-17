<?php

namespace Attendee\Bundle\ApiBundle\EventListener;

use Attendee\Bundle\ApiBundle\Entity\Event;
use Attendee\Bundle\ApiBundle\Entity\Schedule;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Recurr\RecurrenceRule;
use Recurr\RecurrenceRuleTransformer;
use Recurr\TransformerConfig;
use JMS\DiExtraBundle\Annotation\DoctrineListener;

/**
 * Class EventScheduler
 *
 * @package Attendee\Bundle\ApiBundle\EventListener
 *
 * @DoctrineListener(events = { "prePersist" })
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

        foreach ($events as $eventDate) {
            $event = new Event();
            $event
                ->setStartsAt($eventDate)
                ->setEndsAt($eventDate)
                ->setSchedule($schedule)
                ->setLocation($schedule->getDefaultLocation());

            $args->getEntityManager()->persist($event);
        }
    }

    /**
     * @param Schedule $schedule
     *
     * @return \Datetime[]
     */
    private function calculateEvents(Schedule $schedule)
    {
        $transformer = new RecurrenceRuleTransformer(
            new RecurrenceRule(
                $schedule->getRRule(),
                $schedule->getStartsAt()
            )
        );

        $transformerConfig = new TransformerConfig();
        $transformerConfig->enableLastDayOfMonthFix();

        $transformer->setTransformerConfig($transformerConfig);

        return $transformer->getComputedArray();
    }
} 