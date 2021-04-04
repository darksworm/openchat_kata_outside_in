<?php


namespace Tests\Feature;


use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class FeatureTestCase extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        DB::beginTransaction();
    }

    protected function tearDown(): void
    {
        DB::rollback();
        parent::tearDown();
    }

    protected function randomString(int $length = 16): string
    {
        $allowedChars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        $seed = str_repeat(
            string: $allowedChars,
            times: ceil($length / strlen($allowedChars))
        );

        return substr(
            string: str_shuffle($seed),
            offset: 1,
            length: $length
        );
    }
}
