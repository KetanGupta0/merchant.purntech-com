<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\AgentBeneficiary;
use App\Models\AgentWallet;
use App\Models\AgentWalletTransaction;
use App\Models\Beneficiary;
use App\Models\BusinessDetail;
use App\Models\KYCDocument;
use App\Models\Log;
use App\Models\MerchantInfo;
use App\Models\MerchantWallet;
use App\Models\WalletTransaction;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AgentController extends Controller
{
    private function saveLog($event, $description, $ip = null, $userAgent = null)
    {
        Log::create([
            'log_user_id' => Session::get('userId'),
            'log_user_type' => Session::get('userType'),
            'log_event_type' => $event,
            'log_description' => $description,
            'log_ip_address' => $ip,
            'log_user_agent' => $userAgent,
        ]);
    }

    private function checkLogin()
    {
        if (Session::has('is_loggedin') && Session::get('is_loggedin') && Session::has('userType') && Session::get('userType') === "Agent") {
            return true;
        } else {
            return false;
        }
    }

    private function dashboardPage($pageName, $data = [])
    {
        $wallet = AgentWallet::select('balance')->where('agent_id', Session::get('userId'))->first();
        $balance = $wallet ? $wallet->balance : 0;
        return view('same.header', compact('balance')) . view($pageName, $data) . view('same.footer');
    }

    public function dashbaordView()
    {
        if (!$this->checkLogin()) {
            return redirect()->to('login')->with('error', 'Login is required!');
        }
        $agentId = Session::get('userId');
        $agent = Agent::find($agentId);
        $agentPayinCommision = MerchantInfo::where('agent_id', $agentId)
            ->leftJoin('wallet_transactions', 'merchant_infos.merchant_id', '=', 'wallet_transactions.merchant_id')
            ->where('wallet_transactions.type', 'credit')
            ->where('wallet_transactions.visibility', 'visible')
            ->where('wallet_transactions.created_at', '>=', $agent->created_at)
            ->whereIn('wallet_transactions.status', ['completed', 'successful'])
            ->sum('wallet_transactions.pt_agent_commission');

        $agentPayOutCommision = MerchantInfo::where('agent_id', $agentId)
            ->leftJoin('wallet_transactions', 'merchant_infos.merchant_id', '=', 'wallet_transactions.merchant_id')
            ->where('wallet_transactions.type', 'debit')
            ->where('wallet_transactions.visibility', 'visible')
            ->where('wallet_transactions.created_at', '>=', $agent->created_at)
            ->whereIn('wallet_transactions.status', ['completed', 'successful'])
            ->sum('wallet_transactions.pt_agent_commission');
        $totalCommission = $agentPayinCommision + $agentPayOutCommision;
        return $this->dashboardPage('agent.dashboard', compact('agentPayinCommision', 'agentPayOutCommision', 'totalCommission'));
    }

    public function merchantTransactions()
    {
        if (!$this->checkLogin()) {
            return redirect()->to('login')->with('error', 'Login is required!');
        }
        return $this->dashboardPage('agent.merchant-transaction');
    }

    // public function agentSetting()
    // {
    //     if(!$this->checkLogin()){
    //         return redirect()->to('login')->with('error','Login is required!');
    //     }
    //     // return $this->dashboardPage('agent.merchant-transaction');
    //     echo "Page is not ready!";
    // }



    // public function createAgent()
    // {
    //     $name = "Test agent";
    //     $mobile = "1234567890";
    //     $email = "agent@gmail.com";
    //     $password = "Hello@123";
    //     $status = "active";

    //     $hashedPassword = Hash::make($password);

    //     $agent = Agent::create([
    //         'name' => $name,
    //         'mobile' => $mobile,
    //         'email' => $email,
    //         'password' => $hashedPassword,
    //         'plain_password' => $password,
    //         'status' => $status,
    //     ]);

    //     if ($agent) {
    //         return response()->json([
    //             'message' => 'Agent created successfully.',
    //             'email' => $agent->email,
    //             'password' => $agent->plain_password,
    //         ], 200);
    //     }
    // }

    //added by Chandan Raj
    public function agentAccountView()
    {
        if (!$this->checkLogin()) {
            return redirect()->to('login')->with('error', 'Login is required!');
        }
        $agent = Agent::find(Session::get('userId'));
        return $this->dashboardPage('agent.account-details', compact('agent'));
    }

    public function agentSettingsView()
    {
        if (!$this->checkLogin()) {
            return redirect()->to('login')->with('error', 'Login is required!');
        }
        $agent = Agent::find(Session::get('userId'));
        return $this->dashboardPage('agent.settings', compact('agent'));
    }

    public function agentSettingsUpdate(Request $request)
    {
        if (!$this->checkLogin()) {
            return redirect()->to('login')->with('error', 'Login is required!');
        }
        $request->validate([
            'agent_profile' => 'nullable|mimes:png,jpg,jpeg,gif,svg,bmp|max:2048',
            'agent_password' => 'required', // Old password
            'agent_password_new' => 'nullable|min:8|different:agent_password', // New password must be different
            'agent_password_new_confirmed' => 'required_with:agent_password_new|same:agent_password_new',
        ], [
            'agent_profile.mimes' => 'The profile picture must be of type: PNG, JPG, JPEG, GIF, SVG, or BMP.',
            'agent_profile.max' => 'The profile picture size must not exceed 2MB.',
            'agent_password.required' => 'The old password is required.',
            'agent_password_new.min' => 'The new password must be at least 8 characters.',
            'agent_password_new.different' => 'The new password must be different from the old password.',
            'agent_password_new_confirmed.required_with' => 'Please confirm the new password.',
            'agent_password_new_confirmed.same' => 'The new password confirmation does not match.',
        ]);

        try {
            $agent = Agent::find(Session::get('userId'));
            if ($agent) {
                if (!Hash::check($request->agent_password, $agent->password)) {
                    return redirect()->back()->with('error', 'Password is not correct!');
                }
                $temp = $agent->replicate(['plain_password', 'agent_password']);
                if ($request->hasFile('agent_profile')) {
                    $file = $request->file('agent_profile');
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $file->move(public_path('uploads/agent/profile'), $filename);
                    $agent->agent_profile = $filename;
                }
                if ($request->agent_password_new) {
                    $agent->password = Hash::make($request->agent_password_new);
                    $agent->plain_password = $request->agent_password_new;
                }
                if ($agent->save()) {
                    Session::forget('userPic');
                    Session::put('userPic', $agent->agent_profile);
                    $logDescription = [
                        'pastInfo' => $temp,
                        'presentInfo' => $agent,
                        'message' => 'Profile updated successfully!'
                    ];
                    $this->saveLog('Profile Update', json_encode($logDescription), $request->ip(), $request->userAgent());
                    return redirect()->back()->with('success', 'Profile updated successfully!');
                } else {
                    $logDescription = [
                        'message' => 'Unable to updata profile data into database right now! Please try again after sometimes.'
                    ];
                    $this->saveLog('Profile Update', json_encode($logDescription), $request->ip(), $request->userAgent());
                    return redirect()->back()->with('error', 'Something went wrong! Please check activity log for more details.');
                }
            } else {
                $logDescription = [
                    'message' => 'Agent not found!'
                ];
                $this->saveLog('Profile Update', json_encode($logDescription), $request->ip(), $request->userAgent());
                return redirect()->back()->with('error', 'Something went wrong! Please check activity log for more details.');
            }
        } catch (Exception $e) {
            $logDescription = [
                'message' => $e->getMessage()
            ];
            $this->saveLog('Profile Update Exception', json_encode($logDescription), $request->ip(), $request->userAgent());
            return redirect()->back()->with('error', 'Something went wrong! Please check activity log for more details.');
        }
    }

    public function agentMerchantsView()
    {
        if (!$this->checkLogin()) {
            return redirect()->to('login')->with('error', 'Login is required!');
        }
        $merchants = MerchantInfo::where('agent_id', Session::get('userId'))->orderBy('merchant_id', 'DESC')->get();
        return $this->dashboardPage('agent.merchants', compact('merchants'));
    }

    public function agentPayoutView()
    {
        if (!$this->checkLogin()) {
            return redirect()->to('login')->with('error', 'Login is required!');
        }
        $beneficiaries = AgentBeneficiary::where('agent_id', Session::get('userId'))
            ->where('status', 'active')
            ->get();
        $wallet = AgentWallet::select('balance')->where('agent_id', Session::get('userId'))->first();
        $balance = $wallet ? $wallet->balance : 0;
        $transactions = AgentWalletTransaction::where('agent_id', Session::get('userId'))
            ->where('visibility', 'visible')
            ->orderBy('id', 'desc')
            ->get();
        $totalSuccessTransactions = AgentWalletTransaction::where('agent_id', Session::get('userId'))
            ->whereIn('status', ['completed', 'successful'])
            ->where('visibility', 'visible')
            ->sum('amount');
        $totalCommission = AgentWalletTransaction::where('agent_id', Session::get('userId'))
            ->whereIn('status', ['completed', 'successful'])
            ->where('visibility', 'visible')
            ->sum('charge');
        $totalFailedTransactions = AgentWalletTransaction::where('agent_id', Session::get('userId'))
            ->Where('status', 'failed')
            ->where('visibility', 'visible')
            ->sum('amount');
        $totalProcessingTransactions = AgentWalletTransaction::where('agent_id', Session::get('userId'))
            ->Where('status', 'processing')
            ->where('visibility', 'visible')
            ->sum('amount');
        $netAmount = $totalSuccessTransactions + $totalCommission;
        return $this->dashboardPage('agent.payout-agent', compact('beneficiaries', 'balance', 'transactions', 'totalSuccessTransactions','totalFailedTransactions', 'totalProcessingTransactions', 'totalCommission', 'netAmount'));
    }

    public function agentMerchantPayinView()
    {
        if (!$this->checkLogin()) {
            return redirect()->to('login')->with('error', 'Login is required!');
        }
        $merchants = MerchantInfo::select('merchant_id', 'acc_id', 'merchant_name')->where('agent_id', Session::get('userId'))->orderBy('merchant_name', 'ASC')->get();
        return $this->dashboardPage('agent.payin-merchant', compact('merchants'));
    }
    public function agentMerchantPayoutView()
    {
        if (!$this->checkLogin()) {
            return redirect()->to('login')->with('error', 'Login is required!');
        }
        $merchants = MerchantInfo::select('merchant_id', 'acc_id', 'merchant_name')->where('agent_id', Session::get('userId'))->orderBy('merchant_name', 'ASC')->get();
        return $this->dashboardPage('agent.payout-merchant', compact('merchants'));
    }

    public function fetchTransactionsPayout(Request $request)
    {
        $merchantId = $request->merchant_id;
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $agentId = Session::get('userId');
        $agent = Agent::find($agentId);
        // Query for fetching transactions
        $transactions = WalletTransaction::select('wallet_transactions.utr', 'wallet_transactions.acc_no', 'wallet_transactions.ifsc', 'wallet_transactions.beneficiary_name', 'transactions.*')
            ->leftJoin('transactions', 'wallet_transactions.transaction_id', '=', 'transactions.order_id')
            ->where('wallet_transactions.merchant_id', $merchantId)
            ->where('wallet_transactions.created_at', '>=', $agent->created_at)
            ->where('wallet_transactions.type', 'debit')
            ->where('wallet_transactions.visibility', 'visible');

        // Apply date filter if provided
        if ($startDate && $endDate) {
            $transactions->whereBetween('wallet_transactions.created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay(),
            ]);
        }

        $transactions = $transactions
            ->orderBy('wallet_transactions.id', 'desc')
            ->select('wallet_transactions.*', 'transactions.response_data', 'transactions.request_data')
            ->get();

        return response()->json($transactions);
    }
    public function fetchTransactionsPayin(Request $request)
    {
        $merchantId = $request->merchant_id;
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $agentId = Session::get('userId');
        $agent = Agent::find($agentId);
        // Query for fetching transactions
        $transactions = WalletTransaction::select('wallet_transactions.utr', 'wallet_transactions.acc_no', 'wallet_transactions.ifsc', 'wallet_transactions.beneficiary_name', 'transactions.*')
            ->leftJoin('transactions', 'wallet_transactions.transaction_id', '=', 'transactions.order_id')
            ->where('wallet_transactions.merchant_id', $merchantId)
            ->where('wallet_transactions.created_at', '>=', $agent->created_at)
            ->where('wallet_transactions.type', 'credit')
            ->where('wallet_transactions.visibility', 'visible');

        // Apply date filter if provided
        if ($startDate && $endDate) {
            $transactions->whereBetween('wallet_transactions.created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay(),
            ]);
        }

        $transactions = $transactions
            ->orderBy('wallet_transactions.id', 'desc')
            ->select('wallet_transactions.*', 'transactions.response_data', 'transactions.request_data')
            ->get();

        return response()->json($transactions);
    }

    public function agentLogsView()
    {
        if (!$this->checkLogin()) {
            return redirect()->to('login')->with('error', 'Login is required!');
        }
        $logs = Log::where('log_user_id', '=', Session::get('userId'))->where('log_user_type', '=', Session::get('userType'))->orderBy('created_at', 'desc')->get();
        return $this->dashboardPage('agent.logs', compact('logs'));
    }

    public function agentMerchantProfile(Request $request, $id)
    {
        if (!$this->checkLogin()) {
            return redirect()->to('login')->with('error', 'Login is required!');
        }
        try {
            $merchant = MerchantInfo::where('merchant_status', '!=', 'Deleted')->find($id);
            if (!$merchant) {
                return redirect()->back()->with('error', 'Merchant not found!');
            }
            // $business = BusinessDetail::where('business_merchant_id', '=', $merchant->merchant_id)->where('business_status', '!=', 'Deleted')->first();
            // if (!$business) {
            //     return redirect()->back()->with('error', 'Business details not found!');
            // }
            // $documents = KYCDocument::where('kyc_merchant_id', '=', $merchant->merchant_id)->where('kyc_business_id', '=', $business->business_id)->where('kyc_status', '!=', 'Deleted')->get();
            // if (!$documents) {
            //     return redirect()->back()->with('error', 'KYC documents not found!');
            // }
            return $this->dashboardPage('agent.merchants-profile', compact('merchant'));
        } catch (Exception $e) {
            $logDescription = [
                'message' => $e->getMessage()
            ];
            $this->saveLog('Merchant View Exception', json_encode($logDescription), $request->ip(), $request->userAgent());
            return redirect()->back()->with('error', 'Something went wrong! Please check the log for more details.');
        }
    }

    public function agentMerchantsPayinView(Request $request, $id)
    {
        if (!$this->checkLogin()) {
            return redirect()->to('login')->with('error', 'Login is required!');
        }
        $agentId = Session::get('userId');
        $agent = Agent::find($agentId);
        $transactions = WalletTransaction::leftJoin('transactions', 'wallet_transactions.transaction_id', '=', 'transactions.order_id')
            ->where('wallet_transactions.merchant_id', $id)
            ->where('wallet_transactions.type', 'credit')
            ->where('wallet_transactions.visibility', 'visible')
            ->where('wallet_transactions.created_at', '>=', $agent->created_at)
            ->orderBy('wallet_transactions.id', 'desc')
            ->select('wallet_transactions.*', 'transactions.response_data')
            ->get();
        $commision = WalletTransaction::where('merchant_id', $id)
            ->where('type', 'credit')
            ->where('visibility', 'visible')
            ->where('created_at', '>=', $agent->created_at)
            ->whereIn('status', ['completed', 'successful'])
            ->sum('charge');
        $agentPayinCommision = WalletTransaction::where('merchant_id', $id)
            ->where('type', 'credit')
            ->where('visibility', 'visible')
            ->where('created_at', '>=', $agent->created_at)
            ->whereIn('status', ['completed', 'successful'])
            ->sum('pt_agent_commission');
        $totalAmount = WalletTransaction::where('merchant_id', $id)
            ->where('type', 'credit')
            ->where('created_at', '>=', $agent->created_at)
            ->whereIn('status', ['completed', 'successful'])
            ->where('visibility', 'visible')
            ->sum('amount');
        $netAmount = $totalAmount - $commision;
        $totalTransactions = WalletTransaction::where('merchant_id', $id)
            ->where('type', 'credit')
            ->where('created_at', '>=', $agent->created_at)
            ->where('visibility', 'visible')
            ->count();
        $totalSuccessTransactions = WalletTransaction::where('merchant_id', $id)
            ->where('type', 'credit')
            ->where('created_at', '>=', $agent->created_at)
            ->whereIn('status', ['completed', 'successful'])
            ->where('visibility', 'visible')
            ->count();
        $merchantInfo = MerchantInfo::where('merchant_id', $id)->first();
        $merchantName = $merchantInfo->merchant_name;
        $successRate = (float)$totalTransactions > 0.00 ? ((float)$totalSuccessTransactions / (float)$totalTransactions) * 100.00 : 0.00;
        // $wallet = MerchantWallet::select('balance')->where('merchant_id', $id)->first();
        $balance = 0;
        return $this->dashboardPage('agent.merchants-payin', compact('transactions', 'balance', 'commision', 'totalAmount', 'netAmount', 'successRate', 'totalTransactions', 'totalSuccessTransactions', 'merchantName', 'agentPayinCommision'));
    }

    public function agentMerchantsPayoutView(Request $request, $id)
    {
        if (!$this->checkLogin()) {
            return redirect()->to('login')->with('error', 'Login is required!');
        }
        $agentId = Session::get('userId');
        $agent = Agent::find($agentId);
        $transactions = WalletTransaction::leftJoin('transactions', 'wallet_transactions.transaction_id', '=', 'transactions.order_id')
            ->where('wallet_transactions.merchant_id', $id)
            ->where('wallet_transactions.type', 'debit')
            ->where('wallet_transactions.visibility', 'visible')
            ->where('wallet_transactions.created_at', '>=', $agent->created_at)
            ->orderBy('wallet_transactions.id', 'desc')
            ->select('wallet_transactions.*', 'transactions.response_data', 'transactions.request_data')  // Added on 13-03-2025
            ->get();
        $beneficiaries = Beneficiary::where('merchant_id', $id)
            ->where('status', 'active')
            ->get();
        $totalSuccessTransactions = WalletTransaction::where('merchant_id', $id)
            ->where('type', 'debit')
            ->where('created_at', '>=', $agent->created_at)
            ->whereIn('status', ['completed', 'successful'])
            ->where('visibility', 'visible')
            ->sum('amount');
        $totalFailedTransactions = WalletTransaction::where('merchant_id', $id)
            ->where('type', 'debit')
            ->Where('status', 'failed')
            ->where('visibility', 'visible')
            ->where('created_at', '>=', $agent->created_at)
            ->sum('amount');
        $totalProcessingTransactions = WalletTransaction::where('merchant_id', $id)
            ->where('type', 'debit')
            ->Where('status', 'processing')
            ->where('visibility', 'visible')
            ->where('created_at', '>=', $agent->created_at)
            ->sum('amount');
        $agentPayoutCommision = WalletTransaction::where('merchant_id', $id)
            ->where('type', 'debit')
            ->where('visibility', 'visible')
            ->whereIn('status', ['completed', 'successful'])
            ->where('created_at', '>=', $agent->created_at)
            ->sum('pt_agent_commission');
        // $wallet = MerchantWallet::select('balance')
        //     ->where('merchant_id', Session::get('userId'))
        //     ->first();
        $merchantInfo = MerchantInfo::where('merchant_id', $id)->first();
        $merchantName = $merchantInfo->merchant_name;
        $balance = 0;
        return $this->dashboardPage('agent.merchants-payout', compact('transactions', 'balance', 'totalSuccessTransactions', 'totalFailedTransactions', 'totalProcessingTransactions', 'beneficiaries', 'merchantName', 'agentPayoutCommision'));
    }

    public function agentMerchantsTransactionsView(Request $request, $id)
    {
        if (!$this->checkLogin()) {
            return redirect()->to('login')->with('error', 'Login is required!');
        }
        $agentId = Session::get('userId');
        $agent = Agent::find($agentId);
        $transactions = WalletTransaction::where('merchant_id', $id)
            ->where('visibility', 'visible')
            ->where('created_at', '>=', $agent->created_at)
            ->orderBy('id', 'desc')->get();
        $merchantInfo = MerchantInfo::where('merchant_id', $id)->first();
        $merchantName = $merchantInfo->merchant_name;
        return $this->dashboardPage('agent.merchants-transactions', compact('transactions', 'merchantName'));
    }

    public function agentBeneficiariesView()
    {
        if (!$this->checkLogin()) {
            return redirect()->to('login')->with('error', 'Login is required!');
        }
        $beneficiaries = AgentBeneficiary::where('agent_id', Session::get('userId'))
            ->where('status', '!=', 'deleted')
            ->get();
        return $this->dashboardPage('agent.beneficiaries', compact('beneficiaries'));
    }

    public function addBeneficiary(Request $request)
    {
        if (!$this->checkLogin()) {
            return redirect()->to('login')->with('error', 'Login is required!');
        }
        $messages = [
            'name.required' => 'The name field is required.',
            'email.required' => 'The email field is required.',
            'email.email' => 'Enter a valid email address.',
            'email.unique' => 'This email is already registered.',
            'mobile.required' => 'The mobile field is required.',
            'mobile.numeric' => 'The mobile number must be numeric.',
            'mobile.digits_between' => 'The mobile number must be between 10 and 15 digits.',
            'mobile.unique' => 'This mobile number is already registered.',
            'account_no.required' => 'The account number field is required.',
            'account_no.numeric' => 'The account number must be numeric.',
            'account_no.unique' => 'This account number is already registered.',
            'ifsc.required' => 'The IFSC code is required.',
            'ifsc.regex' => 'Enter a valid IFSC code.',
            'bank_name.required' => 'The bank name field is required.',
            'pincode.numeric' => 'The pincode must be numeric.',
            'pincode.digits' => 'The pincode must be exactly 6 digits.',
        ];

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:beneficiaries,email',
            'mobile' => 'required|numeric|digits_between:10,15|unique:beneficiaries,mobile',
            'account_no' => 'required|numeric|unique:beneficiaries,account_no',
            'ifsc' => 'required|regex:/^[A-Z]{4}0[A-Z0-9]{6}$/',
            'bank_name' => 'required|string|max:255',
            'pincode' => 'nullable|numeric|digits:6',
        ];

        $validatedData = $request->validate($rules, $messages);

        $agent = Agent::find(Session::get('userId'));
        $agentId = $agent->id;
        if (!$agent) {
            return redirect()->back()->with('error', 'Agent ID is missing. Please log in again.');
        }


        // Insert into the database
        $beneficiary = AgentBeneficiary::create([
            'agent_id' => $agentId,
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'mobile' => $validatedData['mobile'],
            'account_no' => $validatedData['account_no'],
            'ifsc' => $validatedData['ifsc'],
            'bank_name' => $validatedData['bank_name'],
            'address' => $validatedData['address'] ?? null,
            'country' => $validatedData['country'] ?? null,
            'state' => $validatedData['state'] ?? null,
            'city' => $validatedData['city'] ?? null,
            'pincode' => $validatedData['pincode'] ?? null,
            'status' => 'pending', // Default pending status
        ]);

        return redirect()->back()->with('success', 'Beneficiary added successfully!');
    }

    public function updateBeneficiary(Request $request, $id)
    {
        if (!$this->checkLogin()) {
            return redirect()->to('login')->with('error', 'Login is required!');
        }
        $beneficiary = AgentBeneficiary::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:beneficiaries,email,' . $id,
            'mobile' => 'required|numeric|digits_between:10,15|unique:beneficiaries,mobile,' . $id,
            'account_no' => 'required|numeric|unique:beneficiaries,account_no,' . $id,
            'ifsc' => 'required|regex:/^[A-Z]{4}0[A-Z0-9]{6}$/',
            'bank_name' => 'required|string|max:255',
            'pincode' => 'nullable|numeric|digits:6',
        ]);

        $beneficiary->update($validatedData);

        return redirect()->back()->with('success', 'Beneficiary updated successfully!');
    }

    public function deleteBeneficiary($id)
    {
        if (!$this->checkLogin()) {
            return redirect()->to('login')->with('error', 'Login is required!');
        }
        $beneficiary = AgentBeneficiary::findOrFail($id);
        $beneficiary->delete();

        return redirect()->back()->with('success', 'Beneficiary deleted successfully!');
    }
}
