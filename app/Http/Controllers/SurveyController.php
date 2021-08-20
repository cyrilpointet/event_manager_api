<?php

namespace App\Http\Controllers;

use App\Models\Happening;
use App\Models\Survey;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * @group Survey management
 *
 * APIs for managing surveys
 */
class SurveyController extends Controller
{
    /**
     * Create a new survey
     * @urlParam id int required The team's id
     * @bodyParam name string required Happening name
     * @bodyParam description string required Happening description
     * @bodyParam place string required Happening place
     * @bodyParam members int[] required users ids
     * @bodyParam dates object[] required
     * @bodyParam dates.start string Happening start. Date in string
     * @bodyParam dates.end string Happening end. Date in string
     */
    public function create(Request $request, $id)
    {
        try {
            $request->validate([
                'name' => 'required',
                'description' => 'present',
                'place' => 'present',
                'dates' => 'required',
                'members' => 'required'
            ]);
        } catch (\Exception $e) {
            return response([
                'message' => ['Invalid or missing fields']
            ], 400);
        }

        $dates = $request->dates;
        foreach ($dates as $date) {
            if (new \DateTime($date['start']) > new \DateTime($date['end'])) {
                return response([
                    'message' => ['Invalid dates']
                ], 400);
            }
        }

        $survey = Survey::create([
            'team_id' => $id
        ]);

        $user = $request->user();
        $team = Team::find($id);
        foreach ($dates as $date) {
            $start = new \DateTime($date['start']);
            $end = new \DateTime($date['end']);
            $happening = Happening::create([
                'name' => $request->name,
                'description' => $request->description,
                'place' => $request->place,
                'team_id' => $id,
                'start_at' => $start,
                'end_at' => $end,
                'survey_id' => $survey->id
            ]);
            $happening->members()->attach($user->id);
            foreach ($team->members as $member) {
                if (in_array($member->id, $request->members) && $member->id !== $user->id) {
                    $happening->members()->attach($member->id);
                }
            }
        }

        $survey->team;
        foreach ($survey->happenings as $happening) {
            $happening->members;
        }

        return response($survey, 201);
    }

    /**
     * Get a survey
     * @urlParam id int required The survey's id
     */
    public function show($id)
    {
        $survey = Survey::find($id);
        if ($survey === null) {
            return response([
                'message' => ['Unknown survey']
            ], 404);
        }
        $survey->team;
        foreach ($survey->happenings as $happening) {
            $happening->members;
        }

        return response($survey, 200);
    }

    /**
     * Update a survey
     * @urlParam id int required The survey's id
     * @bodyParam name string required Happening name
     * @bodyParam description string required Happening description
     * @bodyParam place string required Happening place
     */
    public function update(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required',
                'description' => 'present',
                'place' => 'present',
            ]);
        } catch (\Exception $e) {
            return response([
                'message' => ['Invalid or missing fields']
            ], 400);
        }

        $survey = $request->get('survey');

        foreach ($survey->happenings as $happening) {
            $happening->name = $request->name;
            $happening->description = $request->description;
            $happening->place = $request->place;
            $happening->save();
        }

        $survey->team;
        foreach ($survey->happenings as $happening) {
            $happening->members;
        }

        return response($survey, 200);
    }

    /**
     * Validate a survey. Delete the survey and unwanted happenings
     * @urlParam id int required The survey's id
     * @bodyParam happenings int[] required Happenings ids to be validated. Others will be deleted
     */
    public function validateHappenings(Request $request)
    {
        try {
            $request->validate([
                'happenings' => 'present',
            ]);
        } catch (\Exception $e) {
            return response([
                'message' => ['Invalid or missing fields']
            ], 400);
        }

        $survey = $request->get('survey');

        $happenings = [];
        foreach ($survey->happenings as $happening) {
            if (true !== in_array($happening->id, $request->happenings)) {
                $happening->delete();
            } else {
                $happening->survey_id = null;
                $happening->save();
                $happening->members;
                $happenings[] = $happening;
            }
        }

        $survey->delete();

        return response($happenings, 200);
    }

    /**
     * Add a member
     * @urlParam id int required The survey id
     * @bodyParam id int required the user id to be added
     */
    public function addMember(Request $request, $id)
    {
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

        $survey = $request->get('survey');

        $teams = $user->teams()->where('team_id', $survey->team->id)->get();
        if (0 === count($teams)) {
            return response([
                'message' => ['User is not a team member']
            ], 403);
        }

        foreach ($survey->happenings as $happening) {
            $isHappeningMember = false;
            foreach ($happening->members as $member) {
                if ($member->id === $request->id) {
                    $isHappeningMember = true;
                }
            }
            if ($isHappeningMember === false) {
                $happening->members()->attach($request->id);
            }
        }

        $survey = Survey::find($id);
        $survey->team;
        foreach ($survey->happenings as $happening) {
            $happening->members;
        }

        return response($survey, 200);
    }

    /**
     * Remove a member
     * @urlParam id int required The survey id
     * @bodyParam id int required the user id to be added
     */
    public function removeMember(Request $request, $id)
    {
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

        $survey = $request->get('survey');
        foreach ($survey->happenings as $happening) {
            $happening->members()->detach($request->id);
        }

        $survey = Survey::find($id);
        $survey->team;
        foreach ($survey->happenings as $happening) {
            $happening->members;
        }

        return response($survey, 200);
    }
}
