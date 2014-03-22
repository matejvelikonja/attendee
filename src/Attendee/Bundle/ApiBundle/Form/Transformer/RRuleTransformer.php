<?php

namespace Attendee\Bundle\ApiBundle\Form\Transformer;

use Recurr\RecurrenceRule;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * Class RRuleTransformer
 *
 * @package Attendee\Bundle\ApiBundle\Form\Transformer
 */
class RRuleTransformer implements DataTransformerInterface
{
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
     * @return RecurrenceRule
     */
    public function reverseTransform($value)
    {
        if (empty($value)) {
            return null;
        }

        return new RecurrenceRule($value);
    }
}