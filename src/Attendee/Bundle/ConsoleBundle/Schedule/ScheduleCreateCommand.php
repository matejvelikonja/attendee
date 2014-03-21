<?php

namespace Attendee\Bundle\ConsoleBundle\Schedule;

use Attendee\Bundle\ApiBundle\Entity\Location;
use Attendee\Bundle\ApiBundle\Entity\Schedule;
use Attendee\Bundle\ApiBundle\Entity\Team;
use Attendee\Bundle\ConsoleBundle\AbstractCommand;
use Recurr\RecurrenceRule;
use Symfony\Component\Console\Helper\TableHelper;
use Symfony\Component\Console\Input\InputOption;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * Class ScheduleCreateCommand
 *
 * @package Attendee\Bundle\ConsoleBundle\Schedule
 *
 * @DI\Service
 * @DI\Tag("console.command")
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
            ->addOption('teams', null, InputOption::VALUE_IS_ARRAY | InputOption::VALUE_OPTIONAL, 'List of team names or ids.')
            ->addOption('name', null, InputOption::VALUE_OPTIONAL, 'Name of schedule')
            ->addOption('location', null, InputOption::VALUE_OPTIONAL, 'Default location name or id of schedule.')
            ->addOption('startsAt', null, InputOption::VALUE_OPTIONAL, 'Start date of schedule.')
            ->addOption('endsAt', null, InputOption::VALUE_OPTIONAL, 'End date of schedule.')
            ->addOption('rRule', null, InputOption::VALUE_OPTIONAL, 'RRule of schedule.')
            ->addOption('duration', null, InputOption::VALUE_OPTIONAL, 'Duration of schedule.');
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
        $data->duration  = $this->getDuration();

        $schedule = new Schedule();
        $schedule
            ->setName($data->name)
            ->setDefaultLocation($data->location)
            ->setRRule($data->rRule)
            ->setTeams($data->teams)
            ->setDuration($data->duration);

        $this->printScheduleTables($schedule);

        if (! $this->input->isInteractive()) {
            return $schedule;
        }

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
            array('duration',  $schedule->getDuration()->format('%h hours, %i minutes')),
            array('teams',     implode(', ', $teams)),
            array('users',     implode(', ', $users))
        ));

        $table->render($this->output);
        $table->setRows(array());

        /** PRINT EVENTS DATA */
        $events = $this->getContainer()->get('attendee.event_scheduler')->calculateEvents($schedule);

        $table->setHeaders(array('date', 'time', 'until'));

        foreach ($events as $event) {
            $table->addRow(array(
                $event->getStartsAt()->format(self::DATE_FORMAT),
                $event->getStartsAt()->format(self::TIME_FORMAT),
                $event->getEndsAt()->format(self::TIME_FORMAT)
            ));
        }

        $this->output->writeln(sprintf('<info>Events (%d) created by schedule:</info>', count($events)));
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
     * @throws \InvalidArgumentException
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

        foreach ($selected as $teamIdentifier) {
            $key = 'name';
            if (is_numeric($teamIdentifier)) {
                $key = 'id';
            }
            $team = $this->getDoctrine()->getRepository('AttendeeApiBundle:Team')->findOneBy(array(
                $key => $teamIdentifier
            ));

            if (! $team instanceof Team) {
                throw new \InvalidArgumentException("Team $teamIdentifier not found.");
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
                    throw new \InvalidArgumentException(sprintf('Wrong location `%s` selected.', $answer));
                }

                return $answer;
            },
            false,
            null,
            $teamsNames
        );
    }

    /**
     * @throws \InvalidArgumentException
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
                        throw new \InvalidArgumentException(sprintf('Wrong location `%s` selected.', $answer));
                    }

                    return $answer;
                },
                false,
                null,
                $locations
            );
        }

        if ($name) {
            $key = 'name';
            if (is_numeric($name)) {
                $key = 'id';
            }

            $location = $this->getDoctrine()->getRepository('AttendeeApiBundle:Location')->findOneBy(array(
                $key => $name
            ));

            if (! $location instanceof Location) {
                throw new \InvalidArgumentException("Location $name not found.");
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
     * @return \DateInterval
     */
    private function getDuration()
    {
        $durationOption = $this->input->getOption('duration');

        $createDuration = function ($answer) {
            return \DateInterval::createFromDateString($answer);
        };

        if ($durationOption) {
            return $createDuration($durationOption);
        }

        return $this->dialog->askAndValidate(
            $this->output,
            "Duration: ",
            $createDuration
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
     * @throws \InvalidArgumentException
     *
     * @return \DateTime
     */
    private function getEndDate(\DateTime $startDate)
    {
        $defaultDateOption = $this->input->getOption('endsAt');

        if ($defaultDateOption) {
            $defaultDate = new \DateTime($defaultDateOption);

            if ($startDate > $defaultDate) {
                throw new \InvalidArgumentException(
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

        $output = $this->output;
        $dialog = $this->dialog;

        $dateString = $dialog->askAndValidate(
            $this->output,
            $question,
            function ($userInput) use($startDate, $output, $dialog) {
                try {
                    $date = new \DateTime($userInput);
                } catch (\Exception $e) {
                    throw new \InvalidArgumentException('Wrong format. Try again.');
                }

                if ($startDate) {
                    if ($startDate > $date) {
                        throw new \InvalidArgumentException(sprintf('Date must be bigger than %s.', $startDate->format(ScheduleCreateCommand::DATE_TIME_FORMAT)));
                    }
                }

                $answer = $dialog->askConfirmation(
                    $output,
                    sprintf('<question>Is this date `%s` correct?</question> [Y/n]', $date->format(ScheduleCreateCommand::DATE_TIME_FORMAT)),
                    true
                );

                if (! $answer) {
                    throw new \InvalidArgumentException('Try again');
                }

                return $userInput;
            },
            false
        );

        return new \DateTime($dateString);
    }
}
