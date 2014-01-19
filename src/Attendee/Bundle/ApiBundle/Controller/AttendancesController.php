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
     *  description="Lists all attendances.",
     *  section="Attendances",
     *  parameters={
     *      {"name"="ids[]", "dataType"="integer", "required"=false}
     *  }
     * )
     */
    public function indexAction()
    {
        $ids = $this->getRequest()->get('ids');

        $attendances = $this->getAttendanceService()->find($ids);

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
     *  resource=true,
     *  description="Attendance detail.",
     *  section="Attendances"
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
