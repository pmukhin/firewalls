<?php

namespace Redneck1\Firewalls\Tests\Fixture;

use Redneck1\Firewalls\AssertInterface;

/**
 * Class PositiveAssert
 * @package Redneck1\Firewalls\Tests\Fixture
 */
class NegativeAssert implements AssertInterface
{
    /** @inheritdoc */
    public function execute(): bool
    {
        return false;
    }
}
