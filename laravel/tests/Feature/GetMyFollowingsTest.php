<?php


namespace Tests\Feature;


use Illuminate\Testing\TestResponse;
use Tests\Feature\API\FollowsUsers;
use Tests\Feature\API\RegistersUsers;
use Tests\Feature\Providers\InvalidUuidProvider;
use Tests\Feature\Shared\TestsEndpointExistence;

class GetMyFollowingsTest extends FeatureTestCase
{
    use TestsEndpointExistence;
    use InvalidUuidProvider;
    use RegistersUsers, FollowsUsers;

    private string $joanId;
    private string $michaelId;
    private string $charlesId;

    function makeEmptyRequest(): TestResponse
    {
        return $this->get('/followings/71828ab3-6fa2-4d55-ac6d-31359b67bfd5/followees');
    }

    /**
     * @dataProvider invalidUuidProvider
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
        $nonExistentUserId = 'd30ae0de-a3fa-4ba6-8950-678c383ed149';
        $response = $this->get("/followings/{$nonExistentUserId}/followees");
        $response->assertStatus(400);
        $this->assertEquals("User with id {$nonExistentUserId} does not exist.", $response->getContent());
    }

    public function
    test_returns_my_followings()
    {
        $this->michaelId = $this->registerUser(username: 'SaintMichael', password: 'Dieu', about: 'a saint')['id'];
        $this->joanId = $this->registerUser(username: 'Joan', password: 'd/Ark', about: 'i am gods daughter')['id'];
        $this->charlesId = $this->registerUser(username: 'Charles', password: 'DieuMio', about: 'I am the king of France.')['id'];

        $this->followUser($this->michaelId, $this->joanId);
        $this->followUser($this->joanId, $this->michaelId);
        $this->followUser($this->charlesId, $this->joanId);

        $response = $this->get("/followings/{$this->michaelId}/followees");
        $response->assertStatus(200);
        $response->assertJsonStructure([['id', 'username', 'about']]);
        $response->assertJsonCount(1);

        $raw = $response->decodeResponseJson();
        $this->assertValidUUID($raw[0]['id']);
        $this->assertEquals('Joan', $raw[0]['username']);
        $this->assertEquals('i am gods daughter', $raw[0]['about']);
    }
}
