<?php


namespace Tests\Feature\API;


trait FollowsUsers
{
    protected function followUser(string $followerId, string $followeeId)
    {
        return $this->post('/followings', [
            'followerId' => $followerId,
            'followeeId' => $followeeId
        ]);
    }
}
