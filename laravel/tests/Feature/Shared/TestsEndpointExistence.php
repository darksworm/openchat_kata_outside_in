<?php


namespace Tests\Feature\Shared;


use Illuminate\Testing\TestResponse;

trait TestsEndpointExistence
{
    abstract function makeEmptyRequest(): TestResponse;

    public function
    test_endpoint_is_connected()
    {
        $response = $this->makeEmptyRequest();

        $this->assertNotContains(
            needle: $response->getStatusCode(),
            haystack: [404, 405],
            message: "Expected endpoint not to return {$response->getStatusCode()}"
        );
        $response->assertHeader('Access-Control-Allow-Origin', '*');
    }
}
