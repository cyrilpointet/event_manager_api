<?php

namespace App\Http\Middleware;

use App\Models\Survey;
use Closure;
use Illuminate\Http\Request;

class EnsureIsSurveyAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $survey = Survey::find($request->route('id'));
        if ($survey === null) {
            return response([
                'message' => ['Unknown happening']
            ], 404);
        }
        $teamId = $survey->team->id;
        $user = $request->user();
        foreach ($user->teams as $team) {
            if ($team->id === $teamId && true === $team->pivot->admin) {
                $request->attributes->add(['survey' => $survey]);
                return $next($request);
                exit;
            }
        }
        return response([
            'message' => ['User is not a team administrator']
        ], 403);
    }
}
