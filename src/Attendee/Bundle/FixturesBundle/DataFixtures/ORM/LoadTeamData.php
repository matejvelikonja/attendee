<?php

namespace Attendee\Bundle\FixturesBundle\DataFixtures\ORM;
use Attendee\Bundle\ApiBundle\Entity\Team;

/**
 * Class LoadTeamData
 *
 * @package Attendee\Bundle\FixturesBundle\DataFixtures\ORM
 */
class LoadTeamData extends AbstractFixtures
{
    /**
     * Runs fixtures.
     */
    protected function run()
    {
        foreach (range(0, 3) as $i) {
            $group = new Team();
            $group->setName($this->faker->streetSuffix);

            $this->manager->persist($group);
        }
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 5;
    }
}