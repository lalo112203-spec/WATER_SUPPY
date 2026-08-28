<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ClearSettingsAuthorization
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
        if (!$request->is('settings*') && !$request->is('livewire*')) {
            if ($request->session()->has('settings_authorized')) {
                $request->session()->forget('settings_authorized');
            }
        }

        return $next($request);
    }
}
