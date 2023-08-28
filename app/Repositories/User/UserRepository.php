<?php

namespace App\Repositories\User;

use App\Models\User;
use App\Http\Requests\Request;
use App\Repositories\UserProfile\UserProfileInterface;
use Midun\Supports\Patterns\Abstracts\AppRepository as Repository;

class UserRepository extends Repository implements UserInterface
{
    public UserProfileInterface $profileRepository;

    public function __construct(
        UserProfileInterface $profileRepository
    )
    {
        parent::__construct();
        $this->profileRepository = $profileRepository;
    }
    
    public function model(): string
    {
        return User::class;
    }

    public function getList(Request $request): array
    {
        $paginate = $request->paginate ?: config('settings.paginate');
        return $this->model
            ->active()
            ->with(['profile' => function ($query) {
                $query->select('id', 'is_active');
            }])
            ->paginate($paginate);
    }

}
