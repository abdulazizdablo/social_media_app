<?php

namespace App\Http\Controllers;

use App\Models\Post;

use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of all posts.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::latest()->paginate(10);

        return view('posts.index', compact('posts'));
    }

    /**
     * Create a new post.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create()
    {
        $user = auth()->user();

        return view('posts.create', compact('user'));
    }

    /**
     * Store a new post.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        $post = new Post();
        $post->title = $request->title;
        $post->body = $request->body;
        $post->associate($user);
        $post->save();

        return redirect()->route('posts.index');
    }

    /**
     * Display the post with the given ID.
     *
     * @param int $postId
     * @return \Illuminate\Http\Response
     */
    public function show($postId)
    {
        $post = Post::findOrFail($postId);

        return view('posts.show', compact('post'));
    }

    /**
     * Edit the post with the given ID.
     *
     * @param int $postId
     * @return \Illuminate\Http\Response
     */
    public function edit($postId)
    {
        $post = Post::findOrFail($postId);
        $user = auth()->user();

        if ($post->user_id != $user->id) {
            return abort(403);
        }

        return view('posts.edit', compact('post'));
    }

    /**
     * Update the post with the given ID.
     *
     * @param Request $request
     * @param int $postId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $postId)
    {
        $post = Post::findOrFail($postId);
        $user = auth()->user();

        if ($post->user_id != $user->id) {
            return abort(403);
        }

        $post->title = $request->input('title');
        $post->body = $request->input('body');
        $post->save();

        return redirect()->route('posts.show', $post->id);
    }

    /**
     * Delete the post with the given ID.
     *
     * @param int $postId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($postId)
    {
        $post = Post::findOrFail($postId);
        $user = auth()->user();

        if ($post->user_id != $user->id) {
            return abort(403);
        }

        $post->delete();

        return redirect()->route('posts.index');
    }
}
