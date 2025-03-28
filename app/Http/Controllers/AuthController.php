<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Agent;
use App\Models\Log;
use App\Models\MerchantInfo;
use App\Models\MerchantWallet;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    private function saveLog($event, $description, $ip = null, $userAgent = null){
        Log::create([
            'log_user_id' => Session::get('userId'),
            'log_user_type' => Session::get('userType'),
            'log_event_type' => $event,
            'log_description' => $description,
            'log_ip_address' => $ip,
            'log_user_agent' => $userAgent,
        ]);
    }
    public function loginSubmit(Request $request)
    {
        if (Session::has('is_loggedin') && Session::get('is_loggedin')) {
            return redirect()->to('/dashboard')->with('info', 'Please logout before logging in!');
        }
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'Email is required!',
            'email.email' => 'Please enter a valid email!',
            'password.required' => 'Password is required!',
        ]);
        try {
            $user = Admin::where('admin_email', $request->email)->where('admin_status', '!=', 'Deleted')->first();
            $type = $user ? $user->admin_type : 'Merchant';
            if (!$user) {
                $user = MerchantInfo::where('merchant_email', $request->email)->where('merchant_status', '!=', 'Deleted')->first();
                if (!$user) {
                    $user = Agent::where('email',$request->email)->where('status','active')->first();
                    if(!$user){
                        $logDescription = [
                            'message' => "Email: ".$request->email.' & Password: '.$request->password.'. No such user found!'
                        ];
                        $this->saveLog('Login Failed',json_encode($logDescription), ip:$request->ip(), userAgent:$request->userAgent());
                        return redirect()->back()->with('error', 'Email or password is incorrect!');
                    }
                    $check = Hash::check($request->password, $user->password);
                    
                    if($check){
                        $type = "Agent";
                        Session::put([
                            'is_loggedin' => true,
                            'userType' => $type,
                            'userId' => $user->id,
                            'userName' => $user->name,
                        ]);
                        $this->saveLog('Login Successful', 'Agent login successful', ip:$request->ip(), userAgent:$request->userAgent());
                        return redirect()->to('agent/dashboard')->with('success', 'Login successful!');
                    }else{
                        return redirect()->back()->with('error','Invalid login details!');
                    }
                }
            }
            $passwordField = $type == 'Merchant' ? 'merchant_password' : 'admin_password';
            $statusField = $type == 'Merchant' ? 'merchant_status' : 'admin_status';
            if (Hash::check($request->password, $user->$passwordField) || $user->$statusField == 'Blocked') {
                Session::put([
                    'is_loggedin' => true,
                    'userType' => $type,
                    'userId' => $type == 'Merchant' ? $user->merchant_id : $user->admin_id,
                    'userName' => $type == 'Merchant' ? $user->merchant_name : $user->admin_name,
                    'userPic' => $type == 'Merchant' ? $user->merchant_profile : $user->admin_profile_pic,
                ]);
                $logDescription = [
                    'message' => 'Login Success'
                ];
                $this->saveLog('Login Success',json_encode($logDescription), ip:$request->ip(), userAgent:$request->userAgent());
                return redirect()->to('/dashboard')->with('success', 'Login successful!');
            }
            $logDescription = [
                'message' => "Email: ".$request->email.' & Password: '.$request->password.'. Password is incorrect!'
            ];
            $this->saveLog(event: 'Login Failed',description: json_encode($logDescription), ip:$request->ip(), userAgent:$request->userAgent());
            return redirect()->back()->with('error', 'Email or password is incorrect!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
    public function navigateToDashboard()
    {
        if (Session::get('is_loggedin') && Session::has('userType')) {
            // return Session::get('userType') === 'Merchant' ? redirect()->to('/merchant/dashboard') : redirect()->to('/admin/dashboard');
            if(Session::get('userType') === 'Merchant'){
                return redirect()->to('/merchant/dashboard');
            }elseif(Session::get('userType') === 'Agent'){
                return redirect()->to('/agent/dashboard');
            }elseif(Session::get('userType') === 'Super Admin' || Session::get('userType') === 'Admin'){
                return redirect()->to('/admin/dashboard');
            }
        }else{
            return redirect()->to('login');
        }
    }
}
