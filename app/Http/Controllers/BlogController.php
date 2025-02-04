<?php

namespace App\Http\Controllers;

use App\Http\Requests\BlogFilterRequest;
use App\Http\Requests\CreatePostRequest;
use App\Http\Requests\FormPostRequest;
use App\Models\Post;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\Input;

class BlogController extends Controller
{
    public function index(BlogFilterRequest $request): View
    {
        $posts = Post::paginate(1);

        return view('blog.index', [
            'posts' => $posts
        ]);
    }

    public function show(string $slug, Post $post): RedirectResponse | View
    {
        if ($post->slug !== $slug) {
            return to_route('blog.show', ["slug" => $post->slug, "id" => $post->id]);
        }
        return view('blog.show', [
            'post' => $post
        ]);
    }

    public function create(): View
    {
        $post = new Post();
        return view('blog.create',[
            'post' => $post
        ]);
    }

    public function store(FormPostRequest $request)
    {
        $post = Post::create($request->validated());

        return redirect()->route('blog.show', ['slug' => $post->slug, 'post' => $post->id])->with('success', "L'article a bien été sauvegardé");
    }

    public function edit(Post $post): View
    {
        return view('blog.edit', [
            'post' => $post
        ]);
    }

    public function update(Post $post, FormPostRequest $request)
    {

        $post->update($request->validated());
        return redirect()->route('blog.show', ['slug'=> $post->slug, 'post' => $post->id])->with('success', "L'article a bien été modifé");
    }
}
