<?php

namespace App\Http\Middleware;

use App\Models\Report;
use Closure;
use Spatie\Permission\Exceptions\UnauthorizedException;

class IsEditingSelfReport
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Pre-Middleware Action

        $user = app('auth')->user();
        if(!$user->hasRole('user'))
            return $next($request);
        $id = $request->route('id');
        $report = Report::find($id);
        if($report->user_id !== $user->id)
            throw new UnauthorizedException(403);

        // Post-Middleware Action

        return $next($request);
    }
}
