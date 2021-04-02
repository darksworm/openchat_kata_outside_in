<?php


namespace App\Repository;

use App\Models\User;

class UserRepository implements IUserRepository
{
    public function createUser(string $username, string $password, string $about): User
    {
        $user = new User();
        $user->username = $username;
        $user->password = $password;
        $user->about = $about;

        $user->save();

        return $user;
    }

    public function userWithUsernameExists(string $username): bool
    {
        $user = User::where('username', $username)
            ->first();

        return $user !== null;
    }
}
