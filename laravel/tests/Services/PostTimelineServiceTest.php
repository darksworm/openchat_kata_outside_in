<?php

namespace Tests\Services;

use App\Exceptions\UserDoesNotExistException;
use App\Models\Post;
use App\Repositories\IPostRepository;
use App\Services\TimelineService;
use App\Services\UserService;
use Illuminate\Support\Str;
use PHPUnit\Framework\TestCase;

class PostTimelineServiceTest extends TestCase
{
    private UserService $userService;
    private IPostRepository $postRepository;
    private TimelineService $timelineService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userService = $this->createMock(UserService::class);
        $this->postRepository = $this->createMock(IPostRepository::class);
        $this->timelineService = new TimelineService($this->userService, $this->postRepository);
    }

    public function
    test_throws_when_user_does_not_exist()
    {
        $this->expectException(UserDoesNotExistException::class);

        $missingUserId = Str::uuid();

        $this->userService->expects($this->once())
            ->method('validateUsersExist')
            ->with($missingUserId)
            ->willThrowException(new UserDoesNotExistException($missingUserId));

        $this->timelineService->timelineForUserId($missingUserId);
    }

    public function
    test_returns_posts_by_user()
    {
        $expectedPosts = collect([new Post(), new Post()]);
        $userId = Str::uuid();

        $this->userService->expects($this->once())
            ->method('validateUsersExist')
            ->with($userId);

        $this->postRepository->expects($this->once())
            ->method('postsByUserId')
            ->with($userId)
            ->willReturn($expectedPosts);

        $actualPosts = $this->timelineService->timelineForUserId($userId);
        $this->assertEquals($actualPosts, $expectedPosts);
    }
}
