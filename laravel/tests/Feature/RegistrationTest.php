<?php

namespace Tests\Feature;

use Tests\TestCase;

class RegistrationTest extends TestCase
{
    private const ALICE_REGISTRATION_REQUEST = [
        'username' => 'Alice',
        'password' => 'MyCatIsBetterThanYours',
        'about' => 'I like sailing and rocketships.'
    ];

    /**
     * @dataProvider badRequestDataProvider
     * @param $registrationRequest - data to be posted
     */
    public function
    test_returns_error_when_incorrect_data_provided(array $registrationRequest)
    {
        $response = $this->post('/openchat/registration', $registrationRequest);

        $response->assertStatus(400);
        $this->assertEquals('Passed post data does not match expected format', $response->getContent());
    }

    public function
    test_returns_201_and_empty_response_when_user_registered()
    {
        $response = $this->post('/openchat/registration', self::ALICE_REGISTRATION_REQUEST);

        $response->assertStatus(201);
        $this->assertEquals('', $response->getContent());
    }

    public function
    test_returns_400_when_registering_duplicate_username()
    {
        $response = $this->post('/openchat/registration', self::ALICE_REGISTRATION_REQUEST);

        $response->assertStatus(201);
        $this->assertEquals('', $response->getContent());

        $response = $this->post('/openchat/registration', self::ALICE_REGISTRATION_REQUEST);
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
