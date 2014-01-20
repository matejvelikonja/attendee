<?php

namespace Attendee\Bundle\ApiBundle\Controller;

use Attendee\Bundle\ApiBundle\Entity\Attendance;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

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
     * @param Attendance $attendance
     *
     * @Route("/{id}", methods="GET")
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Attendance detail.",
     *  section="Attendances"
     * )
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function showAction(Attendance $attendance)
    {
        return $this->createResponse(
            array(
                'attendance' => $attendance
            )
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param Attendance $attendance
     *
     * @Route("/{id}", methods="PUT")
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Update attendance.",
     *  section="Attendances"
     * )
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function updateAction(Request $request, Attendance $attendance)
    {
//        $params = $this->deSerialize($request->getContent(), get_class($attendance));
//var_dump($request->getContent(), $params);die;
//        $this->getAttendanceService()->update($attendance, $params);

        return $this->createResponse(array());
    }
}
