<?php


namespace App\Services;


use RuntimeException;

class SHA512PasswordHashService implements IPasswordHashService
{
    function hash(string $password): string
    {
        if (empty(trim($password))) {
            throw new RuntimeException('Cannot hash empty password');
        }

        $password = hash("sha512", $password);
        return password_hash($password, PASSWORD_BCRYPT);
    }

    function passwordMatchesHash(string $password, string $hash): bool
    {
        $password = hash("sha512", $password);
        return password_verify($password, $hash);
    }
}
