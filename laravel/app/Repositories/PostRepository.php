<?php


namespace App\Repositories;


use App\Models\Post;
use Illuminate\Support\Collection;

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

    function postsByUserId(string $userId): Collection
    {
        return Post::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
