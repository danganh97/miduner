<?php

namespace App\Controllers;

use App\Main\Controller;

class HomeController extends Controller
{
    public function __invoke($id)
    {
        echo $id;
        echo 'vao invoke';
    }

    public function home()
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
