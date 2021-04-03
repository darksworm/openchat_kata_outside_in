<?php


namespace App\Http\Transformers;

use App\Models\User;

class UserTransformer
{
    public static function transform(User $user): array
    {
        return [
            'userId' => $user->user_id,
            'username' => $user->username,
            'about' => $user->about
        ];
    }
}
