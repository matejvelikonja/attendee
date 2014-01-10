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

    /**
     * @Template()
     */
    public function templatesAction()
    {
        $templates = array();
        $templatesPath = realpath(__DIR__ . '/../Resources/public/js/app/templates');

        foreach (glob("$templatesPath/*.hbs") as $template) {
            $relativePath = str_replace(array($templatesPath . '/', '.hbs'), array(''), $template);
            $templates[$relativePath] = file_get_contents($template);
        }
        return array(
            'templates' => $templates
        );
    }
}
