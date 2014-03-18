<?php

namespace Attendee\Bundle\ApiBundle\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

/**
 * Custom Doctrine type for saving \DateInterval object.
 *
 * @package Attendee\lib\Doctrine\Type
 */
class DateIntervalType extends StringType
{
    const NAME = 'dateinterval';

    /**
     * @param string           $value
     * @param AbstractPlatform $platform
     *
     * @return \DateInterval
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return new \DateInterval($value);
    }

    /**
     * @param \DateInterval    $value
     * @param AbstractPlatform $platform
     *
     * @return string
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value->format('P%yY%mM%dDT%hH%iM%sS');
    }

    /**
     * Gets the name of this type.
     *
     * @return string
     */
    public function getName()
    {
        return self::NAME;
    }
}