<?php

namespace Tests\Service;

use App\Service\SHA512PasswordHashService;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class SHA512PasswordHashServiceTest extends TestCase
{
    public function
    test_does_not_return_empty_string_for_password()
    {
        $passwordHashService = new SHA512PasswordHashService();
        $hashedPassword = $passwordHashService->hashForPassword("somepassword");
        $this->assertNotEmpty($hashedPassword, "hashed password should not be empty");
    }

    public function
    test_two_different_passwords_dont_produce_same_hash()
    {
        $passwordHashService = new SHA512PasswordHashService();
        $hashedPassword = $passwordHashService->hashForPassword("somepassword");
        $otherHashedPassword = $passwordHashService->hashForPassword("differentthing");

        $this->assertNotEquals($hashedPassword, $otherHashedPassword);
    }

    public function
    test_generated_hash_matches_password()
    {
        $passwordHashService = new SHA512PasswordHashService();
        $hash = $passwordHashService->hashForPassword("somepassword");

        $this->assertTrue(
            $passwordHashService->passwordMatchesHash("somepassword", $hash)
        );
    }

    public function
    test_generated_hash_doesnt_match_different_password()
    {
        $passwordHashService = new SHA512PasswordHashService();
        $hashedPassword = $passwordHashService->hashForPassword("somepassword");

        $this->assertFalse(
            $passwordHashService->passwordMatchesHash("otherthing", $hashedPassword)
        );
    }

    /**
     * @dataProvider emptyPasswordProvider
     */
    public function
    test_throws_for_empty_password(string $emptyPassword)
    {
        $this->expectException(RuntimeException::class);
        $passwordHashService = new SHA512PasswordHashService();
        $passwordHashService->hashForPassword($emptyPassword);
    }

    function emptyPasswordProvider()
    {
        return [
            [""],
            [" "],
            ["\t"],
            ["\n"],
            ["\n\t "]
        ];
    }
}