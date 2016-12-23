<?php

namespace Redneck1\Firewalls;

use Redneck1\Firewalls\Annotations\Accessible;
use Redneck1\Firewalls\Annotations\Assert;
use Redneck1\Firewalls\Annotations\Permission;
use Redneck1\Firewalls\Exception\NotAccessibleException;
use DI\Container;
use DI\DependencyException;
use DI\NotFoundException;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\Reader;
use ReflectionClass as RC;
use ReflectionMethod as RM;
use ReflectionProperty as RP;

/**
 * Class AccessManager
 *
 * @package Redneck1\Firewalls
 */
class AccessManager
{
    /** @var Reader */
    private $reader;

    /** @var Container */
    private $container;

    /**
     * FirewallManager constructor.
     *
     * @param Container $container
     * @param Reader $reader
     */
    public function __construct(Container $container, Reader $reader = null)
    {
        $this->container = $container;

        if (null === $reader) {
            $reader = new AnnotationReader;
        }

        $this->reader = $reader;
    }

    /**
     * @param RM $reflectionMethod
     * @param PermissionRepositoryInterface $permissionRepository
     * @return bool
     * @throws NotAccessibleException
     * @throws \InvalidArgumentException
     * @throws NotFoundException
     * @throws DependencyException
     */
    public function authorizeMethod(RM $reflectionMethod, PermissionRepositoryInterface $permissionRepository): bool
    {
        $accessible = $this->reader->getMethodAnnotation($reflectionMethod, Accessible::class);

        if (null === $accessible) {
            throw new NotAccessibleException(
                sprintf('Method %s is not accessible.', $reflectionMethod->getName())
            );
        }

        /**
         * @var Permission $permissions
         * @var Assert $assert
         */
        $permission = $this->reader->getMethodAnnotation(
            $reflectionMethod,
            Permission::class
        );

        $assert = $this->reader->getMethodAnnotation(
            $reflectionMethod,
            Assert::class
        );

        return $this->assert($permissionRepository, $permission, $assert);
    }

    /**
     * @param RP $reflectionProperty
     * @param PermissionRepositoryInterface $permissionRepository
     * @return bool
     * @throws NotAccessibleException
     * @throws \InvalidArgumentException
     * @throws NotFoundException
     * @throws DependencyException
     */
    public function authorizeProperty(RP $reflectionProperty, PermissionRepositoryInterface $permissionRepository): bool
    {
        $accessible = $this->reader->getPropertyAnnotation($reflectionProperty, Accessible::class);

        if (null === $accessible) {
            throw new NotAccessibleException(
                sprintf('Property %s is not accessible.', $reflectionProperty->getName())
            );
        }

        /**
         * @var Permission $permission
         * @var Assert $assert
         */
        $permission = $this->reader->getPropertyAnnotation($reflectionProperty, Permission::class);
        $assert = $this->reader->getPropertyAnnotation($reflectionProperty, Assert::class);

        return $this->assert($permissionRepository, $permission, $assert);
    }

    /**
     * @param RC $reflectionClass
     * @param PermissionRepositoryInterface $permissionRepository
     * @return bool
     * @throws NotAccessibleException
     * @throws \InvalidArgumentException
     * @throws NotFoundException
     * @throws DependencyException
     */
    public function authorizeClass(RC $reflectionClass, PermissionRepositoryInterface $permissionRepository): bool
    {
        $accessible = $this->reader->getClassAnnotation($reflectionClass, Accessible::class);

        if (null === $accessible) {
            throw new NotAccessibleException(
                sprintf('Class %s is not accessible.', $reflectionClass->getName())
            );
        }

        /**
         * @var Permission $permission
         * @var Assert $assert
         */
        $permission = $this->reader->getClassAnnotation($reflectionClass, Permission::class);
        $assert = $this->reader->getClassAnnotation($reflectionClass, Assert::class);

        return $this->assert($permissionRepository, $permission, $assert);
    }

    /**
     * @param Permission $permission
     * @param PermissionRepositoryInterface $permissionRepository
     * @param Assert|null $assert
     * @return bool
     * @throws \InvalidArgumentException
     * @throws NotFoundException
     * @throws DependencyException
     */
    protected function assert(
        PermissionRepositoryInterface $permissionRepository,
        Permission $permission = null,
        Assert $assert = null
    ): bool {
        if ($permission !== null && !$this->assertInGrants($permission, $permissionRepository)) {
            return false;
        }

        if ($assert !== null) {
            $classes = $assert->getClasses();
            $stack = [];

            foreach ($classes as $assertClass) {
                /** @var AssertInterface $assertObject */
                $assertObject = $this->container->get($assertClass);
                $stack[] = $assertObject->execute();
            }

            return !in_array(false, $stack, true);
        }

        return true;
    }

    /**
     * @param Permission $permission
     * @param PermissionRepositoryInterface $permissionRepository
     * @return bool
     */
    protected function assertInGrants(
        Permission $permission,
        PermissionRepositoryInterface $permissionRepository
    ): bool {
        $grantsRequired = $permission->getNames();
        $logic = $permission->getLogic();

        switch ($logic) {
            case Permission::LOGIC_OR:
                while ($grant = array_shift($grantsRequired)) {
                    if ($permissionRepository->hasPermission($grant)) {
                        return true;
                    }
                }

                break;
            case Permission::LOGIC_AND:
                $stack = [];

                while ($grant = array_shift($grantsRequired)) {
                    $stack[] = $permissionRepository->hasPermission($grant);
                }

                return !in_array(false, $stack, true);
        }

        return false;
    }
}
