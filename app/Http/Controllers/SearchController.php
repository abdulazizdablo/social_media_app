<?php

namespace App\Http\Controllers;
use App\Models\Post;
use Illuminate\Http\Request;

class SearchController extends Controller
{

    /**
     * Search for posts.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = $request->input('query');
        $posts = Post::where('title', 'like', '%' . $query . '%')->orWhere('body', 'like', '%' . $query . '%')->paginate(10);

        return view('search.index', compact('posts', 'query'));
    }
}
