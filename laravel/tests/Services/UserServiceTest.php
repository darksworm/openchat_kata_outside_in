<?php

namespace Tests\Services;

use App\Exceptions\UserDoesNotExistException;
use App\Models\User;
use App\Repositories\IUserRepository;
use App\Services\UserService;
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
    test_throws_exception_with_user_id_when_at_least_one_user_does_not_exist()
    {
        $this->userRepository->expects($this->once())
            ->method('getUsersById')
            ->with('one', 'two', 'three')
            ->willReturn(collect([['user_id' => 'one'], ['user_id' => 'two']]));

        try {
            $this->userService->validateUsersExist('one', 'two', 'three');
        } catch (UserDoesNotExistException $e) {
            $this->assertEquals('three', $e->getUserId());
        } finally {
            $this->assertFalse(empty($e), 'Expected UserDoesNotExist exception to be thrown!');
        }
    }

    public function
    test_does_not_throw_when_all_users_exist()
    {
        $this->userRepository->expects($this->once())
            ->method('getUsersById')
            ->with('one', 'two')
            ->willReturn(collect([['user_id' => 'one'], ['user_id' => 'two']]));

        $this->userService->validateUsersExist('one', 'two');
    }

    public function
    test_retrieves_all_existing_users()
    {
        $expectedUsers = collect([new User(), new User()]);

        $this->userRepository->expects($this->once())
            ->method('getAllUsers')
            ->willReturn($expectedUsers);

        $actualUsers = $this->userService->getAllUsers();
        $this->assertEquals($actualUsers, $expectedUsers);
    }
}
