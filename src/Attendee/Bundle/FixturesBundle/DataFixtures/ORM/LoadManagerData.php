<?php

namespace Attendee\Bundle\FixturesBundle\DataFixtures\ORM;
use Attendee\Bundle\ApiBundle\Entity\EventManager;
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
        $admin = $this->getReference(LoadUserData::ADMIN_REF);
        /** @var User $user */
        $user  = $this->getReference(LoadUserData::USER_REF);

        $this->createScheduleManagers($user, $admin);
        $this->createEventManagers($user, $admin);
    }

    /**
     * @param User $user
     * @param User $admin
     */
    protected function createScheduleManagers(User $user, User $admin)
    {
        $schedules = $this->manager->getRepository('AttendeeApiBundle:Schedule')->findAll();

        foreach ($schedules as $schedule) {
            if ($schedule->getId() === 2) {
                $scheduleManager = new ScheduleManager();
                $scheduleManager
                    ->setUser($user)
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
     * @param User $user
     */
    protected function createEventManagers(User $user)
    {
        $event = $this->manager->getRepository('AttendeeApiBundle:Event')->find(2);

        $manager = new EventManager();
        $manager
            ->setUser($user)
            ->setEvent($event);

        $this->manager->persist($manager);
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
}