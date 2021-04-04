<?php


namespace Tests\Feature;


class GetMyFollowingsTest extends FeatureTestCase
{
    private string $joanId;
    private string $michaelId;
    private string $charlesId;

    public function
    test_endpoint_is_connected()
    {
        $response = $this->get('/followings/71828ab3-6fa2-4d55-ac6d-31359b67bfd5/followees');
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
    test_returns_400_when_invalid_follower_id_passed($invalidFollowerId)
    {
        $response = $this->get("/followings/{$invalidFollowerId}/followees");
        $response->assertStatus(400);
        $this->assertEquals('Incorrect follower id.', $response->getContent());
    }

    public function
    test_returns_400_when_nonexistant_follower_id_passed()
    {
        $nonExistantUserId = 'd30ae0de-a3fa-4ba6-8950-678c383ed149';
        $response = $this->get("/followings/{$nonExistantUserId}/followees");
        $response->assertStatus(400);
        $this->assertEquals("User with id {$nonExistantUserId} does not exist.", $response->getContent());
    }

    public function
    test_returns_my_followings()
    {
        $this->createFollowings();

        $response = $this->get("/followings/{$this->michaelId}/followees");
        $response->assertStatus(200);
        $response->assertJsonStructure([['id', 'username', 'about']]);
        $response->assertJsonCount(1);

        $raw = $response->decodeResponseJson();
        $this->assertValidUUID($raw[0]['id']);
        $this->assertEquals('Joan', $raw[0]['username']);
        $this->assertEquals('i am gods daughter', $raw[0]['about']);
    }

    private function createFollowings()
    {
        $michaelResponse = $this->post('/users', ['username' => 'SaintMichael', 'password' => 'Dieu', 'about' => 'a saint.']);
        $this->michaelId = $michaelResponse->json('id');

        $joanResponse = $this->post('/users', ['username' => 'Joan', 'password' => 'd/Ark', 'about' => 'i am gods daughter']);
        $this->joanId = $joanResponse->json('id');

        $charlesResponse = $this->post('/users', ['username' => 'Charles', 'password' => 'DieuMio', 'about' => 'I am the king of France.']);
        $this->charlesId = $charlesResponse->json('id');

        $this->post('/followings', ['followerId' => $this->michaelId, 'followeeId' => $this->joanId]);
        $this->post('/followings', ['followerId' => $this->joanId, 'followeeId' => $this->michaelId]);
        $this->post('/followings', ['followerId' => $this->charlesId, 'followeeId' => $this->joanId]);
    }

    public function invalidUserIdProvider()
    {
        return [
            [' '],
            ['asdfasdf'],
            ['34534534'],
            ['d30ae0de-a3fa-4ba6-8950-']
        ];
    }
}
