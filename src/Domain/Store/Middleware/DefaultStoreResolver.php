<?php

namespace Domain\Store\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use Domain\Store\Actions\ResolveDefaultStoreAction;

class DefaultStoreResolver
{
    /**
     * Goal: Extract the default store ID from the session 
     * using session and attaching to request object.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $store = ResolveDefaultStoreAction::get($request->user());
        
        // Store session data not found.
        if($store === null) abort(404);

        // Attach store to request object for further usages.
        $request->merge([
            'store' => $store
        ]);

        return $next($request);
    }
}
