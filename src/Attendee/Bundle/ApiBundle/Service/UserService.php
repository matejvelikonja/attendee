<?php

namespace Attendee\Bundle\ApiBundle\Service;

use Attendee\Bundle\ApiBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;

/**
 * Class UserService
 *
 * @package Attendee\Bundle\ApiBundle\Service
 *
 * @Service("attendee.user_service")
 */
class UserService
{
    /**
     * @var EntityRepository
     */
    private $repo;

    /**
     * @InjectParams({
     *      "em" = @Inject("doctrine.orm.entity_manager")
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
}