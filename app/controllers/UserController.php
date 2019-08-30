<?php

namespace App\Controllers;

use PDO;
use Exception;
use App\Models\User;
use App\Http\Request;
use App\Main\Database;
use App\Models\Partner;
use App\Main\Controller;
use App\Main\QueryBuilder as DB;

class UserController extends Controller
{
    public function index(Request $request = null)
    {
      try {
        $users = Partner::find('0359030457');
        $users = Partner::get();
        // $users = DB::table('users')->limit(10)->get();
        toPre($users);
        return view('users/index', compact('users'));
      } catch (\Exception $e) {
        throw new \App\Main\AppException($e->getMessage());
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

    public function update($id){
        $data = Request::all();
          $a = 1;
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
