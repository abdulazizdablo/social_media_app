<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of all comments for the given post.
     *
     * @param int $postId
     * @return \Illuminate\Http\Response
     */
    public function index($postId)
    {
        $post = Post::findOrFail($postId);
        $comments = $post->comments;

        return view('comments.index', compact('post', 'comments'));
    }

    /**
     * Create a new comment for the given post.
     *
     * @param Request $request
     * @param int $postId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create($postId, Request $request)
    {
        $post = Post::findOrFail($postId);
        $user = auth()->user();

        $comment = new Comment();
        $comment->body = $request->input('body');
        //  $comment->post_id = $post->id;
        //$comment->user_id = $user->id;
        $comment->associate($post);
        $comment->associate($user);

        $comment->save();

        return redirect()->route('posts.show', $post->id);
    }

    /**
     * Delete the comment with the given ID.
     *
     * @param int $commentId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($commentId)
    {
        $comment = Comment::findOrFail($commentId);
        $user = auth()->user();

        if ($comment->user_id != $user->id) {
            return abort(403);
        }

        $comment->delete();

        return redirect()->route('posts.show', $comment->post_id);
    }
}
