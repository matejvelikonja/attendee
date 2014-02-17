<?php

namespace Attendee\Bundle\ApiBundle\Controller;

use Attendee\Bundle\ApiBundle\Entity\Location;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\Annotations as Rest;

/**
 * Class LocationsController
 *
 * @Route("/locations")
 *
 * @package Attendee\Bundle\ApiBundle\Controller
 */
class LocationsController extends AbstractController
{
    /**
     * @Rest\View
     * @Rest\Get("/")
     *
     * @ApiDoc(
     *  description="Lists all locations."
     * )
     *
     * @return array
     */
    public function indexAction()
    {
        $locations = $this->getDoctrine()->getRepository('AttendeeApiBundle:Location')->findAll();

        return array(
            'locations' => $locations
        );
    }

    /**
     * @param Location $location
     *
     * @Rest\View
     * @Rest\Get("/{id}")
     *
     * @ApiDoc(
     *  description="Location detail."
     * )
     *
     * @return array
     */
    public function showAction(Location $location)
    {
        return array(
            'location' => $location
        );
    }
}
