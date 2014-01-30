<?php

namespace Attendee\Bundle\ApiBundle\Tests\Controller;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class BaseTestCase
 *
 * @package   Attendee\Bundle\WebpageBundle\Tests\Controller
 */
abstract class BaseTestCase extends WebTestCase
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param string $email
     *
     * @return \Symfony\Bundle\FrameworkBundle\Client
     */
    protected function createAuthorizedClient($email = 'admin@example.com')
    {
        $client = static::createClient();
        $container = $client->getContainer();
        $this->container = $container;

        $session = $container->get('session');
        /** @var $userManager \FOS\UserBundle\Doctrine\UserManager */
        $userManager = $container->get('fos_user.user_manager');
        /** @var $loginManager \FOS\UserBundle\Security\LoginManager */
        $loginManager = $container->get('fos_user.security.login_manager');
        $firewallName = $container->getParameter('fos_user.firewall_name');

        $user = $userManager->findUserBy(array('email' => $email));
        $loginManager->loginUser($firewallName, $user);

        // save the login token into the session and put it in a cookie
        $container->get('session')->set('_security_' . $firewallName,
            serialize($container->get('security.context')->getToken()));
        $container->get('session')->save();
        $client->getCookieJar()->set(new Cookie($session->getName(), $session->getId()));

        return $client;
    }

    /**
     * Generates a URL from the given parameters.
     *
     * @param string $route The name of the route
     * @param mixed $parameters An array of parameters
     *
     * @throws \RuntimeException
     *
     * @return string The generated URL
     *
     * @see UrlGeneratorInterface
     */
    protected function url($route, $parameters = array())
    {
        if (! $this->container) {
            throw new \RuntimeException('Container not set.');
        }

        return $this->container->get('router')->generate($route, $parameters, true);
    }

    /**
     * @param string $entityName
     *
     * @return \Doctrine\ORM\EntityRepository
     * @throws \RuntimeException
     */
    protected function getRepo($entityName)
    {
        if (! $this->container) {
            throw new \RuntimeException('Container not set.');
        }

        /** @var EntityManager $em */
        $em = $this->container->get('doctrine.orm.entity_manager');

        return $em->getRepository($entityName);
    }


    /**
     * @param array $keys
     * @param array $array
     */
    protected  function assertArrayHasKeys($keys, $array)
    {
        foreach ($keys as $key) {
            $this->assertArrayHasKey($key, $array, sprintf('Array response should contain %s.', $key));
        }
    }
} 