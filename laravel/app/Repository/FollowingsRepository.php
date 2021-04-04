<?php


namespace App\Repository;


use App\Models\Following;
use Illuminate\Support\Collection;

class FollowingsRepository implements IFollowingsRepository
{
    function followingExists(string $followerId, string $followeeId): bool
    {
        return Following::where('follower_id', $followerId)
            ->where('followee_id', $followeeId)
            ->exists();
    }

    function createFollowing(string $followerId, string $followeeId): Following
    {
        $following = new Following();
        $following->follower_id = $followerId;
        $following->followee_id = $followeeId;
        $following->save();

        return $following;
    }

    function followeesForUser(string $followerId): Collection
    {
        return Following::with(['followee'])
            ->where('follower_id', $followerId)
            ->get()->map(function (Following $f) {
                return $f->followee()->first();
            });
    }
}
