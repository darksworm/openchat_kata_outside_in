<?php


namespace App\Service;


use App\Exceptions\LoginFailException;
use App\Models\User;
use App\Repository\IUserRepository;

class LoginService
{
    private IPasswordHashService $passwordHashService;
    private IUserRepository $userRepository;

    public function __construct(IUserRepository $userRepository, IPasswordHashService $passwordHashService)
    {
        $this->userRepository = $userRepository;
        $this->passwordHashService = $passwordHashService;
    }

    /**
     * @throws LoginFailException
     */
    public function loginUser(string $username, string $password): User
    {
        $this->validateCredentialsNotEmpty($username, $password);
        $user = $this->findUserOrThrow($username);
        $this->validatePassword($password, $user);
        return $user;
    }

    /**
     * @throws LoginFailException
     */
    private function validateCredentialsNotEmpty(string $username, string $password): void
    {
        if (empty(trim($username)) || empty(trim($password))) {
            throw new LoginFailException();
        }
    }

    /**
     * @throws LoginFailException
     */
    private function findUserOrThrow(string $username): User
    {
        $user = $this->userRepository->findByUsername($username);
        if (null === $user) {
            throw new LoginFailException();
        }

        return $user;
    }

    /**
     * @throws LoginFailException
     */
    private function validatePassword(string $password, User $user): void
    {
        if (false === $this->passwordHashService->passwordMatchesHash($password, $user->password)) {
            throw new LoginFailException();
        }
    }
}
