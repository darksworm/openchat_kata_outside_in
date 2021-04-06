<?php

namespace Tests\Services;

use App\Exceptions\UserDoesNotExistException;
use App\Models\Post;
use App\Repositories\IPostRepository;
use App\Services\PostTimelineService;
use App\Services\UserService;
use Illuminate\Support\Str;
use PHPUnit\Framework\TestCase;

class PostTimelineServiceTest extends TestCase
{
    private UserService $userService;
    private IPostRepository $postRepository;
    private PostTimelineService $timelineService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userService = $this->createMock(UserService::class);
        $this->postRepository = $this->createMock(IPostRepository::class);

        $this->timelineService = new PostTimelineService($this->userService, $this->postRepository);
    }

    public function
    test_throws_when_user_does_not_exist()
    {
        $missingUserId = Str::uuid();

        $this->userService->expects($this->once())
            ->method('validateUsersExist')
            ->with($missingUserId)
            ->willThrowException(new UserDoesNotExistException($missingUserId));

        $this->expectException(UserDoesNotExistException::class);
        $this->timelineService->timelineForUserId($missingUserId);
    }

    public function
    test_returns_posts_by_user()
    {
        $userId = Str::uuid();
        $expectedPosts = collect([new Post(), new Post()]);

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
