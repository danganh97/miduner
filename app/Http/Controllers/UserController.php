<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Main\Http\Request;
use App\Main\Database\QueryBuilder\DB;

class UserController extends Controller
{
    public function index(Request $request = null)
    {
        $users = DB::bindClass(User::class)->where('user_id', '>', 1)->take(10)->get();
        return $this->respond($users);
        return view('users/index', compact('users'));
    }

    public function create()
    {
        return $this->respond("User create");
        return simpleView('users/create');
    }

    public function store()
    {
        $data = Request::all();
        $user = User::create($data);
        return response()->json($user, 201);
    }

    public function show($id)
    {
        $user = DB::bindClass(User::class)->findOrFail($id);
        if (!$user) {
            return $this->respondError("User not found");
        }
        return response()->json($user);
    }

    public function edit($id)
    {
        $user = User::find($id);
        if (!$user) {
            echo 'user not found !';
            return false;
        }
        return simpleView('users/edit', ['user' => $user]);
    }

    public function update($id)
    {
        $data = Request::all();
        $user = DB::table('users')->where('id', '=', $id)->update($data);
    }

    public function destroy($id)
    {
        return DB::table('users')->where('id', '=', $id)->delete();
    }

    public function login()
    {
        $data = Request::only(['email', 'password']);
        $user = User::login($data);
        if ($user) {
            session('user', $user);
            return $this->respondSuccess("Login successfully.");
        }
        return $this->respondError('Login fails', 401);
    }

    public function logout()
    {
        if (unsetsession('user')) {
            return $this->respondSuccess("Logout successfully !");
        }
    }
}
