<?php

namespace Attendee\Bundle\ApiBundle\Tests\EventListener;

use Attendee\Bundle\ApiBundle\Entity\Event;
use Attendee\Bundle\ApiBundle\Tests\BaseTestCase;
use Attendee\Bundle\ApiBundle\Entity\Location;
use Attendee\Bundle\ApiBundle\Entity\Schedule;
use Recurr\RecurrenceRule;

/**
 * Class EventSchedulerTest
 *
 * @package   Attendee\Bundle\ApiBundle\Tests\EventListener
 */
class EventSchedulerTest extends BaseTestCase
{
    /**
     * Tests if events are automatically created after schedule.
     */
    public function testIfEventsAreCreated()
    {
        $teams     = $this->getRepo('AttendeeApiBundle:Team')->findBy(array(), array(), 2);
        /** @var Location $location */
        $location  = $this->getRepo('AttendeeApiBundle:Location')->findOneBy(array());
        $startDate = new \DateTime('now');
        $endDate   = new \DateTime('+1 week');

        $rRule = new RecurrenceRule(
            sprintf('FREQ=DAILY;INTERVAL=1;UNTIL=%s;BYHOUR=6', $endDate->format('c')),
            $startDate
        );

        $schedule = new Schedule();
        $schedule
            ->setName('Test schedule')
            ->setTeams($teams)
            ->setDefaultLocation($location)
            ->setRRule($rRule);

        $this->em()->persist($schedule);
        $this->em()->flush();

        $events = $this->getRepo('AttendeeApiBundle:Event')->findBy(array('schedule' => $schedule));

        $this->assertCount(7, $events);

        /** @var Event $event */
        foreach ($events as $event) {
            $this->assertEquals(6, $event->getStartsAt()->format('H'), 'Event should be at 6am.');
        }
    }
}