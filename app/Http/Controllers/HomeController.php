<?php

namespace App\Http\Controllers;

use App\Http\Requests\TestRequestFile;
use App\Repositories\User\UserInterface;
use Request;

class HomeController extends Controller
{
    public $userRepository;

    public function __construct(UserInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function home(Request $request, $id)
    {
        return view('pages/home', compact('users'));
    }

    public function testPostFile(TestRequestFile $request)
    {

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
        return view('pages/contact');
    }
}
