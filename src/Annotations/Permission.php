<?php

namespace Redneck1\Firewalls\Annotations;

use Redneck1\Firewalls\Exception\InvalidArgumentException;

/**
 * Class Permissions
 *
 * @package Redneck1\Firewalls\Annotations
 *
 * @Annotation
 */
class Permission
{
    const LOGIC_OR = 1;
    const LOGIC_AND = 2;

    /** @var array<string> */
    private $names;

    /** @var int */
    private $logic;

    /**
     * Permissions constructor.
     *
     * @param array $values
     * @throws InvalidArgumentException
     */
    public function __construct(array $values)
    {
        if (!array_key_exists('names', $values)
            || false === is_array($values['names'])
            || 0 === count($values['names'])
        ) {
            throw new InvalidArgumentException('Field names must be array of strings.');
        }

        $this->names = $values['names'];
        $this->logic = array_key_exists('logic', $values)
            ? $this->assignLogic($values['logic'])
            : self::LOGIC_OR;
    }

    /** @return string */
    public function getNames()
    {
        return $this->names;
    }

    /** @return int */
    public function getLogic()
    {
        return $this->logic;
    }

    /**
     * @param string $logic
     * @return int
     * @throws InvalidArgumentException
     */
    protected function assignLogic(string $logic): int
    {
        switch (strtolower($logic)) {
            case 'or':
                return 1;
            case 'and':
                return 2;
        }

        throw new InvalidArgumentException(
            sprintf('Logic operator %s is not supported', $logic)
        );
    }
}
