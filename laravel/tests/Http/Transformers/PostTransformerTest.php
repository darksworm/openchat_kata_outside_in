<?php

namespace Tests\Http\Transformers;

use App\Http\Transformers\PostTransformer;
use App\Models\Post;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class PostTransformerTest extends TestCase
{
    public function
    test_transforms_post()
    {
        $post = new MockPost();
        $transformed = PostTransformer::transform($post);

        $this->assertEquals($post->user_id, $transformed['userId']);
        $this->assertEquals($post->post_id, $transformed['postId']);
        $this->assertEquals($post->text, $transformed['text']);

        $actualDate = Carbon::parse($transformed['dateTime']);
        $this->assertEquals($post->created_at->timestamp, $actualDate->timestamp);
    }
}

class MockPost extends Post
{
    public $user_id = "7e961019-f774-4282-9c62-7d3142b50eef";
    public $post_id = "d5e039d9-31cb-49e0-9602-02c03ab9f9a9";
    public $text = "I really loathe hard soaps.";
    public $created_at;

    public function __construct(array $attributes = [])
    {
        $this->created_at = Carbon::now();
    }
}
