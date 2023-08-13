<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Models\Like;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    /**
     * Like a post or comment.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function like($id)
    {
        $model = $this->getLikeableModel($id);
        $user = auth()->user();

        if ($user->hasLiked($model)) {
            $user->unlike($model);
        } else {
            $like = new Like();
            //  $like->user_id = $user->id;
            $like->likeable_id = $model->id;
            $like->associate($user);

            $like->likeable_type = get_class($model);
            $like->save();
        }

        return redirect()->back();
    }

    /**
     * Get the likeable model.
     *
     * @param $id
     * @return Post|Comment
     */
    protected function getLikeableModel($id)
    {
        $model = Post::find($id);

        if (!$model) {
            $model = Comment::find($id);
        }

        return $model;
    }


    public function unlike($id)
    {
        $model = $this->getLikeableModel($id);
        $user = auth()->user();

        $like = $user->likes()->where('likeable_id', $model->id)->where('likeable_type', get_class($model))->first();

        if ($like) {
            $like->delete();
        }

        return redirect()->back();
    }
}
