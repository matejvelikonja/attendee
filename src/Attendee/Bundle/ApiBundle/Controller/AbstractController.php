<?php

namespace Attendee\Bundle\ApiBundle\Controller;

use Attendee\Bundle\ApiBundle\Entity\User;
use Attendee\Bundle\ApiBundle\Service\AttendanceService;
use Attendee\Bundle\ApiBundle\Service\EventService;
use Attendee\Bundle\ApiBundle\Service\LocationService;
use Attendee\Bundle\ApiBundle\Service\TeamService;
use Attendee\Bundle\ApiBundle\Service\UserService;
use FOS\RestBundle\Controller\FOSRestController;

/**
 * Class AbstractController
 *
 * @package   Attendee\Bundle\ApiBundle\Controller
 * @author    Matej Velikonja <mvelikonja@astina.ch>
 * @copyright 2014 Astina AG (http://astina.ch)
 */
class AbstractController extends FOSRestController
{
    /**
     * @return EventService
     */
    protected function getEventService()
    {
        return $this->container->get('attendee.event_service');
    }

    /**
     * @return TeamService
     */
    protected function getTeamService()
    {
        return $this->container->get('attendee.team_service');
    }

    /**
     * @return AttendanceService
     */
    protected function getAttendanceService()
    {
        return $this->container->get('attendee.attendance_service');
    }

    /**
     * @return LocationService
     */
    protected function getLocationService()
    {
        return $this->container->get('attendee.location_service');
    }

    /**
     * @return UserService
     */
    protected function getUserService()
    {
        return $this->container->get('attendee.user_service');
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return parent::getUser();
    }
} 