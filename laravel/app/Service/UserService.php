<?php


namespace App\Service;


use App\Exceptions\UserDoesNotExistException;
use App\Repository\IUserRepository;

class UserService
{
    private IUserRepository $userRepository;

    public function __construct(IUserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @throws UserDoesNotExistException
     */
    public function validateUsersExist(string ...$userIds): void
    {
        $users = $this->userRepository->getUsersById(...$userIds)
            ->groupBy('user_id');

        foreach ($userIds as $id) {
            if (empty($users[$id])) {
                throw new UserDoesNotExistException($id);
            }
        }
    }
}
