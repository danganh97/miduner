<?php

namespace App\Http\Controllers;

use DB;
use App\Models\User;
use App\Http\Requests\Request;
use App\Http\Requests\TestRequest;
use Main\Http\Exceptions\AppException;
use App\Repositories\User\UserInterface;
use Main\Http\Exceptions\ModelException;

class UserController extends Controller
{
    public $userRepository;

    public function __construct(UserInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index(Request $request)
    {
        try {
            $users = User::select(['email'])->where(['user_id' => 3099])->first();
            return $this->respond($users);
            return view('users/index', compact('users'));
        } catch (AppException $e) {
            throw new AppException($e->getMessage());
        }

    }

    public function create()
    {
        return $this->respond("User create");
        return simpleView('users/create');
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $user = User::create($data);
        return response()->json($user, 201);
    }

    public function show($id)
    {
        $user = User::where('user_id', '>', 1)->with(['profile'])->take(10)->get();
        if (!$user) {
            return $this->respondError("User not found");
        }
        return response()->json($user);
    }

    public function edit(TestRequest $testRequest, $id)
    {
        $user = User::where('id', '=', $id)->first();
        if (!$user) {
            throw new ModelException("User not found", 404);
        }
        return view('users/edit', ['user' => $user]);
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
