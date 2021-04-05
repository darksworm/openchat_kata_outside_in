<?php


namespace Tests\Feature;


use Illuminate\Testing\TestResponse;
use Tests\Feature\API\RegistersUsers;
use Tests\Feature\Shared\TestsEndpointExistence;

class GetAllUsersTest extends FeatureTestCase
{
    use TestsEndpointExistence;
    use RegistersUsers;

    function makeEmptyRequest(): TestResponse
    {
        return $this->get('/users');
    }

    public function
    test_endpoint_returns_no_users_when_no_users_created()
    {
        $response = $this->get('/users');
        $response->assertStatus(200);
        $response->assertExactJson([]);
    }

    public function
    test_endpoint_returns_all_users()
    {
        $registeredUsers = $this->registerUsers(3);

        $response = $this->get('/users');
        $response->assertJsonCount(3);
        $response->assertJsonStructure([['id', 'username', 'about']]);

        foreach ($registeredUsers as $user) {
            $response->assertJsonFragment($user);
        }
    }
}
