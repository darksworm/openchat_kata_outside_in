<?php


namespace App\Repositories;


use App\Models\Post;

class PostRepository implements IPostRepository
{
    function createPost(string $userId, string $text): Post
    {
        $post = new Post();
        $post->user_id = $userId;
        $post->text = $text;

        $post->save();

        return $post;
    }
}
