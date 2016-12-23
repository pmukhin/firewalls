<?php

namespace Redneck1\Firewalls\Tests\Fixture;

use Redneck1\Firewalls\PermissionRepositoryInterface;

/**
 * Class GuestUser
 *
 * @package Redneck1\Firewalls\Tests\Fixture
 */
class TestPermissionRepository implements PermissionRepositoryInterface
{
    /** @var array */
    private $permissions = [];

    /**
     * @param string $grant
     * @return TestPermissionRepository
     */
    public function addPermission(string $grant): TestPermissionRepository
    {
        $this->permissions[] = $grant;

        return $this;
    }

    /** @return array */
    public function getPermissions(): array
    {
        return $this->permissions;
    }

    /**
     * @param string $permission
     * @return bool
     */
    public function hasPermission(string $permission): bool
    {
        return in_array($permission, $this->getPermissions(), true);
    }
}
