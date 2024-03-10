<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Http\Controllers\Controller;

class PostController extends Controller
{
    public function index()
    {
        $produks = Post::all();
        return view('produks.index', compact('produks'));
    }

    public function create()
    {
        return view('produks.create');
    }

    public function store(Request $request)
    {
        // Logika untuk menyimpan data
    }

    public function show($id)
    {
        // Logika untuk menampilkan detail data
    }

    public function edit($id)
    {
        // Logika untuk menampilkan form edit
    }

    public function update(Request $request, $id)
    {
        // Logika untuk menyimpan perubahan data
    }

    public function destroy($id)
    {
        // Logika untuk menghapus data
    }
}