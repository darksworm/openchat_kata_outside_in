<?php


namespace Tests\Feature;


class GetMyTimelineTest extends FeatureTestCase
{
    protected function endpoint(string $userId): string
    {
        return "/users/{$userId}/timeline";
    }

    public function
    test_endpoint_is_connected()
    {
        $response = $this->get($this->endpoint('71828ab3-6fa2-4d55-ac6d-31359b67bfd5'));
        $this->assertNotContains(
            needle: $response->getStatusCode(),
            haystack: [404, 405],
            message: "Expected endpoint not to return {$response->getStatusCode()}"
        );
        $response->assertHeader('Access-Control-Allow-Origin', '*');
    }

    /**
     * @dataProvider invalidUserIdProvider
     */
    public function
    test_returns_400_for_invalid_userIds($invalidUserId)
    {
        $response = $this->get($this->endpoint($invalidUserId));
        $response->assertStatus(400);
        $this->assertEquals("Invalid user id.", $response->getContent());
    }

    public function
    test_returns_400_for_nonexistent_userId()
    {
        $userId = "710a2c6a-bca2-42cb-8d84-f77039a43416";
        $response = $this->get($this->endpoint($userId));
        $response->assertStatus(400);
        $this->assertEquals("User with id {$userId} does not exist.", $response->getContent());
    }

    public function
    test_returns_my_timeline()
    {
        $myUser = $this->post('/users', ['username' => 'ThatGuy', 'password' => 'yesbuddy', 'about' => 'hes a guy']);
        $myUserId = $myUser->json('id');

        $strangerUser = $this->post('/users', ['username' => 'Strangers', 'password' => 'whyspillyoursadaround', 'about' => 'dreaming of babylon']);
        $strangerUserId = $strangerUser->json('id');

        $createdPosts = [$this->createPost($myUserId)];
        $this->createPost($strangerUserId);

        // sleep to ensure that the next post has a later timestamp
        sleep(1);
        $createdPosts[] = $this->createPost($myUserId);

        $response = $this->get($this->endpoint($myUserId));
        $response->assertJsonCount(2);

        // reverse is important to validate that the returned data is in reverse-chronological order
        $response->assertExactJson(array_reverse($createdPosts));
    }

    private function createPost(string $posterId): array
    {
        return $this->post(
            uri: "/users/${posterId}/timeline",
            data: ['text' => $this->randomString()]
        )->json();
    }

    public function invalidUserIdProvider(): array
    {
        return [
            [' '],
            ['{}'],
            ['not an uuid'],
            ['710a2c6a-bca2-42cb-8d84-partial'],
            ['710a2c6a-bca2-42cb-8d84-f77039a43416-trailing']
        ];
    }
}
