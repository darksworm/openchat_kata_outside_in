<?php

namespace App\Providers;

use App\Repository\IUserRepository;
use App\Repository\UserRepository;
use App\Service\IPasswordHashService;
use App\Service\RegistrationService;
use App\Service\SHA512PasswordHashService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public $singletons = [
        IUserRepository::class => UserRepository::class,
        IPasswordHashService::class => SHA512PasswordHashService::class,
        RegistrationService::class => RegistrationService::class
    ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
