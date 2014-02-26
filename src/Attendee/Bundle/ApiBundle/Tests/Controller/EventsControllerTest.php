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

        $client->request('GET', $this->url('api_events_index'), array(
            'limit' => $limit
        ));

        $decoded = $this->getResponseData($client);

        $this->assertArrayHasKeys(
            array('events', 'locations', 'attendances', 'users'),
            $decoded
        );

        $this->assertCount($limit, $decoded['events'], "API should return exactly $limit events.");
    }

    /**
     * Tests if filtering by from param works, and API returns events larger than now.
     */
    public function testFilteringByStartDate()
    {
        $client   = $this->createAuthorizedClient();
        $startsAt = new \DateTime();

        $client->request('GET', $this->url('api_events_index'), array(
            'from' => $startsAt->format('c')
        ));

        $decoded = $this->getResponseData($client);

        foreach ($decoded['events'] as $event) {
            $date = new \DateTime($event['starts_at']);
            $this->assertGreaterThan(
                $startsAt,
                $date,
                sprintf('Only dates larger than %s should be displayed.', $startsAt->format('c'))
            );
        }
    }

    /**
     * Test event detail.
     */
    public function testShow()
    {
        $client = $this->createAuthorizedClient();

        $client->request('GET', $this->url('api_events_show', array('id' => 1)));

        $decoded = $this->getResponseData($client);

        $this->assertArrayHasKeys(
            array('event', 'location', 'attendances', 'users'),
            $decoded
        );
    }
}
