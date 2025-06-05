<?php

namespace App\Http\Middleware;

use App\Models\RolePermission;
use Closure;
use Illuminate\Http\Request;

class EligibleRole 
{
    public function handle(Request $request, Closure $next, $role)
    {
        if($role === 'superadmin'){
            if(!in_array($request->user()->role_id,[1,2,3])){
                return response()->json('Not Eligible.', 403);
            }
        }
        return $next($request);
    }
}
