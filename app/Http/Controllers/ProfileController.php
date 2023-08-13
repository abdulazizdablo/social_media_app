<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class ProfileController extends Controller
{



    /**
     * Display the profile for the given user.
     *
     * @param int $userId
     * @return \Illuminate\Http\Response
     */
    public function show($userId)
    {
        $user = User::findOrFail($userId);
        $profile = $user->profile;

        return view('profiles.show', compact('user', 'profile'));
    }

    /**
     * Edit the profile for the current user.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $user = auth()->user();
        $profile = $user->profile;

        return view('profiles.edit', compact('user', 'profile'));
    }

    /**
     * Update the profile for the current user.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $user = auth()->user();
        $profile = $user->profile;

        $profile->fill($request->all());
        $profile->save();

        return redirect()->route('profiles.show', $user->id);
    }
}
