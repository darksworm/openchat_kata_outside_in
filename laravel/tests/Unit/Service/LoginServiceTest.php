<?php

namespace Tests\Unit\Service;

use App\Service\LoginFailException;
use App\Models\User;
use App\Repository\IUserRepository;
use App\Service\IPasswordHashService;
use App\Service\LoginService;
use Generator;
use PHPUnit\Framework\TestCase;

class LoginServiceTest extends TestCase
{
    private LoginService $loginService;
    private IPasswordHashService $passwordHashService;
    private IUserRepository $userRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->passwordHashService = $this->createMock(IPasswordHashService::class);
        $this->userRepository = $this->createMock(IUserRepository::class);

        $this->loginService = new LoginService($this->userRepository, $this->passwordHashService);
    }

    /**
     * @dataProvider randomCredentialsProvider
     */
    public function
    test_random_credentials_arent_valid(string $randomUsername, string $randomPassword)
    {
        $this->expectException(LoginFailException::class);
        $this->loginService->loginUser($randomUsername, $randomPassword);
    }

    /**
     * @dataProvider emptyCredentialsProvider
     */
    public function
    test_empty_credentials_arent_valid(string $maybeEmptyUsername, string $maybeEmptyPassword)
    {
        $this->expectException(LoginFailException::class);
        $this->loginService->loginUser($maybeEmptyUsername, $maybeEmptyPassword);
    }

    public function
    test_existing_username_with_bad_password_isnt_valid()
    {
        $storedUser = new User();
        $storedUser->password = "somelonghash";

        $this->userRepository->expects($this->once())
            ->method('findByUsername')
            ->with('john')
            ->willReturn($storedUser);

        $this->passwordHashService->expects($this->once())
            ->method('passwordMatchesHash')
            ->with( "jibberish", "somelonghash")
            ->willReturn(false);

        $this->expectException(LoginFailException::class);
        $this->loginService->loginUser("john", "jibberish");
    }

    public function
    test_existing_username_with_good_password_is_valid()
    {
        $john = new User();
        $john->password = "johnspasshash";

        $this->userRepository->expects($this->once())
            ->method('findByUsername')
            ->with('john')
            ->willReturn($john);

        $this->passwordHashService->expects($this->once())
            ->method('passwordMatchesHash')
            ->with('johnspassword', 'johnspasshash')
            ->willReturn(true);

        $loggedInUser = $this->loginService->loginUser("john", "johnspassword");
        $this->assertEquals($john, $loggedInUser);
    }

    public function randomCredentialsProvider(): Generator
    {
        for ($i = 0; $i <= 5; $i++) {
            yield [random_bytes(random_int(5, 24)), random_bytes(random_int(5, 24))];
        }
    }

    public function emptyCredentialsProvider(): Generator
    {
        $emptyCredentialVariants = [
            '', ' ', "\n", "\t", "\n\t", "  ", "  \n\t  "
        ];

        foreach ($emptyCredentialVariants as $variant) {
            yield [$variant, "password"];
            yield ["password", $variant];
        }
    }
}