<?php

namespace Redneck1\Firewalls\Tests\Annotations;

use Redneck1\Firewalls\Annotations\Assert;
use Redneck1\Firewalls\Exception\InvalidArgumentException;

/**
 * Class AssertTest
 *
 * @package Redneck1\Firewalls\Tests\Annotations
 * @covers \Redneck1\Firewalls\Annotations\Assert
 */
class AssertTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \Redneck1\Firewalls\Annotations\Assert::__construct
     * @expectedException \Redneck1\Firewalls\Exception\InvalidArgumentException
     *
     * @throws InvalidArgumentException
     */
    public function testConstructorNoClassesKey()
    {
        new Assert([]);
    }

    /**
     * @covers \Redneck1\Firewalls\Annotations\Assert::__construct
     * @expectedException \Redneck1\Firewalls\Exception\InvalidArgumentException
     *
     * @throws InvalidArgumentException
     */
    public function testConstructorEmptyClassesArray()
    {
        new Assert(['classes' => []]);
    }

    /**
     * @covers \Redneck1\Firewalls\Annotations\Assert::getClasses
     * @throws InvalidArgumentException
     */
    public function testGetter()
    {
        $array = ['1', '2'];
        $assert = new Assert(['classes' => $array]);

        static::assertSame($array, $assert->getClasses());
    }
}
