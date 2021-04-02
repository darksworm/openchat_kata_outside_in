<?php


namespace Tests;


use Illuminate\Support\Facades\DB;

class FeatureTestCase extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        DB::beginTransaction();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        DB::rollback();
    }
}
