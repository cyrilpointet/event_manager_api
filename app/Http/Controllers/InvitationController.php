<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;

class InvitationController extends Controller
{
    /**
     * Create an invitation from a team
     * @group Team management
     * @urlParam id int required The team's id
     * @bodyParam email string required an email
     */
    public function createFromTeam(Request $request, $id)
    {
        $request->validate([
            'email' => 'required',
        ]);

        $test = Invitation::where('team_id', '=', $id)->where('user_email', '=', $request->email)->get();
        if (0 !== count($test)) {
            return response([
                "message" => "Invitation alreay exist"
            ], 403);
        }

        $user = User::where('email', '=', $request->email)->first();
        if (null !== $user) {
            $teams = $user->teams()->where('team_id', '=', $id)->get();
            if (0 !== count($teams)) {
                return response([
                    'message' => 'User is already a team member'
                ], 403);
            }
        }

        $invitation = Invitation::create([
            'team_id' => $id,
            'user_email' => $request->email,
            'is_from_team' => true
        ]);
        $invitation->team;

        return response($invitation, 201);
    }

    /**
     * Manage an invitation sent to a team by a user
     * @group Team management
     * @urlParam id int required The team's id
     * @bodyParam id int required The invitation's id
     * @bodyParam status boolean required an email
     */
    public function manageUserInvitation(Request $request, $id)
    {
        try {
            $request->validate([
                'id' => 'required',
                'status' => 'required',
            ]);
        } catch (\Exception $e) {
            return response([
                'message' => ['Invalid or missing fields']
            ], 400);
        }

        $team = Team::find($id);
        if (null === $team) {
            return response([
                "message" => "Unknown team"
            ], 404);
        }

        $invitation = Invitation::find($request->id);

        if (null === $invitation) {
            return response([
                "message" => "Unknown invitation"
            ], 404);
        }
        if (intval($id) !== $invitation->team_id) {
            return response([
                "message" => "Invalid band Id"
            ], 403);
        }
        if (false !== $invitation->is_from_team) {
            return response([
                "message" => "Invitation comes not from user"
            ], 403);
        }

        $user = User::firstWhere('email', $invitation->user_email);
        if (null === $user) {
            return response([
                "message" => "Unknown user"
            ], 404);
        }

        if (true === $request->status) {
            $team->members()->attach($user->id, ['admin' => false]);
        }
        $invitation->delete();

        $team->members;
        $team->invitations;

        return $team;
    }
}
