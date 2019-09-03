<?php

namespace App\Controllers;

use App\Main\Controller;

class HomeController extends Controller
{
    public function home()
    {
        // return action([HomeController::class, 'about']);
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
