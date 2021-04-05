<?php


namespace App\Repositories;


use App\Models\User;
use Illuminate\Support\Collection;

interface IUserRepository
{
    function createUser(string $username, string $hashedPassword, string $about): User;

    function userWithUsernameExists(string $username): bool;

    function findByUsername(string $username): ?User;

    function userWithIdExists(string $userId): bool;

    function getUsersById(string ...$userIds): Collection;

    function getAllUsers(): Collection;
}
