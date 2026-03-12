<?php

namespace Domain\Store\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use Domain\Store\Actions\ResolveDefaultStoreAction;

class EntityStoreOwnershipChecker
{
    /**
     * This is responsible for checking store/enttiy ownership
     * Ex: checking if {product} in the /proudcts/{product} belongs to current store?
     * Making store data isolation across all store administration panel.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $entityName): Response
    {
        // Retrieve the store.
        $store = $request->store ?? ResolveDefaultStoreAction::get($request->user());
      
        throw_if(is_null($store), new \Exception("Store not found"));

        if($request->$entityName->store_id == $store->id)
            return $next($request);
        
        return abort(403);
    }
}
