<?php

namespace Attendee\Bundle\ApiBundle\Controller;

use Attendee\Bundle\ApiBundle\Entity\Event;
use Attendee\Bundle\ApiBundle\Service\EventService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

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
     *  description="Lists all events."
     * )
     */
    public function indexAction()
    {
        $events = $this->getEventService()->find(array());

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
     *  description="Event detail."
     * )
     *
     */
    public function showAction(Event $event)
    {

    }

    /**
     * @return EventService
     */
    private function getEventService()
    {
        return $this->container->get('attendee.event_service');
    }
}
