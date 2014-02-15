<?php

namespace Attendee\Bundle\ApiBundle\Tests\Controller;

use Attendee\Bundle\ApiBundle\Entity\Attendance;
use Attendee\Bundle\ApiBundle\Tests\BaseTestCase;

/**
 * Class AttendanceControllerTest
 *
 * @package Attendee\Bundle\ApiBundle\Tests\Controller
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

    /**
     * Tests if changing of status works.
     */
    public function testUpdate()
    {
        $client = $this->createAuthorizedClient();

        $statusBefore  = Attendance::STATUS_EMPTY;
        $statusChanged = Attendance::STATUS_PRESENT;

        /** @var Attendance $attendance */
        $attendance = $this->getRepo('AttendeeApiBundle:Attendance')->findOneBy(array());
        $attendance->setStatus($statusBefore);
        $this->em()->persist($attendance);
        $this->em()->flush();

        $requestContent = json_encode(
            array('attendance' => array(
                'status'    => $statusChanged
            )));

        $client->request('PUT', $this->url(
            'api_attendances_update', array('id' => $attendance->getId())),
            array(), array(),
            array('CONTENT_TYPE' => 'application/json'),
            $requestContent
        );

        $this->assertEquals(204, $client->getResponse()->getStatusCode());

        $attendance = $this->getRepo('AttendeeApiBundle:Attendance')->findOneBy(array('id' => $attendance->getId()));

        $this->assertEquals($statusChanged, $attendance->getStatus(), 'Status was not changed.');
    }
}
