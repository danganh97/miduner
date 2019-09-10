<?php

namespace App\Http\Controllers;

use App\Main\Controller;
use App\Models\User;
use App\Main\QueryBuilder as DB;

class HomeController extends Controller
{
    public function home()
    {
        // // $a = DB::bindClass(Partner::class)
        // //     ->where(function ($query) {
        // //         $query->where('pid', '=', 1);
        // //         $query->orWhere('created_at', '>=', '123123');
        // //     })
        // //     ->orWhere(function ($query) {
        // //         $query->where('pid', '<>', 2);
        // //         $query->where('created_at', '=', 'zzzz');
        // //     })
        // //     ->toSql();
        // toPre(request()->all());
        // $a = User::create(request()->all());
        // $a = DB::table('users')->insert(request()->all());
        // // $a = User::first();
        // toPre($a);
        // return $this->respond($a);
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
