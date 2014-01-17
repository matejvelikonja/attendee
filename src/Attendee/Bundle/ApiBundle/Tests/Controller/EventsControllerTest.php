<?php

namespace Attendee\Bundle\ApiBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class EventsControllerTest
 *
 * @package   Attendee\Bundle\ApiBundle\Tests\Controller
 */
class EventsControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $client->request('GET', '/api/events/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
