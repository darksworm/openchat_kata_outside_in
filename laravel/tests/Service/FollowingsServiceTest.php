<?php

namespace Tests\Service;

use App\Exceptions\FollowingAlreadyExistsException;
use App\Exceptions\UserDoesNotExistException;
use App\Models\Following;
use App\Repository\IFollowingsRepository;
use App\Service\FollowingsService;
use App\Service\UserService;
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
        $following = new Following();
        $following->follower_id = $followerId;
        $following->followee_id = $followeeId;

        $this->userService->expects($this->once())
            ->method('validateUsersExist')
            ->with($followerId, $followeeId);

        $this->followingsRepository->expects($this->once())
            ->method('followingExists')
            ->with($followerId, $followeeId)
            ->willReturn(false);

        $this->followingsRepository->expects($this->once())
            ->method('createFollowing')
            ->with($followerId, $followeeId)
            ->willReturn($following);

        $createdFollowing = $this->followingsService->createFollowing($followerId, $followeeId);
        $this->assertEquals($following, $createdFollowing);
    }

    public function missingUserStateProvider(): array
    {
        return [
            [UserState::newMissing(), UserState::newExists()],
            [UserState::newMissing(), UserState::newMissing()],
            [UserState::newExists(), UserState::newMissing()],
        ];
    }
}

class UserState
{
    public bool $exists;
    public string $id;

    public static function newExists(): UserState
    {
        return new UserState(true, null);
    }

    public static function newMissing(): UserState
    {
        return new UserState(false, null);
    }

    private function __construct(bool $exists, ?string $id)
    {
        $this->exists = $exists;
        $this->id = $id === null ? Str::uuid() : $id;
    }
}
