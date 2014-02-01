<?php

namespace Attendee\Bundle\ApiBundle\Security\Voter;

use Attendee\Bundle\ApiBundle\Entity\Event;
use Attendee\Bundle\ApiBundle\Entity\EventManager;
use Attendee\Bundle\ApiBundle\Entity\User;
use Attendee\Bundle\ApiBundle\Tests\BaseTestCase;

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

        $event = new Event();
        $event
            ->setStartsAt(new \DateTime())
            ->setEndsAt(new \DateTime());

        $manager = new EventManager();
        $manager
            ->setUser($admin)
            ->setEvent($event);

        $this->em()->persist($event);
        $this->em()->persist($manager);
        $this->em()->flush();

        $userClient = $this->createAuthorizedClient($user);
        $userClient->request('GET', $this->url("api_events_show", array('id' => $event->getId())));
        $this->assertEquals(403, $userClient->getResponse()->getStatusCode());

        $adminClient = $this->createAuthorizedClient($admin);
        $adminClient->request('GET', $this->url("api_events_show", array('id' => $event->getId())));
        $this->assertEquals(200, $adminClient->getResponse()->getStatusCode());
    }
}