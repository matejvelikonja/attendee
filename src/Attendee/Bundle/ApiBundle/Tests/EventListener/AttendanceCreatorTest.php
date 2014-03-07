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
        /** @var Location $location */
        $location  = $this->getRepo('AttendeeApiBundle:Location')->findOneBy(array());
        /** @var User $user */
        $user      = $this->getRepo('AttendeeApiBundle:User')->findOneBy(array());

        $team = new Team();
        $team
            ->setName('Team ' . __FUNCTION__)
            ->addUser($user);

        $startDate = new \DateTime('now');
        $endDate   = new \DateTime('+1 week');

        $rRule = new RecurrenceRule(
            sprintf('FREQ=DAILY;INTERVAL=1;UNTIL=%s;BYHOUR=1;BYMINUTE=1', $endDate->format('c')),
            $startDate
        );

        $schedule = new Schedule();
        $schedule
            ->setName('Schedule ' . __FUNCTION__)
            ->addTeam($team)
            ->setDefaultLocation($location)
            ->setRRule($rRule);

        $this->em()->persist($schedule);
        $this->em()->flush();

        /** LET'S ADD NEW USER TO TEAM */

        $newUser = new User();
        $newUser
            ->setEmail('user.' . strtolower(__FUNCTION__) . rand() . '@example.com')
            ->setPlainPassword(__FUNCTION__);

        $team->addUser($newUser);

        $this->em()->persist($team);
        $this->em()->flush();

        /** ATTENDANCE SHOULD BE CREATED */

        $attendances = $this->getRepo('AttendeeApiBundle:Attendance')->findBy(array(
            'user' => $newUser
        ));

        $this->assertGreaterThan(0, count($attendances), 'New user should have some attendances.');
    }
} 