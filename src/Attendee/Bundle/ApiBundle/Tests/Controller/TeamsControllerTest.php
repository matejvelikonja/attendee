<?php

namespace Attendee\Bundle\ApiBundle\Tests\Controller;

use Attendee\Bundle\ApiBundle\Tests\BaseTestCase;
use Symfony\Bundle\FrameworkBundle\Client;

/**
 * Class TeamsControllerTest
 *
 * @package   Attendee\Bundle\ApiBundle\Tests\Controller
 */
class TeamsControllerTest extends BaseTestCase
{
    /**
     * Tests if listing of events works.
     */
    public function testIndex()
    {
        $client = $this->createAuthorizedClient();

        $teams = $this->getRepo('AttendeeApiBundle:Team')->findBy(array());
        $limit = count($teams);

        $client->request('GET', $this->url("api_teams_index"));

        $decoded = $this->getResponseData($client);

        $this->assertArrayHasKeys(
            array('teams'),
            $decoded
        );

        $this->assertCount($limit, $decoded['teams'], "API should return exactly $limit events.");
    }

    /**
     * @param Client $client
     *
     * @return mixed
     */
    private function getResponseData(Client $client)
    {
        $content = $client->getResponse()->getContent();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $decoded = json_decode($content, true);

        $this->assertEquals(
            JSON_ERROR_NONE,
            json_last_error(),
            sprintf('JSON decoding failed for url `%s` with code %d.',
                json_last_error(),
                $client->getRequest()->getRequestUri()
            )
        );

        return $decoded;
    }
}
