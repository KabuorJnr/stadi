<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocaleFromUser
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if ($user && in_array($user->preferred_locale, ['en', 'sw', 'dholuo'])) {
            app()->setLocale($user->preferred_locale);
        }
        return $next($request);
    }
}
