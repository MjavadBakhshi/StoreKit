<?php

namespace Domain\Store\Middleware;

use Closure;
use Domain\Store\Actions\Shop\DomainStoreResolverAction;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DomainStoreResolver
{
    /**
     * Goal: Extract the store ID from the domain (e.g., store1.example.com) 
     * using database/cache and attache to request object.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $domainName = $request->getHost();
        $store = DomainStoreResolverAction::execute($domainName);
        
        // Store-Domain pair not found.
        if($store === false) abort(404);

        // Attach store to request object for further usages.
        $request->merge([
            'store' => $store
        ]);

        return $next($request);
    }
}
