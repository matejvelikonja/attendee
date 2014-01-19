<?php

namespace Attendee\Bundle\ApiBundle\Controller;

use Attendee\Bundle\ApiBundle\Entity\Attendance;
use Attendee\Bundle\ApiBundle\Entity\Location;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class AttendancesController
 *
 * @Route("/attendances")
 *
 * @package   Attendee\Bundle\ApiBundle\Controller
 */
class AttendancesController extends AbstractController
{
    /**
     * @Route("/", methods="GET")
     *
     * @ApiDoc(
     *  description="Lists all attendances."
     * )
     */
    public function indexAction()
    {
        $attendances = $this->getDoctrine()->getRepository('AttendeeApiBundle:Attendance')->findAll();

        return $this->createResponse(
            array(
                'attendances' => $attendances
            )
        );
    }

    /**
     * @param Attendance $location
     *
     * @Route("/{id}", methods="GET")
     *
     * @ApiDoc(
     *  description="Attendance detail."
     * )
     *
     * @return array
     */
    public function showAction(Attendance $location)
    {
        return $this->createResponse(
            array(
                'attendance' => $location
            )
        );
    }
}
