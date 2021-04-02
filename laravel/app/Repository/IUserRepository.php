<?php


namespace App\Repository;


use App\Models\User;
use App\Service\DuplicateUsernameException;

interface IUserRepository
{
    /**
     * @throws DuplicateUsernameException
     */
    function createUser(string $username, string $password, string $about): User;

    function userWithUsernameExists(string $username): bool;
}
