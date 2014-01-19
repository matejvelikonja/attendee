<?php

namespace Attendee\Bundle\ApiBundle\Controller;

use Attendee\Bundle\ApiBundle\Entity\Location;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class LocationsController
 *
 * @Route("/locations")
 *
 * @package   Attendee\Bundle\ApiBundle\Controller
 */
class LocationsController extends AbstractController
{
    /**
     * @Route("/", methods="GET")
     *
     * @ApiDoc(
     *  description="Lists all locations."
     * )
     */
    public function indexAction()
    {
        $locations = $this->getDoctrine()->getRepository('AttendeeApiBundle:Location')->findAll();

        return $this->createResponse(
            array(
                'locations' => $locations
            )
        );
    }

    /**
     * @param Location $location
     *
     * @Route("/{id}", methods="GET")
     *
     * @ApiDoc(
     *  description="Location detail."
     * )
     *
     * @return array
     */
    public function showAction(Location $location)
    {
        return $this->createResponse(
            array(
                'location' => $location
            )
        );
    }
}
