<?php

namespace App\Controllers;

use App\Http\Request;
use App\Main\Controller;
use App\Main\QueryBuilder as DB;

class UserController extends Controller
{
    public function index(Request $request = null)
    {
        $users = DB::table('users')->limit(10)->get();
        return view('users/index', compact('users'));
    }

    public function create()
    {
        return simpleView('users/create');
    }

    public function store()
    {
        $data = Request::all();
        $user = DB::table('users')->insert($data);
        return response($user);
    }

    public function show($id)
    {
        $user = DB::table('users')->find('id', $id);
        if (!$user) {
            echo 'user not found !';
            return false;
        }
        return response($user);
    }

    public function edit($id)
    {
        $user = DB::table('users')->find('id', $id);
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
        $user = DB::table('users')->login($data);
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
