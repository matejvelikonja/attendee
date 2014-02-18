<?php

namespace Attendee\Bundle\ConsoleBundle\Tests\Location;

use Attendee\Bundle\ConsoleBundle\Location\ImportCommand;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Class ImportCommandTest
 *
 * @package Attendee\Bundle\ConsoleBundle\Tests\Location
 */
class ImportCommandTest extends WebTestCase
{
    /**
     * Test import command with simple test file.
     */
    public function testExecute()
    {
        $kernel = $this->createKernel();
        $kernel->boot();

        $filePath       = $kernel->locateResource('@AttendeeConsoleBundle/Tests/Data/locations.kml');
        $locationsCount = 2;

        $app = new Application($kernel);
        $app->add(new ImportCommand());

        $command = $app->find('attendee:location:import');
        $tester  = new CommandTester($command);
        $tester->execute(
            array(
                'file' => $filePath
            )
        );

        $display = $tester->getDisplay();

        $this->assertRegExp(
            sprintf('/%s/', str_replace('/', '\/', $filePath)),
            $display,
            'Display should show path of the passed file.'
        );

        preg_match('/Imported (\d+) locations./', $display, $matches);

        $this->assertCount(2, $matches, 'Should should the number of imported locations.');

        $this->assertEquals($locationsCount, $matches[1], 'Imported wrong number of locations.');
    }
}