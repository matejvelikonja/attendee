<?php

namespace Attendee\Bundle\FixturesBundle\DataFixtures\ORM;

use Attendee\Bundle\ApiBundle\Entity\TeamManager;
use Attendee\Bundle\ApiBundle\Entity\User;

/**
 * Class LoadManagerData
 *
 * @package Attendee\Bundle\FixturesBundle\DataFixtures\ORM
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

        $this->createTeamManagers($user, $admin);
    }

    /**
     * @param User $user
     * @param User $admin
     */
    protected function createTeamManagers(User $user, User $admin)
    {
        $teams = $this->manager->getRepository('AttendeeApiBundle:Team')->findAll();

        foreach ($teams as $team) {
            if ($team->getId() === 2) {
                $teamManager = new TeamManager();
                $teamManager
                    ->setUser($user)
                    ->setTeam($team);

                $this->manager->persist($teamManager);
            }

            $teamManager = new TeamManager();
            $teamManager
                ->setUser($admin)
                ->setTeam($team);

            $this->manager->persist($teamManager);
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
}