<?php


namespace App\Repository;


use App\Models\Following;
use Illuminate\Support\Collection;

interface IFollowingsRepository
{
    function followingExists(string $followerId, string $followeeId): bool;

    function createFollowing(string $followerId, string $followeeId): Following;

    function followeesForUser(string $followerId): Collection;
}
