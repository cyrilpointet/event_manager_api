<?php

namespace App\Http\Controllers;

use App\Models\Happening;
use App\Models\Survey;
use App\Models\Team;
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
     * @group Team management
     * @urlParam id int required The team's id
     * @bodyParam name string required Happening name
     * @bodyParam description string required Happening description
     * @bodyParam place string required Happening place
     * @bodyParam dates object[] required
     * @bodyParam dates.start string Happening start. Date in string
     * @bodyParam dates.end string Happening end. Date in string
     */
    public function create(Request $request, $id)
    {
        try {
            $request->validate([
                'name' => 'required',
                'description' => 'required',
                'place' => 'required',
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
        $survey->happenings;

        return response($survey, 201);
    }
}
