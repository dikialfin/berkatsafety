<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\Company;
use App\Models\RolePermission;
use App\Models\User;
use App\Traits\FeatureLimit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FeatureLimitController extends BaseController{

    use FeatureLimit;
    public function getLimitAndUsage(Request $request){
        $usages = $this->getOrCreateFeatureUsage($request->user());
        $limit = $this->getUserLimit($request->user());
        Log::info($usages);
        Log::info($limit);
    }
}