<?php

namespace Attendee\Bundle\ApiBundle\EventListener;

use JMS\DiExtraBundle\Annotation as DI;
use Attendee\Bundle\ApiBundle\Entity\Attendance;
use Attendee\Bundle\ApiBundle\Entity\Event;
use Attendee\Bundle\ApiBundle\Service\EventService;
use Doctrine\ORM\Event\LifecycleEventArgs;


/**
 * Class AttendanceCreator
 *
 * @package Attendee\Bundle\ApiBundle\EventListener
 *
 * @DI\DoctrineListener(events = { "prePersist" })
 */
class AttendanceCreator
{
    /**
     * @var \Attendee\Bundle\ApiBundle\Service\EventService
     */
    private $eventService;

    /**
     * @param EventService $eventService
     *
     * @DI\InjectParams({
     *     "eventService" = @DI\Inject("attendee.event_service")
     * })
     */
    public function __construct(EventService $eventService)
    {
        $this->eventService = $eventService;
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        /** @var Event $schedule */
        $event = $args->getEntity();

        if (! $event instanceof Event) {
            return;
        }

        $users = $this->eventService->getUsers($event);

        foreach ($users as $user) {
            $attendance = new Attendance();
            $attendance
                ->setUser($user)
                ->setEvent($event);

            $args->getEntityManager()->persist($attendance);
        }
    }
} 