<?php

namespace Attendee\Bundle\ApiBundle\Controller;

use Attendee\Bundle\ApiBundle\Service\AttendanceService;
use Attendee\Bundle\ApiBundle\Service\EventService;
use Attendee\Bundle\ApiBundle\Service\UserService;
use JMS\Serializer\SerializationContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AbstractController
 *
 * @package   Attendee\Bundle\ApiBundle\Controller
 * @author    Matej Velikonja <mvelikonja@astina.ch>
 * @copyright 2014 Astina AG (http://astina.ch)
 */
class AbstractController extends Controller
{
    /**
     * @param array $params
     *
     * @throws \RuntimeException
     *
     * @return JsonResponse
     */
    protected function createResponse(array $params)
    {
        $serialized = $this->serialize($params);

        if (!$serialized) {
            throw new \RuntimeException('Serialization failed.');
        }

        return new Response(
            $serialized,
            200,
            array(
                'Content-Type' =>'text/javascript'
            )
        );
    }

    /**
     * @param $object
     *
     * @return string
     */
    private function serialize($object)
    {
        /** @var \JMS\Serializer\Serializer $serializer */
        $serializer = $this->container->get('jms_serializer');

        $context = SerializationContext::create();
//        $context->enableMaxDepthChecks();

        return $serializer->serialize($object, 'json', $context);
    }

    /**
     * @return EventService
     */
    protected function getEventService()
    {
        return $this->container->get('attendee.event_service');
    }

    /**
     * @return AttendanceService
     */
    protected function getAttendanceService()
    {
        return $this->container->get('attendee.attendance_service');
    }

    /**
     * @return UserService
     */
    protected function getUserService()
    {
        return $this->container->get('attendee.user_service');
    }
} 