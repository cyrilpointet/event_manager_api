<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;

/**
 * @group Team management
 *
 * APIs for managing teams
 */
class TeamController extends Controller
{
    /**
     * Create a team
     * and set current user as member and admin
     * @bodyParam name string required The team's name
     */
    public function create(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required',
            ]);
        } catch (\Exception $e) {
            return response([
                'message' => ['Invalid or missing fields']
            ], 403);
        }

        $team = Team::create([
            'name' => $request->name
        ]);
        $user = $request->user();

        $team->users()->attach($user->id, ['admin' => true]);
        $team->users;

        return $team;
    }

    /**
     * Get a team by id
     * @urlParam id int required  The team's id
     */
    public function show($id)
    {
        $team = Team::find($id);
        if (null === $team) {
            return response([
                "message" => "Groupe inconnu"
            ], 404);
        }
        $team->users;

        return response($team, 200);
    }

    /**
     * Update a team
     * @urlParam id int required  The team's id
     * @bodyParam name string required The team's name
     */
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'name' => 'required',
            ]);
        } catch (\Exception $e) {
            return response([
                'message' => ['Invalid or missing fields']
            ], 403);
        }

        $team = Team::find($id);
        if (null === $team) {
            return response([
                "message" => "Unknown team"
            ], 404);
        }
        $team->name = $request->name;
        $team->save();

        $team->users;

        return response($team, 200);
    }

    /**
     * Manage the admin status of a team user
     * @urlParam id int required The team's id
     * @bodyParam userId int required The user's id to manage
     * @bodyParam admin boolean required The new user's status
     */
    public function manageAdmin(Request $request, $id)
    {
        try {
            $request->validate([
                'userId' => 'required',
                'admin' => 'required',
            ]);
        } catch (\Exception $e) {
            return response([
                'message' => ['Invalid or missing fields']
            ], 403);
        }

        $team = Team::find($id);
        if (null === $team) {
            return response([
                "message" => "Unknown team"
            ], 404);
        }

        $adminCount = 0;
        foreach ($team->users as $elem) {
            if (true === $elem->pivot->admin) {
                $adminCount++;
            }
        }
        if (2 > $adminCount && false === $request->admin) {
            return response([
                "message" => "Team must have at least one admin"
            ], 403);
        }

        $team->users()->updateExistingPivot($request->userId, [
            'admin' => $request->admin,
        ]);

        $team = Team::find($id);
        $team->users;

        return response($team, 200);
    }

    /**
     * Delete a team
     * @urlParam id int required The team's id
     */
    public function delete($id)
    {
        $group = Team::find($id);
        if (null === $group) {
            return response([
                "message" => "Unknown team"
            ], 404);
        }

        $group->delete();
        return response([
            'message' => 'Team deleted'
        ], 200);
    }
}
