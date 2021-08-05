<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * @group User management
 *
 * APIs for managing users
 */
class UserController extends Controller
{
    /**
     * Create a user
     * @bodyParam name string required The user's nickname
     * @bodyParam email string required The user's email. Must be unique.
     * @bodyParam password string required The user's password
     */
    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required',
                'email' => 'required|email',
                'password' => 'required'
            ]);
        } catch (\Exception $e) {
            return response([
                'message' => ['Invalid or missing fields']
            ], 400);
        }

        $testExist = User::where('email', $request->email)->first();
        if ($testExist) {
            return response([
                'message' => ['Already used email']
            ], 401);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        $token = $user->createToken('event_manager')->plainTextToken;

        $user->teams;
        $user->invitations;
        $user->happenings;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }

    /**
     * Login a user and get a token
     * @bodyParam email string required The user's email.
     * @bodyParam password string required The user's password
     */
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required'
            ]);
        } catch (\Exception $e) {
            return response([
                'message' => ['Invalid or missing fields']
            ], 401);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response([
                'message' => ['Ces identifiant ne correspondent pas']
            ], 404);
        }

        $token = $user->createToken('event_manager')->plainTextToken;

        $user->teams;
        $user->invitations;
        $user->happenings;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }

    /**
     * Get a user by its token
     */
    public function show(Request $request)
    {
        $user = $request->user();
        $user->teams;
        $user->invitations;
        $user->happenings;
        return $user;
    }

    /**
     * Get users by name or email
     * @bodyParam name string required The user's name or email
     */
    public function getUserByNameOrEmail(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required',
            ]);
        } catch (\Exception $e) {
            return response([
                'message' => ['Invalid or missing fields']
            ], 401);
        }

        return User::where('name', 'like', '%' . $request->name . '%')->orWhere('email', 'like', '%' . $request->name . '%')->take(10)->get();
    }

    /**
     * Leave a team
     * @urlParam id int required  The team's id
     */
    public function leaveTeam(Request $request, $id) {
        $user = $request->user();
        foreach ($user->teams as $team) {
            if ($team->id === intval($request->route('id')) && true === $team->pivot->admin) {
                return response([
                    "message" => "Can't leave a team when you're admin"
                ], 403);
            }
        }

        $team = Team::find($id);
        if (null === $team) {
            return response([
                "message" => "Unkwown team"
            ], 404);
        }

        $team->members()->detach($user->id);

        return response([
            "message" => "Team leaved"
        ], 200);
    }
}
