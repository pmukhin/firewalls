<?php

namespace Redneck1\Firewalls\Annotations;

use Redneck1\Firewalls\Exception\InvalidArgumentException;

/**
 * Class Assert
 *
 * @package Redneck1\Firewalls\Annotations
 *
 * @Annotation
 */
class Assert
{
    /** @var string */
    private $classes;

    /**
     * Assert constructor.
     *
     * @param array $values
     * @throws InvalidArgumentException
     */
    public function __construct(array $values)
    {
        if (!array_key_exists('classes', $values)
            || false === is_array($values['classes'])
            || 0 === count($values['classes'])
        ) {
            throw new InvalidArgumentException('Field classes must be non-empty array');
        }

        $this->classes = $values['classes'];
    }

    /** @return array */
    public function getClasses()
    {
        return $this->classes;
    }
}
