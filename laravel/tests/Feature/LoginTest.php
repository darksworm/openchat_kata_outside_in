<?php


namespace Tests\Feature;


class LoginTest extends FeatureTestCase
{
    public function
    test_route_is_connected()
    {
        $response = $this->post('/login');
        $this->assertNotContains(
            needle: $response->getStatusCode(),
            haystack: [404, 405],
            message: "Expected endpoint not to return {$response->getStatusCode()}"
        );
        $response->assertHeader('Access-Control-Allow-Origin', '*');
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
        $this->post('/users', ['username' => 'someone', 'password' => 'someonespassword', 'about' => 'about someone']);
        $response = $this->post('/login', ['username' => 'someone', 'password' => 'otherpassword']);
        $response->assertStatus(400);
        $this->assertEquals('Invalid credentials.', $response->getContent());
    }

    public function
    test_login_successful_for_registered_user()
    {
        $this->post('/users', ['username' => 'someone', 'password' => 'someonespassword', 'about' => 'about someone']);

        $response = $this->post('/login', ['username' => 'someone', 'password' => 'someonespassword']);
        $response->assertStatus(200);

        $response->assertJsonFragment([
            'username' => 'someone',
            'about' => 'about someone'
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
