<?php

namespace App\Http\Controllers\Api\V1\Masterdata;

use App\Events\PushNotification;
use App\Http\Controllers\BaseController;
use App\Models\Notification;
use App\Models\NotificationRecipient;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\Role;
use App\Models\Subscription;
use App\Models\SubscriptionPermission;
use App\Models\UserLog;
use App\Models\UserMembership;

class BroadcastController extends BaseController
{
    public function data( Request $request )
    {
        $size      = $request->input('size', 10);
        $search    = $request->input('search', null);
        $sortField = $request->input('sort', 'id');
        $sortAsc   = $request->input('sort_asc', false);

        $data = Notification::with('admin','recipient.tier')->where('company_id',$request->user()->company_id)->whereNotNull('admin_id');

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

    public function notification( Request $request )
    {
        $data = new Notification();
        if($request->type == 'company'){
            $data = $data->where('company_id',$request->company_id);
            $data = $data->whereDoesntHave('recipient');
            $data = $data->orderBy('id','desc')->get();
        }

        if($request->type == 'user'){
            //get user membership id
            $membership_id = UserMembership::where('user_id',$request->user()->id)->get()->pluck('membership_tier_id')->toArray();
            $data = $data->whereHas('recipient',function($query) use($membership_id){
                $query->whereIn('membership_tier_id',$membership_id);
            });
            $data = $data->orderBy('id','desc')->get();
        }

        return response()->json([
            'data' => $data,
        ]);
    }

    public function permission( Request $request )
    {
        $department_id = $request->department_id;
        $data = Permission::get();
        $return = [];
        foreach($data as $val){
            if($val->group == 'feature'){
                $return[$val->name] = [];
            }
        }
        foreach($data as $val){
            if($val->group != 'feature'){
                $return[$val->group][] = $val;
            }
        }

        return response()->json([
            'data' => $return,
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
        if(!$request->title){
            return $this->sendError('Title cannot be empty!',[]);
        }
        $company_id = $request->user()->company_id;
        $data = new Notification();
        $data->admin_id = $request->user()->id;
        $data->company_id = $request->user()->company_id;
        $data->title = $request->title;
        $data->description = $request->description;
        $data->link = "/app/dashboard/$company_id/dashboard";
        $data->save();

        foreach($request->membership_tier_id as $val){
            $recipient = new NotificationRecipient();
            $recipient->notification_id = $data->id;
            $recipient->membership_tier_id = $val;
            $recipient->save();

            $notification = (object)[
                'title' => $request->title,
                'message' => "",
                'target_url' => "/app/dashboard/$company_id/dashboard",
                'notif_id' => "membership-$val"
            ];
            event(new PushNotification($notification));
        }
        //create log
        $user_log = new UserLog();
        $user_log->user_id = $request->user()->id;
        $user_log->log = "{$request->user()->name} create broadcast";
        $user_log->save();

        $data = [
            'status' => 1,
        ];
        return $data;
    }

    public function edit($id)
    {
        $data = Subscription::where('id', $id)->first();
        $permission = SubscriptionPermission::where('subscription_id',$data->id)->get();
        $perm = [];
        foreach($permission as $val){
            $dataperm = Permission::where('name',$val->permission)->first();
            $dataperm->value = $val->value;
            $perm[] = (object)$dataperm;
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
