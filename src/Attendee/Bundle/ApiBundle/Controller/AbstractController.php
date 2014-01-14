<?php

namespace Attendee\Bundle\ApiBundle\Controller;

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
     * @return JsonResponse
     */
    protected function createResponse(array $params)
    {
        return new Response(
            $this->serialize($params),
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

        return $serializer->serialize($object, 'json');
    }
} 