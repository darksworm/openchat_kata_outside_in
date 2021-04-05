<?php


namespace App\Http\Transformers;

use App\Models\User;
use Illuminate\Support\Collection;
use JetBrains\PhpStorm\ArrayShape;

class UserTransformer
{
    #[ArrayShape(['id' => 'string', 'username' => 'string', 'about' => 'string'])]
    public static function transform(User $user): Collection
    {
        return collect([
            'id' => $user->user_id,
            'username' => $user->username,
            'about' => $user->about
        ]);
    }

    public static function transformAll(User ...$users): Collection
    {
        return collect($users)->map(
            fn($u) => static::transform($u)
        );
    }
}
