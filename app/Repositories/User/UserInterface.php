<?php

namespace App\Repositories\User;

use App\Http\Requests\Request;

interface UserInterface
{
    public function getList(Request $request): array;
}