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
}
