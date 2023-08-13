<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;


class NotifactionsController extends Controller
{
    public function send(Request $request)
    {
        $user = User::find($request->input('userId'));
        $text = $request->input('text');

        $notification = new Notification();
        $notification->text = $text;
        $notification->user_id = $user->id;
        $notification->save();

        $user->notify($notification);

        return redirect()->back();
    }
}
