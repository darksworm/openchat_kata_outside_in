<?php


namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Collection;

class UserRepository implements IUserRepository
{
    public function createUser(string $username, string $hashedPassword, string $about): User
    {
        $user = new User();
        $user->username = $username;
        $user->password = $hashedPassword;
        $user->about = $about;

        $user->save();

        return $user;
    }

    public function userWithUsernameExists(string $username): bool
    {
        return null !== $this->findByUsername($username);
    }

    public function findByUsername(string $username): ?User
    {
        return User::where('username', $username)
            ->first();
    }

    public function userWithIdExists(string $userId): bool
    {
        return null !== User::find($userId);
    }

    function getUsersById(string ...$userIds): Collection
    {
        return User::whereIn('user_id', $userIds)
            ->get();
    }

    function getAllUsers(): Collection
    {
        return User::all();
    }
}
