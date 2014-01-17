<?php

namespace Attendee\Bundle\FixturesBundle\DataFixtures\ORM;

use Attendee\Bundle\ApiBundle\Entity\Team;
use Attendee\Bundle\ApiBundle\Entity\User;
use FOS\UserBundle\Doctrine\UserManager;

/**
 * Class LoadUserData
 *
 * @package   Attendee\Bundle\UserBundle\DataFixtures\ORM
 */
class LoadUserData extends AbstractFixtures
{
    /**
     * @var UserManager
     */
    private $userManager;

    /**
     * Runs fixtures.
     */
    protected function run()
    {
        $this->userManager = $this->container->get('fos_user.user_manager');

        $this->createAdmin();
        $this->createRandomUsers(10);
    }
    /**
     * Creates admin user.
     */
    protected function createAdmin()
    {
        $user = $this->createUser();
        $user
            ->setUsername('admin')
            ->setPlainPassword('admin')
            ->setEmail('admin@example.com')
            ->setEnabled(true);

        $this->userManager->updateUser($user);
    }

    /**
     * Creates random users.
     *
     * @param int $quantity
     */
    private function createRandomUsers($quantity)
    {
        $teams      = $this->manager->getRepository('AttendeeApiBundle:Team')->findAll();
        $teamsCount = count($teams);

        foreach (range(0, $quantity) as $number) {
            $user        = $this->createUser();
            $userName    = $this->faker->userName;
            $email       = "$userName@example.com";
            $numbOfTeams = rand(1, $teamsCount); // how many groups user have

            $user
                ->setUsername($userName)
                ->setPlainPassword('password')
                ->setEmail($email)
                ->setEnabled(true);

            for ($i = 0; $i < $numbOfTeams; $i++) {
                /** @var Team $team */
                do {
                    $team = $this->faker->randomElement($teams);
                } while($user->belongsTo($team));

                $user->addTeam($team);
            }

            $this->userManager->updateUser($user);
        }
    }

    /**
     * @return User
     */
    private function createUser()
    {
        return $this->userManager->createUser();
    }

    /**
     * Get the order of this fixture.
     *
     * @return integer
     */
    public function getOrder()
    {
        return 10;
    }
}