<?php

namespace Attendee\Bundle\ApiBundle\Tests\Controller;

/**
 * Class AttendanceControllerTest
 *
 * @package   Attendee\Bundle\ApiBundle\Tests\Controller
 */
class AttendanceControllerTest extends BaseTestCase
{
    /**
     * Tests if listing of events works.
     */
    public function testIndex()
    {
        $client = $this->createAuthorizedClient();
        $count  = count($this->getRepo('AttendeeApiBundle:Attendance')->findAll());

        $client->request('GET', $this->url("api_attendances_index"));

        $content = $client->getResponse()->getContent();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $decoded = json_decode($content, true);
        $this->assertEquals(JSON_ERROR_NONE, json_last_error(), 'JSON decoding failed with code ' . json_last_error());
        $this->assertArrayHasKey('attendances', $decoded, 'Response should contain attendances.');

        $this->assertCount($count, $decoded['attendances'], "API should return exactly $count attendances.");
    }

    /**
     * Tests if attendance detail works.
     */
    public function testShow()
    {
        $client = $this->createAuthorizedClient();

        $client->request('GET', $this->url("api_attendances_show", array('id' => 1)));

        $content = $client->getResponse()->getContent();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $decoded = json_decode($content, true);
        $this->assertEquals(JSON_ERROR_NONE, json_last_error(), 'JSON decoding failed with code ' . json_last_error());
        $this->assertArrayHasKey('attendance', $decoded, 'Response should contain attendance.');

        $attendance = $decoded['attendance'];
        $this->assertArrayHasKeys(
            array('id', 'status', 'user', 'user_name', 'event'),
            $attendance
        );
    }
}
