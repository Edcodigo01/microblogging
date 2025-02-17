<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MeasureRequestTime
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // return $next($request);
        $startTime = microtime(true);

        $response = $next($request);

        $endTime = microtime(true);

        $executionTime = $endTime - $startTime;

        $content = json_decode($response->getContent(), true);

        if (json_last_error() === JSON_ERROR_NONE) {
            $content['execution_time'] = $executionTime . ' segundos';
            $response->setContent(json_encode($content));
        }

        return $response;
    }
}
