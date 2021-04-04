<?php


namespace Tests\Feature;


class GetAllUsersTest extends FeatureTestCase
{
    public function
    test_endpoint_is_connected()
    {
        $response = $this->get('/users');
        $this->assertNotContains(
            needle: $response->getStatusCode(),
            haystack: [404, 405],
            message: "Expected endpoint not to return {$response->getStatusCode()}"
        );
        $response->assertHeader('Access-Control-Allow-Origin', '*');
    }

    public function
    test_endpoint_returns_no_user_when_table_empty()
    {
        $response = $this->get('/users');
        $response->assertStatus(200);
        $response->assertExactJson([]);
    }

    public function
    test_endpoint_returns_all_users()
    {
        $requests = [
            $this->aRegistrationRequest(), $this->aRegistrationRequest(), $this->aRegistrationRequest()
        ];

        $expectedUsers = collect($requests)->map(function ($r) {
            return $this->post('/users', $r)->json();
        });

        $response = $this->get('/users');

        $response->assertJsonCount(3);
        $response->assertJsonStructure([['id', 'username', 'about']]);

        foreach ($expectedUsers as $user) {
            $response->assertJsonFragment($user);
        }
    }

    private function
    aRegistrationRequest()
    {
        return [
            'username' => $this->randomString(),
            'password' => $this->randomString(),
            'about' => $this->randomString()
        ];
    }
}
