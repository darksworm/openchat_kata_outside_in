<?php

namespace Tests\Feature;


use Illuminate\Testing\TestResponse;
use Tests\Feature\API\RegistersUsers;
use Tests\Feature\Shared\TestsEndpointExistence;

class RegistrationTest extends FeatureTestCase
{
    use TestsEndpointExistence;
    use RegistersUsers;

    function makeEmptyRequest(): TestResponse
    {
        return $this->post('/users');
    }

    /**
     * @dataProvider badRequestDataProvider
     * @param $registrationRequest - data to be posted
     */
    public function
    test_returns_error_when_incorrect_data_provided(array $registrationRequest)
    {
        $response = $this->post('/users', $registrationRequest);

        $response->assertStatus(400);
        $this->assertEquals('Passed post data does not match expected format', $response->getContent());
    }

    public function
    test_returns_201_and_user_info_when_user_registered()
    {
        $response = $this->registerUserRequest(
            username: 'Alice',
            password: 'myPassword',
            about: 'I like to eat pies'
        );
        $response->assertStatus(201);

        $response->assertJsonStructure(['username', 'about', 'id']);
        $response->assertJsonFragment([
            'username' => 'Alice',
            'about' => 'I like to eat pies'
        ]);

        $this->assertValidUUID($response->json('id'));
    }

    public function
    test_returns_400_when_registering_duplicate_username()
    {
        $response = $this->registerUserRequest(username: 'Alice');
        $response->assertStatus(201);

        $response = $this->registerUserRequest(username: 'Alice');
        $response->assertStatus(400);
        $this->assertEquals('Username already in use.', $response->getContent());
    }

    public function badRequestDataProvider(): array
    {
        return [
            [[]],
            [[
                'random' => 'string',
                'poop' => []
            ]],
            [[
                'username' => 'Alice',
                'password' => ''
            ]],
            [[
                'password' => 'alki324d',
                'about' => 'I love playing the piano and travelling.'
            ]],
            [[
                'username' => 'Alice',
                'about' => 'I love playing the piano and travelling.'
            ]],
            [[
                'username' => 'Alice',
                'password' => [],
                'about' => 'I love playing the piano and travelling.'
            ]],
            [[
                'username' => 'Alice',
                'password' => 'yes',
                'about' => []
            ]],
            [[
                'username' => [],
                'password' => 'yes',
                'about' => 'yes'
            ]]
        ];
    }
}
