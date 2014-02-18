<?php

namespace Attendee\Bundle\ConsoleBundle\Command;

use Attendee\Bundle\ApiBundle\Entity\Location;
use Attendee\Bundle\ApiBundle\Entity\Schedule;
use Attendee\Bundle\ApiBundle\Entity\Team;
use Recurr\RecurrenceRule;
use Symfony\Component\Console\Helper\TableHelper;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class ScheduleCreateCommand
 *
 * @package Attendee\Bundle\ConsoleBundle\Command
 */
class ScheduleCreateCommand extends AbstractCommand
{
    const DATE_TIME_FORMAT = 'd. F Y, H:i:s';
    const DATE_FORMAT      = 'd. F Y';
    const TIME_FORMAT      = 'H:i:s';

    /**
     * Configure command.
     */
    protected function configure()
    {
        $this
            ->setName('attendee:schedule:create')
            ->setDescription('Create schedule.')
            ->addOption('teams', null, InputOption::VALUE_IS_ARRAY | InputOption::VALUE_OPTIONAL, 'List of team names.')
            ->addOption('name', null, InputOption::VALUE_OPTIONAL, 'Name of schedule')
            ->addOption('location', null, InputOption::VALUE_OPTIONAL, 'Default location of schedule.')
            ->addOption('startsAt', null, InputOption::VALUE_OPTIONAL, 'Start date of schedule.')
            ->addOption('endsAt', null, InputOption::VALUE_OPTIONAL, 'End date of schedule.')
            ->addOption('rRule', null, InputOption::VALUE_OPTIONAL, 'RRule of schedule.');
    }

    /**
     * Execute command.
     */
    protected function executeCommand()
    {
        $schedule = $this->getSchedule();

        if ($schedule) {
            $this->getDoctrine()->getManager()->persist($schedule);
            $this->getDoctrine()->getManager()->flush();

            $this->output->writeln(
                sprintf(
                    '<info>Successfully create schedule `%s.</info>',
                    $schedule->getName()
                )
            );
        } else {
            $this->output->writeln(
                sprintf('<info>Creating of event canceled.</info>')
            );
        }
    }

    /**
     * @return Schedule
     */
    private function getSchedule()
    {
        $data = new \StdClass();

        $data->teams     = $this->getTeams();
        $data->name      = $this->getScheduleName();
        $data->location  = $this->getLocation();
        $data->startsAt  = $this->getStartDate();
        $data->endsAt    = $this->getEndDate($data->startsAt);
        $data->rRule     = $this->getRRule($data->startsAt, $data->endsAt);

        $schedule = new Schedule();
        $schedule
            ->setName($data->name)
            ->setDefaultLocation($data->location)
            ->setRRule($data->rRule)
            ->setTeams($data->teams);

        $this->printScheduleTables($schedule);

        $confirm = $this->dialog->askConfirmation(
            $this->output,
            '<question>Do you want to create this schedule?</question> [Y/n]',
            true
        );

        if ($confirm) {
            return $schedule;
        } else {
            return null;
        }
    }

    /**
     * @param Schedule $schedule
     */
    private function printScheduleTables(Schedule $schedule)
    {
        /** @var TableHelper $table */
        $table = $this->getApplication()->getHelperSet()->get('table');
        $table->setHeaders(array('Property', 'Value'));

        $teams    = $schedule->getTeams()->toArray();
        $users    = $this->getScheduleService()->getUsers($schedule);
        $location = $schedule->getDefaultLocation() ? $schedule->getDefaultLocation()->getName() : 'none';

        $table->addRows(array(
            array('name',      $schedule->getName()),
            array('location',  $location),
            array('startsAt',  $schedule->getRRule()->getStartDate()->format(self::DATE_TIME_FORMAT)),
            array('endsAt',    $schedule->getRRule()->getUntil()->format(self::DATE_TIME_FORMAT)),
            array('rRule',     $schedule->getRRule()->getString()),
            array('teams',     implode(', ', $teams)),
            array('users',     implode(', ', $users))
        ));

        $table->render($this->output);
        $table->setRows(array());

        /** PRINT EVENTS DATA */
        $events = $this->getContainer()->get('attendee.event_scheduler')->calculateEvents($schedule);

        $table->setHeaders(array('date', 'time'));

        foreach ($events as $event) {
            $table->addRow(array(
                $event->getStartsAt()->format(self::DATE_FORMAT),
                $event->getStartsAt()->format(self::TIME_FORMAT)
            ));
        }

        $this->output->writeln('<info>Events created by schedule:</info>');
        $table->render($this->output);
    }

    /**
     * @return string
     */
    private function getScheduleName()
    {
        $name = $this->input->getOption('name');

        if (! $name) {
            $name = $this->dialog->askAndValidate(
                $this->output,
                "Name: ",
                function ($answer) {
                    if (strlen($answer) == 0) {
                        throw new \RuntimeException('Please enter name of the schedule.');
                    }

                    return $answer;
                },
                false
            );
        }

        return $name;
    }

    /**
     * @throws \RuntimeException
     *
     * @return Team[]
     */
    private function getTeams()
    {
        $teams      = array();
        $selected   = $this->input->getOption('teams');

        // if we don't get any team names from options, then we ask user
        if (empty($selected)) {
            do {
                $name = $this->getTeamName();

                if ($name) {
                    $selected[] = $name;
                }

            } while ($name !== null);
        }

        foreach ($selected as $teamName) {
            $team = $this->getDoctrine()->getRepository('AttendeeApiBundle:Team')->findOneBy(array(
                'name' => $teamName
            ));

            if (! $team instanceof Team) {
                throw new \RuntimeException("Team $teamName not found.");
            }

            $teams[] = $team;
        }

        return $teams;
    }

    /**
     * @return string | null
     */
    private function getTeamName()
    {
        $teamsNames = $this->getTeamsNames();

        return $this->dialog->askAndValidate(
            $this->output,
            "Team: ",
            function ($answer) use ($teamsNames) {
                if (! in_array($answer, $teamsNames) && strlen($answer) > 0) {
                    throw new \RuntimeException(sprintf('Wrong location `%s` selected.', $answer));
                }

                return $answer;
            },
            false,
            null,
            $teamsNames
        );
    }

    /**
     * @throws \RuntimeException
     *
     * @return Location
     */
    private function getLocation()
    {
        $name = $this->input->getOption('location');

        if (! $name) {
            $locations = $this->getLocationNames();

            $name = $this->dialog->askAndValidate(
                $this->output,
                "Location: ",
                function ($answer) use ($locations) {
                    if (! in_array($answer, $locations) && strlen($answer) > 0) {
                        throw new \RuntimeException(sprintf('Wrong location `%s` selected.', $answer));
                    }

                    return $answer;
                },
                false,
                null,
                $locations
            );
        }

        if ($name) {
            $location = $this->getDoctrine()->getRepository('AttendeeApiBundle:Location')->findOneBy(array(
                'name' => $name
            ));

            if (! $location instanceof Location) {
                throw new \RuntimeException("Location $name not found.");
            }

            return $location;
        }

        return null;
    }

    /**
     * @return array
     */
    private function getLocationNames()
    {
        $locations = $this->getDoctrine()->getRepository('AttendeeApiBundle:Location')->findAll();
        $names = array();

        foreach ($locations as $location) {
            $names[] = $location->getName();
        }

        return $names;
    }

    /**
     * @return array
     */
    private function getTeamsNames()
    {
        $teams = $this->getDoctrine()->getRepository('AttendeeApiBundle:Team')->findAll();
        $names = array();

        foreach ($teams as $team) {
            $names[] = $team->getName();
        }

        return $names;
    }

    /**
     * @param \DateTime $startDate
     * @param \DateTime $endDate
     *
     * @return RecurrenceRule
     */
    private function getRRule(\DateTime $startDate, \DateTime $endDate)
    {
        $rRuleOption = $this->input->getOption('rRule');

        $createRRule = function ($answer) use($startDate, $endDate) {
            $string = sprintf('%s;UNTIL=%s', $answer, $endDate->format('c'));

            return new RecurrenceRule($string, $startDate);
        };

        if ($rRuleOption) {
            return $createRRule($rRuleOption);
        }

        return $this->dialog->askAndValidate(
            $this->output,
            "Recurrence Rule: ",
            $createRRule
        );
    }

    /**
     * @return \DateTime
     */
    private function getStartDate()
    {
        $defaultDate = $this->input->getOption('startsAt');

        return $this->getDate("Starts at: ", $defaultDate);
    }

    /**
     * @param \DateTime $startDate
     *
     * @throws \RuntimeException
     *
     * @return \DateTime
     */
    private function getEndDate(\DateTime $startDate)
    {
        $defaultDateOption = $this->input->getOption('endsAt');

        if ($defaultDateOption) {
            $defaultDate = new \DateTime($defaultDateOption);

            if ($startDate > $defaultDate) {
                throw new \RuntimeException(
                    sprintf('Date %s must be bigger than %s.',
                        $defaultDate->format(self::DATE_TIME_FORMAT),
                        $startDate->format(self::DATE_TIME_FORMAT)
                    )
                );
            }
        }

        return $this->getDate("Ends at: ", $defaultDateOption, $startDate);
    }

    /**
     * @param string    $question
     * @param string    $defaultDate
     * @param \DateTime $startDate
     *
     * @return \DateTime
     */
    private function getDate($question, $defaultDate = null, \DateTime $startDate = null)
    {
        if ($defaultDate) {
            return new \DateTime($defaultDate);
        }

        $dateString = $this->dialog->askAndValidate(
            $this->output,
            $question,
            function ($userInput) use($startDate) {
                try {
                    $date = new \DateTime($userInput);
                } catch (\Exception $e) {
                    throw new \RuntimeException('Wrong format. Try again.');
                }

                if ($startDate) {
                    if ($startDate > $date) {
                        throw new \RuntimeException(sprintf('Date must be bigger than %s.', $startDate->format(self::DATE_TIME_FORMAT)));
                    }
                }

                $answer = $this->dialog->askConfirmation(
                    $this->output,
                    sprintf('<question>Is this date `%s` correct?</question> [Y/n]', $date->format(self::DATE_TIME_FORMAT)),
                    true
                );

                if (! $answer) {
                    throw new \RuntimeException('Try again');
                }

                return $userInput;
            },
            false
        );

        return new \DateTime($dateString);
    }
}
