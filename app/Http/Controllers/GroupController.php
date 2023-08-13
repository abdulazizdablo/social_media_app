<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;

class GroupController extends Controller
{

    /**
     * Display a listing of all groups.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $groups = Group::all();

        return view('groups.index', compact('groups'));
    }

    /**
     * Create a new group.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create(Request $request)
    {
        $group = new Group();
        $group->name = $request->input('name');
        $group->save();

        return redirect()->route('groups.index');
    }

    /**
     * Display the group with the given ID.
     *
     * @param int $groupId
     * @return \Illuminate\Http\Response
     */
    public function show($groupId)
    {
        $group = Group::findOrFail($groupId);
        $users = $group->users;

        return view('groups.show', compact('group', 'users'));
    }

    /**
     * Add the current user to the group with the given ID.
     *
     * @param int $groupId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function join($groupId)
    {
        $user = auth()->user();
        $group = Group::findOrFail($groupId);

        $group->users()->attach($user->id);

        return redirect()->route('groups.show', $group->id);
    }

    /**
     * Remove the current user from the group with the given ID.
     *
     * @param int $groupId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function leave($groupId)
    {
        $user = auth()->user();
        $group = Group::findOrFail($groupId);

        $group->users()->detach($user->id);

        return redirect()->route('groups.show', $group->id);
    }
}
