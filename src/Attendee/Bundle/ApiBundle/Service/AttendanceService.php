<?php

namespace Attendee\Bundle\ApiBundle\Service;

use Attendee\Bundle\ApiBundle\Entity\Attendance;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;

/**
 * Class AttendanceService
 *
 * @package Attendee\Bundle\ApiBundle\Service
 *
 * @Service("attendee.attendance_service")
 */
class AttendanceService
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
        $this->repo = $em->getRepository('AttendeeApiBundle:Attendance');
    }

    /**
     * @param int[] $ids
     *
     * @return Attendance[]
     */
    public function find(array $ids = null)
    {
        $qb = $this->repo->createQueryBuilder('a');

        if ($ids) {
            $qb
                ->where('a.id IN (:ids)')
                ->setParameter('ids', $ids);
        }

        return $qb->getQuery()->getResult();
    }
}