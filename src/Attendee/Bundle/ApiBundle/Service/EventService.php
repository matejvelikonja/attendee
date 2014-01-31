<?php

namespace Attendee\Bundle\ApiBundle\Service;

use Attendee\Bundle\ApiBundle\Entity\Event;
use Attendee\Bundle\ApiBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * Class EventService
 *
 * @package Attendee\Bundle\ApiBundle\Service
 *
 * @DI\Service("attendee.event_service")
 */
class EventService
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
        $this->repo = $em->getRepository('AttendeeApiBundle:Event');
    }

    /**
     * @param array $query
     * @param int   $limit
     * @param int   $offset
     *
     * @return Event[]
     */
    private function find($query = array(), $limit = null, $offset = null)
    {
        return $this->repo->findBy($query, array(), $limit, $offset);
    }


    /**
     * @param Event $event
     *
     * @return User[]
     */
    public function getUsers(Event $event)
    {
        $teams = $event->getSchedule()->getTeams();
        $users = array();

        foreach ($teams as $team) {
            foreach ($team->getUsers() as $user) {
                $users[$user->getId()] = $user;
            }
        }

        return array_values($users);
    }

    /**
     * @param User $user
     * @param int  $limit
     * @param int  $offset
     *
     * @return \Attendee\Bundle\ApiBundle\Entity\Event[]
     */
    public function findForUser(User $user, $limit, $offset)
    {
        return $this->find(array(), $limit, $offset);
    }
}