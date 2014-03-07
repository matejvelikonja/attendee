<?php

namespace Attendee\Bundle\ApiBundle\EventListener;

use Attendee\Bundle\ApiBundle\Entity\Schedule;
use Attendee\Bundle\ApiBundle\Entity\Team;
use Attendee\Bundle\ApiBundle\Entity\User;
use Attendee\Bundle\ApiBundle\Service\ScheduleService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\PersistentCollection;
use JMS\DiExtraBundle\Annotation as DI;
use Attendee\Bundle\ApiBundle\Entity\Attendance;
use Attendee\Bundle\ApiBundle\Entity\Event;
use Attendee\Bundle\ApiBundle\Service\EventService;
use Doctrine\ORM\Event\LifecycleEventArgs;


/**
 * Class AttendanceCreator
 *
 * Attendances are created when event is created for the first time or
 * when new user is added to existing team that already has some events.
 *
 * @package Attendee\Bundle\ApiBundle\EventListener
 *
 * @DI\DoctrineListener(events = { "prePersist", "onFlush" })
 * @DI\Service("attendee.attendance_creator")
 */
class AttendanceCreator
{
    /**
     * @var \Attendee\Bundle\ApiBundle\Service\EventService
     */
    private $eventService;

    /**
     * @var \Attendee\Bundle\ApiBundle\Service\ScheduleService
     */
    private $scheduleService;

    /**
     * @var EntityManager | null
     */
    private $em;

    /**
     * @param EventService    $eventService
     * @param ScheduleService $scheduleService
     *
     * @DI\InjectParams({
     *     "eventService"    = @DI\Inject("attendee.event_service"),
     *     "scheduleService" = @DI\Inject("attendee.schedule_service")
     * })
     */
    public function __construct(EventService $eventService, ScheduleService $scheduleService)
    {
        $this->eventService    = $eventService;
        $this->scheduleService = $scheduleService;
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

    /**
     * @param OnFlushEventArgs $args
     */
    public function onFlush(OnFlushEventArgs $args)
    {
        $collections = $args->getEntityManager()->getUnitOfWork()->getScheduledCollectionUpdates();
        $this->em    = $args->getEntityManager();
        $changed     = false;

        /** @var PersistentCollection $collection */
        foreach ($collections as $collection) {
            foreach ($collection->getInsertDiff() as $team) {
                if ($team instanceof Team) {
                    if ($this->fixTeamAttendances($team)) {
                        $changed = true;
                    }
                }
            }
        }

        if ($changed) {
            $args->getEntityManager()->getUnitOfWork()->computeChangeSets();
        }
    }

    /**
     * @param Team $team
     *
     * @return bool
     */
    private function fixTeamAttendances(Team $team)
    {
        $changed = false;

        foreach ($team->getSchedules() as $schedule) {
            foreach ($team->getUsers() as $user) {
                if (!$this->scheduleService->checkAttendances($schedule, $user)) {
                    $this->createAttendances($schedule, $user);
                    $changed = true;
                }
            }
        }

        return $changed;
    }

    /**
     * @param Schedule $schedule
     * @param User     $user
     */
    private function createAttendances(Schedule $schedule, User $user)
    {
        foreach ($schedule->getEvents() as $event) {
            $attendance = new Attendance();
            $attendance->setUser($user);
            $event->addAttendance($attendance);

            $this->em->persist($attendance);
        }
    }
}