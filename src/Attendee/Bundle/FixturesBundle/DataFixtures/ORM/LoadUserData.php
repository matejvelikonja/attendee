<?php

namespace Attendee\Bundle\FixturesBundle\DataFixtures\ORM;

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
    private $manager;

    /**
     * Runs fixtures.
     */
    protected function run()
    {
        $this->manager = $this->container->get('fos_user.user_manager');

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

        $this->manager->updateUser($user);
    }

    /**
     * Creates random users.
     *
     * @param int $quantity
     */
    private function createRandomUsers($quantity)
    {
        foreach (range(0, $quantity) as $number) {
            $user     = $this->createUser();
            $userName = $this->faker->userName;
            $email    = "$userName@example.com";

            $user
                ->setUsername($userName)
                ->setPlainPassword('password')
                ->setEmail($email)
                ->setEnabled(true);

            $this->manager->updateUser($user);
        }
    }

    /**
     * @return User
     */
    private function createUser()
    {
        return $this->manager->createUser();
    }

    /**
     * Get the order of this fixture.
     *
     * @return integer
     */
    public function getOrder()
    {
        return 0;
    }
}