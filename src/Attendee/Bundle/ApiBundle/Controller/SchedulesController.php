<?php

namespace Attendee\Bundle\ApiBundle\Controller;

use Attendee\Bundle\ApiBundle\Entity\Schedule;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\Annotations as Rest;

/**
 * Class TeamsController
 *
 * @Route("/schedules")
 *
 * @package Attendee\Bundle\ApiBundle\Controller
 */
class SchedulesController extends AbstractController
{
    /**
     * @param Schedule $schedule
     *
     * @Rest\View()
     * @Rest\Get("/{id}", name="api_schedules_show")
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Schedule detail.",
     *  section="Schedules"
     * )
     *
     * @return array
     */
    public function showAction(Schedule $schedule)
    {
        return array(
            'schedule' => $schedule
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @Rest\View
     * @Rest\Post("", name="api_schedules_create")
     *
     * @ApiDoc(
     *  section="Schedules",
     *  description="Create schedule.",
     *  parameters={
     *      {"name"="name",       "dataType"="string", "required"=true, "description"="Name of the newly created schedule."},
     *      {"name"="starts_at",  "dataType"="string", "required"=true, "description"="DateTime of schedule start."},
     *  }
     * )
     *
     * @return array
     */
    public function createAction(Request $request)
    {
        $data = $request->request->get('schedule');
        $form = $this->createForm('schedule', new Schedule());
        $form->submit($data);

        $schedule = $form->getData();
        $this->getScheduleService()->save($schedule);

        return $this->showAction($schedule);
    }
}
