<?php

namespace Redneck1\Firewalls;

/**
 * Interface AuthorizableInterface
 *
 * @package Redneck1\Firewalls
 */
interface PermissionRepositoryInterface
{
    /** @return array */
    public function getPermissions(): array;

    /**
     * @param string $permission
     * @return bool
     */
    public function hasPermission(string $permission): bool;
}
