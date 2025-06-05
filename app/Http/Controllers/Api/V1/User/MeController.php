<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\Company;
use App\Models\FeatureUsage;
use App\Models\RolePermission;
use App\Models\User;
use App\Models\UserCredit;
use App\Traits\FeatureLimit;
use Illuminate\Http\Request;

class MeController extends BaseController
{
    use FeatureLimit;
    public function index(Request $request)
    {
        $user = User::where('id',$request->user()->id)->with('role')->first();
        $permission = RolePermission::where('role_id',$user->role_id)->get()->pluck('permission');
        $user->permission = $permission;
        $company = Company::where('id',$user->company_id)->first();
        $user->company = $company;
        return $user;
    }

    public function current(Request $request)
    {
        $user = User::where('id',$request->user()->id)->with('role')->first();

        return $this->sendResponse(
            [
                'data' => $user,
            ],'success'
        );
    }

    public function userCredit(Request $request)
    {
        $credit = 0;
        $user = UserCredit::where('user_id',$request->user()->id)->first();
        if($user){
            $credit = $user->credit;
        }
        return $this->sendResponse(
            [
                'credit' => $credit,
            ],'success'
        );
    }
}
