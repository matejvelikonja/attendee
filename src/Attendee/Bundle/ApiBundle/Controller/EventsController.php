<?php

namespace Attendee\Bundle\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class EventsController
 *
 * @Route("/events")
 *
 * @package   Attendee\Bundle\ApiBundle\Controller
 */
class EventsController extends Controller
{
    /**
     * @Route("/")
     * @Template()
     */
    public function indexAction()
    {
        return new JsonResponse(array());
    }
}
