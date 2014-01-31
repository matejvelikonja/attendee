<?php

namespace Attendee\Bundle\FixturesBundle\DataFixtures\ORM;
use Attendee\Bundle\ApiBundle\Entity\ScheduleManager;
use Attendee\Bundle\ApiBundle\Entity\User;

/**
 * Class LoadManagerData
 *
 * @package   Attendee\Bundle\FixturesBundle\DataFixtures\ORM
 */
class LoadManagerData extends AbstractFixtures
{
    /**
     * Runs fixtures.
     */
    protected function run()
    {
        /** @var User $admin */
        $admin     = $this->getReference(LoadUserData::ADMIN_REF);
        $users     = $this->getRandomUsers(5);
        $schedules = $this->manager->getRepository('AttendeeApiBundle:Schedule')->findAll();

        foreach ($schedules as $schedule) {
            if (rand(0, 2) === 0) {
                /** @var User $randomUser */
                $randomUser = $this->faker->randomElement($users);

                $scheduleManager = new ScheduleManager();
                $scheduleManager
                    ->setUser($randomUser)
                    ->setSchedule($schedule);

                $this->manager->persist($scheduleManager);
            }

            $scheduleManager = new ScheduleManager();
            $scheduleManager
                ->setUser($admin)
                ->setSchedule($schedule);

            $this->manager->persist($scheduleManager);
        }
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 150;
    }

    /**
     * @param $limit
     *
     * @return User[]
     */
    private function getRandomUsers($limit)
    {
        $all = $this->manager->getRepository('AttendeeApiBundle:User')->findAll();
        shuffle($all);

        return array_slice($all, 0, $limit);
    }
}