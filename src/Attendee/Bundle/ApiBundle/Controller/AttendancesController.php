<?php

namespace Attendee\Bundle\ApiBundle\Controller;

use Attendee\Bundle\ApiBundle\Entity\Attendance;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\Annotations as Rest;

/**
 * Class AttendancesController
 *
 * @Route("/attendances")
 *
 * @package Attendee\Bundle\ApiBundle\Controller
 */
class AttendancesController extends AbstractController
{
    /**
     * @param Request $request
     *
     * @Rest\View
     * @Rest\Get("", name="api_attendances_index")
     *
     * @ApiDoc(
     *  description="Lists all attendances.",
     *  section="Attendances",
     *  parameters={
     *      {"name"="ids[]", "dataType"="integer", "required"=false}
     *  }
     * )
     *
     * @return array
     */
    public function indexAction(Request $request)
    {
        $ids = $request->get('ids');

        $attendances = $this->getAttendanceService()->find($ids);

        return array(
            'attendances' => $attendances
        );
    }

    /**
     * @param Attendance $attendance
     *
     * @Rest\View
     * @Rest\Get("/{id}", name="api_attendances_show")
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Attendance detail.",
     *  section="Attendances"
     * )
     *
     * @return array
     */
    public function showAction(Attendance $attendance)
    {
        return array(
            'attendance' => $attendance
        );
    }

    /**
     * @param \Attendee\Bundle\ApiBundle\Entity\Attendance $attendance
     * @param \Symfony\Component\HttpFoundation\Request    $request
     *
     * This should be PATCH method, but EmberJS does not support it yet.
     * Only changing status implemented for now.
     *
     * @Rest\View(statusCode=204)
     * @Rest\Put("/{id}", name="api_attendances_update")
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Update attendance.",
     *  section="Attendances"
     * )
     */
    public function updateAction(Attendance $attendance, Request $request)
    {
        $data   = $request->request->get('attendance');
        $status = $data['status'];

        $this->getAttendanceService()->changeStatus($attendance, $status);
    }
}
