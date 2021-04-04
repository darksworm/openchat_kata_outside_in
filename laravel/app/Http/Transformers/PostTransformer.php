<?php


namespace App\Http\Transformers;


use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use JetBrains\PhpStorm\ArrayShape;

class PostTransformer
{
    #[ArrayShape(['postId' => "mixed", 'userId' => "mixed", 'text' => "mixed", 'dateTime' => "string"])]
    public static function transform(Post $post): Collection
    {
        return collect([
            'postId' => $post->post_id,
            'userId' => $post->user_id,
            'text' => $post->text,
            'dateTime' => self::formatDateTime($post->created_at)
        ]);
    }

    private static function formatDateTime(Carbon $date): string
    {
        return $date->format("Y-m-d\TH:i:s\Z");
    }
}
