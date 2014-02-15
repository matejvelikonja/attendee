<?php

namespace Attendee\Bundle\WebpageBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class HomeControllerTest
 *
 * @package Attendee\Bundle\WebpageBundle\Tests\Controller
 */
class HomeControllerTest extends WebTestCase
{
    /**
     * Tests if home page works.
     */
    public function testIndex()
    {
        $client = static::createClient();

        $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
