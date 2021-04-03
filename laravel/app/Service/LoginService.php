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
        if (empty(trim($username)) || empty(trim($password))) {
            throw new LoginFailException();
        }

        $user = $this->userRepository->findByUsername($username);

        if (null === $user) {
            throw new LoginFailException();
        }

        if (false === $this->passwordHashService->passwordMatchesHash($password, $user->password)) {
            throw new LoginFailException();
        }

        return $user;
    }
}
