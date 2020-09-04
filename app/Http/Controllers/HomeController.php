<?php

namespace App\Http\Controllers;

use Validator;
use Midun\Http\Request;
use App\Jobs\ExampleJob;
use App\Http\Requests\TestRequestFile;
use App\Repositories\User\UserInterface;

class HomeController extends Controller
{
    public $userRepository;

    public function __construct(
        UserInterface $userRepository
    ) {
        $this->userRepository = $userRepository;
    }

    public function home(Request $request)
    {
        return view('pages/home');
    }

    public function testPostFile(TestRequestFile $request)
    {
        $validator = Validator::makeValidate($request, [
            'email' => 'required|email|unique:users,email;danganh.dev@gmail.co'
        ], [
            'email.unique' => 'already taken'
        ]);

        if ($validator->isFailed()) {
            return $this->respondError($validator->errors());
        }
        dd('passed');
    }

    public function about()
    {
        return view('pages/about');
    }

    public function post()
    {
        return view('pages/post');
    }

    public function contact()
    {
        dispatch(new ExampleJob('danganh.dev@gmail.com', 'dang anh'));
        return view('pages/contact');
    }
}
