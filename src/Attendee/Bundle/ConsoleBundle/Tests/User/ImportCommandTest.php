<?php

namespace Attendee\Bundle\ConsoleBundle\Tests\User;

use Attendee\Bundle\ConsoleBundle\User\ImportCommand;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Class ImportCommandTest
 *
 * @package Attendee\Bundle\ConsoleBundle\Tests\User
 */
class ImportCommandTest extends WebTestCase
{
    /**
     * Test import command with simple test file.
     *
     * @param string  $fileName
     * @param boolean $dryRun
     * @param int     $resultCount
     *
     * @dataProvider executeProvider
     */
    public function testExecute($fileName, $dryRun, $resultCount)
    {
        $kernel = $this->createKernel();
        $kernel->boot();

        $filePath = $kernel->locateResource(sprintf('@AttendeeConsoleBundle/Tests/Data/%s', $fileName));

        $app = new Application($kernel);
        $app->add(new ImportCommand());

        $command = $app->find('attendee:user:import');
        $tester  = new CommandTester($command);
        $tester->execute(
            array(
                'file'      => $filePath,
                '--dry-run' => $dryRun
            )
        );

        $display = $tester->getDisplay();

        $this->assertRegExp(
            sprintf('/%s/', str_replace('/', '\/', $filePath)),
            $display,
            'Display should show path of the passed file.'
        );

        preg_match('/Imported (\d+) users./', $display, $matches);

        $this->assertCount(2, $matches, 'Should show number of imported users.');

        $this->assertEquals($resultCount, $matches[1], 'Imported wrong number of users.');
    }

    /**
     * @return array
     */
    public function executeProvider()
    {
        return array(
            array('users.csv', true, 3),                 // basic import with dry run
            array('users.csv', false, 3),                // basic import
            array('users.csv', false, 3),                // check if importer handles importing same file twice
            array('users_with_passwords.csv', false, 3), // users with password
        );
    }
}