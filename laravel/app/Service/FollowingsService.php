<?php


namespace App\Service;


use App\Exceptions\FollowingAlreadyExistsException;
use App\Exceptions\UserDoesNotExistException;
use App\Models\Following;
use App\Repository\IFollowingsRepository;
use Illuminate\Support\Collection;

class FollowingsService
{
    private UserService $userService;
    private IFollowingsRepository $followingsRepository;

    public function __construct(UserService $userService, IFollowingsRepository $followingsRepository)
    {
        $this->userService = $userService;
        $this->followingsRepository = $followingsRepository;
    }

    /**
     * @throws UserDoesNotExistException
     * @throws FollowingAlreadyExistsException
     */
    public function createFollowing(string $followerId, string $followeeId): Following
    {
        $this->userService->validateUsersExist($followerId, $followeeId);
        $this->validateFollowingDoesNotExist($followerId, $followeeId);

        return $this->followingsRepository->createFollowing($followerId, $followeeId);
    }

    /**
     * @throws UserDoesNotExistException
     */
    public function getFolloweesForUser(string $followerId): Collection
    {
        $this->userService->validateUsersExist($followerId);
        return $this->followingsRepository->followeesForUser($followerId);
    }

    /**
     * @throws FollowingAlreadyExistsException
     */
    private function validateFollowingDoesNotExist(string $followerId, string $followeeId): void
    {
        if ($this->followingsRepository->followingExists($followerId, $followeeId)) {
            throw new FollowingAlreadyExistsException();
        }
    }
}
