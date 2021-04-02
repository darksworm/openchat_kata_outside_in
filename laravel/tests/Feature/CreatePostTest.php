<?php


namespace Tests\Feature;


use DateTime;
use Tests\FeatureTestCase;

class CreatePostTest extends FeatureTestCase
{
    /**
     * @dataProvider badRequestDataProvider
     */
    public function
    test_returns_400_when_passed_json_malformed($badRequest)
    {
        $response = $this->post('/openchat/user/4312ac78-dbf7-4e97-b675-49889efc0ba4/posts', $badRequest);
        $response->assertStatus(400);
        $this->assertEquals("Malformed request.", $response->getContent());
    }

    public function
    test_returns_400_when_invalid_uuid_passed()
    {
        $response = $this->post('/openchat/user/asdf/posts', []);
        $response->assertStatus(400);
        $this->assertEquals("Invalid user id.", $response->getContent());
    }

    public function
    test_returns_400_when_user_doesnt_exist()
    {
        $response = $this->post('/openchat/user/4312ac78-dbf7-4e97-b675-49889efc0ba4/posts', ['text' => 'good.']);
        $response->assertStatus(400);
        $this->assertEquals("User does not exist.", $response->getContent());
    }

    public function
    test_returns_201_when_post_creation_successful()
    {
        $userResponse = $this->post('/openchat/registration', ['username' => 'someone', 'password' => 'someonespassword', 'about' => 'about someone']);
        $userId = $userResponse->json('userId');

        $response = $this->post("/openchat/user/${userId}/posts", ['text' => 'my post text']);

        $response->assertStatus(201);
        $response->assertJsonStructure(['postId', 'userId', 'text', 'dateTime']);

        $this->assertValidUUID($response->json('userId'));
        $this->assertValidUUID($response->json('postId'));
        $this->assertEquals($response->json('text'), 'my post text');
        $this->assertValidDateTime($response->json('dateTime'));
        $this->assertRecentDateTime($response->json('dateTime'));
    }

    public function badRequestDataProvider()
    {
        return [
            [],
            [[]],
            ['post'],
            ['te' => 't']
        ];
    }

    private function assertValidDateTime(string $datetime)
    {
        return DateTime::createFromFormat("Y-m-d\TH:i:s\Z", $datetime) !== null;
    }

    private function assertRecentDateTime(string $datetime)
    {
        $then = DateTime::createFromFormat("Y-m-d\TH:i:s\Z", $datetime);
        $now = new DateTime();
        $secondsBetween = $now->getTimestamp() - $then->getTimestamp();
        $this->assertLessThan(100000, $secondsBetween);
    }
}
