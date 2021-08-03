<?php

namespace App\Http\Controllers;

use App\Models\Happening;
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

        return response($happening, 200);
    }

    /**
     * Get an Happening by id
     * @group Happening management
     * @urlParam id int required The happening id
     */
    public function show(Request $request)
    {
        $happening = $request->get('happening');
        $happening->team;

        return $happening;
    }
}
