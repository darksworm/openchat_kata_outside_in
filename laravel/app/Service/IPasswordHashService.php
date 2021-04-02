<?php


namespace App\Service;


interface IPasswordHashService
{
    function hashForPassword(string $password): string;
    function passwordMatchesHash(string $password, string $hash): bool;
}
