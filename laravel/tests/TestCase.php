<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplicationTest;

    private const UUID_REGEXP = '/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/';

    protected function assertValidUUID(string $uuid)
    {
        $this->assertTrue($this->isValidUuid($uuid), "UUID invalid: ${uuid}!");
    }

    protected function isValidUuid(string $uuid): bool
    {
        return 1 === preg_match(self::UUID_REGEXP, $uuid);
    }
}
