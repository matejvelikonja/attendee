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
use Symfony\Bridge\Monolog\Logger;


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
     * @var \Symfony\Bridge\Monolog\Logger
     */
    private $logger;

    /**
     * @param EventService    $eventService
     * @param ScheduleService $scheduleService
     * @param Logger          $logger
     *
     * @DI\InjectParams({
     *     "eventService"    = @DI\Inject("attendee.event_service"),
     *     "scheduleService" = @DI\Inject("attendee.schedule_service"),
     *     "logger"          = @DI\Inject("logger"),
     * })
     */
    public function __construct(EventService $eventService, ScheduleService $scheduleService, Logger $logger)
    {
        $this->eventService    = $eventService;
        $this->scheduleService = $scheduleService;
        $this->logger          = $logger;
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
            $this->logger->addDebug(sprintf('Persisted attendance user_id=`%d`, event=`%s`.', $user->getId(), $event));
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
                    $this->logger->addDebug(sprintf('Trying to fix team `%s`(%d).', $team->getName(), $team->getId()));
                    if ($this->fixTeamAttendances($team)) {
                        $changed = true;
                        $this->logger->addDebug(sprintf('Fix team `%s`(%d) attendances.', $team->getName(), $team->getId()));
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
            $this->logger->addDebug(sprintf('Persisted attendance user_id=`%d`, event=`%s` because of fixing team attendances.', $user->getId(), $event));
        }
    }
}