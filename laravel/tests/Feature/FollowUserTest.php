<?php


namespace Tests\Feature;


use Generator;
use Illuminate\Support\Str;

class FollowUserTest extends FeatureTestCase
{
    private string $joanId;
    private string $michaelId;

    public function
    test_endpoint_is_connected()
    {
        $response = $this->post('/followings');
        $this->assertNotEquals(404, $response->getStatusCode());
        $response->assertHeader('Access-Control-Allow-Origin', '*');
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
    test_returns_400_for_nonexistant_follower()
    {
        $this->createThreeUsers();

        $following = ['followerId' => Str::uuid()->toString(), 'followeeId' => $this->joanId];
        $followResponse = $this->post('/followings', $following);

        $followResponse->assertStatus(400);
        $this->assertEquals("User with id {$following['followerId']} does not exist.", $followResponse->getContent());
    }

    public function
    test_returns_400_for_nonexistant_followee()
    {
        $this->createThreeUsers();

        $following = ['followerId' => $this->michaelId, 'followeeId' => Str::uuid()->toString()];
        $followResponse = $this->post('/followings', $following);

        $followResponse->assertStatus(400);
        $this->assertEquals("User with id {$following['followeeId']} does not exist.", $followResponse->getContent());
    }

    public function
    test_returns_400_for_duplicate_following()
    {
        $this->createThreeUsers();

        $followResponse = $this->post('/followings', ['followerId' => $this->michaelId, 'followeeId' => $this->joanId]);
        $followResponse->assertStatus(201);

        $followResponse = $this->post('/followings', ['followerId' => $this->michaelId, 'followeeId' => $this->joanId]);
        $followResponse->assertStatus(400);
        $this->assertEquals("Following already exists.", $followResponse->getContent());
    }

    public function
    test_cannot_follow_self()
    {
        $this->createThreeUsers();

        $followResponse = $this->post('/followings', ['followerId' => $this->joanId, 'followeeId' => $this->joanId]);
        $followResponse->assertStatus(400);
        $this->assertEquals("You cannot follow yourself.", $followResponse->getContent());
    }

    private function createThreeUsers()
    {
        $michaelResponse = $this->post('/users', ['username' => 'SaintMichael', 'password' => 'Dieu', 'about' => 'a saint.']);
        $michaelResponse->assertStatus(201);
        $this->michaelId = $michaelResponse->json('id');

        $joanResponse = $this->post('/users', ['username' => 'Joan', 'password' => 'd/Ark', 'about' => 'i am gods daughter']);
        $joanResponse->assertStatus(201);
        $this->joanId = $joanResponse->json('id');

        $charlesResponse = $this->post('/users', ['username' => 'Charles', 'password' => 'DieuMio', 'about' => 'I am the king of France.']);
        $charlesResponse->assertStatus(201);
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
