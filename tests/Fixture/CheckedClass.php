<?php

namespace Redneck1\Firewalls\Tests\Fixture;

use Redneck1\Firewalls\Annotations as ACL;

/**
 * Class CheckClass
 * @package Redneck1\Firewalls\Tests\Fixture
 *
 * @ACL\Accessible()
 * @ACL\Permission(names={"TEST_GRANT"})
 * @ACL\Assert(classes={
 *     "Redneck1\Firewalls\Tests\Fixture\PositiveAssert"
 * })
 */
class CheckedClass
{
    /** @return string */
    public function doSomethingSpecial()
    {
        return 'some-secret';
    }
}
