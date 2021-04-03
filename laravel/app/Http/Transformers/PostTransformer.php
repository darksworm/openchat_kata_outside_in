<?php


namespace App\Http\Transformers;


use App\Models\Post;
use Carbon\Carbon;
use JetBrains\PhpStorm\ArrayShape;

class PostTransformer
{
    #[ArrayShape(['postId' => "mixed", 'userId' => "mixed", 'text' => "mixed", 'dateTime' => "string"])]
    public static function transform(Post $post): array
    {
        return [
            'postId' => $post->post_id,
            'userId' => $post->user_id,
            'text' => $post->text,
            'dateTime' => self::formatDateTime($post->created_at)
        ];
    }

    private static function formatDateTime(Carbon $date)
    {
        return $date->format("Y-m-d\TH:i:s\Z");
    }
}
