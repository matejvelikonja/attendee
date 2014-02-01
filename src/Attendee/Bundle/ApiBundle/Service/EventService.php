<?php

namespace Attendee\Bundle\ApiBundle\Service;

use Attendee\Bundle\ApiBundle\Entity\Event;
use Attendee\Bundle\ApiBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;
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
        if (!$event->getSchedule()) {
            return array();
        }

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
     * Finds events that user manages.
     * User manages event if (s)he is a manager of event or of event's schedule.
     *
     * @param User $user
     * @param int  $limit
     * @param int  $offset
     *
     * @return \Attendee\Bundle\ApiBundle\Entity\Event[]
     */
    public function findForUser(User $user, $limit = null, $offset = 0)
    {
        $events = $this->repo->createQueryBuilder('e')
            ->leftJoin('e.schedule', 's')
            ->leftJoin('s.managers', 'sm')
            ->leftJoin('e.managers', 'em')
            ->where('sm.user   = :user')
            ->orWhere('em.user = :user')
            ->setParameter('user', $user)
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery()->getResult();

        return $events;
    }

    /**
     * @param User  $user
     * @param Event $event
     *
     * @return bool
     */
    public function isManager(User $user, Event $event)
    {
        $qb = $this->repo->createQueryBuilder('e');
        try {
            $qb
                ->leftJoin('e.schedule', 's')
                ->leftJoin('s.managers', 'sm')
                ->leftJoin('e.managers', 'em')
                ->where('e.id       = :id')
                ->andWhere($qb->expr()->orX(
                    $qb->expr()->eq('sm.user', ':user'),
                    $qb->expr()->eq('em.user', ':user')
                ))
                ->setParameter('user', $user)
                ->setParameter('id', $event->getId())
                ->getQuery()->getSingleResult();
        } catch(NoResultException $e) {
            return false;
        }

        return true;
    }
}