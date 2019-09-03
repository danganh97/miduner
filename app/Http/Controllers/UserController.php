<?php

namespace App\Http\Controllers;

use App\Http\Request;
use App\Http\Exceptions\Exception;
use App\Main\Controller;
use App\Main\QueryBuilder as DB;
use App\Models\User;

class UserController extends Controller
{
    public function index(Request $request = null)
    {
        try {
            $users = User::get();
            return view('users/index', compact('users'));
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function create()
    {
        return simpleView('users/create');
    }

    public function store()
    {
        $data = Request::all();
        $user = User::create($data);
        return response()->json($user);
    }

    public function show($id)
    {
        $user = User::find($id);
        if (!$user) {
            echo 'user not found !';
        }
        toPre($user);die();
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
            return sendMessage('Login successfully', 200);
        }
        return sendMessage('Login fails', 401);
    }

    public function logout()
    {
        if (unsetsession('user')) {
            return sendMessage('Logout successfully !', 200);
        }
    }
}
