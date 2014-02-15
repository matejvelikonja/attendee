<?php

namespace Attendee\Bundle\ApiBundle\Service;

use Attendee\Bundle\ApiBundle\Entity\Attendance;
use Attendee\Bundle\ApiBundle\Entity\Event;
use Attendee\Bundle\ApiBundle\Entity\Location;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * Class EventService
 *
 * @package Attendee\Bundle\ApiBundle\Service
 *
 * @DI\Service("attendee.location_service")
 */
class LocationService
{
    /**
     * @var EntityRepository
     */
    private $repo;

    /**
     * @param EntityManager $em
     *
     * @DI\InjectParams({
     *      "em" = @DI\Inject("doctrine.orm.entity_manager")
     * })
     */
    public function __construct(EntityManager $em)
    {
        $this->repo = $em->getRepository('AttendeeApiBundle:Location');
    }

    /**
     * @param Event[] $events
     *
     * @return Attendance[]
     */
    public function findByEvents($events)
    {
        $locations = array();

        foreach ($events as $event) {
            $locations[$event->getLocation()->getId()] = $event->getLocation();
        }

        return array_values($locations);
    }

    /**
     * @param int $id
     *
     * @return Location
     */
    public function find($id)
    {
        return $this->repo->find($id);
    }


}