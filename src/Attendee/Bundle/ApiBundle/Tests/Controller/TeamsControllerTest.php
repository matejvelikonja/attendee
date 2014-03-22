<?php

namespace Attendee\Bundle\ApiBundle\Tests\Controller;

use Attendee\Bundle\ApiBundle\Entity\Team;
use Attendee\Bundle\ApiBundle\Entity\User;
use Attendee\Bundle\ApiBundle\Tests\BaseTestCase;

/**
 * Class TeamsControllerTest
 *
 * @package Attendee\Bundle\ApiBundle\Tests\Controller
 */
class TeamsControllerTest extends BaseTestCase
{
    /**
     * Tests if listing of events works.
     */
    public function testIndex()
    {
        $client = $this->createAuthorizedClient();

        $client->request('GET', $this->url("api_teams_index"));

        $decoded = $this->getResponseData($client);

        $this->assertArrayHasKeys(
            array('teams', 'users', 'schedules'),
            $decoded
        );
    }

    /**
     * Test team detail.
     */
    public function testShow()
    {
        $client = $this->createAuthorizedClient();

        $client->request('GET', $this->url("api_teams_show", array('id' => 1)));

        $decoded = $this->getResponseData($client);

        $this->assertArrayHasKeys(
            array('team', 'users', 'schedules'),
            $decoded
        );
    }

    /**
     * Test updating of team.
     *
     * Test tries to update name and users of team.
     */
    public function testUpdate()
    {
        /** @var User[] $users */
        $users       = $this->getRepo('AttendeeApiBundle:User')->findBy(array(), array(), 5);
        /** @var Team $team */
        $team        = $this->getRepo('AttendeeApiBundle:Team')->findOneBy(array());
        $userIds     = array();
        $newTeamName = 'Some random name ' . time();

        foreach ($users as $user) {
            $userIds[] = $user->getId();
        }

        $requestContent = json_encode(
            array('team' => array(
                'name'  => $newTeamName,
                'users' => $userIds
            )));

        $client = $this->createAuthorizedClient();

        $client->request('PUT', $this->url(
            'api_teams_update', array('id' => $team->getId())),
            array(), array(),
            array('CONTENT_TYPE' => 'application/json'),
            $requestContent
        );

        $decoded = $this->getResponseData($client);

        $this->assertArrayHasKeys(
            array('team', 'users'),
            $decoded
        );

        /** @var Team $newTeam */
        $newTeam = $this->getRepo('AttendeeApiBundle:Team')->find($team->getId());

        $this->assertEquals($newTeamName, $newTeam->getName(), 'Name of team should be changed.');
        $this->assertCount(count($users), $newTeam->getUsers(), 'Users were not changed.');
    }
}
