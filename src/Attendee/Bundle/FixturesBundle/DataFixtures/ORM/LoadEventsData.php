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
        $this->createRandomEvents(50);
    }

    /**
     * @param int $quantity
     */
    private function createRandomEvents($quantity)
    {
        foreach (range(0, $quantity) as $q) {
            $event = new EventSchedule();
            $event
                ->setName($this->faker->sentence())
                ->setStartsAt($this->faker->dateTime)
                ->setEndsAt($this->faker->dateTime);
            $this->manager->persist($event);
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