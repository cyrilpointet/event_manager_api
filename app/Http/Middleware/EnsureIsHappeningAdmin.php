<?php

namespace App\Http\Middleware;

use App\Models\Happening;
use Closure;
use Illuminate\Http\Request;

class EnsureIsHappeningAdmin
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
        $happening = Happening::find($request->route('id'));
        if ($happening === null) {
            return response([
                'message' => ['Unknown happening']
            ], 404);
        }

        $teamId = $happening->team->id;
        $user = $request->user();
        foreach ($user->teams as $team) {
            if ($team->id === $teamId && true === $team->pivot->admin) {
                $request->attributes->add(['happening' => $happening]);
                return $next($request);
                exit;
            }
        }
        return response([
            'message' => ['User is not a team administrator']
        ], 403);
    }
}
