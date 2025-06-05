<?php

namespace App\Http\Controllers\Api\V1\Masterdata;

use App\Mail\EmailForQueuing;
use App\Models\Company;
use App\Models\CompanySaasSetting;
use App\Models\CompanyWebsite;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\User;
use App\Models\UserLog;
use Illuminate\Support\Facades\Mail;

class CompanyController extends Controller
{
    public function data( Request $request )
    {
        $size      = $request->input('size', 10);
        $search    = $request->input('search', null);
        $sortField = $request->input('sort', 'id');
        $sortAsc   = $request->input('sort_asc', false);

        $data = Company::with('admin','subscription');
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
        //check email
        $check = User::where('email',$request->email)->first();
        if($check){
            return response()->json(['message' => 'Email already exist!'],500);
        }

        //create company
        $company = new Company();
        $company->name = $request->name;
       
        $company->brand_color = $request->brand_color;
        $company->subscription_id = $request->subscription_id;
        $company->save();

        if($request->is_landing_page){
            $website = new CompanyWebsite();
            $website->company_id = $company->id;
            $website->website_url = $request->company_website['website_url'];
            $website->component_key = $request->company_website['component_key'];
            $website->save();
        }

        if($request->is_saas){
            $website = new CompanySaasSetting();
            if($request->photo){
                $img = $request->photo->store('heyjen');
                if(env('FILESYSTEM_DRIVER') == 's3'){
                    $url = env('AWS_BASE_URL').$img;
                }else{
                    $url = url('images/'.$img);
                }
                $website->website_logo = $url;
            }
            $website->company_id = $company->id;
            $website->type = $request->company_saas_setting['type'];
            $website->adaptive_feedback_id = $request->company_saas_setting['adaptive_feedback_id'];
            $website->website_url = $request->company_saas_setting['website_url'];
            $website->payment_gateway = $request->company_saas_setting['payment_gateway'];
            $website->payment_gateway_key = $request->company_saas_setting['payment_gateway_key'];
            $website->save();
        }

        $User = new User;
        $User->name = $request->name;
        $User->username = '';
        $User->email = $request->email;
        $User->phone = $request->phone;
        $User->role_id = 2;
        $User->company_id = $company->id;
        $User->status = 1;
        $User->password = bcrypt($request->password);
        $User->two_factor_authentication = $request->two_factor_authentication?1:0;
        $User->newsletter = $request->newsletter?1:0;
        $User->anti_phising = $request->anti_phising?1:0;
        if($User->anti_phising){
            $User->anti_phising_code = $request->anti_phising_code;
        }
       
        $User->save();

        //create log
        $user_log = new UserLog();
        $user_log->user_id = $request->user()->id;
        $user_log->log = "{$request->user()->name} create company {$request->name}";
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
        $company = Company::with('admin','saas_setting')->where('id', $id)->first();
        $data = User::where('company_id',$company->id)->where('role_id',2)->first();
        $data->company = $company;
        if($company->saas_setting){
            $data->is_saas = true;
            $data->company_saas_setting = $company->saas_setting;
        }
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
        $User = Company::where('id', $id);
        
        if(!$User->delete()){
            $data = [
                'status' => 0
            ];
            return $data;
        }else{
            $User = Company::where('id', $id)->withTrashed()->first();
             //create log
            $user_log = new UserLog();
            $user_log->user_id = $request->user()->id;
            $user_log->log = "{$request->user()->name} delete company {$User->name}";
            $user_log->save();

            //delete admin
            $User = User::where('company_id',$id)->where('role_id',2)->first();
            if($User){
                $User->delete();
            }

            $data = [
                'status' => 1
            ];
            return $data;
        }

    }

    public function restore($id, Request $request)
    {
        $User = Company::where('id', $id)->withTrashed();

        if(!$User->restore()){
            $data = [
                'status' => 0
            ];
            return $data;
        }else{
            $User = Company::where('id', $id)->first();
            //create log
            $user_log = new UserLog();
            $user_log->user_id = $request->user()->id;
            $user_log->log = "{$request->user()->name} restore company {$User->name}";
            $user_log->save();

            //restore admin
            $User = User::where('company_id',$id)->where('role_id',2)->withTrashed()->first();
            if($User){
                $User->restore();
            }

            $data = [
                'status' => 1
            ];
            return $data;
        }

    }

    public function update(Request $request)
    {
        //cheeck email
        $check = User::where('email',$request->email)->where('id','!=',$request->id)->first();
        if($check){
            return response()->json(['message' => 'Email already exist!'],500);
        }

        $User = User::findOrFail($request->id);

        $User->name = $request->name;
        $User->email = $request->email;
        $User->phone = $request->phone;
        $User->two_factor_authentication = $request->two_factor_authentication?1:0;
        $User->newsletter = $request->newsletter?1:0;
        $User->anti_phising = $request->anti_phising?1:0;
        if($User->anti_phising){
            $User->anti_phising_code = $request->anti_phising_code;
        }
        if($request->password){
            $User->password = bcrypt($request->password);
        }

        //update company
        $company = Company::where('id',$User->company_id)->first();
        $company->brand_color = $request->brand_color;
        $company->subscription_id = $request->subscription_id;
        $company->name = $request->name;
        $company->update();

        if($request->is_saas){
            if(isset($request->company_saas_setting['id'])){
                $website = CompanySaasSetting::where('id',$request->company_saas_setting['id'])->first();
                $website->company_id = $company->id;
                if($request->photo){
                    $img = $request->photo->store('heyjen');
                    if(env('FILESYSTEM_DRIVER') == 's3'){
                        $url = env('AWS_BASE_URL').$img;
                    }else{
                        $url = url('images/'.$img);
                    }
                    $website->website_logo = $url;
                }
                $website->adaptive_feedback_id = $request->company_saas_setting['adaptive_feedback_id'];
                $website->type = $request->company_saas_setting['type'];
                $website->website_url = $request->company_saas_setting['website_url'];
                $website->payment_gateway = $request->company_saas_setting['payment_gateway'];
                $website->payment_gateway_key = $request->company_saas_setting['payment_gateway_key'];
                $website->update();
            }else{
                $website = new CompanySaasSetting();
                $website->company_id = $company->id;
                $website->type = $request->company_saas_setting['type'];
                $website->payment_gateway = $request->company_saas_setting['payment_gateway'];
                $website->payment_gateway_key = $request->company_saas_setting['payment_gateway_key'];
                $website->save();
            }
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
            $user_log->log = "{$request->user()->name} update company {$User->name}";
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
        // $User->role_id = $request->role_id;
        if($request->photo){
            $img = $request->photo->store('berkas');
            $User->photo = url('images/'.$img);
        }

        if($request->password){
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

}
