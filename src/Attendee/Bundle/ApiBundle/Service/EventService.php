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
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * @param EntityManager $em
     *
     * @DI\InjectParams({
     *      "em" = @DI\Inject("doctrine.orm.entity_manager")
     * })
     */
    public function __construct(EntityManager $em)
    {
        $this->em   = $em;
        $this->repo = $em->getRepository('AttendeeApiBundle:Event');
    }

    /**
     * @param Event $event
     */
    public function update(Event $event)
    {
        $this->em->persist($event);
        $this->em->flush();
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
     * User manages event if (s)he is a manager of team of event's schedule.
     *
     * @param User $user
     * @param int  $limit
     * @param int  $offset
     *
     * @return \Attendee\Bundle\ApiBundle\Entity\Event[]
     */
    public function findForUser(User $user, $limit = null, $offset = 0)
    {
        /**
         * The code here is not optimal. We are always fetching
         * all the events and than slicing them with php.
         * I could not figure out how to make a Doctrine query with
         * joins and limiting and offsetting.
         *
         * http://docs.doctrine-project.org/en/latest/tutorials/pagination.html
         */

        $events = $this->repo->createQueryBuilder('e')
            ->leftJoin('e.schedule', 's')
            ->leftJoin('s.teams', 't')
            ->leftJoin('t.teamManagers', 'm')
            ->where('m.user = :user')
            ->setParameter('user', $user)
            ->getQuery()->getResult();

        return array_slice($events, $offset, $limit);
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
                ->leftJoin('s.teams', 't')
                ->leftJoin('t.teamManagers', 'm')
                ->where('e.id = :id')
                ->andWhere('m.user = :user')
                ->setParameter('id', $event->getId())
                ->setParameter('user', $user)
                ->getQuery()->getSingleResult();
        } catch (NoResultException $e) {
            return false;
        }

        return true;
    }
}