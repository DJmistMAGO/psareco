<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePasswordIsChanged
{
    /**
     * The blocking modal renders on top of whatever page the user is on
     * (handled in the layout), but this middleware is defense-in-depth:
     * it stops any state-changing request other than the password update
     * itself and logout, in case someone bypasses the UI.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        $exemptRouteNames = ['profile.password.update', 'logout'];

        if (
            $user
            && $user->must_change_password
            && ! $request->isMethod('get')
            && ! in_array($request->route()?->getName(), $exemptRouteNames, true)
        ) {
            return redirect()->back()->with('error', 'Please set a new password before continuing.');
        }

        return $next($request);
    }
}
