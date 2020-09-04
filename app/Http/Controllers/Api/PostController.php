<?php

namespace App\Http\Controllers\Api;

class PostController extends Controller
{
    public function index()
    {
        return $this->respond("Post index");
    }

    public function create()
    {
        return $this->respond("Post create");
    }

    public function store()
    {
        return $this->respond("Post Store");
    }

    public function show($id)
    {
        return $this->respond("Post show {$id}");
    }

    public function edit($id)
    {
        return $this->respond("Post edit {$id}");
    }

    public function update($id)
    {
        return $this->respond("Post update {$id}");
    }

    public function destroy($id)
    {
        return $this->respond("Post destroy {$id}");
    }
}
