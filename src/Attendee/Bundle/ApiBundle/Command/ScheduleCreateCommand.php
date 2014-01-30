<?php

namespace Attendee\Bundle\ApiBundle\Command;

use Attendee\Bundle\ApiBundle\Entity\Location;
use Attendee\Bundle\ApiBundle\Entity\Schedule;
use Recurr\RecurrenceRule;
use Symfony\Component\Console\Helper\TableHelper;

/**
 * Class ScheduleCreateCommand
 *
 * @package   Attendee\Bundle\ApiBundle\Command
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
            ->setDescription('Create schedule.');
    }

    /**
     * Execute command.
     */
    protected function executeCommand()
    {
        $schedule = $this->getSchedule();

        $this->getDoctrine()->getManager()->persist($schedule);
        $this->getDoctrine()->getManager()->flush();

        $this->output->writeln(
            sprintf(
                '<info>Successfully create schedule `%s` with %d events.</info>',
                $schedule->getName(),
                count($schedule->getEvents())
            )
        );
    }

    /**
     * @return Schedule
     */
    private function getSchedule()
    {
        do {
            $data = new \StdClass();

            $data->name      = $this->getScheduleName();
            $data->location  = $this->getLocation();
            $data->startsAt  = $this->getStartDate();
            $data->endsAt    = $this->getEndDate($data->startsAt);
            $data->rRule     = $this->getRRule($data->startsAt, $data->endsAt);

            $schedule = new Schedule();
            $schedule
                ->setName($data->name)
                ->setDefaultLocation($data->location)
                ->setRRule($data->rRule);

            $this->printScheduleTables($schedule);

            $confirm = $this->dialog->askConfirmation(
                $this->output,
                '<question>Do you want to create this schedule?</question> [Y/n]',
                true
            );

        } while(! $confirm);

        return $schedule;
    }

    /**
     * @param Schedule $schedule
     */
    private function printScheduleTables(Schedule $schedule)
    {
        /** PRINT SCHEDULE DATA */
        /** @var TableHelper $table */
        $table = $this->getApplication()->getHelperSet()->get('table');
        $table->setHeaders(array('Property', 'Value'));

        $table->addRows(array(
            array('name',      $schedule->getName()),
            array('location',  $schedule->getDefaultLocation()->getName()),
            array('startsAt',  $schedule->getRRule()->getStartDate()->format(self::DATE_TIME_FORMAT)),
            array('endsAt',    $schedule->getRRule()->getUntil()->format(self::DATE_TIME_FORMAT)),
            array('rRule',     $schedule->getRRule()->getString()),
        ));

        $table->render($this->output);
        $table->setRows(array());

        /** PRINT EVENTS DATA */
        $events = $this->getContainer()->get('attendee.event_scheduler')->calculateEvents($schedule);

        $table->setHeaders(array('date', 'time'));

        foreach($events as $event) {
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
        return $this->dialog->askAndValidate(
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

    /**
     * @return Location
     */
    private function getLocation()
    {
        $locations = $this->getLocationNames();

        $name = $this->dialog->askAndValidate(
            $this->output,
            "Location: ",
            function ($answer) use ($locations) {
                if (! in_array($answer, $locations)) {
                    throw new \RuntimeException(sprintf('Wrong location `%s` selected.', $answer));
                }

                return $answer;
            },
            false,
            null,
            $locations
        );

        $location = $this->getDoctrine()->getRepository('AttendeeApiBundle:Location')->findOneBy(array(
            'name' => $name
        ));

        return $location;
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
     * @param \DateTime $startDate
     * @param \DateTime $endDate
     *
     * @return RecurrenceRule
     */
    private function getRRule(\DateTime $startDate, \DateTime $endDate)
    {
        return $this->dialog->askAndValidate(
            $this->output,
            "Recurrence Rule: ",
            function ($answer) use($startDate, $endDate) {
                $string = sprintf('%s;UNTIL=%s', $answer, $endDate->format('c'));

                return new RecurrenceRule($string, $startDate);
            }
        );
    }

    /**
     * @return \DateTime
     */
    private function getStartDate()
    {
        return $this->getDate("Starts at: ");
    }

    /**
     * @param \DateTime $startDate
     *
     * @return \DateTime
     */
    private function getEndDate(\DateTime $startDate)
    {
        return $this->getDate("Ends at: ", $startDate);
    }

    /**
     * @param string    $question
     * @param \DateTime $startDate
     *
     * @return \DateTime
     */
    private function getDate($question, \DateTime $startDate = null)
    {
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