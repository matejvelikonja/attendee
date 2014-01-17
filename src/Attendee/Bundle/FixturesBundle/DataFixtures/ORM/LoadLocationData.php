<?php

namespace Attendee\Bundle\FixturesBundle\DataFixtures\ORM;
use Attendee\Bundle\ApiBundle\Entity\Location;

/**
 * Class LoadLocationData
 *
 * @package   Attendee\Bundle\FixturesBundle\DataFixtures\ORM
 */
class LoadLocationData extends AbstractFixtures
{
    /**
     * Runs fixtures.
     */
    protected function run()
    {
        foreach(range(0, 10) as $i) {
            $location = new Location();
            $location
                ->setName($this->faker->city)
                ->setLat($this->faker->latitude)
                ->setLng($this->faker->longitude);

            $this->manager->persist($location);
        }
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 300;
    }
}