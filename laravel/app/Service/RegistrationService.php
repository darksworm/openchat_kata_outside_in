<?php


namespace App\Service;


use App\DTO\UserRegistrationRequest;
use App\Exceptions\DuplicateUsernameException;
use App\Models\User;
use App\Repository\IUserRepository;

class RegistrationService
{
    private IUserRepository $userRepository;
    private IPasswordHashService $passwordHashService;

    public function __construct(IUserRepository $userRepository, IPasswordHashService $passwordHashService)
    {
        $this->userRepository = $userRepository;
        $this->passwordHashService = $passwordHashService;
    }

    /**
     * @throws DuplicateUsernameException
     */
    public function registerUser(UserRegistrationRequest $request): ?User
    {
        $this->validateUsernameNotTaken($request->getUsername());

        $hashedPassword = $this->passwordHashService->hash($request->getPassword());

        return $this->userRepository->createUser(
            username: $request->getUsername(),
            hashedPassword: $hashedPassword,
            about: $request->getAbout()
        );
    }

    /**
     * @throws DuplicateUsernameException
     */
    private function validateUsernameNotTaken(string $username): void
    {
        if ($this->userRepository->userWithUsernameExists($username)) {
            throw new DuplicateUsernameException();
        }
    }
}
