<?php

namespace App\Controllers;

use App\Http\Request;
use App\Models\Partner;
use App\Main\Controller;

class PartnerController extends Controller
{
    public function index(Request $request = null)
    {
      try {
        $users = Partner::get();
        toPre($users);
        return view('users/index', compact('users'));
      } catch (\Exception $e) {
        throw new \App\Main\AppException($e->getMessage());
      }
    }
    public function store()
    {
        $data = Request::all();
        $user = Partner::create($data);
        return response()->json($user);
    }

    public function show($id)
    {
        return response()->json(Partner::find($id));
    }

    public function update($id){
        $data = Request::all();
        $user = DB::table('users')->where('id', '=', $id)->update($data);
    }

    public function destroy($id)
    {
        return DB::table('users')->where('id', '=', $id)->delete();
    }
}
