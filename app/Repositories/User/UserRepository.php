<?php

namespace App\Repositories\User;

use App\Models\User;
use Main\Supports\Patterns\Abstracts\AppRepository as Repository;

class UserRepository extends Repository implements UserInterface
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
