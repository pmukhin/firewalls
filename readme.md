# Redneck1\Firewalls

## About
Firewalls is a brand new annotation-driven multipurpose ACL written in PHP built on top of php-di library which is used to resolve dependencies for Dynamic Assertions classes.

## Dependencies
Firewalls use Doctrine\Annotations package as an annotation driver and PHP-DI to resolve dependencies for Dynamic Assertions.

## How to use
- Implement PermissionRepositoryInterface
- Instantiate AccessManager pushing through your PHP-DI container instance

## Details
AccessManager can accept two params:
- AnnotationReader that implementsDoctrine\Common\Annotations\Reader
- Instantiator that implements Doctrine\Instantiator\InstantiatorInterface

## Example
```php
<?php

use Redneck1\Firewalls\AccessManager;
use Redneck1\Firewalls\Annotations as ACL;
use Redneck1\Firewalls\AuthorizableInterface;

class MyController
{
    /**
     * @ACL\Accessible()
     * @ACL\Permission(name="ROLE_GUEST")
     * @ACL\Assert(classes={"Redneck1\MyProject\MyAssert"})
     */
    public function indexAction()
    {
    }
}

class AwesomeModel
{
    /**
     * @var int
     *
     * @ACL\Accessible()
     * @ACL\Permission(name="PROPERTY_ID")
     * @ACL\Assert(classes={"Redneck1\Firewalls\Tests\Fixture\NegativeAssert"})
     */
    public $id;

    /**
     * @var string
     *
     * @ACL\Accessible()
     * @ACL\Permission(name="PROPERTY_TITLE")
     * @ACL\Assert(classes={"Redneck1\Firewalls\Tests\Fixture\PositiveAssert"})
     */
    public $title;
}

class User implements AuthorizableInterface
{
    /**
     * @return array
     */
    public function getGrants(): array
    {
        return ['ROLE_GUEST'];
    }
}

/**
 * Class CheckClass
 * @package Coccoc\Firewalls\Tests\Fixture
 *
 * @ACL\Permission(name="TEST_GRANT")
 * @ACL\Assert(classes={"
        Redneck1\Firewalls\Tests\Fixture\PositiveAssert",
        Redneck1\Firewalls\Tests\Fixture\AnotherAssert"
 * })
 */
class CheckedClass
{
    /**
     * @return string
     */
    public function doSomethingSpecial()
    {
        return 'some-secret';
    }
}

$containerBuilder = new DI\ContainerBuilder();
$containerBuilder->addDefinitions([...])
$container = $containerBuilder->build();

$am = new AccessManager($container);
$user = new User;

if (!$am->authorizeMethod((new ReflectionClass(MyController::class)->getMethod('myMethod')), $user)) {
    throw new ForbiddenHttpException('You are not allowed to see this data.');
}

if (!$am->authorizeProperty((new ReflectionClass(AwesomeModel::class)->getPropery('title')), $user)) {
    throw new ForbiddenHttpException('You are not allowed to see this data.');
}

if (!$am->authorizeClass((new ReflectionClass(CheckedClass::class), $user)) {
    throw new ForbiddenHttpException('You are not allowed to see this data.');
}
```
