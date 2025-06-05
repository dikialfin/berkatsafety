<?php

namespace App\Http\Controllers\Api\V1\Masterdata;

use App\Mail\EmailForQueuing;
use App\Models\Company;
use App\Models\EmailConfiguration;
use App\Models\EmailNotification;
use App\Models\EmailNotificationRecipient;
use App\Models\Notification;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\Role;
use App\Models\Subscription;
use App\Models\SubscriptionPermission;
use App\Models\User;
use App\Models\UserLog;
use App\Models\UserMembership;
use Illuminate\Support\Facades\Mail;

class EmailConfigurationController extends Controller
{
    public function add(Request $request)
    {
        $data = new EmailConfiguration();
        if($request->id){
            $data = EmailConfiguration::where('id',$request->id)->first();
        }
        $data->company_id = $request->user()->company_id;
        $data->name = $request->name;
        $data->email = $request->email;
        $data->username = $request->username;
        $data->password = $request->password;
        $data->host = $request->host;
        $data->port = $request->port;
        $data->encryption = $request->encryption;
        $data->save();
        
        //create log
        $user_log = new UserLog();
        $user_log->user_id = $request->user()->id;
        $user_log->log = "{$request->user()->name} update email configuration";
        $user_log->save();

        $data = [
            'status' => 1,
        ];
        return $data;
    }

    public function data(Request $request)
    {
        $data = EmailConfiguration::where('company_id',$request->user()->company_id)->first();

        return  response()->json([
            'data' => $data
        ]);
    }
}
