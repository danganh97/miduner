<?php

namespace App\Repositories\User;

use App\Models\User;
use App\Repositories\UserProfile\UserProfileInterface;
use Main\Supports\Patterns\Abstracts\AppRepository as Repository;

class UserRepository extends Repository implements UserInterface
{
    public $profileRepository;

    public function __construct(
        UserProfileInterface $profileRepository
    )
    {
        parent::__construct();
        $this->profileRepository = $profileRepository;
    }
    
    public function model()
    {
        return User::class;
    }

}
