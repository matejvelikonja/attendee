<?php

namespace Attendee\Bundle\ApiBundle\Service;

use Attendee\Bundle\ApiBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * Class TeamService
 *
 * @package Attendee\Bundle\ApiBundle\Service
 *
 * @DI\Service("attendee.team_service")
 */
class TeamService
{
    /**
     * @var EntityRepository
     */
    private $repo;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * @DI\InjectParams({
     *      "em" = @DI\Inject("doctrine.orm.entity_manager")
     * })
     */
    public function __construct(EntityManager $em)
    {
        $this->em   = $em;
        $this->repo = $em->getRepository('AttendeeApiBundle:Team');
    }

    /**
     * Finds teams that user manages.
     *
     * @param User $user
     * @param int  $limit
     * @param int  $offset
     *
     * @return \Attendee\Bundle\ApiBundle\Entity\Event[]
     */
    public function findForUser(User $user, $limit = null, $offset = 0)
    {
        $teams = $this->repo->createQueryBuilder('t')
            ->leftJoin('t.teamManagers', 'm')
            ->where('m.user = :user')
            ->setParameter('user', $user)
            ->getQuery()->getResult();

        return array_slice($teams, $offset, $limit);
    }

}