<?php

namespace Tests\Service;

use App\Exceptions\UserDoesNotExistException;
use App\Models\User;
use App\Repository\IUserRepository;
use App\Service\UserService;
use PHPUnit\Framework\TestCase;

class UserServiceTest extends TestCase
{
    private IUserRepository $userRepository;
    private UserService $userService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userRepository = $this->createMock(IUserRepository::class);
        $this->userService = new UserService($this->userRepository);
    }

    public function
    test_validateUsersExist_throws_when_at_least_one_user_doesnt_exist()
    {
        $this->userRepository->expects($this->once())
            ->method('getUsersById')
            ->with('otheruser', 'someuser', 'user_id')
            ->willReturn(collect([['user_id' => 'otheruser'], ['user_id' => 'someuser']]));

        try {
            $this->userService->validateUsersExist('otheruser', 'someuser', 'user_id');
        } catch (UserDoesNotExistException $e) {
            $this->assertEquals('user_id', $e->getUserId());
        } finally {
            $this->assertFalse(empty($e), 'Expected UserDoesNotExist exception to be thrown!');
        }
    }

    public function
    test_validateUsersExist_does_not_throw_when_all_users_exist()
    {
        $this->userRepository->expects($this->once())
            ->method('getUsersById')
            ->with('user_id', 'dank')
            ->willReturn(collect([['user_id' => 'user_id'], ['user_id' => 'dank']]));

        $this->userService->validateUsersExist('user_id', 'dank');
    }

    public function
    test_getAllUsers_delegates_to_repository()
    {
        $expectedUsers = collect([
            new User(),
            new User(),
        ]);

        $this->userRepository->expects($this->once())
            ->method('getAllUsers')
            ->willReturn($expectedUsers);

        $actualUsers = $this->userService->getAllUsers();
        $this->assertEquals($actualUsers, $expectedUsers);
    }
}
