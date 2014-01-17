<?php

namespace Attendee\Bundle\FixturesBundle\DataFixtures\ORM;

use Attendee\Bundle\ApiBundle\Entity\Location;
use Attendee\Bundle\ApiBundle\Entity\Schedule;

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
        foreach (range(1, $quantity) as $q) {
            /** @var \DateTime $startDate */
            $startDate = $this->faker->dateTimeThisYear;
            $endDate   = clone $startDate;
            $endDate   = $endDate->add(new \DateInterval('P1Y'));

            $location = $this->getRandomLocation();

            $schedule = new Schedule();
            $schedule
                ->setName($this->faker->sentence() . ' ' . $q)
                ->setStartsAt($startDate)
                ->setEndsAt($endDate)
                ->setFrequency(Schedule::MONTHLY)
                ->setDefaultLocation($location);

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