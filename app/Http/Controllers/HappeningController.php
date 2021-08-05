<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Happening;
use App\Models\User;
use Illuminate\Http\Request;

class HappeningController extends Controller
{
    /**
     * Create a new happening
     * @group Team management
     * @urlParam id int required The team's id
     * @bodyParam name string required Happening name
     * @bodyParam description string required Happening description
     * @bodyParam place string required Happening place
     * @bodyParam startAt string required Happening start. Date in string
     * @bodyParam endAt string required Happening end. Date in string
     */
    public function create(Request $request, $id)
    {
        try {
            $request->validate([
                'name' => 'required',
                'description' => 'required',
                'place' => 'required',
                'startAt' => 'required',
                'endAt' => 'required',
            ]);
        } catch (\Exception $e) {
            return response([
                'message' => ['Invalid or missing fields']
            ], 400);
        }

        $start = new \DateTime($request->startAt);
        $end = new \DateTime($request->endAt);

        $happening = Happening::create([
            'name' => $request->name,
            'description' => $request->description,
            'place' => $request->place,
            'team_id' => $id,
            'start_at' => $start,
            'end_at' => $end,
        ]);
        $user = $request->user();
        $happening->members()->attach($user->id);

        $happening->team;
        $happening->members;
        $happening->comments;

        return response($happening, 200);
    }

    /**
     * Update an happening
     * @group Happening management
     * @urlParam id int required The Happening id
     * @bodyParam name string required Happening name
     * @bodyParam description string required Happening description
     * @bodyParam place string required Happening place
     * @bodyParam status string required Happening status: project, validated or canceled
     * @bodyParam startAt string required Happening start. Date in string
     * @bodyParam endAt string required Happening end. Date in string
     */
    public function update(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required',
                'description' => 'required',
                'place' => 'required',
                'status' => ['required', 'regex:/project|validated|canceled/'],
                'startAt' => 'required',
                'endAt' => 'required',
            ]);
        } catch (\Exception $e) {
            return response([
                'message' => ['Invalid or missing fields']
            ], 400);
        }

        $happening = $request->get('happening');

        $happening->name = $request->name;
        $happening->description = $request->description;
        $happening->place = $request->place;
        $happening->status = $request->status;

        $start = new \DateTime($request->startAt);
        $end = new \DateTime($request->endAt);

        $happening->start_at = $start;
        $happening->end_at = $end;

        $happening->save();

        $happening->team;
        $happening->members;
        $happening->comments;

        return response($happening, 200);
    }

    /**
     * Get an Happening
     * @group Happening management
     * @urlParam id int required The happening id
     */
    public function show(Request $request)
    {
        $happening = Happening::find($request->route('id'));
        if ($happening === null) {
            return response([
                'message' => ['Unknown happening']
            ], 404);
        }
        $happening->team;
        $happening->members;
        $happening->comments;

        return $happening;
    }

    /**
     * Add a member
     * @group Happening management
     * @urlParam id int required The happening id
     * @bodyParam id int required the user id to be added
     */
    public function addMember(Request $request, $id) {
        try {
            $request->validate([
                'id' => 'required',
            ]);
        } catch (\Exception $e) {
            return response([
                'message' => ['Invalid or missing fields']
            ], 400);
        }

        $user = User::find($request->id);
        if ($user === null) {
            return response([
                'message' => ['Unknown user']
            ], 404);
        }
        $happening = $request->get('happening');

        $teams = $user->teams()->where('team_id', $happening->team->id)->get();
        if (0 === count($teams)) {
            return response([
                'message' => ['User is not a team member']
            ], 403);
        }

        foreach ($happening->members as $member) {
            if ($member->id === $request->id) {
                return response([
                    'message' => ['User is already a member']
                ], 403);
                break;
            }
        }

        $happening->members()->attach($request->id);

        $happening = Happening::find($id);
        $happening->team;
        $happening->members;
        $happening->comments;

        return response($happening, 200);
    }

    /**
     * update user presence
     * @group Happening management
     * @urlParam id int required The happening id
     * @bodyParam presence string required the user presence: yes|maybe|no
     */
    public function updatePresence(Request $request) {
        try {
            $request->validate([
                'presence' => ['required', 'regex:/unknown|yes|maybe|no/'],
            ]);
        } catch (\Exception $e) {
            return response([
                'message' => ['Invalid or missing fields']
            ], 400);
        }

        $happening = Happening::find($request->route('id'));
        if ($happening === null) {
            return response([
                'message' => ['Unknown happening']
            ], 404);
        }

        $user = $request->user();
        $happening->members()->updateExistingPivot($user->id, [
            'presence' => $request->presence,
        ]);

        $happening->team;
        $happening->members;
        $happening->comments;

        return $happening;
    }

    /**
     * Remove a member
     * @group Happening management
     * @urlParam id int required The happening id
     * @bodyParam id int required the user id to be added
     */
    public function removeMember(Request $request) {
        try {
            $request->validate([
                'id' => 'required',
            ]);
        } catch (\Exception $e) {
            return response([
                'message' => ['Invalid or missing fields']
            ], 400);
        }

        $happening = $request->get('happening');
        $happening->members()->detach($request->id);

        $happening->team;
        $happening->members;
        $happening->comments;

        return response($happening, 200);
    }

    /**
     * Add a comment
     * @group Happening management
     * @urlParam id int required The happening id
     * @bodyParam text string required the comment's content
     */
    public function addComment(Request $request) {
        try {
            $request->validate([
                'text' => 'required',
            ]);
        } catch (\Exception $e) {
            return response([
                'message' => ['Invalid or missing fields']
            ], 400);
        }

        $user = $request->user();
        Comment::create([
            'content' => $request->text,
            'happening_id' => intval($request->route('id')),
            'user_id' => $user->id
        ]);

        $happening = Happening::find($request->route('id'));
        $happening->team;
        $happening->members;
        $happening->comments;

        return response($happening, 201);
    }

    /**
     * Add a comment
     * @group Happening management
     * @urlParam id int required The happening id
     * @urlParam comment_id int required the comment id to be removed
     */
    public function removeComment(Request $request, $id, $comment_id) {
        $comment = Comment::find($comment_id);

        if ($comment === null) {
            return response([
                'message' => 'Unknown comment'
            ], 404);
        }

        $happening = Happening::find($id);
        $teamId = $happening->team->id;
        $isAdmin = false;
        $user = $request->user();
        foreach ($user->teams as $team) {
            if ($team->id === $teamId && true === $team->pivot->admin) {
                $isAdmin = true;
            }
        }

        if ($isAdmin === false && $user->id !== $comment->user->id) {
            return response([
                'message' => 'insufficient rights'
            ], 403);
        }

        $comment->delete();

        $happening->team;
        $happening->members;
        $happening->comments;

        return response($happening, 200);
    }

    /**
     * Delete an Happening
     * @group Happening management
     * @urlParam id int required The happening id
     */
    public function delete(Request $request)
    {
        $happening = $request->get('happening');
        $happening->delete();
        return response([
            'message' => 'Happening deleted'
        ], 200);
    }
}
