<?php

namespace App\Http\Controllers;

use App\Http\Requests\Request;
use App\Http\Requests\TestRequest;
use App\Http\Requests\TestRequestFile;
use App\Models\User;

class HomeController extends Controller
{
    public function home(Request $request, $id)
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
