<?php

namespace App\Providers;

use App\Repository\FollowingsRepository;
use App\Repository\IFollowingsRepository;
use App\Repository\IPostRepository;
use App\Repository\IUserRepository;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use App\Service\FollowingsService;
use App\Service\IPasswordHashService;
use App\Service\PostCreationService;
use App\Service\RegistrationService;
use App\Service\SHA512PasswordHashService;
use App\Service\UserService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public array $singletons = [
        IUserRepository::class => UserRepository::class,
        IPostRepository::class => PostRepository::class,
        IFollowingsRepository::class => FollowingsRepository::class,

        IPasswordHashService::class => SHA512PasswordHashService::class,
        RegistrationService::class => RegistrationService::class,
        PostCreationService::class => PostCreationService::class,
        FollowingsService::class => FollowingsService::class,
        UserService::class => UserService::class
    ];
}
