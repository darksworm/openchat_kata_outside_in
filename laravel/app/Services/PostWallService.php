<?php


namespace App\Services;


use App\Exceptions\UserDoesNotExistException;
use App\Repositories\IPostRepository;
use Illuminate\Support\Collection;

class PostWallService
{
    public function __construct(
        private UserService $userService,
        private IPostRepository $postRepository
    )
    {
    }

    /**
     * @throws UserDoesNotExistException
     */
    public function wallForUserId(string $userId): Collection
    {
        $this->userService->validateUsersExist($userId);
        return $this->postRepository->getWallPostsForUserId($userId);
    }
}
