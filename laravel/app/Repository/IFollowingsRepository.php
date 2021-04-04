<?php


namespace App\Repository;


use App\Models\Following;

interface IFollowingsRepository
{
    function followingExists(string $followerId, string $followeeId): bool;
    function createFollowing(string $followerId, string $followeeId): Following;
}
