<?php

namespace Attendee\Bundle\ApiBundle\Tests\Controller;

use Attendee\Bundle\ApiBundle\Tests\BaseTestCase;

/**
 * Class EventsControllerTest
 *
 * @package Attendee\Bundle\ApiBundle\Tests\Controller
 */
class EventsControllerTest extends BaseTestCase
{
    /**
     * Tests if listing of events works.
     */
    public function testIndex()
    {
        $client = $this->createAuthorizedClient();
        $limit  = 15;

        $client->request('GET', $this->url("api_events_index"), array(
            'limit' => $limit
        ));

        $decoded = $this->getResponseData($client);

        $this->assertArrayHasKeys(
            array('events', 'locations', 'attendances'),
            $decoded
        );

        $this->assertCount($limit, $decoded['events'], "API should return exactly $limit events.");
    }

    /**
     * Test event detail.
     */
    public function testShow()
    {
        $client = $this->createAuthorizedClient();

        $client->request('GET', $this->url("api_events_show", array('id' => 1)));

        $decoded = $this->getResponseData($client);

        $this->assertArrayHasKeys(
            array('event', 'location', 'attendances'),
            $decoded
        );
    }
}
