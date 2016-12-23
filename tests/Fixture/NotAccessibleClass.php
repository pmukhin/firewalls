<?php

namespace Redneck1\Firewalls\Tests\Fixture;

/**
 * Class NotAccessibleClass
 *
 * @package Redneck1\Firewalls\Tests\Fixture
 */
class NotAccessibleClass
{
    /** @var int */
    public $id = 0;

    /** @throws \RuntimeException */
    public function testMethod()
    {
        throw new \RuntimeException('We should not have got here.');
    }
}
