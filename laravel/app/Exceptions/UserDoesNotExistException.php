<?php


namespace App\Exceptions;


class UserDoesNotExistException extends \RuntimeException
{
    private string $userId;

    public function __construct(string $userId)
    {
        parent::__construct();
        $this->userId = $userId;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }
}
