<?php

namespace Attendee\Bundle\ApiBundle\Security\Voter;

use Attendee\Bundle\ApiBundle\Entity\Event;
use Attendee\Bundle\ApiBundle\Entity\Schedule;
use Attendee\Bundle\ApiBundle\Entity\Team;
use Attendee\Bundle\ApiBundle\Entity\TeamManager;
use Attendee\Bundle\ApiBundle\Entity\User;
use Attendee\Bundle\ApiBundle\Tests\BaseTestCase;
use Recurr\RecurrenceRule;

/**
 * Class EventVoterTest
 *
 * @package   Attendee\Bundle\ApiBundle\Security\Voter
 */
class EventVoterTest  extends BaseTestCase
{
    /**
     * Tests if user cannot access event detail if (s)he is not a manager.
     */
    public function testIfUserCantAccessEventWhichHeDoesNotOwn()
    {
        /** @var User $admin */
        $admin = $this->getRepo('AttendeeApiBundle:User')->find(1);
        /** @var User $user */
        $user  = $this->getRepo('AttendeeApiBundle:User')->find(2);

        $startDate = new \DateTime('now');
        $endDate   = new \DateTime('+1 week');

        $rRule = new RecurrenceRule(
            sprintf('FREQ=DAILY;INTERVAL=1;UNTIL=%s;BYHOUR=13;BYMINUTE=13', $endDate->format('c')),
            $startDate
        );

        $schedule = new Schedule();
        $schedule
            ->setName('schedule name')
            ->setRRule($rRule);

        $team = new Team();
        $team
            ->setName('team name')
            ->addSchedule($schedule);

        $manager = new TeamManager();
        $manager
            ->setUser($admin)
            ->setTeam($team);

        $this->em()->persist($team);
        $this->em()->persist($manager);
        $this->em()->flush();

        /** @var Event $event */
        $event = $this->getRepo('AttendeeApiBundle:Event')->findOneBy(array('schedule' => $schedule));

        $userClient = $this->createAuthorizedClient($user);
        $userClient->request('GET', $this->url("api_events_show", array('id' => $event->getId())));
        $this->assertEquals(403, $userClient->getResponse()->getStatusCode(), 'User should not have permission to this event.');

        $adminClient = $this->createAuthorizedClient($admin);
        $adminClient->request('GET', $this->url("api_events_show", array('id' => $event->getId())));
        $this->assertEquals(200, $adminClient->getResponse()->getStatusCode(), 'Admin should have permission for this event.');
    }
}