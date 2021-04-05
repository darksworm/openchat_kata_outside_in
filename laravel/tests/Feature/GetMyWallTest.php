<?php


namespace Tests\Feature;


use Illuminate\Testing\TestResponse;
use Tests\Feature\API\CreatesPosts;
use Tests\Feature\API\FollowsUsers;
use Tests\Feature\API\RegistersUsers;
use Tests\Feature\Providers\InvalidUuidProvider;
use Tests\Feature\Shared\TestsEndpointExistence;

class GetMyWallTest extends FeatureTestCase
{
    use TestsEndpointExistence;
    use RegistersUsers, FollowsUsers, CreatesPosts;
    use InvalidUuidProvider;

    public function endpoint(string $userId): string
    {
        return "/users/{$userId}/wall";
    }

    public function makeEmptyRequest(): TestResponse
    {
        return $this->get($this->endpoint('71828ab3-6fa2-4d55-ac6d-31359b67bfd5'));
    }

    /**
     * @dataProvider invalidUuidProvider
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
        [$myUserId, $friendUserId, $strangerUserId] = collect($this->registerUsers(3))
            ->pluck('id')->toArray();

        $this->followUser($myUserId, $friendUserId);

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
}
