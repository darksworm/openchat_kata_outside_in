<?php


namespace Tests\Services;


use App\DTO\UserRegistrationRequest;
use App\Exceptions\DuplicateUsernameException;
use App\Models\User;
use App\Repositories\IUserRepository;
use App\Services\IPasswordHashService;
use App\Services\RegistrationService;
use Tests\TestCase;

class RegistrationServiceTest extends TestCase
{
    private User $createdUser;
    private UserRegistrationRequest $userRegistrationData;

    private IUserRepository $userRepository;

    private RegistrationService $registrationService;

    protected function setUp(): void
    {
        parent::setUp();

        $passwordHashService = $this->createMock(IPasswordHashService::class);
        $this->userRepository = $this->createMock(IUserRepository::class);

        $this->registrationService = new RegistrationService($this->userRepository, $passwordHashService);

        $this->userRegistrationData = new UserRegistrationRequest('Alice', 'password', 'I like pies.');
        $this->createdUser = new User();

        $passwordHashService->expects($this->any())
            ->method('hash')
            ->will($this->returnValue('mockPassHash'));
    }

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
    test_throws_exception_when_existing_username_passed()
    {
        $this->userRepository->expects($this->any())
            ->method('createUser')
            ->willReturn($this->createdUser);

        $this->userRepository->expects($this->any())
            ->method('userWithUsernameExists')
            ->willReturn(true);

        $this->expectException(DuplicateUsernameException::class);
        $this->registrationService->registerUser($this->userRegistrationData);
    }
}
