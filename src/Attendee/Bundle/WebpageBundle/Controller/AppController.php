<?php

namespace Attendee\Bundle\WebpageBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


class AppController extends BaseController
{
    /**
     * @Route("/app", name="app")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }
}
