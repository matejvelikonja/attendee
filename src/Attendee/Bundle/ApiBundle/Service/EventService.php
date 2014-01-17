<?php

namespace Attendee\Bundle\ApiBundle\Service;

use Attendee\Bundle\ApiBundle\Entity\Event;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;

/**
 * Class EventService
 *
 * @package Attendee\Bundle\ApiBundle\Service
 *
 * @Service("attendee.event_service")
 */
class EventService
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
        $this->repo = $em->getRepository('AttendeeApiBundle:Event');
    }

    /**
     * @return Event[]
     */
    public function find()
    {
        return $this->repo->findAll();
    }
}