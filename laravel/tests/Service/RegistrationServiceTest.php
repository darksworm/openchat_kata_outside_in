<?php


namespace Tests\Service;


use App\DTO\UserRegistrationRequest;
use App\Exceptions\DuplicateUsernameException;
use App\Models\User;
use App\Repository\IUserRepository;
use App\Service\IPasswordHashService;
use App\Service\RegistrationService;
use Tests\TestCase;

class RegistrationServiceTest extends TestCase
{
    private User $createdUser;
    private UserRegistrationRequest $userRegistrationData;

    private IUserRepository $userRepository;

    private RegistrationService $registrationService;

    public function
    test_creates_user()
    {
        $this->userRepository->expects($this->once())
            ->method('createUser')
            ->willReturn($this->createdUser);

        $this->userRepository->expects($this->any())
            ->method('userWithUsernameExists')
            ->willReturn(false);

        $createdUser = $this->registrationService->registerUser($this->userRegistrationData);

        $this->assertEquals($this->createdUser, $createdUser);
    }

    public function
    test_throws_on_duplicate_username()
    {
        $this->expectException(DuplicateUsernameException::class);

        $this->userRepository->expects($this->any())
            ->method('createUser')
            ->willReturn($this->createdUser);

        $this->userRepository->expects($this->any())
            ->method('userWithUsernameExists')
            ->willReturn(true);

        $this->registrationService->registerUser($this->userRegistrationData);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->userRegistrationData = new UserRegistrationRequest('Alice', 'password', 'I like pies.');
        $this->createdUser = new User();

        $passwordHashService = $this->createMock(IPasswordHashService::class);
        $this->userRepository = $this->createMock(IUserRepository::class);

        $this->registrationService = new RegistrationService($this->userRepository, $passwordHashService);

        $passwordHashService->expects($this->any())
            ->method('hash')
            ->will($this->returnValue('mockPassHash'));
    }
}
