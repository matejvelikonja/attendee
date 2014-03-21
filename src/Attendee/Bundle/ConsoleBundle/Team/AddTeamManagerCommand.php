<?php

namespace Attendee\Bundle\ConsoleBundle\Team;

use Attendee\Bundle\ApiBundle\Entity\Team;
use Attendee\Bundle\ApiBundle\Entity\User;
use Attendee\Bundle\ConsoleBundle\AbstractCommand;
use Symfony\Component\Console\Input\InputOption;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * Class AddTeamManagerCommand
 *
 * @DI\Service
 * @DI\Tag("console.command")
 */
class AddTeamManagerCommand extends AbstractCommand
{
    /**
     * Configure command.
     */
    protected function configure()
    {
        $this
            ->setName('attendee:team:add-manager')
            ->setDescription('Makes user a manager of team.')
            ->addOption('email', null, InputOption::VALUE_REQUIRED, 'Make user with email as team manager.')
            ->addOption('team', null, InputOption::VALUE_REQUIRED, 'Team that gets a new manager.');
    }

    /**
     * Use instead of execute.
     */
    protected function executeCommand()
    {
        $userEmail = $this->input->getOption('email');
        $teamId    = (int) $this->input->getOption('team');
        $service   = $this->getContainer()->get('attendee.team_service');
        $team      = $this->getDoctrine()->getRepository('AttendeeApiBundle:Team')->find($teamId);
        $user      = $this->getDoctrine()->getRepository('AttendeeApiBundle:User')->findOneBy(array('email' => $userEmail));

        if (! $team instanceof Team) {
            throw new \RuntimeException("Team id=$teamId not found.");
        }

        if (! $user instanceof User) {
            throw new \RuntimeException("User email=$userEmail not found.");
        }

        if ($service->isManager($user, $team)) {
            $this->output->writeln("<error>User {$user->getName()} is already a manager of {$team->getName()}.</error>");

            return;
        }

        $service->addManager($team, $user);

        $this->output->writeln("<info>Successfully added {$user->getName()} as a manager of {$team->getName()}.</info>");
    }
}