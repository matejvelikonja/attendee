<?php

namespace Attendee\Bundle\ConsoleBundle;

use Attendee\Bundle\ApiBundle\Service\ScheduleService;
use Sensio\Bundle\GeneratorBundle\Command\Helper\DialogHelper;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class AbstractCommand
 *
 * @package Attendee\Bundle\ConsoleBundle
 */
abstract class AbstractCommand extends ContainerAwareCommand
{
    /**
     * @var DialogHelper $dialog
     */
    protected $dialog;

    /**
     * @var InputInterface
     */
    protected $input;

    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * Use instead of execute.
     */
    abstract protected function executeCommand();

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->dialog = $this->getHelperSet()->get('dialog');
        $this->input  = $input;
        $this->output = $output;

        $this->executeCommand();
    }

    /**
     * @return \Doctrine\Bundle\DoctrineBundle\Registry
     */
    protected function getDoctrine()
    {
        return $this->getContainer()->get('doctrine');
    }

    /**
     * @return ScheduleService
     */
    protected function getScheduleService()
    {
        return $this->getContainer()->get('attendee.schedule_service');
    }
}