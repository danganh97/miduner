<?php

namespace App\Controllers;

use App\Main\Controller;

class PostController extends Controller
{
    public function index()
    {
        echo 'post index';
    }

    public function create()
    {
        echo 'post create';
    }

    public function store()
    {
        echo 'post store';
    }

    public function show($id)
    {
        echo 'post show' . $id;
    }

    public function edit($id)
    {
        echo 'post edit' . $id;
    }

    public function update($id)
    {
        echo 'post update' . $id;
    }

    public function destroy($id)
    {
        echo 'post destroy' . $id;
    }
}
