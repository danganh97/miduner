<?php

namespace App\Http\Controllers;

use Validator;
use Midun\View\View;
use Midun\Http\Request;
use App\Jobs\ExampleJob;
use Midun\Supports\Response\Response;
use App\Http\Requests\TestRequestFile;

class HomeController extends Controller
{
    /**
     * Routing home
     * 
     * @param Request $request
     * 
     * @return \Midun\View\View
     */
    public function home(Request $request): View
    {
        return view('pages/home');
    }

    public function testPostFile(TestRequestFile $request): Response
    {
        $validator = Validator::makeValidate($request, [
            'email' => 'required|email|unique:users,email;danganh.dev@gmail.co'
        ], [
            'email.unique' => 'already taken'
        ]);

        if ($validator->isFailed()) {
            return $this->respondError($validator->errors());
        }

        return $this->respondSuccess(true);
    }

    public function about(): View
    {
        return view('pages/about');
    }

    public function post(): View
    {
        return view('pages/post');
    }

    public function contact(): view
    {
        dispatch(new ExampleJob('danganh.dev@gmail.com', 'dang anh'));
        return view('pages/contact');
    }
}
