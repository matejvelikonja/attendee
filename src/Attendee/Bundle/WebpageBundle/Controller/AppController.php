<?php

namespace Attendee\Bundle\WebpageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


class AppController extends Controller
{
    /**
     * @Route("/app")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }
}
