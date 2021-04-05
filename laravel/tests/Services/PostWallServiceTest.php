<?php

namespace Tests\Services;

use App\Exceptions\UserDoesNotExistException;
use App\Models\Post;
use App\Repositories\IPostRepository;
use App\Services\PostWallService;
use App\Services\UserService;
use Illuminate\Support\Str;
use PHPUnit\Framework\TestCase;

class PostWallServiceTest extends TestCase
{
    private UserService $userService;
    private PostWallService $wallService;
    private IPostRepository $postRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userService = $this->createMock(UserService::class);
        $this->postRepository = $this->createMock(IPostRepository::class);
        $this->wallService = new PostWallService($this->userService, $this->postRepository);
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

        $this->wallService->wallForUserId($missingUserId);
    }

    public function
    test_returns_wall()
    {
        $expectedPosts = collect([new Post(), new Post()]);
        $userId = Str::uuid();

        $this->userService->expects($this->once())
            ->method('validateUsersExist')
            ->with($userId);

        $this->postRepository->expects($this->once())
            ->method('getWallPostsForUserId')
            ->with($userId)
            ->willReturn($expectedPosts);

        $actualPosts = $this->wallService->wallForUserId($userId);
        $this->assertEquals($actualPosts, $expectedPosts);
    }
}
