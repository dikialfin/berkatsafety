<?php

namespace App\Http\Controllers\Api\V1\Masterdata;

use App\Mail\EmailForQueuing;
use App\Models\Company;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\User;
use App\Models\UserLog;
use App\Traits\FeatureLimit;
use Illuminate\Support\Facades\Mail;

class CustomerController extends Controller
{

    public function data( Request $request )
    {
        $size      = $request->input('size', 10);
        $search    = $request->input('search', null);
        $sortField = $request->input('sort', 'id');
        $sortAsc   = $request->input('sort_asc', false);

        $data = new Customer();
        if($request->data_status == 'archived'){
            $data = $data->onlyTrashed();
        }
        if($search){
            $data = $data->where(function($query) use($search){
                $query->where('name','like','%'.$search.'%');
            });
        }
        $data = $data->where('company_id',$request->user()->company_id);
        $data = $data->orderBy($sortField ? $sortField : 'id', $sortAsc ? 'asc' : 'desc');
        $data = $data->paginate($size);

        return response()->json([
            'data' => $data,
            'pagination' => true,
        ]);
    }

    public function log( Request $request )
    {
        $size      = $request->input('size', 10);
        $search    = $request->input('search', null);
        $sortField = $request->input('sort', 'id');
        $sortAsc   = $request->input('sort_asc', false);
        $department_id = $request->department_id;

        $data = UserLog::with('user');
        if($department_id){
            $data = $data->whereHas('user',function($query) use($department_id){
                $query->whereHas('role',function($query) use($department_id){
                    $query->where('department_id',$department_id);
                });
            });
        }
        if($search){
            $data = $data->where('log','like','%'.$search.'%');
        }
        $data = $data->orderBy($sortField ? $sortField : 'id', $sortAsc ? 'asc' : 'desc');
        $data = $data->paginate($size);

        return response()->json([
            'data' => $data,
            'pagination' => true,
        ]);
    }

    public function select($id, Request $request)
    {
        $data = User::get();

        $datas = [];
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
        $User = new Customer;
        $User->company_id = $request->user()->company_id;
        $User->name = $request->name;
        $User->job = $request->job;
        $User->save();

        //create log
        $user_log = new UserLog();
        $user_log->user_id = $request->user()->id;
        $user_log->log = "{$request->user()->name} create user {$request->name}";
        $user_log->save();

        $data = [
            'status' => 1,
        ];
        return $data;
    }

    public function resendVerification($id, Request $request)
    {
        $data = User::where('id', $id)->first();

        $token = encrypt($data->id);
        $user = User::where('id',$data->id)->update(['verify_token' => $token]);

        $details = [
            'email' => $data->email,
            'subject' => 'User Verification',
            'view' => 'email.user-verification',
            'data' => [
                'user' => $data,
                'link' => url('/verification/'.$token)
            ],
        ];
        $email = new EmailForQueuing($details);
        Mail::send($email);
        // SendEmail::dispatch($details);

        //create log
        $user_log = new UserLog();
        $user_log->user_id = $request->user()->id;
        $user_log->log = "{$request->user()->name} resend verification for {$data->name}";
        $user_log->save();

        return  response()->json([
            'data' => $data
        ]);
    }

    public function edit($id)
    {
        $data = Customer::where('id', $id)->first();

        return  response()->json([
            'data' => $data
        ]);
    }

    public function active($id)
    {
        $User = User::where('id', $id);

        if(!$User->update(['status' => 1])){
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
        $User = User::where('id', $id);

        if(!$User->update(['status' => 0])){
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
        $User = Customer::where('id', $id);

        if(!$User->delete()){
            $data = [
                'status' => 0
            ];
            return $data;
        }else{
            $User = Customer::where('id', $id)->withTrashed()->first();
             //create log
            $user_log = new UserLog();
            $user_log->user_id = $request->user()->id;
            $user_log->log = "{$request->user()->name} delete user {$User->name}";
            $user_log->save();

            $data = [
                'status' => 1
            ];
            return $data;
        }

    }

    public function restore($id, Request $request)
    {
        $User = Customer::where('id', $id)->withTrashed();

        if(!$User->restore()){
            $data = [
                'status' => 0
            ];
            return $data;
        }else{
            $User = Customer::where('id', $id)->first();
            //create log
            $user_log = new UserLog();
            $user_log->user_id = $request->user()->id;
            $user_log->log = "{$request->user()->name} restore user {$User->name}";
            $user_log->save();

            $data = [
                'status' => 1
            ];
            return $data;
        }

    }

    public function update(Request $request)
    {

        $User = Customer::findOrFail($request->id);
        $User->company_id = $request->user()->company_id;
        $User->name = $request->name;
        $User->job = $request->job;

        if(!$User->update()){
            $data = [
                'status' => 0,
            ];
            return $data;
        }else{
            //create log
            $user_log = new UserLog();
            $user_log->user_id = $request->user()->id;
            $user_log->log = "{$request->user()->name} update user {$User->name}";
            $user_log->save();

            $data = [
                'status' => 1,
                'data' => $User
            ];
            return $data;
        }
    }

    public function account(Request $request)
    {
        $data = User::where('id', $request->user()->id)->first();
        if($data->role_id == 2){
            $company = Company::with('admin')->where('id', $data->company_id)->first();
            $data->company = $company;
        }
        return  response()->json([
            'data' => $data
        ]);
    }

    public function updateAccount(Request $request)
    {
        //cheeck email
        $check = User::where('email',$request->email)->where('id','!=',$request->user()->id)->first();
        if($check){
            return response()->json(['message' => 'Email already exist!'],500);
        }

        $User = User::findOrFail($request->user()->id);

        $User->name = $request->name;
        $User->email = $request->email;
        $User->phone = $request->phone;

        if($request->password){
            if($request->password != $request->password_confirm){
                return response()->json(['message' => 'Confirm password is wrong!'],500);
            }
            $User->password = bcrypt($request->password);
        }

        if(!$User->update()){
            $data = [
                'status' => 0,
            ];
            return $data;
        }else{
            //create log
            $user_log = new UserLog();
            $user_log->user_id = $request->user()->id;
            $user_log->log = "{$request->user()->name} update user {$User->name}";
            $user_log->save();

            $data = [
                'status' => 1,
                'data' => $User
            ];
            return $data;
        }
    }

    public function updateNew(Request $request)
    {
        $data = User::where('id', $request->user()->id)->first();
        $data->is_new = false;
        $data->save();

        return  response()->json([
            'data' => $data
        ]);
    }

}
