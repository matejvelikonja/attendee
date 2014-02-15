<?php

namespace Attendee\Bundle\ApiBundle\Tests;

use Attendee\Bundle\ApiBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class BaseTestCase
 *
 * @package Attendee\Bundle\WebpageBundle\Tests
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
     * @param User $user If not specified admin is used.
     *
     * @return \Symfony\Bundle\FrameworkBundle\Client
     */
    protected function createAuthorizedClient(User $user = null)
    {
        $session = $this->container->get('session');
        /** @var $userManager \FOS\UserBundle\Doctrine\UserManager */
        $userManager = $this->container->get('fos_user.user_manager');
        /** @var $loginManager \FOS\UserBundle\Security\LoginManager */
        $loginManager = $this->container->get('fos_user.security.login_manager');
        $firewallName = $this->container->getParameter('fos_user.firewall_name');

        if (!$user) {
            $email = 'admin@example.com';
            $user = $userManager->findUserBy(array('email' => $email));
        }

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
     * @param string $route      The name of the route
     * @param mixed  $parameters An array of parameters
     *
     * @return string The generated URL
     *
     * @see UrlGeneratorInterface
     */
    protected function url($route, $parameters = array())
    {
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
     */
    protected function em()
    {
        /** @var EntityManager $em */
        $em = $this->container->get('doctrine.orm.entity_manager');

        return $em;
    }

    /**
     * @param string $service
     *
     * @return mixed
     */
    protected function get($service)
    {
        return $this->container->get($service);
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

    /**
     * @param Client $client
     *
     * @return mixed
     */
    protected function getResponseData(Client $client)
    {
        $content = $client->getResponse()->getContent();

        $this->assertEquals(
            200,
            $client->getResponse()->getStatusCode(),
            $this->tryToGetException($client->getResponse()->getContent())
        );

        $decoded = json_decode($content, true);

        $this->assertEquals(
            JSON_ERROR_NONE,
            json_last_error(),
            sprintf('JSON decoding failed for url `%s` with code %d.',
                json_last_error(),
                $client->getRequest()->getRequestUri()
            )
        );

        return $decoded;
    }

    /**
     * @param string $content
     *
     * @return string
     */
    private function tryToGetException($content)
    {
        $decoded = json_decode($content);

        if (is_array($decoded)) {
            $first = reset($decoded);
            if (is_object($first) && property_exists($first, 'message')) {
                return $first->message;
            }
        }


        return '';
    }
} 