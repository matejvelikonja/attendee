<?php

namespace Attendee\Bundle\FixturesBundle\DataFixtures\ORM;

use Attendee\Bundle\ApiBundle\Entity\EventSchedule;

/**
 * Class LoadEventsData
 *
 * @package   Attendee\Bundle\FixturesBundle\DataFixtures\ORM
 */
class LoadEventsData extends AbstractFixtures
{
    /**
     * Runs fixtures.
     */
    protected function run()
    {
        $this->createRandomEventSchedules(2);
    }

    /**
     * @param int $quantity
     */
    private function createRandomEventSchedules($quantity)
    {
        foreach (range(1, $quantity) as $q) {
            /** @var \DateTime $startDate */
            $startDate = $this->faker->dateTimeThisYear;
            $endDate   = clone $startDate;
            $endDate   = $endDate->add(new \DateInterval('P1Y'));

            $schedule = new EventSchedule();
            $schedule
                ->setName($this->faker->sentence() . ' ' . $q)
                ->setStartsAt($startDate)
                ->setEndsAt($endDate)
                ->setFrequency(EventSchedule::MONTHLY);

            $this->manager->persist($schedule);
        }
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