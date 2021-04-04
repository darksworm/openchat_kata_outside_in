<?php


namespace Tests\Feature;


use DateTime;

class CreatePostTest extends FeatureTestCase
{
    public function
    test_route_is_connected()
    {
        $response = $this->post('/users/asdfasdf/timeline');
        $this->assertNotContains(
            needle: $response->getStatusCode(),
            haystack: [404, 405],
            message: "Expected endpoint not to return {$response->getStatusCode()}"
        );
        $response->assertHeader('Access-Control-Allow-Origin', '*');
    }

    /**
     * @dataProvider badRequestDataProvider
     */
    public function
    test_returns_400_when_passed_json_malformed($badRequest)
    {
        $response = $this->post('/users/4312ac78-dbf7-4e97-b675-49889efc0ba4/timeline', $badRequest);
        $this->assertEquals(400, $response->status(), 'badRequest not received with data ' . json_encode($badRequest));
        $this->assertEquals("Malformed request.", $response->getContent());
    }

    public function
    test_returns_400_when_invalid_uuid_passed()
    {
        $response = $this->post('/users/asdf/timeline', []);
        $response->assertStatus(400);
        $this->assertEquals("Invalid user id.", $response->getContent());
    }

    public function
    test_returns_400_when_user_doesnt_exist()
    {
        $response = $this->post('/users/4312ad78-dbf7-4e97-b675-49889efc0ba4/timeline', ['text' => 'goodtext']);
        $response->assertStatus(400);
        $this->assertEquals("User does not exist.", $response->getContent());
    }

    public function
    test_returns_400_when_inappropriate_language_used_doesnt_exist()
    {
        $userResponse = $this->post('/users', ['username' => 'someone', 'password' => 'someonespassword', 'about' => 'about someone']);
        $userId = $userResponse->json('id');

        $response = $this->post("/users/${userId}/timeline", ['text' => 'I like orAnges and elepHANTS!']);

        $response->assertStatus(400);
        $this->assertEquals("Inappropriate language used.", $response->getContent());
    }

    public function
    test_returns_201_when_post_creation_successful()
    {
        $userResponse = $this->post('/users', ['username' => 'someone', 'password' => 'someonespassword', 'about' => 'about someone']);
        $userId = $userResponse->json('id');

        $response = $this->post("/users/${userId}/timeline", ['text' => 'my post text']);

        $response->assertStatus(201);
        $response->assertJsonStructure(['postId', 'userId', 'text', 'dateTime']);

        $this->assertValidUUID($response->json('userId'));
        $this->assertValidUUID($response->json('postId'));
        $this->assertEquals($response->json('text'), 'my post text');
        $this->assertValidDateTime($response->json('dateTime'));
        $this->assertRecentDateTime($response->json('dateTime'));
    }

    private function assertValidDateTime(string $datetime)
    {
        return DateTime::createFromFormat("Y-m-d\TH:i:s\Z", $datetime) instanceof DateTime;
    }

    private function assertRecentDateTime(string $datetime)
    {
        $then = DateTime::createFromFormat("Y-m-d\TH:i:s\Z", $datetime);
        $this->assertNotFalse($then, "${datetime} does not match the required datetime format!");
        $now = new DateTime();
        $secondsBetween = $now->getTimestamp() - $then->getTimestamp();
        $this->assertLessThan(100000, $secondsBetween);
    }

    public function badRequestDataProvider()
    {
        return [
            [[]],
            [[[]]],
            [['post']],
            [['te' => 't']]
        ];
    }
}
