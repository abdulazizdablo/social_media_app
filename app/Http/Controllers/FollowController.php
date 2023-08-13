<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class FollowController extends Controller
{



    public function follow(User $followed_user)
    {
        // Check if user already followed

        $user = auth()->user();


        $followed_useres = $user->followedUsers;

        $followed_useres->contains($followed_user->id) ?
            $followed_useres->attach($followed_user->id) :
            $followed_useres->detach($followed_user->id);


        /* if (!is_null($check_followed)) {
        }*/
    }

    public function followersList()
    {


    }


    public function followingList()
    {

        $user = auth()->user()->followedUsers;


        
    }
}
