<?php


namespace App\Repositories;


use App\Models\Following;
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
        return $this->postsByUserIds($userId);
    }

    function getWallPostsForUserId(string $userId): Collection
    {
        $followedUserIds = Following::where('follower_id', $userId)
            ->pluck('followee_id')->toArray();

        return $this->postsByUserIds($userId, ...$followedUserIds);
    }

    private function postsByUserIds(string ...$userIds): Collection
    {
        return Post::whereIn('user_id', $userIds)
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
