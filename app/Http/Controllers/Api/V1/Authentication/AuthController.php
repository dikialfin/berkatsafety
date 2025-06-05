<?php

namespace App\Http\Controllers\Api\V1\Authentication;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\ResetRequest;
use App\Jobs\SendEmail;
use App\Mail\EmailForQueuing;
use App\Mail\EmailVerif;
use App\Models\CompanySaasSetting;
use App\Models\Komoditi;
use App\Models\Pricing;
use App\Models\User;
use App\Models\UserCredit;
use App\Models\UserCreditDetail;
use App\Models\UserLog;
use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends BaseController
{
    public function login(LoginRequest $request)
    {
        $request->ensureIsNotRateLimited();
        //cek username
        $user = User::where('email',$request->email)->first();
        if(!$user){
            return $this->sendError('Invalid credential');
        }
        if (!Hash::check($request->password, $user->password)){
            return $this->sendError('Invalid credential!');
        }
        // if($user->status == 0){
        //     return $this->sendError('Account not verified!');
        // }
        $token = Auth::login($user);

        $user = User::with(['role'])->where('id',$user->id)->first();

        Auth::setToken($token);

        $token = (string) Auth::getToken();
        $expiration = Auth::getPayload()->get('exp');

        $logs = new UserLog();
        $logs->user_id = $user->id;
        $logs->log = "{$user->name} login to system";
        $logs->ip = $request->ip();
        $logs->save();

        return $this->sendResponse(
            [
                'user' => $user,
                'token' => $token,
                'token_type' => 'bearer',
                'expires_in' => $expiration - time(),
            ],'success'
        );
    }

    public function generateToken(LoginRequest $request)
    {
        $request->ensureIsNotRateLimited();
        //cek username
        $user = User::where('email',$request->email)->first();
        if(!$user){
            return $this->sendError('Invalid credential');
        }
        if (!Hash::check($request->password, $user->password)){
            return $this->sendError('Invalid credential!');
        }
        if($user->status == 0){
            return $this->sendError('Account not verified!');
        }
        $token = Auth::login($user);

        $user = User::with('role')->where('id',$user->id)->first();

        Auth::setToken($token);

        $token = (string) Auth::getToken();
        $expiration = Auth::getPayload()->get('exp');

        $logs = new UserLog();
        $logs->user_id = $user->id;
        $logs->log = "{$user->name} login to system";
        $logs->ip = $request->ip();
        $logs->save();

        return $this->sendResponse(
            [
                'token' => $token,
                'token_type' => 'bearer',
                'expires_in' => $expiration - time(),
            ],'success'
        );
    }

    public function loginGoogle(Request $request){
        $client = new Client();
        $response = $client->request('GET', 
        "https://www.googleapis.com/oauth2/v1/userinfo", [
            'headers' => [
                'Authorization' => "Bearer $request->token",
            ]
        ]);
        $response = json_decode($response->getBody(), true);
        
        $user = User::where('email',$response['email'])->first();
        if(!$user){
            return $this->sendError('Invalid credential');
        }
        if(!$user->is_sso){
            return $this->sendError('Invalid credential');
        }
        //update image
        if($response['picture']){
            $user->photo = $response['picture'];
            $user->save();
        }

        $token = Auth::login($user);

        $user = User::with('role')->where('id',$user->id)->first();

        Auth::setToken($token);

        $token = (string) Auth::getToken();
        $expiration = Auth::getPayload()->get('exp');

        $logs = new UserLog();
        $logs->user_id = $user->id;
        $logs->log = "{$user->name} login to system via google";
        $logs->ip = $request->ip();
        $logs->save();

        return $this->sendResponse(
            [
                'user' => $user,
                'token' => $token,
                'token_type' => 'bearer',
                'expires_in' => $expiration - time(),
            ],'success'
        );
    }

    public function register(Request $request)
    {
        //cek confirm password
        if($request->password != $request->password_confirm){
            return $this->sendError('Confirm password is not wrong!');
        }
        //cek nik
        $user = User::where('email',$request->email)->first();
        if($user){
            return $this->sendError('Email has been registered');
        }

        //get pricing
        $credit = 200;
        $pricing = Pricing::where('company_id',$request->company_id)->where('price',0)->first();
        if($pricing){
            $credit = $pricing->credit;
        }
        //register
        $data = new User;
        $data->name = $request->name;
        $data->email = $request->email;
        $data->password = bcrypt($request->password);
        $data->role_id = 3;
        $data->company_id = $request->company_id;
        $data->status = 1;
        $data->is_new = true;
        
        if (!$data->save()) {
            return $this->sendError(__('response.FailedStoreData'));
        }

        //create user credit
        $userCredit = new UserCredit();
        $userCredit->user_id = $data->id;
        $userCredit->credit = $credit;
        $userCredit->save();
        //create credit history
        $creditHistory = new UserCreditDetail();
        $creditHistory->user_id = $data->id;
        $creditHistory->credit = $credit;
        $creditHistory->type = "New user free credits.";
        $creditHistory->save();

        $token = Auth::login($data);

        $user = User::with('role')->where('id',$data->id)->first();

        Auth::setToken($token);

        $token = (string) Auth::getToken();
        $expiration = Auth::getPayload()->get('exp');

        return $this->sendResponse(
            [
                'user' => $user,
                'token' => $token,
                'token_type' => 'bearer',
                'expires_in' => $expiration - time(),
            ], __('response.SuccessGetData'), 200
        );
    }

    public function verify(Request $request)
    {
        if($request->password != $request->password_confirmation){
            return response()->json(['message' => 'Confirmation Password is incorrect!'],500);
        }
        $user_id = decrypt($request->token);
        //update customer
        $user = User::where('id', $user_id)->update([
            'status' => 1,
            'password' => bcrypt($request->password)
        ]);

        return $this->sendResponse(
            [
            'status' => 'Akun berhasil diverifiikasi',
            ], __('response.SuccessGetData'), 200
        );
    }

    public function verifyCheck($token)
    {
        $data = User::where('verify_token',$token)->first();
        if(!$data) return $this->sendError('not-eligible');

        if($data->status == 1) return $this->sendError('not-eligible');

        return $this->sendResponse(
            [
            'status' => 'Success',
            ], __('response.SuccessGetData'), 200
        );
    }

    public function resetCheck($token)
    {
        $data = User::where('forgot_token',$token)->first();
        if(!$data) return $this->sendError('not-eligible');
        
        $token = decrypt($token);
        $now = Carbon::now()->setTimezone('Asia/Jakarta');
        $exp = Carbon::parse($token->expired);

        if($now->gt($exp)) return $this->sendError('not-eligible');

        return $this->sendResponse(
            [
            'status' => 'Success',
            ], __('response.SuccessGetData'), 200
        );
    }

    public function logout(Request $request){
        $logs = new UserLog();
        $logs->user_id = $request->user()->id;
        $logs->log = "{$request->user()->name} logout from system";
        $logs->ip = $request->ip();
        $logs->save();

        Auth::logout();
       
        return response()->json('ok');
    }

    public function forgot(Request $request)
    {
        $user = User::where('email',$request->email)->first();
        if(!$user){
            return $this->sendResponse(
                [], 'Success', 200
            );
        }
        $expired = Carbon::now()->addMinutes(60)->setTimezone('Asia/Jakarta')->format('Y-m-d H:m');
        $tokenParam = (object)[
            'id' => $user->id,
            'expired' => $expired
        ];
        
        $token = encrypt($tokenParam);
        //update token
        $updateToken = User::where('id',$user->id)->update(['forgot_token' => $token]);
        $details = [
            'email' => $request->email,
            'subject' => 'Forgot Password',
            'view' => 'email.user-forgot',
            'data' => [
                'user' => $user,
                'link' => url('/reset/'.$token)
            ],
        ];
        // SendEmail::dispatch($details);
        $email = new EmailForQueuing($details);
        Mail::send($email);


        return $this->sendResponse(
            [], 'Success', 200
        );
    }

    public function reset(ResetRequest $request)
    {
        //check token
        $user = User::where('forgot_token',$request->token)->first();
        if(!$user){
            return $this->sendResponse(
                [], 'Invalid Token', 419
            );
        }
        $token = decrypt($request->token);
        $now = Carbon::now()->setTimezone('Asia/Jakarta');
        $exp = Carbon::parse($token->expired);

        if($now->gt($exp)) return $this->sendError('not-eligible');
        //update user password
        $user = User::where('id',$user->id)->update([
            'password' => bcrypt($request->password)
        ]);

        return $this->sendResponse(
            [], 'Success', 200
        );
    }

    public function checkSaas(Request $request){
        $data = CompanySaasSetting::with('adaptive_feedback')->where('website_url',$request->url)->first();
        return $this->sendResponse(
            ['data' => $data], 'Success', 200
        );
    }
}
