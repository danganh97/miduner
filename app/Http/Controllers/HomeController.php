<?php

namespace App\Http\Controllers;

use Request;
use App\Http\Requests\TestRequestFile;
use App\Repositories\User\UserInterface;
use App\Repositories\UserProfile\UserProfileInterface;

class HomeController extends Controller
{
    public $userRepository;
    public $profileRepository;

    public function __construct(
        UserInterface $userRepository,
        UserProfileInterface $profileRepository
    ) {
        $this->userRepository = $userRepository;
        $this->profileRepository = $profileRepository;
    }

    public function home(Request $request)
    {
        return view('pages/home');
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
