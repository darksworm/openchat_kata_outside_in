<?php


namespace Tests\Feature;


use Illuminate\Support\Str;
use Illuminate\Testing\TestResponse;
use Tests\Feature\API\CreatesPosts;
use Tests\Feature\API\RegistersUsers;
use Tests\Feature\Providers\InvalidUuidProvider;
use Tests\Feature\Shared\AssertsDateTimes;
use Tests\Feature\Shared\TestsEndpointExistence;

class CreatePostTest extends FeatureTestCase
{
    use TestsEndpointExistence, AssertsDateTimes;
    use InvalidUuidProvider;
    use RegistersUsers, CreatesPosts;

    function makeEmptyRequest(): TestResponse
    {
        return $this->post('/users/f307ec13-93b9-4041-923e-30f64bf488ac/timeline');
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

    /**
     * @dataProvider invalidUuidProvider
     */
    public function
    test_returns_400_when_invalid_uuid_passed(string $invalidUuid)
    {
        $response = $this->post("/users/{$invalidUuid}/timeline", []);
        $response->assertStatus(400);
        $this->assertEquals("Invalid user id.", $response->getContent());
    }

    public function
    test_returns_400_when_user_doesnt_exist()
    {
        $randomUuid = Str::uuid();
        $response = $this->createPostRequest(userId: $randomUuid, text: 'goodtext');
        $response->assertStatus(400);
        $this->assertEquals("User does not exist.", $response->getContent());
    }

    public function
    test_returns_400_when_inappropriate_language_used_doesnt_exist()
    {
        $userId = $this->registerUser()['id'];
        $response = $this->createPostRequest(userId: $userId, text: 'I like orAnges and elepHANTS!');
        $response->assertStatus(400);
        $this->assertEquals("Inappropriate language used.", $response->getContent());
    }

    public function
    test_returns_201_when_post_creation_successful()
    {
        $userId = $this->registerUser()['id'];
        $response = $this->createPostRequest(userId: $userId, text: 'my post text');

        $response->assertStatus(201);
        $response->assertJsonStructure(['postId', 'userId', 'text', 'dateTime']);

        $this->assertValidUUID($response->json('userId'));
        $this->assertValidUUID($response->json('postId'));

        $this->assertEquals('my post text', $response->json('text'));

        $this->assertValidDateTime($response->json('dateTime'));
        $this->assertRecentDateTime($response->json('dateTime'));
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
