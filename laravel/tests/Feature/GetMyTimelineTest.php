<?php


namespace Tests\Feature;


use Illuminate\Testing\TestResponse;
use Tests\Feature\API\CreatesPosts;
use Tests\Feature\API\RegistersUsers;
use Tests\Feature\Providers\InvalidUuidProvider;
use Tests\Feature\Shared\TestsEndpointExistence;

class GetMyTimelineTest extends FeatureTestCase
{
    use TestsEndpointExistence;
    use InvalidUuidProvider;
    use RegistersUsers, CreatesPosts;

    protected function endpoint(string $userId): string
    {
        return "/users/{$userId}/timeline";
    }

    function makeEmptyRequest(): TestResponse
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
    test_returns_my_timeline()
    {
        $myUserId = $this->registerUser(null, null, null)['id'];
        $strangerUserId = $this->registerUser(null, null, null)['id'];

        $expectedPosts = [$this->createPost($myUserId)];
        // strangers post should not appear in result, do not add it to expected posts
        $this->createPost($strangerUserId);

        // sleep to ensure that the next post has a later timestamp
        sleep(1);
        $expectedPosts[] = $this->createPost($myUserId);

        $response = $this->get($this->endpoint($myUserId));
        $response->assertJsonCount(2);

        // reverse is important to validate that the returned data is in reverse-chronological order
        $response->assertExactJson(array_reverse($expectedPosts));
    }
}
