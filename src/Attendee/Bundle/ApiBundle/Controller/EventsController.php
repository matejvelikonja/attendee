<?php

namespace Attendee\Bundle\ApiBundle\Controller;

use Attendee\Bundle\ApiBundle\Entity\Event;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class EventsController
 *
 * @Route("/events")
 *
 * @package   Attendee\Bundle\ApiBundle\Controller
 */
class EventsController extends AbstractController
{
    /**
     * @Route("/", methods="GET")
     *
     * @ApiDoc(
     *  section="Events",
     *  description="Lists all events.",
     *  parameters={
     *      {"name"="limit",  "dataType"="integer", "required"=false, "description"="Limit number of results."},
     *      {"name"="offset", "dataType"="integer", "required"=false, "description"="Offset results."}
     *  }
     * )
     */
    public function indexAction(Request $request)
    {
        $limit  = $request->get('limit', 10);
        $offset = $request->get('offset');
        $events = $this->getEventService()->find(array(), $limit, $offset);

        return $this->createResponse(
            array(
                'events' => $events
            )
        );
    }

    /**
     * @param Event $event
     *
     * @Route("/{id}", methods="GET")
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
        return $this->createResponse(
            array(
                'event' => $event
            )
        );
    }
}
