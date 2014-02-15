<?php

namespace Attendee\Bundle\ApiBundle\Security\Voter;

use Attendee\Bundle\ApiBundle\Entity\Team;
use Attendee\Bundle\ApiBundle\Entity\TeamManager;
use Attendee\Bundle\ApiBundle\Entity\User;
use Attendee\Bundle\ApiBundle\Tests\BaseTestCase;

/**
 * Class TeamVoterTest
 *
 * @package Attendee\Bundle\ApiBundle\Security\Voter
 */
class TeamVoterTest  extends BaseTestCase
{
    /**
     * Tests if user cannot access team detail if (s)he is not a manager.
     */
    public function testIfUserCantAccessTeamWhichHeDoesNotOwn()
    {
        /** @var User $admin */
        $admin = $this->getRepo('AttendeeApiBundle:User')->find(1);
        /** @var User $user */
        $user  = $this->getRepo('AttendeeApiBundle:User')->find(2);

        $team = new Team();
        $team
            ->setName('team name');

        $manager = new TeamManager();
        $manager
            ->setUser($admin)
            ->setTeam($team);

        $this->em()->persist($team);
        $this->em()->persist($manager);
        $this->em()->flush();

        $userClient = $this->createAuthorizedClient($user);
        $userClient->request('GET', $this->url("api_teams_show", array('id' => $team->getId())));
        $this->assertEquals(403, $userClient->getResponse()->getStatusCode(), 'User should not have permission to this team.');

        $adminClient = $this->createAuthorizedClient($admin);
        $adminClient->request('GET', $this->url("api_teams_show", array('id' => $team->getId())));
        $this->assertEquals(200, $adminClient->getResponse()->getStatusCode(), 'Admin should have permission for this team.');
    }
}