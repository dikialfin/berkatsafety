<?php

namespace App\Traits;

use App\Models\DeviceUUID;
use Exception;
use Illuminate\Support\Facades\Log;

trait DeviceUUIDHelper
{
     /**
     * create or get device_uuid by uuid
     *
     * @param string $uuid
     * @return ?int $id
     */
    public function getOrCreateDeviceUUID(?string $uuid){
        try{
            if(!$uuid) return null;
            $d = DeviceUUID::where('uuid', $uuid)->first();
            if(!$d){
                $d = new DeviceUUID();
                $d->uuid = $uuid;
                $d->save();
                $d->refresh();
            }
            return $d->id;
        }catch(Exception $e){
            Log::error("failed to getOrCreateDeviceUUID : {$e}");
            return null;
        }
    }

}
