<?php

namespace Tests\Http\Transformers;

use App\Http\Transformers\UserTransformer;
use App\Models\User;
use PHPUnit\Framework\TestCase;

class UserTransformerTest extends TestCase
{
    public function
    test()
    {
        $user = new MockUser();

        $transformed = UserTransformer::transform($user);

        $this->assertEquals($transformed['userId'], $user->id);
        $this->assertEquals($transformed['username'], $user->username);
        $this->assertEquals($transformed['about'], $user->about);
    }
}

class MockUser extends User {
    public $id, $username, $about;
}
