<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureIsHappeningMember
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
        $user = $request->user();
        $happenings = $user->happenings()->where('happening_id', $request->route('id'))->get();
        if (0 === count($happenings)) {
            return response([
                'message' => ['User is not a happening member']
            ], 403);
        }
        return $next($request);
    }
}
