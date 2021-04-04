<?php


namespace App\Service;


use App\Exceptions\Login\BadPasswordException;
use App\Exceptions\Login\EmptyCredentialsException;
use App\Exceptions\Login\LoginFailException;
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
     * @throws EmptyCredentialsException
     */
    private function validateCredentialsNotEmpty(string $username, string $password): void
    {
        if (empty(trim($username)) || empty(trim($password))) {
            throw new EmptyCredentialsException();
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
     * @throws BadPasswordException
     */
    private function validatePassword(string $password, User $user): void
    {
        if (false === $this->passwordHashService->passwordMatchesHash($password, $user->password)) {
            throw new BadPasswordException();
        }
    }
}
