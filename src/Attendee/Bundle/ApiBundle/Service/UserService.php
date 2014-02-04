<?php

namespace Attendee\Bundle\ApiBundle\Service;

use Attendee\Bundle\ApiBundle\Entity\Team;
use Attendee\Bundle\ApiBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * Class UserService
 *
 * @package Attendee\Bundle\ApiBundle\Service
 *
 * @DI\Service("attendee.user_service")
 */
class UserService
{
    /**
     * @var EntityRepository
     */
    private $repo;

    /**
     * @DI\InjectParams({
     *      "em" = @DI\Inject("doctrine.orm.entity_manager")
     * })
     */
    public function __construct(EntityManager $em)
    {
        $this->repo = $em->getRepository('AttendeeApiBundle:User');
    }

    /**
     * @param int[] $ids
     *
     * @return User[]
     */
    public function find(array $ids = null)
    {
        $qb = $this->repo->createQueryBuilder('u');

        if ($ids) {
            $qb
                ->where('u.id IN (:ids)')
                ->setParameter('ids', $ids);
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * @param Team[] $teams
     *
     * @return User[]
     */
    public function findByTeams($teams)
    {
        $users = array();

        foreach($teams as $team) {
            foreach($team->getUsers() as $user) {
                $users[$user->getId()] = $user;
            }
        }

        return array_values($users);
    }

    public function findByTeam(Team $team)
    {
        $users = array();

        foreach($team->getUsers() as $user) {
            $users[$user->getId()] = $user;
        }

        return array_values($users);
    }
}