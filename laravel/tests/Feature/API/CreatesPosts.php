<?php


namespace Tests\Feature\API;


use Illuminate\Support\Str;
use Illuminate\Testing\TestResponse;

trait CreatesPosts
{
    private function createPost(string $userId, ?string $text = null): array
    {
        return $this->createPostRequest(
            userId: $userId,
            text: $text
        )->json();
    }

    protected function createPostRequest(string $userId, ?string $text = null): TestResponse
    {
        return $this->post(
            uri: "/users/${userId}/timeline",
            data: [
                'text' => $text ?? Str::random(64)
            ]
        );
    }
}
