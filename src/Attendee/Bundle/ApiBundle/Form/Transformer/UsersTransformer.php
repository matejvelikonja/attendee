<?php

namespace Attendee\Bundle\ApiBundle\Form\Transformer;

use Attendee\Bundle\ApiBundle\Service\UserService;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * Class UsersTransformer
 *
 * @package Attendee\Bundle\ApiBundle\Form\Transformer
 */
class UsersTransformer implements DataTransformerInterface
{
    /**
     * @var \Attendee\Bundle\ApiBundle\Service\UserService
     */
    private $service;

    /**
     * @param UserService $service
     */
    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    /**
     * @param mixed $value
     *
     * @throws \Symfony\Component\Form\Exception\TransformationFailedException
     *
     * @return mixed|void
     */
    public function transform($value)
    {
        return null;
    }

    /**
     * @param mixed $value
     *
     * @return \Attendee\Bundle\ApiBundle\Entity\User[]
     */
    public function reverseTransform($value)
    {
        if (empty($value)) {
            return array();
        }

        return $this->service->find($value);
    }
}