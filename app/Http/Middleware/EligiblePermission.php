<?php

namespace App\Http\Middleware;

use App\Models\RolePermission;
use Closure;
use Illuminate\Http\Request;

class EligiblePermission 
{
    public function handle(Request $request, Closure $next, $permission)
    {
        $userPermission = RolePermission::where('role_id',$request->user()->role_id)->get()->pluck('permission')->toArray();
        if(!in_array($permission,$userPermission)){
            return response()->json('Not Eligible.', 403);
        }
        return $next($request);
    }
}
