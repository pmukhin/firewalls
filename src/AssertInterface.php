<?php

namespace Redneck1\Firewalls;

/**
 * Interface AssertInterface
 *
 * @package Redneck1\Firewalls
 */
interface AssertInterface
{
    /** @return bool */
    public function execute(): bool;
}
