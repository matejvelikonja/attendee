<?php

namespace Attendee\Bundle\ApiBundle\Tests\Controller;

use Attendee\Bundle\ApiBundle\Entity\User;
use Attendee\Bundle\ApiBundle\Tests\BaseTestCase;

/**
 * Class UsersControllerTest
 *
 * @package Attendee\Bundle\ApiBundle\Tests\Controller
 */
class UsersControllerTest extends BaseTestCase
{
    /**
     * Tests if listing of users works.
     */
    public function testIndex()
    {
        $client = $this->createAuthorizedClient();

        $client->request('GET', $this->url("api_users_index"));

        $decoded = $this->getResponseData($client);

        $this->assertArrayHasKeys(
            array('users'),
            $decoded
        );
    }

    /**
     * Test user detail.
     */
    public function testShow()
    {
        $client = $this->createAuthorizedClient();

        $client->request('GET', $this->url("api_users_show", array('id' => 1)));

        $decoded = $this->getResponseData($client);

        $this->assertArrayHasKeys(
            array('user'),
            $decoded
        );
    }

    /**
     * Test creating of user..
     */
    public function testCreate()
    {
        $client = $this->createAuthorizedClient();

        $firstName = 'First name';
        $lastName  = 'Last  name';
        $email     = 'email@example.com';

        $requestContent = json_encode(array(
            'user' => array(
                'firstName' => $firstName,
                'lastName'  => $lastName,
                'email'     => $email
            )
        ));

        $client->request('POST',
            $this->url('api_users_create'),
            array(), array(),
            array('CONTENT_TYPE' => 'application/json'),
            $requestContent
        );

        $decoded = $this->getResponseData($client);

        $this->assertArrayHasKeys(
            array('user'),
            $decoded
        );

        $this->assertArrayHasKeys(
            array('id'),
            $decoded['user']
        );

        $this->assertGreaterThan(0, $decoded['user']['id'], 'Returned user id is probably not a number.');

        /** @var User $user */
        $user = $this->getRepo('AttendeeApiBundle:User')->find($decoded['user']['id']);

        $this->assertEquals($firstName, $user->getFirstName(), 'First name was not saved correctly.');
        $this->assertEquals($lastName, $user->getLastName(), 'Last name was not saved correctly.');
        $this->assertEquals($email, $user->getEmail(), 'Email was not saved correctly.');

        // cleanup
        $this->em()->remove($user);
        $this->em()->flush();
    }
}
