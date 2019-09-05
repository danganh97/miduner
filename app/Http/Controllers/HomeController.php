<?php

namespace App\Http\Controllers;

use App\Main\Controller;
use App\Main\QueryBuilder as DB;
use App\Models\User;

class HomeController extends Controller
{
    public function home()
    {
        $a = DB::bindClass(User::class)
            // ->where('user_id', '=', 1222123123)
            ->where(function ($query) {
                $query->where('user_id', '=', 1);
                $query->orWhere('created_at', '>=', '123123');
                // $query->where(function ($query) {
                //     $query->orWhere('updated_at', '>=', '123123');
                // });
            })
            // ->orWhere('user_id', '=', '1232')
            ->where(function ($query) {
                $query->where('user_id', '=', 2);
                $query->orWhere('created_at', '=', 'zzzz');
            })
            ->tosql();
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
