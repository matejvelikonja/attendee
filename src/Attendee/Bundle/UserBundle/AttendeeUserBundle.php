<?php

namespace Attendee\Bundle\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class AttendeeUserBundle
 *
 * @package Attendee\Bundle\UserBundle
 */
class AttendeeUserBundle extends Bundle
{
    /**
     * @return string
     */
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
