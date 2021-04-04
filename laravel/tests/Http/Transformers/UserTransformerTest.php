<?php

namespace Tests\Http\Transformers;

use App\Http\Transformers\UserTransformer;
use App\Models\User;
use PHPUnit\Framework\TestCase;

class UserTransformerTest extends TestCase
{
    public function
    test_copies_id_username_and_about()
    {
        $user = new MockUser();
        $transformed = UserTransformer::transform($user);

        $this->assertEquals($transformed['id'], $user->user_id);
        $this->assertEquals($transformed['username'], $user->username);
        $this->assertEquals($transformed['about'], $user->about);
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
        $user = new MockUser();
        $transformed = UserTransformer::transformAll($user);

        $this->assertEquals($transformed[0]['id'], $user->user_id);
        $this->assertEquals($transformed[0]['username'], $user->username);
        $this->assertEquals($transformed[0]['about'], $user->about);
    }
}

class MockUser extends User
{
    public
        $user_id = '37ccf70d-e63e-4299-85f0-2684e160bc12',
        $username = 'john',
        $about = 'a sailor.',
        $password = 'somehorriblepasswordhash.0/234xjjiwehjkczuhiw';
}
