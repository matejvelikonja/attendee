<?php

namespace Attendee\Bundle\ApiBundle\Service;

use Attendee\Bundle\ApiBundle\Entity\Schedule;
use Attendee\Bundle\ApiBundle\Entity\Team;
use Attendee\Bundle\ApiBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * Class ScheduleService
 *
 * @package Attendee\Bundle\ApiBundle\Service
 *
 * @DI\Service("attendee.schedule_service")
 */
class ScheduleService
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
     * @param EntityManager $em
     *
     * @DI\InjectParams({
     *      "em" = @DI\Inject("doctrine.orm.entity_manager")
     * })
     */
    public function __construct(EntityManager $em)
    {
        $this->em   = $em;
        $this->repo = $em->getRepository('AttendeeApiBundle:Schedule');
    }

    /**
     * @param Schedule $schedule
     */
    public function save(Schedule $schedule)
    {
        $this->em->persist($schedule);
        $this->em->flush();
    }

    /**
     * @param Schedule $schedule
     *
     * @return User[]
     */
    public function getUsers(Schedule $schedule)
    {
        $teams = $schedule->getTeams();
        $users = array();

        foreach ($teams as $team) {
            foreach ($team->getUsers() as $user) {
                $users[$user->getId()] = $user;
            }
        }

        return array_values($users);
    }

    /**
     * @param Schedule $schedule
     * @param User     $user
     *
     * @return bool
     */
    public function checkAttendances(Schedule $schedule, User $user)
    {
        foreach ($schedule->getEvents() as $event) {
            foreach ($event->getAttendances() as $attendance) {
                if ($attendance->getUser() === $user) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @param Team[] $teams
     *
     * @return Schedule[]
     */
    public function findByTeams(array $teams)
    {
        $schedules = array();

        foreach ($teams as $team) {
            foreach ($team->getSchedules() as $schedule) {
                $schedules[$schedule->getId()] = $schedule;
            }
        }

        return array_values($schedules);
    }

    /**
     * @param Team $team
     *
     * @return Schedule[]
     */
    public function findByTeam(Team $team)
    {
        $schedules = array();

        foreach ($team->getSchedules() as $schedule) {
            $schedules[$schedule->getId()] = $schedule;
        }

        return array_values($schedules);
    }
}