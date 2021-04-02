<?php

namespace Tests\Feature;

use Tests\FeatureTestCase;

class RegistrationTest extends FeatureTestCase
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
    test_returns_201_and_user_info_when_user_registered()
    {
        $response = $this->post('/openchat/registration', self::ALICE_REGISTRATION_REQUEST);

        $response->assertStatus(201);

        $response->assertJsonStructure(['username', 'about', 'userId']);
        $response->assertJsonFragment([
            'username' => self::ALICE_REGISTRATION_REQUEST['username'],
            'about' => self::ALICE_REGISTRATION_REQUEST['about']
        ]);

        $uuid = $response->json('userId');
        $this->assertTrue($this->isValidUuid($uuid), "an invalid uuid was returned: ${uuid}");
    }

    public function
    test_returns_400_when_registering_duplicate_username()
    {
        $response = $this->post('/openchat/registration', self::ALICE_REGISTRATION_REQUEST);
        $response->assertStatus(201);

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

    private function isValidUuid(string $uuid) {
        if (preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/', $uuid) !== 1) {
            return false;
        }

        return true;
    }
}
