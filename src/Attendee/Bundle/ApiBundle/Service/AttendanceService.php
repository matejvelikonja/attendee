<?php

namespace Attendee\Bundle\ApiBundle\Service;

use Attendee\Bundle\ApiBundle\Entity\Attendance;
use Attendee\Bundle\ApiBundle\Entity\Event;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * Class AttendanceService
 *
 * @package Attendee\Bundle\ApiBundle\Service
 *
 * @DI\Service("attendee.attendance_service")
 */
class AttendanceService
{
    /**
     * @var EntityRepository
     */
    private $repo;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * @DI\InjectParams({
     *      "em" = @DI\Inject("doctrine.orm.entity_manager")
     * })
     */
    public function __construct(EntityManager $em)
    {
        $this->em   = $em;
        $this->repo = $em->getRepository('AttendeeApiBundle:Attendance');
    }

    /**
     * @param int[] $ids
     *
     * @return Attendance[]
     */
    public function find(array $ids = null)
    {
        $qb = $this->repo->createQueryBuilder('a');

        if ($ids) {
            $qb
                ->where('a.id IN (:ids)')
                ->setParameter('ids', $ids);
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * @param Attendance $attendance
     */
    public function update(Attendance $attendance)
    {
        $this->em->persist($attendance);
        $this->em->flush();
    }

    /**
     * @param Attendance $attendance
     * @param string     $status
     */
    public function changeStatus(Attendance $attendance, $status)
    {
        $attendance->setStatus($status);
        $this->update($attendance);
    }

    /**
     * @param Event[] $events
     *
     * @return Attendance[]
     */
    public function findByEvents($events)
    {
        $all = array();

        foreach ($events as $event) {
            $byEvent = $this->findByEvent($event);
            $all = array_merge($all, $byEvent);
        }

        return $all;
    }

    /**
     * @param $event Event
     *
     * @return Attendance[]
     */
    public function findByEvent(Event $event)
    {
        $attendances = array();

        foreach ($event->getAttendances() as $attendance) {
            $attendances[] = $attendance;
        }

        return $attendances;
    }
}