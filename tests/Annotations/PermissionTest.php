<?php

namespace Redneck1\Firewalls\Tests\Annotations;

use Redneck1\Firewalls\Annotations\Permission;
use Redneck1\Firewalls\Exception\InvalidArgumentException;

/**
 * Class PermissionTest
 *
 * @package \Redneck1\Firewalls\Tests\Annotations
 *
 * @covers \Redneck1\Firewalls\Annotations\Permission
 */
class PermissionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \Redneck1\Firewalls\Annotations\Permission::__construct
     * @expectedException \Redneck1\Firewalls\Exception\InvalidArgumentException
     *
     * @throws InvalidArgumentException
     */
    public function testConstructorEmptyArray()
    {
        new Permission([]);
    }

    /**
     * @covers \Redneck1\Firewalls\Annotations\Permission::__construct
     * @expectedException \Redneck1\Firewalls\Exception\InvalidArgumentException
     *
     * @throws InvalidArgumentException
     */
    public function testConstructorValuesNotAnArray()
    {
        new Permission(['names' => 1]);
    }

    /**
     * @covers \Redneck1\Firewalls\Annotations\Permission::getNames
     *
     * @throws InvalidArgumentException
     */
    public function testGetNames()
    {
        $permission = new Permission(['names' => ['TEST_1', 'TEST_2']]);

        static::assertContains('TEST_1', $permission->getNames());
        static::assertContains('TEST_2', $permission->getNames());
    }

    /**
     * @covers \Redneck1\Firewalls\Annotations\Permission::getLogic
     *
     * @throws InvalidArgumentException
     */
    public function testGetLogicDefault()
    {
        $permission = new Permission(['names' => ['TEST_1', 'TEST_2']]);

        static::assertEquals(Permission::LOGIC_OR, $permission->getLogic());
    }

    /**
     * @covers \Redneck1\Firewalls\Annotations\Permission::getLogic
     *
     * @throws InvalidArgumentException
     */
    public function testGetLogicSetAnd()
    {
        $permission = new Permission(['names' => ['TEST_1', 'TEST_2'], 'logic' => 'AND']);

        static::assertEquals(Permission::LOGIC_AND, $permission->getLogic());
    }

    /**
     * @covers \Redneck1\Firewalls\Annotations\Permission::getLogic
     *
     * @throws InvalidArgumentException
     */
    public function testGetLogicSetOr()
    {
        $permission = new Permission(['names' => ['TEST_1', 'TEST_2'], 'logic' => 'OR']);

        static::assertEquals(Permission::LOGIC_OR, $permission->getLogic());
    }

    /**
     * @covers \Redneck1\Firewalls\Annotations\Permission::__construct
     * @expectedException \Redneck1\Firewalls\Exception\InvalidArgumentException
     *
     * @throws InvalidArgumentException
     */
    public function testConstructorBadLogicValue()
    {
        new Permission(['names' => ['TEST_1', 'TEST_2'], 'logic' => 'XOR']);
    }
}
