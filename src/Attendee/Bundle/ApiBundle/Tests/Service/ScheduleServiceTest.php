<?php

namespace Attendee\Bundle\ApiBundle\Tests\Service;

use Attendee\Bundle\ApiBundle\Entity\Attendance;
use Attendee\Bundle\ApiBundle\Entity\Event;
use Attendee\Bundle\ApiBundle\Entity\Schedule;
use Attendee\Bundle\ApiBundle\Entity\User;
use Attendee\Bundle\ApiBundle\Service\ScheduleService;
use Doctrine\ORM\EntityManager;

/**
 * Class ScheduleServiceTest
 *
 * @package Attendee\Bundle\ApiBundle\Tests\Service
 */
class ScheduleServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests if checkAttendance method returns true if attendance has user.
     */
    public function testCheckAttendancesMethod()
    {
        /** @var EntityManager $em */
        $em         = $this->getMock('Doctrine\ORM\EntityManager', array(), array(), '', false);
        $service    = new ScheduleService($em);
        $schedule   = new Schedule();
        $event      = new Event();
        $attendance = new Attendance();
        $user       = new User();

        $attendance->setUser($user);
        $event->setAttendances(array($attendance));
        $schedule->setEvents(array($event));

        $this->assertTrue($service->checkAttendances($schedule, $user));
    }
}
 