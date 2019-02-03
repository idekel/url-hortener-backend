<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;

class AllowOriginWhenDebug
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        if (env('APP_DEBUG') && $request->isMethod('OPTIONS')) {
            $response = new Response();
            $response->setStatusCode(\Symfony\Component\HttpFoundation\Response::HTTP_OK);
            $response->header('Access-Control-Allow-Headers', 'Origin, Content-Type, Content-Range, Content-Disposition, Content-Description');
            $response->header('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, PUT');
        }
        if (env('APP_DEBUG')) {
            $response->header('Access-Control-Allow-Origin', '*');
        }
        return $response;
    }
}
