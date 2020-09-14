<?php

namespace App\Providers;

use App\Models\UserProfile;
use App\Repositories\User\UserInterface;
use App\Repositories\User\UserRepository;
use App\Repositories\UserProfile\UserProfileInterface;
use App\Repositories\UserProfile\UserProfileRepository;
use Midun\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(UserInterface::class, UserRepository::class);
        $this->app->bind(UserProfileInterface::class, UserProfileRepository::class);
    }
}