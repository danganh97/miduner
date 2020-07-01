<?php

namespace App\Repositories\User;

use App\Models\User;
use App\Repositories\BaseRepository;

class UserRepository extends BaseRepository implements UserInterface
{
    public function model()
    {
        return User::class;
    }

    public function index()
    {
        return $this->model->take(10)->get();
    }

}
