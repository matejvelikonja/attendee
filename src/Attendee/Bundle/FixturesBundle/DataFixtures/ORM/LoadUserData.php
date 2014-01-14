<?php

namespace Attendee\Bundle\FixturesBundle\DataFixtures\ORM;

use Attendee\Bundle\ApiBundle\Entity\User;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use FOS\UserBundle\Doctrine\UserManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class LoadUserData
 *
 * @package   Attendee\Bundle\UserBundle\DataFixtures\ORM
 * @author    Matej Velikonja <mvelikonja@astina.ch>
 * @copyright 2014 Astina AG (http://astina.ch)
 */
class LoadUserData implements FixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        /** @var UserManager $manager */
        $manager = $this->container->get('fos_user.user_manager');

        /** @var User $user */
        $user = $manager->createUser();
        $user
            ->setUsername('admin')
            ->setPlainPassword('admin')
            ->setEmail('admin@example.com')
            ->setEnabled(true);

        $manager->updateUser($user);
    }

    /**
     * Sets the Container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     *
     * @api
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}