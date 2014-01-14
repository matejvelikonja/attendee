<?php

namespace Attendee\Bundle\ApiBundle\HttpFoundation;

use Symfony\Component\HttpFoundation\Response as BaseResponse;

/**
 * Class Response
 *
 * @package   Attendee\Bundle\ApiBundle\HttpFoundation
 */
class Response extends BaseResponse
{
    public function __construct(array $params)
    {
        foreach($params as $param) {

        }
    }
} 