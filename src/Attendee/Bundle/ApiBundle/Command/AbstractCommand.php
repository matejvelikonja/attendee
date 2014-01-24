<?php

namespace Attendee\Bundle\ApiBundle\Command;

use Sensio\Bundle\GeneratorBundle\Command\Helper\DialogHelper;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class AbstractCommand
 *
 * @package   Attendee\Bundle\ApiBundle\Command
 */
abstract class AbstractCommand extends ContainerAwareCommand
{
    /**
     * @var DialogHelper $dialog
     */
    protected $dialog;

    /**
     * @var OutputInterface
     */
    protected $output;

    abstract protected function executeCommand();

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->dialog = $this->getHelperSet()->get('dialog');
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
}