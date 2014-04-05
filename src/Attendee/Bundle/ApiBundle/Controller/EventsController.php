<?php

namespace Attendee\Bundle\ApiBundle\Controller;

use Attendee\Bundle\ApiBundle\Entity\Event;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use JMS\SecurityExtraBundle\Annotation\SecureParam;
use FOS\RestBundle\Controller\Annotations as Rest;

/**
 * Class EventsController
 *
 * @Route("/events")
 *
 * @package Attendee\Bundle\ApiBundle\Controller
 */
class EventsController extends AbstractController
{
    /**
     * @param Request $request
     *
     * @return array
     *
     * @Rest\View
     * @Rest\Get("", name="api_events_index")
     *
     * @ApiDoc(
     *  section="Events",
     *  description="Lists all events of current user.",
     *  parameters={
     *      {"name"="limit",  "dataType"="integer", "required"=false, "description"="Limit number of results."},
     *      {"name"="offset", "dataType"="integer", "required"=false, "description"="Offset results."},
     *      {"name"="from",   "dataType"="string",  "required"=false, "description"="Returns only events larger than date."}
     *  }
     * )
     */
    public function indexAction(Request $request)
    {
        $limit       = $request->get('limit', 15);
        $offset      = $request->get('offset');
        $startsAt    = $request->get('from');

        if ($startsAt) {
            $startsAt = new \DateTime($startsAt);
        }

        $events      = $this->getEventService()->findForUser($this->getUser(), $startsAt, $limit, $offset);
        $locations   = $this->getLocationService()->findByEvents($events);
        $attendances = $this->getAttendanceService()->findByEvents($events);
        $users       = $this->getUserService()->findByAttendances($attendances);

        return array(
            'events'      => $events,
            'locations'   => $locations,
            'attendances' => $attendances,
            'users'       => $users
        );
    }

    /**
     * @param Event $event
     *
     * @Rest\View
     * @Rest\Get("/{id}", name="api_events_show")
     * @SecureParam(name="event", permissions="MANAGER")
     *
     * @ApiDoc(
     *  section="Events",
     *  description="Event detail."
     * )
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function showAction(Event $event)
    {
        $attendances = $this->getAttendanceService()->findByEvent($event);
        $users       = $this->getUserService()->findByAttendances($attendances);

        return array(
            'event'       => $event,
            'location'    => $event->getLocation(),
            'attendances' => $attendances,
            'users'       => $users
        );
    }

    /**
     * @param Event   $event
     * @param Request $request
     *
     * @Rest\View(statusCode=200)
     * @Rest\Put("/{id}", name="api_events_update")
     * @SecureParam(name="event", permissions="MANAGER")
     *
     * @ApiDoc(
     *  section="Events",
     *  description="Update event."
     * )
     *
     * @return array
     */
    public function updateAction(Event $event, Request $request)
    {
        $data = $request->request->get('event');
        $form = $this->createForm('event', $event);
        $form->submit($data);

        $this->getEventService()->update($event);

        return $this->showAction($event);
    }
}
