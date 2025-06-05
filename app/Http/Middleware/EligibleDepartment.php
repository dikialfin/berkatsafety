<?php

namespace App\Http\Middleware;

use App\Models\Role;
use App\Models\RolePermission;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class EligibleDepartment
{
    public function handle(Request $request, Closure $next, $department)
    {
        $currentDepartment = 99;
        $userDepartment = Role::where('id',$request->user()->role_id)->first();
        if($userDepartment){
            $currentDepartment = $userDepartment->department_id;
        }
        if($currentDepartment != $department && $currentDepartment != 0){
            return response()->json('Not Eligible.', 403);
        }
        return $next($request);
    }
}
