<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyScannerApiKey
{
    public function handle(Request $request, Closure $next): Response
    {
        $expected = config('stadium.scanner_api_key');
        if (empty($expected)) {
            return response()->json(['error' => 'Scanner API key not configured'], 500);
        }

        $provided = $request->header('X-Scanner-Key');
        if (!$provided || !hash_equals($expected, $provided)) {
            return response()->json(['result' => 'red', 'message' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}
