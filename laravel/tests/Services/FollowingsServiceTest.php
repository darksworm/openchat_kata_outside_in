<?php

namespace Tests\Services;

use App\Exceptions\FollowingAlreadyExistsException;
use App\Exceptions\UserDoesNotExistException;
use App\Models\Following;
use App\Models\User;
use App\Repositories\IFollowingsRepository;
use App\Services\FollowingsService;
use App\Services\UserService;
use Illuminate\Support\Str;
use PHPUnit\Framework\TestCase;

class FollowingsServiceTest extends TestCase
{
    private UserService $userService;
    private IFollowingsRepository $followingsRepository;
    private FollowingsService $followingsService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userService = $this->createMock(UserService::class);
        $this->followingsRepository = $this->createMock(IFollowingsRepository::class);

        $this->followingsService = new FollowingsService($this->userService, $this->followingsRepository);
    }

    /**
     * @dataProvider missingUserStateProvider
     */
    public function
    test_throws_exception_when_user_does_not_exist(UserState $follower, UserState $followee)
    {
        $missingId = (!$follower->exists ? $follower->id : $followee->id);

        $this->userService->expects($this->once())
            ->method('validateUsersExist')
            ->with($follower->id, $followee->id)
            ->willThrowException(new UserDoesNotExistException($missingId));

        try {
            $this->followingsService->createFollowing($follower->id, $followee->id);
        } catch (UserDoesNotExistException $e) {
            $this->assertEquals($missingId, $e->getUserId());
        } finally {
            $this->assertFalse(empty($e), 'expected UserDoesNotExistException to be thrown!');
        }
    }

    public function
    test_throws_when_following_already_exists()
    {
        $this->expectException(FollowingAlreadyExistsException::class);

        [$followerId, $followeeId] = [Str::uuid()->toString(), Str::uuid()->toString()];

        $this->userService->expects($this->once())
            ->method('validateUsersExist')
            ->with($followerId, $followeeId);

        $this->followingsRepository->expects($this->once())
            ->method('followingExists')
            ->with($followerId, $followeeId)
            ->willReturn(true);

        $this->followingsService->createFollowing($followerId, $followeeId);
    }

    public function
    test_creates_following()
    {
        [$followerId, $followeeId] = [Str::uuid()->toString(), Str::uuid()->toString()];

        $this->userService->expects($this->once())
            ->method('validateUsersExist')
            ->with($followerId, $followeeId);

        $this->followingsRepository->expects($this->once())
            ->method('followingExists')
            ->with($followerId, $followeeId)
            ->willReturn(false);

        $following = new Following();
        $following->follower_id = $followerId;
        $following->followee_id = $followeeId;

        $this->followingsRepository->expects($this->once())
            ->method('createFollowing')
            ->with($followerId, $followeeId)
            ->willReturn($following);

        $createdFollowing = $this->followingsService->createFollowing($followerId, $followeeId);
        $this->assertEquals($following, $createdFollowing);
    }

    public function
    test_throws_when_trying_to_get_followees_for_non_existent_user()
    {
        $nonExistentUserId = Str::uuid();

        $this->userService->expects($this->once())
            ->method('validateUsersExist')
            ->with($nonExistentUserId)
            ->willThrowException(new UserDoesNotExistException($nonExistentUserId));

        $this->expectException(UserDoesNotExistException::class);
        $this->followingsService->getFolloweesForUser($nonExistentUserId);
    }

    public function
    test_returns_followed_users()
    {
        $expectedUsers = collect([new User(), new User()]);
        $userId = Str::uuid();

        $this->userService->expects($this->once())
            ->method('validateUsersExist')
            ->with($userId);

        $this->followingsRepository->expects($this->once())
            ->method('followeesForUser')
            ->with($userId)
            ->willReturn($expectedUsers);

        $actualUsers = $this->followingsService->getFolloweesForUser($userId);
        $this->assertEquals($actualUsers, $expectedUsers);
    }

    public function missingUserStateProvider(): array
    {
        return [
            [UserState::newMissing(), UserState::newExisting()],
            [UserState::newMissing(), UserState::newMissing()],
            [UserState::newExisting(), UserState::newMissing()],
        ];
    }
}

class UserState
{
    public bool $exists;
    public string $id;

    public static function newExisting(): UserState
    {
        return new UserState(true);
    }

    public static function newMissing(): UserState
    {
        return new UserState(false);
    }

    private function __construct(bool $exists)
    {
        $this->exists = $exists;
        $this->id = Str::uuid();
    }
}
