<?php

namespace Attendee\Bundle\ApiBundle\Tests\Controller;

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
        $this->assertEquals(JSON_ERROR_NONE, json_last_error(), json_last_error_msg());
        $this->assertArrayHasKey('events', $decoded, 'Response should contain events.');
        $this->assertArrayHasKey('locations', $decoded, 'Response should contain locations.');
        $this->assertArrayHasKey('attendances', $decoded, 'Response should contain attendances.');

        $this->assertEquals($limit, count($decoded['events']), "API should return exactly $limit events.");
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
        $this->assertEquals(JSON_ERROR_NONE, json_last_error(), json_last_error_msg());
        $this->assertArrayHasKey('event', $decoded, 'Response should contain event.');
        $this->assertArrayHasKey('location', $decoded, 'Response should contain location.');
        $this->assertArrayHasKey('attendances', $decoded, 'Response should contain attendances.');
    }
}
