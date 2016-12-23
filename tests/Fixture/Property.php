<?php

namespace Redneck1\Firewalls\Tests\Fixture;

use Redneck1\Firewalls\Annotations as ACL;

/**
 * Class Property
 *
 * @package Redneck1\Firewalls\Tests\Fixture
 */
class Property
{
    /**
     * @var int
     *
     * @ACL\Accessible()
     * @ACL\Permission(names={"PROPERTY_ID"})
     * @ACL\Assert(classes={"Redneck1\Firewalls\Tests\Fixture\NegativeAssert"})
     */
    public $id;

    /**
     * @var string
     *
     * @ACL\Accessible()
     * @ACL\Permission(names={"PROPERTY_TITLE"})
     * @ACL\Assert(classes={"Redneck1\Firewalls\Tests\Fixture\PositiveAssert"})
     */
    public $title;
}
