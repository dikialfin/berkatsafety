<?php

namespace App\Traits;

use App\Models\Company;
use App\Models\FeatureUsage;
use App\Models\SubscriptionPermission;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Log;

trait FeatureLimit
{
    /**
     * check is user eligible to make actions that have limiter
     *
     * @param User $user
     * @param string $type
     * @return Boolean $response
     */
    public  function checkFeatureLimit(User $user, string $type)
    {
        // Log::debug($user);
        $limit = $this->getUserLimit($user, $type);
        Log::debug("limit : {$limit}");
        if ($limit) {
            $count = $this->getOrCreateFeatureUsage($user, $type);
            if ($count >= $limit) throw new Exception("this feature has reach its limit. usage : {$count}, limit : {$limit}");
        } else {
            Log::info('there is no limit');
        }
    }

    // TODO : use this to calculate usage for all user in x company
    // public function getUsageForCompany(int $company_id){
    //     $usage = FeatureUsage::where('company_id', $company_id)
    //     ->sum()
    // }

    public function getOrCreateFeatureUsage(User $user, ?string $type = null)
    {
        $usage = FeatureUsage::where('user_id', $user->id)->first();
        if (!$usage) {
            $usage = new FeatureUsage();
            $usage->user_id = $user->id;
            $usage->company_id = $user->company_id;
            $usage->save();
            $usage->refresh();
        }
        if ($type) {
            return $usage[$type];
        } else {
            return $usage;
        }
    }

    public function addFeatureUsage(User $user, string $type)
    {
        try {

            $usage = $this->getOrCreateFeatureUsage($user);
            $usage[$type] = $usage[$type]+1;
            $usage->update();
        } catch (Exception $e) {
            Log::error("[FeatureLimit] addFeatureUsage failed : {$e}");
        }
    }

    /** to get limit : 
     * table : subscription_permission
     * subscription_id == $user->company->subscription_id
     * permission == $type
     * get value
     */

    public function getUserLimit(User $user, string $type)
    {
        $company_subs_id = Company::where('id', $user->company_id)
            ->pluck('subscription_id')
            ->first();
        $permission =  SubscriptionPermission::where('subscription_id', $company_subs_id)
            ->where('permission', "{$type}_limit")
            ->pluck('value')
            ->first();
        return $permission;
    }
}
