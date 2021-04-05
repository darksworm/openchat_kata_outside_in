<?php


namespace Tests\Feature\API;


use Illuminate\Support\Str;
use Illuminate\Testing\TestResponse;

trait RegistersUsers
{
    protected function registerUsers(int $count): array
    {
        return collect(range(1, $count))->map(
            fn() => $this->registerUser()
        )->toArray();
    }

    protected function registerUser(
        ?string $username = null,
        ?string $password = null,
        ?string $about = null): array
    {
        return $this->registerUserRequest(
            username: $username,
            password: $password,
            about: $about
        )->json();
    }

    protected function registerUserRequest(
        ?string $username = null,
        ?string $password = null,
        ?string $about = null): TestResponse
    {
        return
            $this->post('/users', [
                    'username' => $username ?? Str::random(),
                    'password' => $password ?? Str::random(),
                    'about' => $about ?? Str::random()
                ]
            );
    }
}
