<?php


namespace App\Services;


interface IPasswordHashService
{
    function hash(string $password): string;

    function passwordMatchesHash(string $password, string $hash): bool;
}
