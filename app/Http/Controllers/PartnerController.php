<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use Main\Http\Request;
use App\Http\Exceptions\Exception;

class PartnerController extends Controller
{
    public function index(Request $request = null)
    {
        try {
            $users = Partner::get();
            return view('users/index', compact('users'));
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
    public function store()
    {
        $data = Request::all();
        // toPre($data);
        $user = Partner::create($data);
        return response()->json($user);
    }

    public function show($id)
    {
        return response()->json(Partner::find($id));
    }

    public function update($id)
    {
        $data = Request::all();
        $user = DB::table('users')->where('id', '=', $id)->update($data);
    }

    public function destroy($id)
    {
        return DB::table('users')->where('id', '=', $id)->delete();
    }
}
