<?php


namespace App\Service;


interface IPasswordHashService
{
    function hash(string $password): string;

    function passwordMatchesHash(string $password, string $hash): bool;
}
