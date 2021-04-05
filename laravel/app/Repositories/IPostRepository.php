<?php


namespace App\Repositories;


use App\Models\Post;
use Illuminate\Support\Collection;

interface IPostRepository
{
    function createPost(string $userId, string $text): Post;

    function postsByUserId(string $userId): Collection;
}
