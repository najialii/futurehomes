<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CacheHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only apply caching headers to GET requests
        if ($request->isMethod('GET')) {
            // Cache for 5 minutes for most content
            $cacheTime = 300; // 5 minutes
            
            // Different cache times for different content types
            if ($request->is('api/*/company') || $request->is('api/companies')) {
                $cacheTime = 3600; // 1 hour for company info (changes rarely)
            } elseif ($request->is('api/*/services*') || $request->is('api/services*')) {
                $cacheTime = 1800; // 30 minutes for services
            } elseif ($request->is('api/*/projects*') || $request->is('api/projects*')) {
                $cacheTime = 900; // 15 minutes for projects
            } elseif ($request->is('api/*/partners*') || $request->is('api/partners*')) {
                $cacheTime = 1800; // 30 minutes for partners
            } elseif ($request->is('api/*/testimonials*') || $request->is('api/testimonials*')) {
                $cacheTime = 600; // 10 minutes for testimonials
            } elseif ($request->is('api/*/pages*') || $request->is('api/pages*')) {
                $cacheTime = 1800; // 30 minutes for pages
            }

            $response->headers->set('Cache-Control', "public, max-age={$cacheTime}");
            $response->headers->set('Expires', gmdate('D, d M Y H:i:s', time() + $cacheTime) . ' GMT');
            
            // Add ETag for better caching
            if ($response->getContent()) {
                $etag = md5($response->getContent());
                $response->headers->set('ETag', '"' . $etag . '"');
                
                // Check if client has cached version
                if ($request->header('If-None-Match') === '"' . $etag . '"') {
                    return response('', 304);
                }
            }
        }

        return $response;
    }
}
