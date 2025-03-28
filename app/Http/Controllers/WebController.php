<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\MerchantInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class WebController extends Controller
{
    private function saveLog($event, $description, $userId = null, $userType = null, $ip = null, $userAgent = null){
        Log::create([
            'log_user_id' => $userId,
            'log_user_type' => $userType,
            'log_event_type' => $event,
            'log_description' => $description,
            'log_ip_address' => $ip,
            'log_user_agent' => $userAgent,
        ]);
    }
    private function page($pagename, $data = []){
        return view('header').view($pagename,$data).view('footer');
    }
    
    public function homeView(){
        return $this->page('welcome');
    }
    public function loginView(){
        if(Session::has('is_loggedin')){
            if(Session::get('is_loggedin')){
                return redirect()->to('/dashboard');
            }
        }
        return $this->page('login');
    }

    public function logout(Request $request){
        Session::flush();
        if (Session::has('is_loggedin') && Session::get('is_loggedin') && Session::has('userType') && Session::has('userId')){
            $this->saveLog(Session::get('userType').' Logout','Logout Successfull.',Session::get('userId'),Session::get('userType'),$request->ip(),$request->userAgent());
        }
        return redirect()->to('/login')->with('success', 'You have been logged out successfully!');
    }
  
  //added by Chandan Raj
    public function contactView(){
        return $this->page('contact');
    }
    
    public function aboutView(){
        return $this->page('about');
    }

    public function resourcesView(){
        return $this->page('resources');
    }

    public function merchantsView(){
        return $this->page('merchants');
    }

    public function paymentsView(){
        return $this->page('payments');
    }
    
}
