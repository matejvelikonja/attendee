<?php

namespace Attendee\Bundle\UserBundle\Controller;

use FOS\UserBundle\Controller\SecurityController as BaseSecurityController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class SecurityController
 *
 * @package Attendee\Bundle\UserBundle\Controller
 */
class SecurityController extends BaseSecurityController
{
    /**
     * @return Response
     *
     * @Route("/login", name="attendee_login")
     * @Template()
     */
    public function loginAction()
    {
        return parent::loginAction();
    }
}
