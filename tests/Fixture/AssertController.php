<?php

namespace Redneck1\Firewalls\Tests\Fixture;

use Redneck1\Firewalls\Annotations as ACL;

/**
 * Class AssertController
 *
 * @package Redneck1\Firewalls\Tests\Fixture
 */
class AssertController
{
    /**
     * @ACL\Accessible()
     * @ACL\Permission(names={"ASSERT_TEST"})
     * @ACL\Assert(classes={
     *     "Redneck1\Firewalls\Tests\Fixture\PositiveAssert"
     * })
     */
    public function testAllowed()
    {
    }

    /**
     * @ACL\Accessible()
     * @ACL\Permission(names={"ASSERT_TEST"})
     * @ACL\Assert(classes={
     *     "Redneck1\Firewalls\Tests\Fixture\PositiveAssert",
     *     "Redneck1\Firewalls\Tests\Fixture\NegativeAssert"
     * })
     *
     * @throws \RuntimeException
     */
    public function testNotAllowedTwoAsserts()
    {
        throw new \RuntimeException('We should have got that far.');
    }

    /**
     * @ACL\Accessible()
     * @ACL\Permission(names={"ASSERT_TEST"})
     * @ACL\Assert(classes={
     *     "Redneck1\Firewalls\Tests\Fixture\NegativeAssert"
     * })
     *
     * @throws \RuntimeException
     */
    public function testRestricted()
    {
        throw new \RuntimeException('We should not have got here');
    }
}
