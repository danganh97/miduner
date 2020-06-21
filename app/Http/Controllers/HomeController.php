<?php

namespace App\Http\Controllers;

use App\Http\Requests\Request;
use App\Http\Requests\TestRequest;

class HomeController extends Controller
{
    public function home(TestRequest $request, Request $id)
    {
        return view('pages/home');
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
