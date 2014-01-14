<?php

namespace Attendee\Bundle\FixturesBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;

/**
 * Class AbstractFixtures
 *
 * @package   Attendee\Bundle\FixturesBundle\DataFixtures\ORM
 */
abstract class AbstractFixtures implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var \Faker\Generator
     */
    protected $faker;

    /**
     * @var ObjectManager
     */
    private $manager;

    /**
     * Runs fixtures.
     *
     * @return void
     */
    abstract protected function run();

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;
        $this->faker   = $this->container->get('faker');

        $this->run();
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