<?php

namespace Attendee\Bundle\FixturesBundle\DataFixtures\ORM;

use Attendee\Bundle\ApiBundle\Entity\Location;
use Attendee\Bundle\ApiBundle\Entity\Schedule;
use Attendee\Bundle\ApiBundle\Entity\Team;
use Recurr\RecurrenceRule;

/**
 * Class LoadScheduleData
 *
 * @package Attendee\Bundle\FixturesBundle\DataFixtures\ORM
 */
class LoadScheduleData extends AbstractFixtures
{
    /**
     * @var Location[]
     */
    private $locations;

    /**
     * @var string[]
     */
    private $rRuleStrings;

    /**
     * Runs fixtures.
     */
    protected function run()
    {
        $this->rRuleStrings = array(
            'FREQ=WEEKLY;INTERVAL=1;BYDAY=TH;UNTIL=%s;BYHOUR=20',
            'FREQ=WEEKLY;INTERVAL=1;BYDAY=TU,TH;UNTIL=%s;BYHOUR=17',
            'FREQ=WEEKLY;INTERVAL=1;BYDAY=MO;UNTIL=%s;BYHOUR=18',
        );

        $this->createRandomSchedules(count($this->rRuleStrings));
        $this->createCurrentlyRunningSchedule();
    }

    /**
     * @param int $quantity
     */
    private function createRandomSchedules($quantity)
    {
        $teams = $this->manager->getRepository('AttendeeApiBundle:Team')->findAll();

        foreach (range(1, $quantity) as $q) {
            /** @var \DateTime $startDate */
            $startDate   = new \DateTime('first day of January this year');
            $endDate     = clone $startDate;
            $endDate     = $endDate->add(new \DateInterval('P1Y'));
            $numbOfTeams = rand(1, count($teams)); // how many teams schedule has
            $duration    = \DateInterval::createFromDateString('2 hours');

            $location = $this->getRandomLocation();

            $rrule = new RecurrenceRule(
                sprintf($this->getRandomRRuleString(), $endDate->format('c')),
                $startDate
            );

            $schedule = new Schedule();
            $schedule
                ->setName($this->faker->company . ' ' . $q)
                ->setDefaultLocation($location)
                ->setRRule($rrule)
                ->setDuration($duration);

            for ($i = 0; $i < $numbOfTeams; $i++) {
                do {
                    /** @var Team $team */
                    $team = $this->faker->randomElement($teams);
                } while ($schedule->belongsTo($team));

                $schedule->addTeam($team);
            }

            $this->manager->persist($schedule);
        }
    }

    /**
     * Creates schedule with only one event, lasting for whole today.
     */
    private function createCurrentlyRunningSchedule()
    {
        $team = $this->manager->getRepository('AttendeeApiBundle:Team')->findOneBy(array());

        /** @var \DateTime $startDate */
        $startDate = new \DateTime('today');
        $endDate   = clone $startDate;
        $endDate   = $endDate->add(new \DateInterval('P1D'));
        $duration  = \DateInterval::createFromDateString('23 hours');

        $location = $this->getRandomLocation();

        $rrule = new RecurrenceRule(
            sprintf('FREQ=DAILY;INTERVAL=1;UNTIL=%s;BYHOUR=1', $endDate->format('c')),
            $startDate
        );

        $schedule = new Schedule();
        $schedule
            ->setName('Only now')
            ->setDefaultLocation($location)
            ->setRRule($rrule)
            ->setDuration($duration);

        $schedule->addTeam($team);

        $this->manager->persist($schedule);
    }

    /**
     * @return Location
     */
    private function getRandomLocation()
    {
        if (! $this->locations) {
            $this->locations = $this->manager->getRepository('AttendeeApiBundle:Location')->findAll();
        }

        return $this->locations[array_rand($this->locations)];
    }

    /**
     * @return string
     */
    private function getRandomRRuleString()
    {
        return $this->faker->unique()->randomElement($this->rRuleStrings);
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 100;
    }
}