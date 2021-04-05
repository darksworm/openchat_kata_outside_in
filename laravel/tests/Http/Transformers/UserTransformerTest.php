<?php

namespace Tests\Http\Transformers;

use App\Http\Transformers\UserTransformer;
use App\Models\User;
use Illuminate\Support\Str;
use PHPUnit\Framework\TestCase;

class UserTransformerTest extends TestCase
{
    public function
    test_copies_id_username_and_about()
    {
        $user = new MockUser();
        $transformed = UserTransformer::transform($user)->toArray();
        $this->assertUserTransformed($user, $transformed);
    }

    public function
    test_does_not_copy_password()
    {
        $user = new MockUser();
        $transformed = UserTransformer::transform($user);
        $this->assertArrayNotHasKey(key: 'password', array: $transformed);
    }

    public function
    test_copies_id_username_and_about_via_transformAll()
    {
        $users = [new MockUser(), new MockUser()];
        $transformed = UserTransformer::transformAll(...$users)->toArray();

        collect($users)->map(
            fn($user, $key) => ['user' => $user, 'transformed' => $transformed[$key]]
        )->each(
            fn($tuple) => $this->assertUserTransformed($tuple['user'], $tuple['transformed'])
        );
    }

    private function assertUserTransformed(User $user, array $transformed)
    {
        $this->assertEquals($transformed['id'], $user->user_id);
        $this->assertEquals($transformed['username'], $user->username);
        $this->assertEquals($transformed['about'], $user->about);
    }
}

class MockUser extends User
{
    public
        $user_id,
        $username,
        $about,
        $password;

    public function __construct(array $attributes = [])
    {
        $this->user_id = Str::uuid();
        $this->username = Str::random();
        $this->about = Str::random();
        $this->password = Str::random(32);
    }
}
