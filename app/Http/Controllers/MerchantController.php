<?php

namespace App\Http\Controllers;

use App\Models\AccountDetail;
use App\Models\Beneficiary;
use App\Models\BusinessDetail;
use App\Models\KYCDocument;
use App\Models\Log;
use App\Models\MerchantGateway;
use App\Models\MerchantInfo;
use App\Models\MerchantWallet;
use App\Models\TransactionLimit;
use App\Models\Settlement;
use App\Models\SettlementScheduel;
use App\Models\UrlWhiteListing;
use App\Models\WalletTransaction;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class MerchantController extends Controller
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
    private function checkLoginStatus()
    {
        if (Session::has('is_loggedin') && Session::has('userType') && Session::get('is_loggedin') && (Session::get('userType') == 'Merchant')) {
            return true;
        } else {
            return false;
        }
    }
    private function dashboardPage($pagename, $data = [])
    {
        if ($this->checkLoginStatus()) {
            $wallet = MerchantWallet::select('balance')->where('merchant_id', Session::get('userId'))->first();
            $balance = $wallet ? $wallet->balance : 0;
            $transaction_limits = TransactionLimit::where('merchant_id', Session::get('userId'))->first();
            return view('same.header', compact('balance')) . view($pagename, $data, compact('transaction_limits')) . view('same.footer');
        } else {
            return redirect()->to('/login')->with('error', 'Login is required!');
        }
    }
    private function generateAccountId($length = 30)
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $accountId = '';
        $maxIndex = strlen($characters) - 1;
        for ($i = 0; $i < $length; $i++) {
            $accountId .= $characters[random_int(0, $maxIndex)];
        }
        return $accountId;
    }
    private function generateOrderId($length = 10)
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        return substr(str_shuffle(str_repeat($characters, ceil($length / strlen($characters)))), 0, $length);
    }

    public function merchantDashboardView()
    {
        if (!$this->checkLoginStatus()) {
            return redirect()->to('/login')->with('error', 'Login is required!');
        }
        $merchant = MerchantInfo::select('merchant_is_verified')->find(Session::get('userId'));
        $wallet = MerchantWallet::where('merchant_id', Session::get('userId'))->first();
        $totalTransactions = WalletTransaction::where('merchant_id', Session::get('userId'))->where('type', 'credit')->where('visibility', 'visible')->count();
        $completedTransactions = WalletTransaction::where('merchant_id', Session::get('userId'))->whereIn('status', ['completed', 'successful'])->where('type', 'credit')->where('visibility', 'visible')->count();
        $creditedAmount = WalletTransaction::where('merchant_id', Session::get('userId'))->whereIn('status', ['completed', 'successful'])->where('type', 'credit')->where('visibility', 'visible')->sum('amount');
        $debitedAmount = WalletTransaction::where('merchant_id', Session::get('userId'))->whereIn('status', ['completed', 'successful'])->where('type', 'debit')->where('visibility', 'visible')->sum('amount');
        $successPercent = $totalTransactions > 0 ? ($completedTransactions / $totalTransactions) * 100 : 0;
        $merchantGateway = MerchantGateway::where('mid', Session::get('userId'))->first();
        $rollingCharge = MerchantInfo::select('rolling_charge')->find(Session::get('userId'));
        $settlementType = MerchantInfo::select('settlement_type')->find(Session::get('userId'));
        $settlementSchedules = SettlementScheduel::where('status', 'active')->where('type', $settlementType->settlement_type)->get();
        return $this->dashboardPage('merchant.dashboard', compact(
            'merchant',
            'wallet',
            'totalTransactions',
            'creditedAmount',
            'debitedAmount',
            'successPercent',
            'completedTransactions',
            'merchantGateway',
            'rollingCharge',
            'settlementSchedules'
        ));
    }

    public function merchantAccountDetailsView()
    {
        if (!$this->checkLoginStatus()) {
            return redirect()->to('/login')->with('error', 'Login is required!');
        }
        $account = AccountDetail::where('acc_merchant_id', '=', Session::get('userId'))->where('acc_status', '!=', 'Deleted')->first();
        return $this->dashboardPage('merchant.account-details', compact('account'));
    }
    
    public function merchantAccountDetailsUpdate(Request $request)
    {
        if (!$this->checkLoginStatus()) {
            return redirect()->to('/login')->with('error', 'Login is required!');
        }
        // dd($request);exit;
        $request->validate([
            'merchant_id' => 'required|exists:merchant_infos,merchant_id',
            'business_id' => 'nullable|exists:business_details,business_id',
            'acc_bank_name' => 'required|string|max:255',
            'acc_branch_name' => 'nullable|string|max:255', // Optional
            'acc_account_number' => 'required|digits_between:8,20', // Must be 8-20 digits
            'acc_ifsc_code' => [
                'required',
                'regex:/^[A-Z]{4}0[A-Z0-9]{6}$/', // Matches standard IFSC code pattern
                'max:11'
            ],
            'acc_micr_code' => 'nullable|digits:9', // Optional, must be 9 digits if provided
            'acc_swift_code' => 'nullable|string|max:11', // Optional, max length 11 characters
            'acc_account_type' => 'required|in:Business,Current,Savings,Other',
            'network_type' => 'required|string',
            'wallet_address' => 'required|string',
        ], [
            'merchant_id.required' => 'Merchant ID is required.',
            'merchant_id.exists' => 'Invalid Merchant ID.',
            'business_id.exists' => 'Invalid Business ID.',
            'acc_bank_name.required' => 'The Bank Name is required.',
            'acc_bank_name.string' => 'The Bank Name must be a valid string.',
            'acc_bank_name.max' => 'The Bank Name must not exceed 255 characters.',
            'acc_branch_name.string' => 'The Branch Name must be a valid string.',
            'acc_branch_name.max' => 'The Branch Name must not exceed 255 characters.',
            'acc_account_number.required' => 'The Account Number is required.',
            'acc_account_number.digits_between' => 'The Account Number must be between 8 and 20 digits.',
            'acc_ifsc_code.required' => 'The IFSC Code is required.',
            'acc_ifsc_code.regex' => 'The IFSC Code must be a valid 11-character code.',
            'acc_ifsc_code.max' => 'The IFSC Code must not exceed 11 characters.',
            'acc_micr_code.digits' => 'The MICR Code must be exactly 9 digits.',
            'acc_swift_code.string' => 'The Swift Code must be a valid string.',
            'acc_swift_code.max' => 'The Swift Code must not exceed 11 characters.',
            'acc_account_type.required' => 'The Account Type is required.',
            'acc_account_type.in' => 'The Account Type must be one of Business, Current, Savings, or Other.',
            'network_type.required' => 'Network Type is required.',
            'network_type.string' => 'Network Type must be a valid string.',
            'wallet_address.required' => 'Wallet Address is required.',
            'wallet_address.string' => 'Wallet Address must be a valid string.',
        ]);
        try {
            $merchant = MerchantInfo::find($request->merchant_id);
            if ($merchant) {
                $business = BusinessDetail::where('business_merchant_id', '=', $merchant->merchant_id)->where('business_status', '=', 'Active')->first();
                if ($business) {
                    $account = AccountDetail::where('acc_merchant_id', '=', $request->merchant_id)
                        ->where('acc_business_id', '=', $request->business_id)
                        ->where('acc_account_number', '=', $request->acc_account_number)
                        ->where('acc_status', '!=', 'Deleted')
                        ->first();
                    $temp = null;
                    if (!$account) {
                        $account = new AccountDetail();
                        $account->acc_status = 'Inactive';
                        $account->acc_merchant_id = $merchant->merchant_id;
                        $account->acc_business_id = $business->business_id;
                        $account->acc_bank_name = $request->acc_bank_name;
                        $account->acc_account_number = $request->acc_account_number;
                        $account->acc_ifsc_code = $request->acc_ifsc_code;
                        $account->acc_account_type = $request->acc_account_type;
                    } else {
                        $temp = $account->replicate();
                    }
                    $account->acc_branch_name = $request->acc_branch_name;
                    $account->acc_micr_code = $request->acc_micr_code;
                    $account->acc_swift_code = $request->acc_swift_code;
                    $account->network_type = $request->network_type;
                    $account->wallet_address = $request->wallet_address;
                    if ($account->save()) {
                        $logDescription = [
                            'pastInfo' => $temp,
                            'presentInfo' => $account,
                            'message' => $temp ? "Account updated successfully!" : "Account created successfully!"
                        ];
                        $this->saveLog('Account Details Update', json_encode($logDescription), $request->ip(), $request->userAgent());
                        return redirect()->back()->with('success', $temp ? "Account updated successfully!" : "Account created successfully!");
                    } else {
                        $logDescription = [
                            'message' => "Unable to save/update data into database!"
                        ];
                        $this->saveLog('Account Details Update', json_encode($logDescription), $request->ip(), $request->userAgent());
                        return redirect()->back()->with('error', 'An unecpected error occured! Please try after sometimes.');
                    }
                } else {
                    $logDescription = [
                        'message' => 'Business info not found!'
                    ];
                    $this->saveLog('Account Details Update', json_encode($logDescription), $request->ip(), $request->userAgent());
                    return redirect()->back()->with('error', 'An unecpected error occured! Please try after sometimes.');
                }
            } else {
                $logDescription = [
                    'message' => 'Merchant info not found!'
                ];
                $this->saveLog('Account Details Update', json_encode($logDescription), $request->ip(), $request->userAgent());
                return redirect()->back()->with('error', 'An unecpected error occured! Please try after sometimes.');
            }
        } catch (Exception $e) {
            $logDescription = [
                'message' => $e->getMessage()
            ];
            $this->saveLog('Account Details Update Exception', json_encode($logDescription), $request->ip(), $request->userAgent());
            return redirect()->back()->with('error', 'Something went wrong! Please check activity log for more details.');
        }
    }

    public function merchantUrlWhitelistingView()
    {
        if (!$this->checkLoginStatus()) {
            return redirect()->to('/login')->with('error', 'Login is required!');
        }
        $urls = UrlWhiteListing::where('uwl_merchant_id', '=', Session::get('userId'))->where('uwl_status', '!=', 'Deleted')->get();
        return $this->dashboardPage('merchant.url-white-listing', compact('urls'));
    }
    public function merchantUrlWhitelistingRequest(Request $request, $type)
    {
        if (!$this->checkLoginStatus()) {
            if ($request->ajax() || $request->isXmlHttpRequest()) {
                return response()->json(['message' => 'Login is required!'], 400);
            }
            return redirect()->to('/login')->with('error', 'Login is required!');
        }
        switch ($type) {
            case 'new':
                $request->validate(
                    [
                        'uwl_url' => 'required|url',
                        'uwl_ip_address' => 'nullable|ip',
                        'uwl_environment' => 'required|in:Production,UAT',
                    ],
                    [
                        'uwl_url.required' => 'The Requested URL is required.',
                        'uwl_url.url' => 'The Requested URL must be a valid URL in the format: https://example.com or https://www.example.com.',
                        'uwl_ip_address.ip' => 'The IP Address must be a valid IP address.',
                        'uwl_environment.required' => 'Please select an environment.',
                        'uwl_environment.in' => 'The Environment must be either "Production" or "UAT".',
                    ]
                );
                try {
                    $url = UrlWhiteListing::where('uwl_merchant_id', '=', Session::get('userId'))->where('uwl_url', '=', $request->uwl_url)->first();
                    if ($url) {
                        return redirect()->back()->with('error', 'Requested URL already exists!');
                    } else {
                        $url = new UrlWhiteListing();
                        $url->uwl_merchant_id = Session::get('userId');
                        $url->uwl_url = $request->uwl_url;
                        $url->uwl_ip_address = $request->uwl_ip_address;
                        $url->uwl_environment = $request->uwl_environment;
                        $url->uwl_status = 'Inactive';
                        if ($url->save()) {
                            return redirect()->back()->with('success', 'URL White Listing request added successfully!');
                        } else {
                            return redirect()->back()->with('error', 'Unable to complete your request right now! Please try again later.');
                        }
                    }
                } catch (Exception $e) {
                    $logDescription = [
                        'message' => $e->getMessage()
                    ];
                    $this->saveLog('URL White Listing Exception', json_encode($logDescription), $request->ip(), $request->userAgent());
                    return redirect()->back()->with('error', 'Something went wrong! Please check activity log for more details.');
                }
                break;
            case 'delete':
                if (!$request->ajax()) {
                    return response()->json(['message' => 'Invalid request. Only AJAX requests are allowed for delete.'], 403);
                }
                $request->validate([
                    'uwl_id' => 'required|numeric',
                ], [
                    'uwl_id.required' => 'Something went wrong! Please reload the page and try again.',
                    'uwl_id.numeric' => 'Something went wrong! Please reload the page and try again.',
                ]);
                try {
                    $url = UrlWhiteListing::where('uwl_status', '!=', 'Deleted')->find($request->uwl_id);
                    if ($url) {
                        $url->uwl_status = 'Deleted';
                        if ($url->save()) {
                            return response()->json(['message' => 'URL deleted successfully.', 'status' => true], 200);
                        } else {
                            return response()->json(['message' => 'Unable to complete your request right now! Please try again later.'], 400);
                        }
                    } else {
                        return response()->json(['message' => 'Requested URL not found or already deleted! Please reload the page and try again.'], 404);
                    }
                } catch (Exception $e) {
                    $logDescription = [
                        'message' => $e->getMessage(),
                    ];
                    $this->saveLog('URL White Listing Delete Exception', json_encode($logDescription), $request->ip(), $request->userAgent());

                    return response()->json(['message' => 'Something went wrong! Please check activity log for more details.'], 500);
                }
                break;
            default:
                if ($request->ajax() || $request->isXmlHttpRequest()) {
                    return response()->json(['message' => 'Invalid request type.'], 400);
                }
                return redirect()->back()->with('error', 'Invalid request type.');
        }
    }

    public function merchantSettlementReportsView()
    {
        if (!$this->checkLoginStatus()) {
            return redirect()->to('/login')->with('error', 'Login is required!');
        }
        $settlement = Settlement::where('merchant_id', Session::get('userId'))->orderBy('created_at', 'DESC')->get();
        return $this->dashboardPage('merchant.settlement-report', compact('settlement'));
    }
    public function merchantSettingsView()
    {
        if (!$this->checkLoginStatus()) {
            return redirect()->to('/login')->with('error', 'Login is required!');
        }
        $merchant = MerchantInfo::find(Session::get('userId'));
        return $this->dashboardPage('merchant.settings', compact('merchant'));
    }
    public function merchantSettingsUpdate(Request $request)
    {
        if (!$this->checkLoginStatus()) {
            return redirect()->to('/login')->with('error', 'Login is required!');
        }
        $request->validate([
            'merchant_phone2' => 'nullable|numeric|digits:10',
            'merchant_zip' => 'nullable|numeric|digits:6',
            'merchant_profile' => 'nullable|mimes:png,jpg,jpeg,gif,svg,bmp|max:2048',
            'merchant_password' => 'required', // Old password
            'merchant_password_new' => 'nullable|min:8|different:merchant_password', // New password must be different
            'merchant_password_new_confirmed' => 'required_with:merchant_password_new|same:merchant_password_new',
        ], [
            'merchant_phone2.numeric' => 'The alternate phone number must be a numeric value.',
            'merchant_phone2.digits' => 'The alternate phone number must be exactly 10 digits.',
            'merchant_zip.numeric' => 'The zip code must be a numeric value.',
            'merchant_zip.digits' => 'The zip code must be exactly 6 digits.',
            'merchant_profile.mimes' => 'The profile picture must be of type: PNG, JPG, JPEG, GIF, SVG, or BMP.',
            'merchant_profile.max' => 'The profile picture size must not exceed 2MB.',
            'merchant_password.required' => 'The old password is required.',
            'merchant_password_new.min' => 'The new password must be at least 8 characters.',
            'merchant_password_new.different' => 'The new password must be different from the old password.',
            'merchant_password_new_confirmed.required_with' => 'Please confirm the new password.',
            'merchant_password_new_confirmed.same' => 'The new password confirmation does not match.',
        ]);

        try {
            $merchant = MerchantInfo::find(Session::get('userId'));
            if ($merchant) {
                if (!Hash::check($request->merchant_password, $merchant->merchant_password)) {
                    return redirect()->back()->with('error', 'Password is not correct!');
                }
                $temp = $merchant->replicate(['merchant_plain_password', 'merchant_password']);
                if ($request->hasFile('merchant_profile')) {
                    $file = $request->file('merchant_profile');
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $file->move(public_path('uploads/merchant/profile'), $filename);
                    $merchant->merchant_profile = $filename;
                }
                $merchant->merchant_phone2 = $request->merchant_phone2;
                $merchant->merchant_city = $request->merchant_city;
                $merchant->merchant_state = $request->merchant_state;
                $merchant->merchant_country = $request->merchant_country;
                $merchant->merchant_zip = $request->merchant_zip;
                $merchant->merchant_landmark = $request->merchant_landmark;
                if ($request->merchant_password_new) {
                    $merchant->merchant_password = Hash::make($request->merchant_password_new);
                    $merchant->merchant_plain_password = $request->merchant_password_new;
                }
                if ($merchant->save()) {
                    Session::forget('userPic');
                    Session::put('userPic', $merchant->merchant_profile);
                    $logDescription = [
                        'pastInfo' => $temp,
                        'presentInfo' => $merchant,
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
                    'message' => 'Merchant not found!'
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
    public function merchantLogsView()
    {
        if (!$this->checkLoginStatus()) {
            return redirect()->to('/login')->with('error', 'Login is required!');
        }
        $logs = Log::where('log_user_id', '=', Session::get('userId'))->where('log_user_type', '=', Session::get('userType'))->orderBy('created_at', 'desc')->get();
        return $this->dashboardPage('merchant.logs', compact('logs'));
    }

    // File Break
    private function page($pagename, $data = [])
    {
        return view('header') . view($pagename, $data) . view('footer');
    }
    public function merchantOnboardingView()
    {
        return $this->page('Onboarding.index');
    }

    public function merchantOnboardingStepsAJAX(Request $request, $id)
    {
        switch ($id) {
            case 1:
                $request->validate([
                    'merchant_name' => 'required',
                    'merchant_phone' => 'required|numeric|digits:10',
                    'merchant_email' => 'required|email',
                    'merchant_aadhar_no' => 'required|numeric|digits:12',
                    'merchant_pan_no' => 'required|min:10|max:10',
                    'merchant_password' => 'required|min:6',
                    'merchant_confirm_password' => 'required|same:merchant_password'
                ], [
                    'merchant_name.required' => 'The merchant name field is required.',
                    'merchant_phone.required' => 'The phone number field is required.',
                    'merchant_phone.numeric' => 'The phone number must be numeric.',
                    'merchant_phone.digits' => 'The phone number must be exactly 10 digits.',
                    'merchant_email.required' => 'The email field is required.',
                    'merchant_email.email' => 'Please enter a valid email address.',
                    'merchant_aadhar_no.required' => 'The Aadhar number field is required.',
                    'merchant_aadhar_no.numeric' => 'The Aadhar number must be numeric.',
                    'merchant_aadhar_no.digits' => 'The Aadhar number must be exactly 12 digits.',
                    'merchant_pan_no.required' => 'The PAN number field is required.',
                    'merchant_pan_no.min' => 'The PAN number must be exactly 10 characters.',
                    'merchant_pan_no.max' => 'The PAN number must be exactly 10 characters.',
                    'merchant_password.required' => 'The password field is required.',
                    'merchant_password.min' => 'The password must be at least 6 characters.',
                    'merchant_confirm_password.required' => 'The confirm password field is required.',
                    'merchant_confirm_password.same' => 'The confirm password must match the password.'
                ]);
                try {
                    $uniqueChecks = [
                        'merchant_aadhar_no' => 'Aadhar number',
                        'merchant_pan_no' => 'PAN number',
                        'merchant_email' => 'Email',
                        'merchant_phone' => 'Mobile'
                    ];
                    foreach ($uniqueChecks as $field => $fieldName) {
                        $existingRecord = MerchantInfo::where($field, $request->input($field))->first();
                        if ($existingRecord) {
                            if ($existingRecord->merchant_is_onboarded === "Yes") {
                                return response()->json(['message' => "$fieldName is already registered!"], 400);
                            } elseif ($existingRecord->merchant_is_onboarded === "No") {
                                return response()->json(['status' => true, 'merchant_id' => $existingRecord->merchant_id]);
                            }
                        }
                    }
                    $hashedPassword = Hash::make($request->merchant_password);
                    $acc_id = $this->generateAccountId();
                    $check = MerchantInfo::create([
                        'acc_id' => $acc_id,
                        'merchant_name' => $request->merchant_name,
                        'merchant_phone' => $request->merchant_phone,
                        'merchant_email' => $request->merchant_email,
                        'merchant_aadhar_no' => $request->merchant_aadhar_no,
                        'merchant_pan_no' => $request->merchant_pan_no,
                        'merchant_password' => $hashedPassword,
                        'merchant_plain_password' => $request->merchant_password,
                    ]);
                    if ($check) {
                        MerchantWallet::create([
                            'merchant_id' => $check->merchant_id,
                            'balance' => 0.0
                        ]);
                        return response()->json(['status' => true, 'merchant_id' => $check->merchant_id]);
                    } else {
                        return response()->json(['message' => 'Unable to process merchant data!'], 400);
                    }
                } catch (Exception $e) {
                    return response()->json(['message', $e->getMessage()], 400);
                }
                break;
            case 2:
                $request->validate([
                    'business_name' => 'required',
                    'business_type' => 'required',
                    'business_address' => 'required',
                    'business_website' => 'required',
                    'merchant_id' => 'required|numeric',
                    'business_id' => 'sometimes|numeric'
                ], [
                    'business_name.required' => 'The business name field is required.',
                    'business_type.required' => 'The business type field is required.',
                    'business_address.required' => 'The business address field is required.',
                    'business_website.required' => 'The business website field is required.',
                    'merchant_id.required' => 'Something went wrong!',
                    'merchant_id.numeric' => 'Something went wrong!',
                    'business_id.numeric' => 'Something went wrong!'
                ]);
                if (isset($request->business_id) && $request->business_id != 0) {
                    $business = BusinessDetail::where('business_merchant_id', '=', $request->merchant_id)->where('business_status', '=', 'Active')->find($request->business_id);
                } else {
                    $business = BusinessDetail::where('business_merchant_id', '=', $request->merchant_id)->where('business_status', '=', 'Active')->first();
                    if (!$business) {
                        $business = new BusinessDetail();
                    }
                }
                $business->business_merchant_id = $request->merchant_id;
                $business->business_name = $request->business_name;
                $business->business_type = $request->business_type;
                $business->business_address = $request->business_address;
                $business->business_website = $request->business_website;
                if ($business->save()) {
                    return response()->json(['status' => true, 'merchant_id' => $request->merchant_id, 'business_id' => $business->business_id]);
                } else {
                    return response()->json(['message' => 'Unable to process business details!'], 400);
                }
                break;
            case 3:
                try {
                    // Define business type
                    $businessType = $request->input('business_type');

                    // Validation rules based on business type
                    $rules = [
                        'gst' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',  // 2MB max
                        'msme' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
                        'merchant_id' => 'required|numeric',
                        'business_id' => 'required|numeric'
                    ];

                    if ($businessType !== 'Individual' && $businessType !== 'Solo Proprietorship') {
                        $rules['pan'] = 'required|file|mimes:jpeg,png,jpg,pdf|max:2048';
                        $rules['cin'] = 'required|file|mimes:jpeg,png,jpg,pdf|max:2048';
                    }

                    // Validate request
                    $request->validate($rules);

                    if ($request->merchant_id == 0 || $request->business_id == 0) {
                        return response()->json(['message' => 'Cannot process your request right now!'], 400);
                    }

                    $merchant = MerchantInfo::find($request->merchant_id);
                    if ($merchant && $merchant->merchant_is_onboarded == 'No') {
                        $merchant->merchant_is_onboarded = 'Yes';
                    } else {
                        return response()->json(['message' => 'Merchant is already onboarded or not found!'], 400);
                    }

                    foreach (['pan', 'cin', 'gst', 'msme'] as $field) {
                        if ($request->hasFile($field)) {
                            $document = new KYCDocument();
                            $file = $request->file($field);
                            $filename = time() . '_' . $file->getClientOriginalName();
                            $file->move(public_path('uploads/kyc/docs'), $filename);
                            $document->kyc_merchant_id = $request->merchant_id;
                            $document->kyc_business_id = $request->business_id;
                            $document->kyc_document_name = $filename;
                            $document->kyc_document_type = $field;
                            $document->kyc_document_path = 'uploads/kyc/docs';
                            $document->save();
                        }
                    }
                    $merchant->save();
                    return response()->json(true);
                } catch (Exception $exception) {
                    return response()->json(['message' => $exception->getMessage()], 400);
                }
                break;
            default:
                return response()->json(['message' => 'Unknown Step'], 400);
        }
    }

    public function merchantOnboardingDataCheckAJAX(Request $request, $type)
    {
        try {
            switch ($type) {
                case 'phone':
                    $data = MerchantInfo::select('merchant_id', 'merchant_name', 'merchant_phone', 'merchant_email', 'merchant_aadhar_no', 'merchant_pan_no')
                        ->where('merchant_phone', '=', $request->merchant_phone)
                        ->where('merchant_is_onboarded', '=', 'No')
                        ->first();
                    break;
                case 'email':
                    $data = MerchantInfo::select('merchant_id', 'merchant_name', 'merchant_phone', 'merchant_email', 'merchant_aadhar_no', 'merchant_pan_no')
                        ->where('merchant_email', '=', $request->merchant_email)
                        ->where('merchant_is_onboarded', '=', 'No')
                        ->first();
                    break;
                case 'aadhar':
                    $data = MerchantInfo::select('merchant_id', 'merchant_name', 'merchant_phone', 'merchant_email', 'merchant_aadhar_no', 'merchant_pan_no')
                        ->where('merchant_aadhar_no', '=', $request->merchant_aadhar_no)
                        ->where('merchant_is_onboarded', '=', 'No')
                        ->first();
                    break;
                case 'pan':
                    $data = MerchantInfo::select('merchant_id', 'merchant_name', 'merchant_phone', 'merchant_email', 'merchant_aadhar_no', 'merchant_pan_no')
                        ->where('merchant_pan_no', '=', $request->merchant_pan_no)
                        ->where('merchant_is_onboarded', '=', 'No')
                        ->first();
                    break;
                default:
                    return response()->json(['message' => 'Link not found!'], 404);
            }
            $businessData = BusinessDetail::select('business_id', 'business_name', 'business_type', 'business_address', 'business_website')
                ->where('business_merchant_id', '=', $data->merchant_id)
                ->first();
            return response()->json(['status' => true, 'data' => $data, 'businessData' => $businessData]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function merchantTransactionView()
    {
        if (!$this->checkLoginStatus()) {
            return redirect()->to('/login')->with('error', 'Login is required!');
        }
        $transactions = WalletTransaction::where('merchant_id', Session::get('userId'))->where('visibility', 'visible')->orderBy('id', 'desc')->get();
        return $this->dashboardPage('merchant.transactions', compact('transactions'));
    }
    public function developerSectionView()
    {
        if (!$this->checkLoginStatus()) {
            return redirect()->to('/login')->with('error', 'Login is required!');
        }
        $merchantGateway = MerchantGateway::where('mid', Session::get('userId'))->first();
        $merchantInfo = MerchantInfo::find(Session::get('userId'));
        if (!$merchantInfo) {
            return redirect()->to('/merchant/dashboard');
        }
        $accountId = $merchantInfo->acc_id;
        $callbackUrl = $merchantInfo->callback_url;
        $webhookUrl = $merchantInfo->webhook_url;
        return $this->dashboardPage('merchant.developer-section', compact(
            'merchantGateway',
            'accountId',
            'callbackUrl',
            'webhookUrl'
        ));
    }
    public function merchantPayinView()
    {
        if (!$this->checkLoginStatus()) {
            return redirect()->to('/login')->with('error', 'Login is required!');
        }
        // $transactions = WalletTransaction::where('merchant_id', Session::get('userId'))->where('type','credit')->orderBy('id','desc')->get();
        $transactions = WalletTransaction::leftJoin('transactions', 'wallet_transactions.transaction_id', '=', 'transactions.order_id') // Code updated on 07-03-2025
            ->where('wallet_transactions.merchant_id', Session::get('userId'))
            ->where('wallet_transactions.type', 'credit')
            ->where('wallet_transactions.visibility', 'visible')
            ->orderBy('wallet_transactions.id', 'desc')
            ->select('wallet_transactions.*', 'transactions.response_data')
            ->get();
        $preCommission = WalletTransaction::where('merchant_id', Session::get('userId'))
            ->where('type', 'credit')
            ->where('visibility', 'visible')
            ->whereIn('status', ['completed', 'successful'])
            ->sum('charge');
        $gst = WalletTransaction::where('merchant_id', Session::get('userId'))
            ->where('type', 'credit')
            ->where('visibility', 'visible')
            ->whereIn('status', ['completed', 'successful'])
            ->sum('gst');

        $commision = $preCommission + $gst;
        $totalAmount = WalletTransaction::where('merchant_id', Session::get('userId'))
            ->where('type', 'credit')
            ->whereIn('status', ['completed', 'successful'])
            ->where('visibility', 'visible')
            ->sum('amount');
        $netAmount = $totalAmount - $commision;
        $totalTransactions = WalletTransaction::where('merchant_id', Session::get('userId'))
            ->where('type', 'credit')
            ->where('visibility', 'visible')
            ->count();
        $totalSuccessTransactions = WalletTransaction::where('merchant_id', Session::get('userId'))
            ->where('type', 'credit')
            ->whereIn('status', ['completed', 'successful'])
            ->where('visibility', 'visible')
            ->count();
        $successRate = (float)$totalTransactions > 0.00 ? ((float)$totalSuccessTransactions / (float)$totalTransactions) * 100.00 : 0.00;
        $wallet = MerchantWallet::select('balance')->where('merchant_id', Session::get('userId'))->first();
        $balance = $wallet ? $wallet->balance : 0;
        return $this->dashboardPage('merchant.payin', compact('transactions', 'balance', 'commision', 'totalAmount', 'netAmount', 'successRate', 'totalTransactions', 'totalSuccessTransactions'));
    }
    public function merchantPayoutView()
    {
        if (!$this->checkLoginStatus()) {
            return redirect()->to('/login')->with('error', 'Login is required!');
        }
        // $transactions = WalletTransaction::where('merchant_id', Session::get('userId'))->where('type','debit')->orderBy('id','desc')->get();
        $transactions = WalletTransaction::leftJoin('transactions', 'wallet_transactions.transaction_id', '=', 'transactions.order_id') // Code updated on 07-03-2025
            ->where('wallet_transactions.merchant_id', Session::get('userId'))
            ->where('wallet_transactions.type', 'debit')
            ->where('wallet_transactions.visibility', 'visible')
            ->orderBy('wallet_transactions.id', 'desc')
            ->select('wallet_transactions.*', 'transactions.response_data', 'transactions.request_data')  // Added on 13-03-2025
            ->get();
        $beneficiaries = Beneficiary::where('merchant_id', Session::get('userId'))
            ->where('status', 'active')
            ->get();
        $totalSuccessTransactions = WalletTransaction::where('merchant_id', Session::get('userId'))
            ->where('type', 'debit')
            ->whereIn('status', ['completed', 'successful'])
            ->where('visibility', 'visible')
            ->sum('amount');
        $commission = WalletTransaction::where('merchant_id', Session::get('userId'))
            ->where('type', 'debit')
            ->whereIn('status', ['completed', 'successful'])
            ->where('visibility', 'visible')
            ->sum('charge');
      	$gst = WalletTransaction::where('merchant_id', Session::get('userId'))
            ->where('type', 'debit')
            ->whereIn('status', ['completed', 'successful'])
            ->where('visibility', 'visible')
            ->sum('gst');
      	$totalCommission = $commission + $gst;
        $totalFailedTransactions = WalletTransaction::where('merchant_id', Session::get('userId'))
            ->where('type', 'debit')
            ->Where('status', 'failed')
            ->where('visibility', 'visible')
            ->sum('amount');
        $totalProcessingTransactions = WalletTransaction::where('merchant_id', Session::get('userId'))
            ->where('type', 'debit')
            ->Where('status', 'processing')
            ->where('visibility', 'visible')
            ->sum('amount');
        $wallet = MerchantWallet::select('balance')
            ->where('merchant_id', Session::get('userId'))
            ->first();
        $balance = $wallet ? $wallet->balance : 0;
        $netAmount = $totalSuccessTransactions + $totalCommission;
        return $this->dashboardPage('merchant.payout', compact('transactions', 'balance', 'totalSuccessTransactions', 'totalFailedTransactions', 'totalProcessingTransactions', 'beneficiaries', 'totalCommission', 'netAmount'));
    }
    public function fundRequestView()
    {
        if (!$this->checkLoginStatus()) {
            return redirect()->to('/login')->with('error', 'Login is required!');
        }
        $wallet = MerchantWallet::select('balance')->where('merchant_id', Session::get('userId'))->first();
        $balance = $wallet ? $wallet->balance : 0;
        return $this->dashboardPage('merchant.fund-request', compact('balance'));
    }
    public function beneficiariesView()
    {
        if (!$this->checkLoginStatus()) {
            return redirect()->to('/login')->with('error', 'Login is required!');
        }
        $beneficiaries = Beneficiary::where('merchant_id', Session::get('userId'))->where('status', 'active')->get();
        $wallet = MerchantWallet::select('balance')->where('merchant_id', Session::get('userId'))->first();
        $balance = $wallet ? $wallet->balance : 0;
        return $this->dashboardPage('merchant.beneficiaries', compact('beneficiaries', 'balance'));
    }
    public function topupDetailsView()
    {
        if (!$this->checkLoginStatus()) {
            return redirect()->to('/login')->with('error', 'Login is required!');
        }
        return $this->dashboardPage('merchant.topup-details');
    }

    public function addBeneficiary(Request $request)
    {
        if (!$this->checkLoginStatus()) {
            return redirect()->to('/login')->with('error', 'Login is required!');
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

        $merchantId = Session::get('userId');

        if (!$merchantId) {
            return redirect()->back()->with('error', 'Merchant ID is missing. Please log in again.');
        }


        // Insert into the database
        $beneficiary = Beneficiary::create([
            'merchant_id' => $merchantId,
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
            'status' => 1, // Default active status
        ]);

        return redirect()->back()->with('success', 'Beneficiary added successfully!');
    }

    public function initiatePayout(Request $request)
    {
        if (!$this->checkLoginStatus()) {
            return redirect()->to('/login')->with('error', 'Login is required!');
        }
        $request->validate([
            'amount' => 'required|numeric|min:100',
            'pay_mode' => 'required',
            'beneficiary' => 'required|numeric|exists:beneficiaries,id'
        ], [
            'amount.required' => 'The amount field is required.',
            'amount.numeric' => 'The amount must be a number.',
            'amount.min' => 'The minimum payout amount must be ₹100.',
            'pay_mode.required' => 'The payment mode is required.',
            'beneficiary.required' => 'The beneficiary field is required.',
            'beneficiary.numeric' => 'The beneficiary ID must be a number.',
            'beneficiary.exists' => 'The selected beneficiary does not exist in our records.'
        ]);

        $merchant = MerchantInfo::find(Session::get('userId'));
        if (!$merchant) {
            return redirect()->back()->with('error', 'Login required!');
        }

        $merchantGateway = MerchantGateway::where('mid', Session::get('userId'))->where('status', 'active')->first();
        if (!$merchantGateway) {
            return redirect()->back()->with('error', 'Unable to process your request right now! Please try after sometimes.');
        }

        $beneficiary = Beneficiary::find($request->beneficiary);
        if (!$beneficiary) {
            return redirect()->back()->with('error', 'Selected beneficiary is more available to complete this request!');
        }

        $acc_id = $merchant->acc_id;
        $apiKey = $merchantGateway->api_key;
        $merchantId = $merchantGateway->merchant_id;
        $sub_pay_mode = $request->pay_mode;
        $amount = $request->amount ?? 0.0;
        $account_number = $beneficiary->account_no;
        $bank_ifsc = $beneficiary->ifsc;
        $bene_name = $beneficiary->name;
        $order_id = $this->generateOrderId(20);

        if (!$apiKey || !$merchantId || $amount < 100) {
            return redirect()->back()->with('error', 'Unable to process your request right now! Please try after sometimes.');
        }

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $apiKey,
            ])->post(url('/api/v1/payout-request'), [
                "acc_id" => $acc_id,
                "amount" => $amount,
                "merchant_id" => $merchantId,
                "currency" => "INR",
                "pay_mode" => "NB",
                "sub_pay_mode" => $sub_pay_mode,
                "bene_name" => $bene_name,
                "bank_ifsc" => $bank_ifsc,
                "account_number" => $account_number,
                "vpa" => "example@upi",
                "remarks" => "Payment to beneficiary $bene_name",
                "order_id" => $order_id
            ]);
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
        // Handle response
        if ($response->successful()) {
            // return $response->json();
            return redirect()->back()->with('success', "Payout Initiated successfully!");
        } else {
            // return [
            //     'error' => true,
            //     'status' => $response->status(),
            //     'message' => $response->body()
            // ];
            return redirect()->back()->with('error', $response->body());
        }
    }

    public function updateBeneficiary(Request $request, $id)
    {
        if (!$this->checkLoginStatus()) {
            return redirect()->to('/login')->with('error', 'Login is required!');
        }
        $beneficiary = Beneficiary::findOrFail($id);

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
        if (!$this->checkLoginStatus()) {
            return redirect()->to('/login')->with('error', 'Login is required!');
        }
        $beneficiary = Beneficiary::findOrFail($id);
        $beneficiary->delete();

        return redirect()->back()->with('success', 'Beneficiary deleted successfully!');
    }
}
