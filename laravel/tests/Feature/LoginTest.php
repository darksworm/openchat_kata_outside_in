<?php


namespace Tests\Feature;


use Illuminate\Testing\TestResponse;
use Tests\Feature\API\RegistersUsers;
use Tests\Feature\Shared\TestsEndpointExistence;

class LoginTest extends FeatureTestCase
{
    use TestsEndpointExistence;
    use RegistersUsers;

    function makeEmptyRequest(): TestResponse
    {
        return $this->post('/login');
    }

    /**
     * @dataProvider badLoginRequestProvider
     */
    public function
    test_returns_400_on_malformed_request($badRequest)
    {
        $response = $this->post('/login', $badRequest);
        $response->assertStatus(400);
        $this->assertEquals('Malformed request.', $response->getContent());
    }

    public function
    test_returns_400_when_user_doesnt_exist()
    {
        $response = $this->post('/login', ['username' => 'someone', 'password' => 'someonespassword']);
        $response->assertStatus(400);
        $this->assertEquals('Invalid credentials.', $response->getContent());
    }

    public function
    test_returns_400_for_incorrect_password()
    {
        $this->registerUser(
            username: 'myName',
            password: 'myPassword'
        );
        $response = $this->post('/login', ['username' => 'someone', 'password' => 'otherpassword']);
        $response->assertStatus(400);
        $this->assertEquals('Invalid credentials.', $response->getContent());
    }

    public function
    test_login_successful_for_registered_user()
    {
        $this->registerUser(
            username: 'myName',
            password: 'myPassword',
            about: 'about me'
        );

        $response = $this->post('/login', ['username' => 'myName', 'password' => 'myPassword']);
        $response->assertStatus(200);

        $response->assertJsonFragment([
            'username' => 'myName',
            'about' => 'about me'
        ]);

        $this->assertValidUUID($response->json('id'));
    }

    public function badLoginRequestProvider()
    {
        return [
            [[]],
            [["username" => "yes"]],
            [["password" => "yes"]],
            [["username" => "yes", "password" => ""]],
            [["username" => "", "password" => ""]],
            [["username" => "", "password" => "yes"]]
        ];
    }
}
