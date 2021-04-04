<?php


namespace App\Repository;


use App\Exceptions\DuplicateUsernameException;
use App\Models\User;
use Illuminate\Support\Collection;

interface IUserRepository
{
    /**
     * @throws DuplicateUsernameException
     */
    function createUser(string $username, string $password, string $about): User;

    function userWithUsernameExists(string $username): bool;

    function findByUsername(string $username): ?User;

    function userWithIdExists(string $userId) : bool;

    function getUsersById(string ...$userIds): Collection;
}
