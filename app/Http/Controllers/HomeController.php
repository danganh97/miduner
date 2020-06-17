<?php

namespace App\Http\Controllers;

use Main\Http\Request;

class HomeController extends Controller
{
    public function home(Request $request)
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
