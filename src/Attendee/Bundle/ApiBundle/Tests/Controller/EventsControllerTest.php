<?php

namespace Attendee\Bundle\ApiBundle\Tests\Controller;

use Attendee\Bundle\ApiBundle\Tests\BaseTestCase;

/**
 * Class EventsControllerTest
 *
 * @package   Attendee\Bundle\ApiBundle\Tests\Controller
 */
class EventsControllerTest extends BaseTestCase
{
    /**
     * Tests if listing of events works.
     */
    public function testIndex()
    {
        $client = $this->createAuthorizedClient();
        $limit  = 5;

        $client->request('GET', $this->url("api_events_index"), array(
            'limit' => $limit
        ));

        $content = $client->getResponse()->getContent();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $decoded = json_decode($content, true);
        $this->assertEquals(JSON_ERROR_NONE, json_last_error(), 'JSON decoding failed with code ' . json_last_error());
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

        $content = $client->getResponse()->getContent();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $decoded = json_decode($content, true);
        $this->assertEquals(JSON_ERROR_NONE, json_last_error(), 'JSON decoding failed with code ' . json_last_error());
        $this->assertArrayHasKeys(
            array('event', 'location', 'attendances'),
            $decoded
        );
    }
}
