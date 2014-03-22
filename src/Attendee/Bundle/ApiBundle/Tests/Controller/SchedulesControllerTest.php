<?php

namespace Attendee\Bundle\ApiBundle\Tests\Controller;

use Attendee\Bundle\ApiBundle\Entity\Schedule;
use Attendee\Bundle\ApiBundle\Tests\BaseTestCase;

/**
 * Class SchedulesControllerTest
 *
 * @package Attendee\Bundle\ApiBundle\Tests\Controller
 */
class SchedulesControllerTest extends BaseTestCase
{
    /**
     * Test schedule detail.
     */
    public function testShow()
    {
        $client = $this->createAuthorizedClient();

        $client->request('GET', $this->url("api_schedules_show", array('id' => 1)));

        $decoded = $this->getResponseData($client);

        $this->assertArrayHasKeys(
            array('schedule'),
            $decoded
        );
    }

    /**
     * Test creating of user.
     */
    public function testCreate()
    {
        $client = $this->createAuthorizedClient();

        $name     = __CLASS__ . ' SCHEDULE';
        $startsAt = new \DateTime();

        $requestContent = json_encode(array(
            'schedule' => array(
                'name'     => $name,
                'startsAt' => $startsAt->format('c'),
                'rRule'    => 'FREQ=WEEKLY;INTERVAL=1;BYDAY=TU,TH;BYHOUR=11;BYMINUTE=11;BYSECOND=11',
            )
        ));

        $client->request('POST',
            $this->url('api_schedules_create'),
            array(), array(),
            array('CONTENT_TYPE' => 'application/json'),
            $requestContent
        );

        $decoded = $this->getResponseData($client);

        $this->assertArrayHasKeys(
            array('schedule'),
            $decoded
        );

        $this->assertArrayHasKeys(
            array('id'),
            $decoded['schedule']
        );

        $this->assertGreaterThan(0, $decoded['schedule']['id'], 'Returned user id is probably not a number.');

        /** @var Schedule $schedule */
        $schedule = $this->getRepo('AttendeeApiBundle:Schedule')->find($decoded['schedule']['id']);

        $this->assertEquals($name, $schedule->getName(), 'Name was not saved correctly.');

        // cleanup
        $this->em()->remove($schedule);
        $this->em()->flush();
    }
}
