<?php


namespace App\DTO;


class PostCreationRequest
{
    private string $userId;
    private string $text;

    public function __construct(string $userId, string $text)
    {
        $this->userId = $userId;
        $this->text = $text;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getText(): string
    {
        return $this->text;
    }
}
