<?php

namespace Attendee\Bundle\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

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
