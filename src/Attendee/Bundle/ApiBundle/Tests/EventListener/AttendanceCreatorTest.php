<?php

namespace Attendee\Bundle\ApiBundle\Tests\EventListener;

use Attendee\Bundle\ApiBundle\Entity\Location;
use Attendee\Bundle\ApiBundle\Entity\Schedule;
use Attendee\Bundle\ApiBundle\Entity\Team;
use Attendee\Bundle\ApiBundle\Entity\User;
use Attendee\Bundle\ApiBundle\Tests\BaseTestCase;
use Recurr\RecurrenceRule;

/**
 * Class AttendanceCreatorTest
 *
 * @package Attendee\Bundle\ApiBundle\Tests\EventListener
 */
class AttendanceCreatorTest extends BaseTestCase
{
    /**
     * Tests if user's attendance is created when user is added to team with some schedule.
     */
    public function testIfAttendanceIsCreatedWhenUserIsAddedToTeam()
    {
        /** FIRST LET'S CREATE SOME TEAM WITH SCHEDULE */

        $team = $this->createTestTeam(__FUNCTION__);

        /** LET'S ADD NEW USER TO TEAM */

        $newUser = new User();
        $newUser
            ->setEmail('user.' . strtolower(__FUNCTION__) . rand() . '@example.com')
            ->setPlainPassword(__FUNCTION__);

        $team->addUser($newUser);

        $this->em()->persist($team);
        $this->em()->flush();

        /** ATTENDANCES SHOULD BE CREATED */

        $attendances = $this->getRepo('AttendeeApiBundle:Attendance')->findBy(array(
            'user' => $newUser
        ));

        $this->assertEquals(7, count($attendances), 'New user should have some attendances.');
    }

    /**
     * Test's if attendances are removed when user is removed from team.
     */
    public function testIfAttendancesAreRemovedWhenUserIsRemovedFromTeam()
    {
        /** FIRST LET'S CREATE SOME TEAM WITH SCHEDULE */
        $team = $this->createTestTeam(__FUNCTION__);

        /** @var USER IS REMOVED FROM TEAM */

        $user = $team->getUsers()->first();
        $team->removeUser($user);

        $this->em()->persist($team);
        $this->em()->flush();

        /** ATTENDANCES SHOULD BE REMOVED */

        $attendancesCount = $this->getRepo('AttendeeApiBundle:Attendance')
            ->createQueryBuilder('a')
            ->select('count(a.id)')
            ->leftJoin('a.event', 'e')
            ->leftJoin('e.schedule', 's')
            ->leftJoin('s.teams', 't')
            ->where('a.user = :user')
            ->andWhere('t.id = :team')
            ->setParameter('user', $user)
            ->setParameter('team', $team->getId())
            ->getQuery()->getSingleScalarResult();

        $this->assertEquals(0, $attendancesCount, 'All user attendances should be removed.');
    }

    /**
     * @param string $name
     *
     * @return Team
     */
    private function createTestTeam($name)
    {
        /** @var User $user */
        $user     = $this->getRepo('AttendeeApiBundle:User')->findOneBy(array());
        /** @var Location $location */
        $location = $this->getRepo('AttendeeApiBundle:Location')->findOneBy(array());

        $team = new Team();
        $team
            ->setName('Team ' . $name)
            ->addUser($user);

        $startDate = new \DateTime('now');
        $endDate   = new \DateTime('+1 week');

        $rRule = new RecurrenceRule(
            sprintf('FREQ=DAILY;INTERVAL=1;UNTIL=%s;BYHOUR=1;BYMINUTE=1', $endDate->format('c')),
            $startDate
        );

        $schedule = new Schedule();
        $schedule
            ->setName('Schedule ' . $name)
            ->addTeam($team)
            ->setDefaultLocation($location)
            ->setRRule($rRule);

        $this->em()->persist($schedule);
        $this->em()->flush();

        return $team;
    }
} 