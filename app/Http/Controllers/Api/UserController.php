<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Request;
use Midun\Supports\Response\Response;
use App\Repositories\User\UserInterface;
use Midun\Routing\Controller\Controller;
use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;

class UserController extends Controller
{
    /**
     * User repository
     * 
     * @var UserInterface
     */
    public UserInterface $userRepository;

    /**
     * Initial constructor UserController
     * 
     * @param UserInterface $userRepository
     * 
     * @return void
     */
    public function __construct(UserInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Index get list users
     * 
     * @param Request $request
     * 
     * @return \Midun\Supports\Response\Response
     */
    public function index(Request $request, User $user): Response
    {
        $paginate = $request->paginate ?: config('settings.pagination');
        $users = $this->userRepository->paginate($paginate);
        return $this->respond($users);
    }

    /**
     * Create user
     * 
     * @param CreateUserRequest $request
     * 
     * @return \Midun\Response\Response
     */
    public function store(CreateUserRequest $request): Response
    {
        $data = $request->all();
        $user = $this->userRepository->create($data);

        return $this->respondCreated($user);
    }

    /**
     * Get one user
     * 
     * @param int $id
     * 
     * @return \Midun\Response\Response
     */
    public function show(User $user, User $u): Response
    {
        return $this->respond($user);
    }

    /**
     * Update user
     * 
     * @param UpdateUserRequest $request
     * @param int $id
     * 
     * @return \Midun\Response\Response
     */
    public function update(UpdateUserRequest $request, int $id): Response
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

    public function destroy(int $id): Response
    {
        $user = $this->userRepository->findOrFail($id);

        if (!$user->delete($id)) {
            throw new \Exception("Unable to delete");
        }

        return $this->respondSuccess(true);
    }
}
