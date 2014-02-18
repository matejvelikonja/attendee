<?php

namespace Attendee\Bundle\WebpageBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Class HomeController
 */
class HomeController extends BaseController
{
    /**
     * @Route("/", name="home", methods="GET")
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        return array();
    }
}
