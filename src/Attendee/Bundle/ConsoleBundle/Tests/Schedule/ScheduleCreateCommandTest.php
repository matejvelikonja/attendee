<?php

namespace Attendee\Bundle\ConsoleBundle\Tests\Schedule;

use Attendee\Bundle\ConsoleBundle\Schedule\ScheduleCreateCommand;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class ScheduleCreateCommandTest
 *
 * @package Attendee\Bundle\ConsoleBundle\Tests\Schedule
 */
class ScheduleCreateCommandTest extends WebTestCase
{
    /**
     * Test basic schedule create.
     */
    public function testExecute()
    {
        $kernel = $this->createKernel();
        $kernel->boot();

        $app = new Application($kernel);
        $app->setAutoExit(false);
        $app->add(new ScheduleCreateCommand());

        $team     = $this->getRepo('AttendeeApiBundle:Team', $kernel->getContainer())->findOneBy(array());
        $location = $this->getRepo('AttendeeApiBundle:Location', $kernel->getContainer())->findOneBy(array());

        $command = $app->find('attendee:schedule:create');
        $tester  = new CommandTester($command);
        $tester->execute(
            array(
                '--teams'    => array($team->getName()),
                '--name'     => __CLASS__ . ' SCHEDULE',
                '--location' => $location->getName(),
                '--startsAt' => 'now',
                '--endsAt'   => '+1 month',
                '--rRule'    => 'FREQ=WEEKLY;BYDAY=WE;BYHOUR=11',
                '--duration' => '1 hour 17 minutes'
            ),
            array(
                'interactive' => false
            )
        );
    }

    /**
     * @param string             $entityName
     * @param ContainerInterface $container
     *
     * @return \Doctrine\ORM\EntityRepository
     */
    protected function getRepo($entityName, ContainerInterface $container)
    {
        /** @var EntityManager $em */
        $em = $container->get('doctrine.orm.entity_manager');

        return $em->getRepository($entityName);
    }
}