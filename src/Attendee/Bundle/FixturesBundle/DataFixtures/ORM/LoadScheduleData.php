<?php

namespace Attendee\Bundle\FixturesBundle\DataFixtures\ORM;

use Attendee\Bundle\ApiBundle\Entity\Location;
use Attendee\Bundle\ApiBundle\Entity\Schedule;
use Attendee\Bundle\ApiBundle\Entity\Team;

/**
 * Class LoadScheduleData
 *
 * @package   Attendee\Bundle\FixturesBundle\DataFixtures\ORM
 */
class LoadScheduleData extends AbstractFixtures
{
    /**
     * @var Location[]
     */
    private $locations;

    /**
     * Runs fixtures.
     */
    protected function run()
    {
        $this->createRandomSchedules(2);
    }

    /**
     * @param int $quantity
     */
    private function createRandomSchedules($quantity)
    {
        $teams = $this->manager->getRepository('AttendeeApiBundle:Team')->findAll();

        foreach (range(1, $quantity) as $q) {
            /** @var \DateTime $startDate */
            $startDate   = $this->faker->dateTimeThisYear;
            $endDate     = clone $startDate;
            $endDate     = $endDate->add(new \DateInterval('P1Y'));
            $numbOfTeams = rand(1, count($teams)); // how many teams schedule has

            $location = $this->getRandomLocation();

            $schedule = new Schedule();
            $schedule
                ->setName($this->faker->sentence() . ' ' . $q)
                ->setStartsAt($startDate)
                ->setEndsAt($endDate)
                ->setFrequency(Schedule::MONTHLY)
                ->setDefaultLocation($location);

            for ($i = 0; $i < $numbOfTeams; $i++) {
                do {
                    /** @var Team $team */
                    $team = $this->faker->randomElement($teams);
                } while($schedule->belongsTo($team));

                $schedule->addTeam($team);
            }

            $this->manager->persist($schedule);
        }
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
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 100;
    }
}