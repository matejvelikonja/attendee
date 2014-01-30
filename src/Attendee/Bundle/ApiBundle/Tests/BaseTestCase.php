<?php

namespace Attendee\Bundle\ApiBundle\Tests;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class BaseTestCase
 *
 * @package   Attendee\Bundle\WebpageBundle\Tests
 */
abstract class BaseTestCase extends WebTestCase
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var Client
     */
    private $client;

    /**
     * Creates client
     */
    public function setUp()
    {
        $this->client    = static::createClient();
        $this->container = $this->client->getContainer();
    }

    /**
     * @param string $email
     *
     * @return \Symfony\Bundle\FrameworkBundle\Client
     */
    protected function createAuthorizedClient($email = 'admin@example.com')
    {
        $session = $this->container->get('session');
        /** @var $userManager \FOS\UserBundle\Doctrine\UserManager */
        $userManager = $this->container->get('fos_user.user_manager');
        /** @var $loginManager \FOS\UserBundle\Security\LoginManager */
        $loginManager = $this->container->get('fos_user.security.login_manager');
        $firewallName = $this->container->getParameter('fos_user.firewall_name');

        $user = $userManager->findUserBy(array('email' => $email));
        $loginManager->loginUser($firewallName, $user);

        // save the login token into the session and put it in a cookie
        $this->container->get('session')->set('_security_' . $firewallName,
            serialize($this->container->get('security.context')->getToken()));
        $this->container->get('session')->save();
        $this->client->getCookieJar()->set(new Cookie($session->getName(), $session->getId()));

        return $this->client;
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
     */
    protected function getRepo($entityName)
    {
        return $this->em()->getRepository($entityName);
    }

    /**
     * @return EntityManager
     *
     * @throws \RuntimeException
     */
    protected function em()
    {
        if (! $this->container) {
            throw new \RuntimeException('Container not set.');
        }

        /** @var EntityManager $em */
        $em = $this->container->get('doctrine.orm.entity_manager');

        return $em;
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