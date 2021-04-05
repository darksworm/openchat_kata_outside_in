<?php


namespace Tests\Feature;


use Illuminate\Support\Str;

class GetMyWallTest extends FeatureTestCase
{
    public function endpoint($userId): string
    {
        return "/users/{$userId}/wall";
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
    test_returns_wall()
    {
        [$myUserId, $friendUserId, $strangerUserId] = collect($this->createUsers(3))
            ->pluck('id')->toArray();

        $this->createFollowing($myUserId, $friendUserId);

        $postCreators = collect([$myUserId, $friendUserId, $myUserId, $friendUserId]);
        $expectedPosts = $postCreators->map(function ($creatorId, $key) use ($postCreators) {
            sleep(seconds: (int)$key > 0);
            return $this->createPost($creatorId);
        })->toArray();

        $this->createPost($strangerUserId);

        $response = $this->get($this->endpoint($myUserId));

        // reverse is important to validate that the returned posts are in reverse-chronological order
        $response->assertExactJson(array_reverse($expectedPosts));
    }

    private function createUsers(int $count): array
    {
        return collect(range(1, $count))->map(
            fn() => $this->createUser()
        )->toArray();
    }

    private function createUser()
    {
        return
            $this->post('/users', [
                    'username' => Str::random(),
                    'password' => Str::random(),
                    'about' => Str::random()
                ]
            )->json();
    }

    private function createFollowing(string $followerId, string $followeeId)
    {
        $this->post('/followings', [
            'followerId' => $followerId,
            'followeeId' => $followeeId
        ]);
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

    private function createPost(string $posterId): array
    {
        return $this->post(
            uri: "/users/${posterId}/timeline",
            data: [
                'text' => Str::random(64)
            ]
        )->json();
    }
}
