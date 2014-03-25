<?php

namespace Attendee\Bundle\ConsoleBundle\User;

use Attendee\Bundle\ConsoleBundle\AbstractCommand;
use Symfony\Component\Console\Input\InputArgument;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class ImportCommand
 *
 * @DI\Service
 * @DI\Tag("console.command")
 */
class ImportCommand extends AbstractCommand
{
    /**
     * Configure command.
     */
    protected function configure()
    {
        $this
            ->setName('attendee:user:import')
            ->setDescription('Imports users. Try to finds duplicates using user\'s email.')
            ->addArgument('file', InputArgument::REQUIRED, 'File to import.')
            ->addOption('dry-run', null, InputOption::VALUE_NONE, 'If presents, users are not saved');
    }

    /**
     * Use instead of execute.
     */
    protected function executeCommand()
    {
        $file   = $this->input->getArgument('file');
        $dryRun = $this->input->getOption('dry-run');

        $this->output->writeln("Reading from $file.");

        $importer = $this->getContainer()->get('attendee.importer.user');
        $result = $importer->import($file, $dryRun);

        if ($result) {
            $this->output->writeln("<info>Imported $result users.</info>");
        } else {
            $this->output->writeln("<error>No users imported.</error>");
        }
    }
}