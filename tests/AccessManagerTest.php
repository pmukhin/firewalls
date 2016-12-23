<?php

namespace Redneck1\Firewalls\Tests;

use Redneck1\Firewalls\AccessManager;
use Redneck1\Firewalls\Tests\Fixture\AssertController;
use Redneck1\Firewalls\Tests\Fixture\CheckedClass;
use Redneck1\Firewalls\Tests\Fixture\NoAssertController;
use Redneck1\Firewalls\Tests\Fixture\NotAccessibleClass;
use Redneck1\Firewalls\Tests\Fixture\Property;
use Redneck1\Firewalls\Tests\Fixture\TestPermissionRepository;
use DI\Container;
use DI\ContainerBuilder;

/**
 * Class PermissionsTest
 *
 * @package Redneck1\Firewalls\Tests
 * @covers \Redneck1\Firewalls\AccessManager
 */
class AccessManagerTest extends \PHPUnit_Framework_TestCase
{
    /** @var Container */
    private $container;

    /** @var AccessManager */
    private $accessManager;

    /** @return void */
    public function setUp()
    {
        $this->container = ContainerBuilder::buildDevContainer();
        $this->accessManager = new AccessManager($this->container);
    }

    /**
     * @covers \Redneck1\Firewalls\AccessManager::authorizeMethod
     */
    public function testNoAssertPositive()
    {
        $class = new \ReflectionClass(NoAssertController::class);
        $action = $class->getMethod('testAction');
        $user = (new TestPermissionRepository)->addPermission('CONTROLLER_TEST');

        static::assertTrue($this->accessManager->authorizeMethod($action, $user));
    }

    /** @covers \Redneck1\Firewalls\AccessManager::authorizeMethod */
    public function testNoAssertNegative()
    {
        $class = new \ReflectionClass(NoAssertController::class);
        $action = $class->getMethod('testRestricted');
        $user = new TestPermissionRepository;

        static::assertFalse($this->accessManager->authorizeMethod($action, $user));
    }

    /** @covers \Redneck1\Firewalls\AccessManager::authorizeMethod */
    public function testAssertPositive()
    {
        $class = new \ReflectionClass(AssertController::class);
        $action = $class->getMethod('testAllowed');
        $user = (new TestPermissionRepository)->addPermission('ASSERT_TEST');

        static::assertTrue($this->accessManager->authorizeMethod($action, $user));
    }

    /** @covers \Redneck1\Firewalls\AccessManager::authorizeMethod */
    public function testNotAllowedTwoAsserts()
    {
        $class = new \ReflectionClass(AssertController::class);
        $action = $class->getMethod('testNotAllowedTwoAsserts');
        $user = new TestPermissionRepository;

        static::assertFalse($this->accessManager->authorizeMethod($action, $user));
    }

    /** @covers \Redneck1\Firewalls\AccessManager::authorizeMethod */
    public function testAssertNegative()
    {
        $class = new \ReflectionClass(AssertController::class);
        $action = $class->getMethod('testRestricted');
        $user = new TestPermissionRepository;

        static::assertFalse($this->accessManager->authorizeMethod($action, $user));
    }

    /** @covers \Redneck1\Firewalls\AccessManager::authorizeProperty */
    public function testPropertyPositive()
    {
        $class = new \ReflectionClass(Property::class);
        $property = $class->getProperty('title');
        $user = (new TestPermissionRepository)->addPermission('PROPERTY_TITLE');

        static::assertTrue($this->accessManager->authorizeProperty($property, $user));
    }

    /** @covers \Redneck1\Firewalls\AccessManager::authorizeProperty */
    public function testPropertyNegative()
    {
        $class = new \ReflectionClass(Property::class);
        $property = $class->getProperty('id');
        $user = new TestPermissionRepository;

        static::assertFalse($this->accessManager->authorizeProperty($property, $user));
    }

    /** @covers \Redneck1\Firewalls\AccessManager::authorizeClass */
    public function testClassPositive()
    {
        $class = new \ReflectionClass(CheckedClass::class);

        $user = new TestPermissionRepository;
        $user->addPermission('TEST_GRANT');

        static::assertTrue($this->accessManager->authorizeClass($class, $user));
    }

    /** @covers \Redneck1\Firewalls\AccessManager::authorizeClass */
    public function testClassNegative()
    {
        $class = new \ReflectionClass(CheckedClass::class);
        $user = new TestPermissionRepository;

        static::assertFalse($this->accessManager->authorizeClass($class, $user));
    }

    /** @covers \Redneck1\Firewalls\AccessManager::authorizeMethod */
    public function testNullPermissionAction()
    {
        $user = new TestPermissionRepository;
        $class = new \ReflectionClass(NoAssertController::class);
        $action = $class->getMethod('noPermissionAction');

        static::assertTrue($this->accessManager->authorizeMethod($action, $user));
    }

    /** Redneck1\Firewalls\AccessManager::authorizeMethod */
    public function testPermissionAndPositive()
    {
        $user = (new TestPermissionRepository)
            ->addPermission('TEST_1')
            ->addPermission('TEST_2');

        $class = new \ReflectionClass(NoAssertController::class);
        $action = $class->getMethod('logicAndAction');

        static::assertTrue($this->accessManager->authorizeMethod($action, $user));
    }

    /** Redneck1\Firewalls\AccessManager::authorizeMethod */
    public function testPermissionAndNegative()
    {
        $user = (new TestPermissionRepository)->addPermission('TEST_1');
        $class = new \ReflectionClass(NoAssertController::class);
        $action = $class->getMethod('logicAndAction');

        static::assertFalse($this->accessManager->authorizeMethod($action, $user));
    }

    /**
     * Redneck1\Firewalls\AccessManager::authorizeMethod
     *
     * @expectedException \Redneck1\Firewalls\Exception\NotAccessibleException
     */
    public function testIsNotAccessibleMethod()
    {
        $user = new TestPermissionRepository();
        $class = new \ReflectionClass(NotAccessibleClass::class);
        $method = $class->getMethod('testMethod');

        $this->accessManager->authorizeMethod($method, $user);
    }

    /**
     * Redneck1\Firewalls\AccessManager::authorizeClass
     *
     * @expectedException \Redneck1\Firewalls\Exception\NotAccessibleException
     */
    public function testIsNotAccessibleClass()
    {
        $user = new TestPermissionRepository();
        $class = new \ReflectionClass(NotAccessibleClass::class);

        $this->accessManager->authorizeClass($class, $user);
    }

    /**
     * Redneck1\Firewalls\AccessManager::authorizeProperty
     *
     * @expectedException \Redneck1\Firewalls\Exception\NotAccessibleException
     */
    public function testIsNotAccessibleProperty()
    {
        $user = new TestPermissionRepository();
        $class = new \ReflectionClass(NotAccessibleClass::class);
        $property = $class->getProperty('id');

        $this->accessManager->authorizeProperty($property, $user);
    }
}
