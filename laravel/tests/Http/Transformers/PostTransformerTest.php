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
        $post = $this->createMock(MockPost::class);
        $post->user_id = "some-user-id";
        $post->post_id = "some-post-id";
        $post->text = "i am a post";
        $post->created_at = Carbon::now();

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
    public $user_id;
    public $post_id;
    public $text;
    public $created_at;
}
