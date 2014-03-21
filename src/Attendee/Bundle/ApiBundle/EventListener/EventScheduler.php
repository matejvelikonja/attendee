<?php

namespace Attendee\Bundle\ApiBundle\EventListener;

use Attendee\Bundle\ApiBundle\Entity\Event;
use Attendee\Bundle\ApiBundle\Entity\Schedule;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Recurr\RecurrenceRuleTransformer;
use Recurr\TransformerConfig;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Bridge\Monolog\Logger;

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
     * @var \Symfony\Bridge\Monolog\Logger
     */
    private $logger;

    /**
     * @param Logger $logger
     *
     * @DI\InjectParams({
     *     "logger" = @DI\Inject("logger"),
     * })
     */
    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

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
        $schedule->setEvents($events);
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
        /** @var \DateTime $eventDate */
        foreach ($transformer->getComputedArray() as $eventDate) {
            $endDate = clone $eventDate;
            $endDate->add($schedule->getDuration());

            $event = new Event();
            $event
                ->setStartsAt($eventDate)
                ->setEndsAt($endDate)
                ->setSchedule($schedule)
                ->setLocation($schedule->getDefaultLocation());

            $events[] = $event;

            $this->logger->addDebug(sprintf('Created event `%s`.', $event));
        }

        return $events;
    }
} 