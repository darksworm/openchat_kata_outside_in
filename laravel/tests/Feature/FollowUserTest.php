<?php


namespace Tests\Feature;


use Generator;
use Illuminate\Support\Str;
use Illuminate\Testing\TestResponse;
use Tests\Feature\API\FollowsUsers;
use Tests\Feature\API\RegistersUsers;
use Tests\Feature\Shared\TestsEndpointExistence;

class FollowUserTest extends FeatureTestCase
{
    use TestsEndpointExistence;
    use RegistersUsers, FollowsUsers;

    private string $joanId;
    private string $michaelId;

    function makeEmptyRequest(): TestResponse
    {
        return $this->post('/followings');
    }

    /**
     * @dataProvider malformedRequestProvider
     */
    public function
    test_returns_400_for_malformed_request($request)
    {
        $response = $this->post('/followings', $request);
        $response->assertStatus(400);
        $this->assertEquals('Malformed request.', $response->getContent());
    }

    public function
    test_returns_400_for_nonexistent_follower()
    {
        $this->createThreeUsers();

        $nonExistentUserId = Str::uuid()->toString();
        $followResponse = $this->followUser($nonExistentUserId, $this->joanId);

        $followResponse->assertStatus(400);
        $this->assertEquals("User with id {$nonExistentUserId} does not exist.", $followResponse->getContent());
    }

    public function
    test_returns_400_for_nonexistant_followee()
    {
        $this->createThreeUsers();

        $nonExistentUserId = Str::uuid()->toString();
        $followResponse = $this->followUser($this->joanId, $nonExistentUserId);

        $followResponse->assertStatus(400);
        $this->assertEquals("User with id {$nonExistentUserId} does not exist.", $followResponse->getContent());
    }

    public function
    test_returns_400_for_duplicate_following()
    {
        $this->createThreeUsers();

        $followResponse = $this->followUser($this->michaelId, $this->joanId);
        $followResponse->assertStatus(201);

        $followResponse = $this->followUser($this->michaelId, $this->joanId);
        $followResponse->assertStatus(400);
        $this->assertEquals("Following already exists.", $followResponse->getContent());
    }

    public function
    test_cannot_follow_self()
    {
        $this->createThreeUsers();

        $followResponse = $this->followUser($this->joanId, $this->joanId);
        $followResponse->assertStatus(400);
        $this->assertEquals("You cannot follow yourself.", $followResponse->getContent());
    }

    private function createThreeUsers()
    {
        $this->michaelId = $this->registerUser(username: 'SaintMichael', password: 'Dieu', about: 'a saint')['id'];
        $this->joanId = $this->registerUser(username: 'Joan', password: 'd/Ark', about: 'i am gods daughter')['id'];
        $this->registerUser(username: 'Charles', password: 'DieuMio', about: 'I am the king of France.')['id'];
    }

    public function malformedRequestProvider(): Generator
    {
        $variants = [
            Str::uuid(),
            [],
            '',
            'some random text',
            1234,
            123.23425
        ];

        foreach ($variants as $variant) {
            foreach ($variants as $other) {
                yield [['followeeId' => $variant, 'followerId' => $other]];
            }
        }

        yield [['followeeId' => Str::uuid()->toString()]];
        yield [['followerId' => Str::uuid()->toString()]];
        yield [[]];
    }
}
