<?php


namespace App\Repositories;


use App\Models\Post;

interface IPostRepository
{
    function createPost(string $userId, string $text): Post;
}
