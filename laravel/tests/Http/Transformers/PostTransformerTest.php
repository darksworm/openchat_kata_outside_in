<?php

namespace Tests\Http\Transformers;

use App\Http\Transformers\PostTransformer;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Support\Str;
use PHPUnit\Framework\TestCase;

class PostTransformerTest extends TestCase
{
    public function
    test_transforms_post()
    {
        $post = new MockPost();
        $transformed = PostTransformer::transform($post)->toArray();
        $this->assertPostTransformed($post, $transformed);
    }

    public function
    test_transforms_all_posts()
    {
        $posts = [new MockPost(), new MockPost()];
        $transformed = PostTransformer::transformAll(... $posts)->toArray();

        collect($posts)->map(
            fn($post, $key) => ['post' => $post, 'transformed' => $transformed[$key]]
        )->each(
            fn($tuple) => $this->assertPostTransformed($tuple['post'], $tuple['transformed'])
        );
    }

    private function assertPostTransformed(Post $post, array $transformed)
    {
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

    public function __construct(array $attributes = [])
    {
        $this->user_id = Str::uuid();
        $this->post_id = Str::uuid();
        $this->text = Str::random();
        $this->created_at = Carbon::now();
    }
}
