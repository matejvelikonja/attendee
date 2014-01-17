<?php

namespace Attendee\Bundle\WebpageBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;


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
     * @Route("/templates")
     * @Template()
     */
    public function templatesAction()
    {
        $templates = array();
        $templatesPath = realpath(__DIR__ . '/../Resources/public/js/app/templates');

        $finder = new Finder();
        $finder->files()->in($templatesPath);

        /** @var SplFileInfo $file */
        foreach ($finder as $file) {
            $id = str_replace('.hbs', '', $file->getRelativePathname());
            $templates[$id] = file_get_contents($file);
        }

        return array(
            'templates' => $templates
        );
    }
}
