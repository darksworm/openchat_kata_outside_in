<?php


namespace App\Service;


use App\DTO\UserRegistrationRequest;
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

    public function registerUser(UserRegistrationRequest $request): ?User
    {
        if ($this->userRepository->userWithUsernameExists($request->getUsername())) {
            throw new DuplicateUsernameException();
        }

        $passwordHash = $this->passwordHashService->hashForPassword($request->getPassword());

        return $this->userRepository->createUser(
            $request->getUsername(), $passwordHash, $request->getAbout()
        );
    }
}
