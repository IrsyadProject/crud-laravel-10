<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Post;

use Illuminate\View\View;

use Illuminate\Http\RedirectResponse;

class PostController extends Controller
{
    public function index(): View
    {
        $posts = Post::latest()->paginate(5);
        $title = 'Daftar Post - Halaman Utama';

        return view('posts.index', compact('posts', 'title'));
    }

    public function create(): View
    {
        return view('posts.create');
    }

    public function store(Request $request): RedirectResponse
    {
        //validate form
        $this->validate($request, [
            'title'     => 'required|min:5',
            'content'   => 'required|min:10',
            'image'     => 'required|image|mimes:jpeg,jpg,png|max:2048',
        ]);

        //upload image
        $image = $request->file('image');
        $image->storeAs('public/posts', $image->hashName());

        //create post
        Post::create([
            'title'     => $request->title,
            'content'   => $request->content,
            'image'     => $image->hashName(),
        ]);

        //redirect to index
        return redirect()->route('posts.index')->with(['success' => 'Data Berhasil Disimpan!']);
    }

    public function show(string $id): View
    {
        //get post by ID
        $post = Post::findOrFail($id);
        $title = 'Detail Post';

        //render view with post
        return view('posts.show', compact('post', 'title'));
    }
}
