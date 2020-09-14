<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Request;
use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Repositories\User\UserInterface;
use Midun\Routing\Controller\Controller;

class UserController extends Controller
{
    public UserInterface $userRepository;

    public function __construct(UserInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index(Request $request)
    {
        $paginate = $request->paginate ?: config('settings.pagination');
        $users = $this->userRepository->paginate($paginate);
        return $this->respond($users);
    }

    public function store(CreateUserRequest $request)
    {
        $data = $request->all();
        $user = $this->userRepository->create($data);

        return $this->respondCreated($user);
    }

    public function show($id)
    {
        $user = $this->userRepository->findOrFail($id);
        return response()->json($user);
    }

    public function update(UpdateUserRequest $request, $id)
    {
        try {
            $data = $request->all();
            $user = $this->userRepository->findOrFail($id);

            if ($user->update($data)) {
                $user = $this->userRepository->findOrFail($id);
            }

            return $this->respondSuccess($user);
        } catch (\AppException $e) {
            return $this->respondError($e->getMessage());
        }
    }

    public function destroy($id)
    {
        $user = $this->userRepository->findOrFail($id);

        if (!$user->delete($id)) {
            throw new \Exception("Unable to delete");
        }

        return $this->respondSuccess(true);
    }
}
