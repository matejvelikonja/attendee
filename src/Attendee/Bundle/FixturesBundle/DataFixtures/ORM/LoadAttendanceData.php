<?php

namespace Attendee\Bundle\FixturesBundle\DataFixtures\ORM;
use Attendee\Bundle\ApiBundle\Entity\Attendance;

/**
 * Class LoadAttendanceData
 *
 * @package   Attendee\Bundle\FixturesBundle\DataFixtures\ORM
 */
class LoadAttendanceData extends AbstractFixtures
{
    /**
     * Runs fixtures.
     */
    protected function run()
    {
        $this->createRandomAttendances();
    }


    /**
     * Creates random attendances.
     */
    private function createRandomAttendances()
    {
        $events = $this->manager->getRepository('AttendeeApiBundle:Event')->findAll();
        $users  = $this->manager->getRepository('AttendeeApiBundle:User')->findAll();

        foreach ($events as $event) {
            foreach ($users as $user) {
                $attendance = new Attendance();
                $attendance
                    ->setEvent($event)
                    ->setUser($user)
                    ->setStatus($this->faker->randomElement(Attendance::getStatuses()));

                $this->manager->persist($attendance);
            }
        }
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 200;
    }
}