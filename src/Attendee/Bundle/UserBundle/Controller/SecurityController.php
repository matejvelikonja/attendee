<?php

namespace Attendee\Bundle\UserBundle\Controller;

use FOS\UserBundle\Controller\SecurityController as BaseSecurityController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class SecurityController extends BaseSecurityController
{
    /**
     * @Route("/login", name="attendee_login")
     */
    public function loginAction()
    {
        return parent::loginAction();
    }
}
