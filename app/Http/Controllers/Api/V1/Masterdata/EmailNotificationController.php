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

class EmailNotificationController extends Controller
{
    public function data( Request $request )
    {
        $size      = $request->input('size', 10);
        $search    = $request->input('search', null);
        $sortField = $request->input('sort', 'id');
        $sortAsc   = $request->input('sort_asc', false);

        $data = EmailNotification::with('admin','recipient.tier','recipient.user')->where('company_id',$request->user()->company_id);

        if($request->data_status == 'archived'){
            $data = $data->onlyTrashed();
        }

        if($search){
            $data = $data->where(function($query) use($search){
                $query->where('name','like','%'.$search.'%');
            });
        }

        $data = $data->orderBy($sortField ? $sortField : 'id', $sortAsc ? 'asc' : 'desc');
        $data = $data->paginate($size);

        return response()->json([
            'data' => $data,
            'pagination' => true,
        ]);
    }

    public function select(Request $request)
    {
        $data = Subscription::get();

        $datas = [];
        if($request->filter){
            $datas[] = [
                'id' => 0,
                'text' => 'All Roles'
            ];
        }
        foreach($data as $key){
            $datas[] = [
                'id' => $key->id,
                'text' => $key->name
            ];
        }
        return json_encode($datas);
    }

    public function add(Request $request)
    {
        $data = new EmailNotification();
        $data->admin_id = $request->user()->id;
        $data->company_id = $request->user()->company_id;
        $data->title = $request->title;
        $data->subject = $request->subject;
        $data->content = $request->content;
        $data->recipient_type = $request->recipient_type;
        $data->save();
        
        $recipients = [];
        if($request->recipient_type == 'tier'){
            foreach($request->membership_tier_id as $val){
                $recipient = new EmailNotificationRecipient();
                $recipient->email_notification_id = $data->id;
                $recipient->membership_tier_id = $val;
                $recipient->save();

                //get membership under this tier
                $member = UserMembership::with('user')->where('membership_tier_id',$val)->get();
                foreach($member as $mem){
                    $recipients[] = $mem->user->email;
                }
            }
        }else{
            foreach($request->user_id  as $val){
                $recipient = new EmailNotificationRecipient();
                $recipient->email_notification_id = $data->id;
                $recipient->user_id = $val;
                $recipient->save();

                //get email under user_id
                $member = User::where('id',$val)->first();
                $recipients[] = $member->email;
            }
        }
        $company = Company::where('id',$request->user()->company_id)->first();
        $details = [
            'email' => $recipients,
            'subject' => $request->subject,
            'view' => 'email.email-notification',
            'data' => [
                'company_name' => $company->name,
                'company_logo' => $company->brand_logo,
                'content' => $request->content,
                'title' => $request->title
            ],
        ];
        if ($request->sender_type == 'default'){
            $email = new EmailForQueuing($details);
            Mail::send($email);
        }else{
            $setting = EmailConfiguration::where('company_id',$request->user()->company_id)->first();
            if($setting){
                $details['address'] = $setting->email;
                $details['name'] = $setting->name;
                $transport = (new \Swift_SmtpTransport($setting->host, $setting->port))
                ->setEncryption($setting->encryption)
                ->setUsername($setting->username)
                ->setPassword($setting->password);

                $mailer = app(\Illuminate\Mail\Mailer::class);
                $mailer->setSwiftMailer(new \Swift_Mailer($transport));
                $email = new EmailForQueuing($details);
                $mailer->send($email);
            }else{
                $email = new EmailForQueuing($details);
                Mail::send($email);
            }
        }
        
        //create log
        $user_log = new UserLog();
        $user_log->user_id = $request->user()->id;
        $user_log->log = "{$request->user()->name} create email notification";
        $user_log->save();

        $data = [
            'status' => 1,
        ];
        return $data;
    }

    public function preview(Request $request){
        $company = Company::where('id',$request->user()->company_id)->first();
        $details = [
            'email' => $request->email_preview,
            'subject' => $request->subject,
            'view' => 'email.email-notification',
            'data' => [
                'company_name' => $company->name,
                'company_logo' => $company->brand_logo,
                'content' => $request->content,
                'title' => $request->title
            ],
        ];
        if ($request->sender_type == 'default'){
            $email = new EmailForQueuing($details);
            Mail::send($email);
        }else{
            $setting = EmailConfiguration::where('company_id',$request->user()->company_id)->first();
            if($setting){
                $details['address'] = $setting->email;
                $details['name'] = $setting->name;
                $transport = (new \Swift_SmtpTransport($setting->host, $setting->port))
                ->setEncryption($setting->encryption)
                ->setUsername($setting->username)
                ->setPassword($setting->password);

                $mailer = app(\Illuminate\Mail\Mailer::class);
                $mailer->setSwiftMailer(new \Swift_Mailer($transport));
                $email = new EmailForQueuing($details);
                $mailer->send($email);
            }else{
                $email = new EmailForQueuing($details);
                Mail::send($email);
            }
        }
        return response()->json(['message' => 'success']);
    }

    public function edit($id)
    {
        $data = Subscription::where('id', $id)->first();
        $permission = SubscriptionPermission::where('subscription_id',$data->id)->get();
        $perm = [];
        foreach($permission as $val){
            $dataperm = Permission::where('name',$val->permission)->first();
            if($dataperm){
                $dataperm->value = $val->value;
                $perm[] = (object)$dataperm;
            }
        }
        $data->permissions = $perm;

        return  response()->json([
            'data' => $data
        ]);
    }

    public function active($id)
    {
        $Role = Role::where('id', $id);

        if(!$Role->update(['status' => 1])){
            $data = [
                'status' => 0
            ];
            return $data;
        }else{
            $data = [
                'status' => 1
            ];
            return $data;
        }
    }

    public function deactive($id)
    {
        $Role = Role::where('id', $id);

        if(!$Role->update(['status' => 0])){
            $data = [
                'status' => 0
            ];
            return $data;
        }else{
            $data = [
                'status' => 1
            ];
            return $data;
        }
    }

    public function delete($id,Request $request)
    {
        $Role = Notification::where('id', $id);

        if(!$Role->delete()){
            $data = [
                'status' => 0
            ];
            return $data;
        }else{

            $data = [
                'status' => 1
            ];
            return $data;
        }

    }

    public function restore($id, Request $request)
    {
        $Role = Subscription::where('id', $id)->withTrashed();

        if(!$Role->restore()){
            $data = [
                'status' => 0
            ];
            return $data;
        }else{
            $Role = Subscription::where('id', $id)->withTrashed()->first();
            //create log
            $user_log = new UserLog();
            $user_log->user_id = $request->user()->id;
            $user_log->log = "{$request->user()->name} restore subscription {$Role->name}";
            $user_log->save();

            $data = [
                'status' => 1
            ];
            return $data;
        }

    }

    public function update(Request $request)
    {

        $Role = Subscription::findOrFail($request->id);

        $Role->name = $request->name;

        $deletepermission = SubscriptionPermission::where('subscription_id',$request->id)->delete();

        foreach($request->permissions as $val){
            $group = [];
            foreach($request->permissions as $val){
                if(!in_array($val['group'],$group)){
                    $group[] = $val['group'];
                }
            }
            foreach($group as $val){
                $permission = new SubscriptionPermission();
                $permission->permission = $val;
                $permission->subscription_id = $Role->id;
                $permission->save();
            }
            
            foreach($request->permissions as $val){
                $permission = new SubscriptionPermission();
                $permission->permission = $val['name'];
                $permission->subscription_id = $Role->id;
                $permission->value = $val['value']?$val['value']:0;
                $permission->save();
            }
        }
        if(!$Role->update()){
            $data = [
                'status' => 0,
            ];
            return $data;
        }else{
            //create log
            $user_log = new UserLog();
            $user_log->user_id = $request->user()->id;
            $user_log->log = "{$request->user()->name} update subscription {$Role->name}";
            $user_log->save();

            $data = [
                'status' => 1,
                'data' => $Role
            ];
            return $data;
        }
    }
    
}
