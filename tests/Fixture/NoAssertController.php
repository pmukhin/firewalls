<?php

namespace Redneck1\Firewalls\Tests\Fixture;

use Redneck1\Firewalls\Annotations as ACL;

/**
 * Class Controller
 *
 * @package Redneck1\Firewalls\Tests\Fixture
 */
class NoAssertController
{
    /**
     * @ACL\Accessible()
     * @ACL\Permission(names={"CONTROLLER_TEST"})
     */
    public function testAction()
    {
    }

    /**
     * @ACL\Accessible()
     * @ACL\Permission(names={"CONTROLLER_RESTRICTED"})
     */
    public function testRestricted()
    {
    }

    /**
     * @ACL\Accessible()
     * @return null
     */
    public function noPermissionAction()
    {
        return null;
    }

    /**
     * @ACL\Accessible()
     * @ACL\Permission(names={"TEST_1", "TEST_2"}, logic="AND")
     */
    public function logicAndAction()
    {
    }
}
