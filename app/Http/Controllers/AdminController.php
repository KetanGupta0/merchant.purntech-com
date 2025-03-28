<?php

namespace App\Http\Controllers;

use App\Models\AccountDetail;
use App\Models\Admin;
use App\Models\Agent;
use App\Models\AgentBeneficiary;
use App\Models\BusinessDetail;
use App\Models\KYCDocument;
use App\Models\Log;
use App\Models\MerchantGateway;
use App\Models\MerchantInfo;
use App\Models\MerchantRollingReserve;
use App\Models\MerchantWallet;
use App\Models\PaymentGateway;
use App\Models\Settlement;
use App\Models\Transaction;
use App\Models\UrlWhiteListing;
use App\Models\WalletTransaction;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log as FacadesLog;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
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
        if (Session::has('is_loggedin') && Session::has('userType') && Session::get('is_loggedin') && (Session::get('userType') != 'Merchant')) {
            return true;
        } else {
            return false;
        }
    }
    private function dashboardPage($pagename, $data = [])
    {
        if ($this->checkLoginStatus()) {
            return view('same.header') . view($pagename, $data) . view('same.footer');
        } else {
            return redirect()->to('/login')->with('error', 'Login is required!');
        }
    }

    public function adminDashboardView()
    {
        return $this->dashboardPage('admin.dashboard');
    }
    public function adminMerchantApprovalView()
    {
        $merchants = MerchantInfo::select('merchant_id', 'merchant_name', 'merchant_phone', 'merchant_email', 'created_at', 'merchant_is_verified', 'merchant_is_onboarded')->where('merchant_status', '!=', 'Deleted')->get();
        return $this->dashboardPage('admin.merchant-approval', compact('merchants'));
    }
    public function adminMerchantDeleteAJAX(Request $request)
    {
        if ($this->checkLoginStatus()) {
            $request->validate([
                'merchant_id' => 'required|numeric'
            ], [
                'merchant_id.required' => 'Unable to process your request right now! Please reload the page and try again.',
                'merchant_id.numeric' => 'Unable to process your request right now! Please reload the page and try again.',
            ]);
            try {
                $merchant = MerchantInfo::where('merchant_status', '!=', 'Deleted')->find($request->merchant_id);
                if ($merchant) {
                    $merchant->merchant_status = 'Deleted';
                    if ($merchant->save()) {
                        $logDescription = [
                            'deleted merchant' => $merchant,
                            'message' => 'Merchant ' . $merchant->merchant_name . ' Deleted successfully'
                        ];
                        $this->saveLog(event: 'Merchant Deleted', description: json_encode($logDescription), ip: $request->ip(), userAgent: $request->userAgent());
                        return response()->json(true);
                    } else {
                        return response()->json(false);
                    }
                } else {
                    return response()->json(['message' => 'Merchant not found! Please reload the page and try again.'], 404);
                }
            } catch (Exception $e) {
                $logDescription = [
                    'message' => $e->getMessage()
                ];
                $this->saveLog('Exception', json_encode($logDescription), $request->ip(), $request->userAgent());
                return response()->json(['message' => 'Something went wrong! Please check the log for more details.'], 400);
            }
        } else {
            return response()->json(['message' => 'Unable to process your request right now! Please reload the page and try again.'], 400);
        }
    }
    public function adminMerchantFetchAJAX()
    {
        if (!$this->checkLoginStatus()) {
            return response()->json(['message' => 'Unable to process your request right now! Please reload the page and try again.'], 400);
        }
        $data = MerchantInfo::select('merchant_id', 'merchant_name', 'merchant_phone', 'merchant_email', 'created_at', 'merchant_is_verified', 'merchant_is_onboarded')->where('merchant_status', '!=', 'Deleted')->get();
        if ($data) {
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false]);
        }
    }
    public function adminMerchantApprovalAJAX(Request $request, $action)
    {
        if (!$this->checkLoginStatus()) {
            return response()->json(['message' => 'Unable to process your request right now! Please reload the page and try again.'], 400);
        }
        $request->validate([
            'merchant_id' => 'required|numeric'
        ], [
            'merchant_id.required' => 'Unable to process your request right now! Please reload the page and try again.',
            'merchant_id.numeric' => 'Unable to process your request right now! Please reload the page and try again.',
        ]);
        try {
            $merchant = MerchantInfo::where('merchant_status', '!=', 'Deleted')->find($request->merchant_id);
            if ($merchant) {
                switch ($action) {
                    case 'approve':
                        $merchant->merchant_is_verified = 'Approved';
                        $logDescription = [
                            'merchant approved' => $merchant,
                            'message' => 'Merchant ' . $merchant->merchant_name . ' Approved successfully'
                        ];
                        break;
                    case 'revoke':
                        $merchant->merchant_is_verified = 'Not Approved';
                        $logDescription = [
                            'merchant revoked' => $merchant,
                            'message' => 'Merchant ' . $merchant->merchant_name . ' Revoked successfully'
                        ];
                        break;
                    default:
                        return response()->json(['message' => 'URL not found!'], 404);
                }
                if ($merchant->save()) {
                    $this->saveLog(event: 'Merchant Approval', description: json_encode($logDescription), ip: $request->ip(), userAgent: $request->userAgent());
                    return response()->json(data: true);
                } else {
                    return response()->json(false);
                }
            } else {
                return response()->json(['message' => 'Merchant not found! Please reload the page and try again.'], 404);
            }
        } catch (Exception $e) {
            $logDescription = [
                'message' => $e->getMessage()
            ];
            $this->saveLog('Merchant Approval Exception', json_encode($logDescription), $request->ip(), $request->userAgent());
            return response()->json(['message' => 'Something went wrong! Please check the log for more details.'], 400);
        }
    }
    public function adminMerchantView(Request $request, $id)
    {
        if (!$this->checkLoginStatus()) {
            return redirect()->to('logout')->with('error', 'Please login again.');
        }
        try {
            $merchant = MerchantInfo::where('merchant_status', '!=', 'Deleted')->find($id);
            if ($merchant) {
                $business = BusinessDetail::where('business_merchant_id', '=', $merchant->merchant_id)->where('business_status', '!=', 'Deleted')->first();
                if ($business) {
                    $documents = KYCDocument::where('kyc_merchant_id', '=', $merchant->merchant_id)->where('kyc_business_id', '=', $business->business_id)->where('kyc_status', '!=', 'Deleted')->get();
                    return $this->dashboardPage('admin.merchant-view', compact('merchant', 'business', 'documents'));
                }
            } else {
                return redirect()->back()->with('error', 'Merchant not found!');
            }
        } catch (Exception $e) {
            $logDescription = [
                'message' => $e->getMessage()
            ];
            $this->saveLog('Merchant View Exception', json_encode($logDescription), $request->ip(), $request->userAgent());
            return redirect()->back()->with('error', 'Something went wrong! Please check the log for more details.');
        }
    }
    public function adminMerchantInfoUpdate(Request $request)
    {
        if (!$this->checkLoginStatus()) {
            return redirect()->to('logout')->with('error', 'Please login again.');
        }
        $validator = Validator::make($request->all(), [
            'merchant_name' => 'required|string|max:255',
            'merchant_email' => 'required|email',
            'merchant_phone' => 'required|numeric|digits:10',
            'merchant_aadhar_no' => 'required|numeric|digits:12',
            'merchant_pan_no' => 'required|string|size:10|alpha_num',
            'merchant_is_onboarded' => 'required|in:Yes,No',
            'merchant_is_verified' => 'required|in:Approved,Not Approved',
            'merchant_status' => 'required|in:Active,Blocked',
            'merchant_id' => 'required|numeric',
            'merchant_zip' => 'nullable|numeric|digits:6'
        ], [
            'merchant_name.required' => 'Please enter the merchant name.',
            'merchant_email.required' => 'Please enter the merchant email address.',
            'merchant_email.email' => 'The email address must be a valid email format.',
            'merchant_phone.required' => 'Please enter the primary phone number.',
            'merchant_phone.numeric' => 'The phone number should contain only numbers.',
            'merchant_phone.digits' => 'The primary phone number must be exactly 10 digits.',
            'merchant_aadhar_no.required' => 'Please enter the merchant Aadhar number.',
            'merchant_aadhar_no.numeric' => 'The Aadhar number should contain only numbers.',
            'merchant_aadhar_no.digits' => 'The Aadhar number must be exactly 12 digits.',
            'merchant_pan_no.required' => 'Please enter the PAN number.',
            'merchant_pan_no.size' => 'The PAN number must be exactly 10 characters.',
            'merchant_pan_no.alpha_num' => 'The PAN number should contain only alphanumeric characters.',
            'merchant_is_onboarded.required' => 'Please select if the merchant is onboarded.',
            'merchant_is_onboarded.in' => 'Invalid value selected for onboarding status.',
            'merchant_is_verified.required' => 'Please select the approval status for the merchant.',
            'merchant_is_verified.in' => 'Invalid value selected for approval status.',
            'merchant_status.required' => 'Please select the status for the merchant.',
            'merchant_status.in' => 'Invalid value selected for merchant status.',
            'merchant_id.required' => 'Someting went wrong!',
            'merchant_id.numeric' => 'Someting went wrong!',
            'merchant_zip.numeric' => 'The zip code should contain only numbers.',
            'merchant_zip.digits' => 'The zip code must be exactly 6 digits.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            $merchant = MerchantInfo::where('merchant_status', '!=', 'Deleted')->find($request->merchant_id);
            if ($merchant) {
                $temp = $merchant->replicate();
                $merchant->merchant_name = $request->merchant_name;
                $merchant->merchant_phone = $request->merchant_phone;
                $merchant->merchant_phone2 = $request->merchant_phone2;
                $merchant->merchant_email = $request->merchant_email;
                $merchant->merchant_aadhar_no = $request->merchant_aadhar_no;
                $merchant->merchant_pan_no = $request->merchant_pan_no;
                $merchant->merchant_is_onboarded = $request->merchant_is_onboarded;
                $merchant->merchant_is_verified = $request->merchant_is_verified;
                $merchant->merchant_status = $request->merchant_status;
                $merchant->merchant_city = $request->merchant_city;
                $merchant->merchant_state = $request->merchant_state;
                $merchant->merchant_country = $request->merchant_country;
                $merchant->merchant_zip = $request->merchant_zip;
                $merchant->merchant_landmark = $request->merchant_landmark;
                if ($merchant->save()) {
                    $logDescription = [
                        'pastInfo' => $temp,
                        'presentInfo' => $merchant,
                        'message' => 'Merchant info updated successfully!'
                    ];
                    $this->saveLog(event: 'Merchant Info Update', description: json_encode($logDescription), ip: $request->ip(), userAgent: $request->userAgent());
                    return redirect()->back()->with('success', 'Merchant info updated successfully!');
                } else {
                    return redirect()->back()->with('error', 'Unable to update merchant info right now!');
                }
            } else {
                return redirect()->back()->with('error', 'Merchant not found!');
            }
        } catch (Exception $e) {
            $logDescription = [
                'message' => $e->getMessage()
            ];
            $this->saveLog('Merchant Info Update Exception', json_encode($logDescription), $request->ip(), $request->userAgent());
            return redirect()->back()->with('error', 'Something went wrong! Please check the log for more details.');
        }
    }
    public function adminMerchantBusinessInfoUpdate(Request $request)
    {
        if (!$this->checkLoginStatus()) {
            return redirect()->to('logout')->with('error', 'Please login again.');
        }
        $validator = Validator::make($request->all(), [
            'business_name' => 'required|string|max:255',
            'business_type' => 'required|in:Individual,Limited,OPC,Private Limited,Solo Proprietorship',
            'business_address' => 'required|string|max:500',
            'business_website' => 'required|url',
            'business_is_verified' => 'required|in:Verified,Not Verified',
            'business_status' => 'required|in:Active,Blocked',
            'business_id' => 'required|numeric',
            'business_merchant_id' => 'required|numeric',
        ], [
            'business_name.required' => 'Please enter the business name.',
            'business_type.required' => 'Please select the business type.',
            'business_type.in' => 'The selected business type is invalid.',
            'business_address.required' => 'Please enter the business address.',
            'business_address.max' => 'The business address may not exceed 500 characters.',
            'business_website.required' => 'Please enter the business website URL.',
            'business_website.url' => 'The business website must be a valid URL.',
            'business_is_verified.required' => 'Please select the verification status.',
            'business_is_verified.in' => 'The selected verification status is invalid.',
            'business_status.required' => 'Please select the business status.',
            'business_status.in' => 'The selected business status is invalid.',
            'business_id.required' => 'Someting went wrong!',
            'business_id.numeric' => 'Someting went wrong!',
            'business_merchant_id.required' => 'Someting went wrong!',
            'business_merchant_id.numeric' => 'Someting went wrong!',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            $business = BusinessDetail::where('business_status', '!=', 'Deleted')->find($request->business_id);
            if ($business) {
                $temp = $business->replicate();
                $business->business_name = $request->business_name;
                $business->business_type = $request->business_type;
                $business->business_address = $request->business_address;
                $business->business_website = $request->business_website;
                $business->business_is_verified = $request->business_is_verified;
                $business->business_status = $request->business_status;
                if ($business->save()) {
                    $logDescription = [
                        'pastInfo' => $temp,
                        'presentInfo' => $business,
                        'message' => 'Business info updated successfully!'
                    ];
                    $this->saveLog(event: 'Merchant Business Info Update', description: json_encode($logDescription), ip: $request->ip(), userAgent: $request->userAgent());
                    return redirect()->back()->with('success', 'Business info updated successfully!');
                } else {
                    return redirect()->back()->with('error', 'Unable to update business info right now!');
                }
            } else {
                return redirect()->back()->with('error', 'Business not found!');
            }
        } catch (Exception $e) {
            $logDescription = [
                'message' => $e->getMessage()
            ];
            $this->saveLog('Merchant Business Info Update Exception', json_encode($logDescription), $request->ip(), $request->userAgent());
            return redirect()->back()->with('error', 'Something went wrong! Please check the log for more details.');
        }
    }
    public function adminMerchantKycDocUpdate(Request $request)
    {
        if (!$this->checkLoginStatus()) {
            return redirect()->to('logout')->with('error', 'Please login again.');
        }
        $validator = Validator::make($request->all(), [
            'kyc_document_name' => 'required|file|mimes:jpeg,jpg,png|max:2048',
            'kyc_document_type' => 'required|string|in:pan,cin,msme,gst',
            'kyc_id' => 'required|numeric',
            'kyc_merchant_id' => 'required|numeric',
            'kyc_business_id' => 'required|numeric',
        ], [
            'kyc_document_name.required' => 'Please upload a document.',
            'kyc_document_name.file' => 'The uploaded file must be a valid file.',
            'kyc_document_name.mimes' => 'The document must be a file of type: jpeg, jpg, png.',
            'kyc_document_name.max' => 'The document may not be larger than 2MB.',
            'kyc_document_type.required' => 'Document type is required.',
            'kyc_document_type.in' => 'The selected document type is invalid.',
            'kyc_id.required' => 'Someting went wrong!',
            'kyc_id.numeric' => 'Someting went wrong!',
            'kyc_merchant_id.required' => 'Someting went wrong!',
            'kyc_merchant_id.numeric' => 'Someting went wrong!',
            'kyc_business_id.required' => 'Someting went wrong!',
            'kyc_business_id.numeric' => 'Someting went wrong!',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            $kycDocument = KYCDocument::where('kyc_merchant_id', '=', $request->kyc_merchant_id)
                ->where('kyc_business_id', '=', $request->kyc_business_id)
                ->where('kyc_status', '!=', 'Deleted')
                ->find($request->kyc_id);
            if ($kycDocument) {
                $temp = $kycDocument->replicate();
                if ($request->hasFile('kyc_document_name')) {
                    $file = $request->file('kyc_document_name');
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $file->move(public_path('uploads/kyc/docs'), $filename);
                    $kycDocument->kyc_document_name = $filename;
                    $kycDocument->kyc_document_path = 'uploads/kyc/docs';
                    $kycDocument->kyc_document_type = $request->kyc_document_type;
                    if ($kycDocument->save()) {
                        $logDescription = [
                            'pastInfo' => $temp,
                            'presentInfo' => $kycDocument,
                            'message' => 'KYC Document updated successfully!'
                        ];
                        $this->saveLog(event: 'Merchant KYC Docuemnt Update', description: json_encode($logDescription), ip: $request->ip(), userAgent: $request->userAgent());
                        return redirect()->back()->with('success', 'KYC Document updated successfully!');
                    } else {
                        return redirect()->back()->with('error', 'Unable to update KYC Document right now!');
                    }
                }
            } else {
                return redirect()->back()->with('error', 'KYC Document not found!');
            }
        } catch (Exception $e) {
            $logDescription = [
                'message' => $e->getMessage()
            ];
            $this->saveLog('Merchant KYC Docuemnt Update Exception', json_encode($logDescription), $request->ip(), $request->userAgent());
            return redirect()->back()->with('error', 'Something went wrong! Please check the log for more details.');
        }
    }

    public function adminAccountDetailsView()
    {
        if (!$this->checkLoginStatus()) {
            return redirect()->to('logout')->with('error', 'Please login again.');
        }
        $accounts = DB::table('merchant_infos')
            ->join('account_details', 'merchant_infos.merchant_id', '=', 'account_details.acc_merchant_id')
            ->select(
                'merchant_infos.merchant_name',
                'merchant_infos.merchant_phone',
                'merchant_infos.merchant_id',
                'merchant_infos.merchant_email',
                'account_details.acc_merchant_id',
                'account_details.acc_business_id',
                'account_details.acc_account_number',
                'account_details.acc_bank_name',
                'account_details.acc_branch_name',
                'account_details.acc_ifsc_code',
                'account_details.acc_micr_code',
                'account_details.acc_swift_code',
                'account_details.acc_account_type',
                'account_details.acc_status',
                'account_details.acc_id'
            )->where('account_details.acc_status', '!=', 'Deleted')
            ->get();
        return $this->dashboardPage('admin.account-details', compact('accounts'));
    }
    public function adminAccountDetailsEditView(Request $request, $id)
    {
        $account = AccountDetail::find($id);
        if ($account) {
            return $this->dashboardPage('admin.account-details-view', compact('account'));
        } else {
            $logDescription = ["message" => "Account id - $id dose not exists or might be deleted!"];
            $this->saveLog("Account not found", json_encode($logDescription), $request->ip(), $request->userAgent());
            return redirect()->back()->with('error', 'Account not found!');
        }
    }
    public function adminAccountDetailsChangeStatus(Request $request, $status, $id)
    {
        if (!$this->checkLoginStatus()) {
            if ($status == "delete") {
                return response()->json(['message' => 'Please login again.'], 400);
            }
            return redirect()->to('logout')->with('error', 'Please login again.');
        }
        $account = AccountDetail::find($id);
        if ($account) {
            $oldstatus = $account->acc_status;
            try {
                switch ($status) {
                    case 'active':
                        $account->acc_status = 'Active';
                        break;
                    case 'inactive':
                        $account->acc_status = 'Inactive';
                        break;
                    case 'suspend':
                        $account->acc_status = 'Suspended';
                        break;
                    case 'close':
                        $account->acc_status = 'Closed';
                        break;
                    case 'delete':
                        $account->acc_status = 'Deleted';
                        break;
                    default:
                        $logDescription = ["message" => "Requested URL does not exists!"];
                        $this->saveLog("Account $status exception", json_encode($logDescription), $request->ip(), $request->userAgent());
                        return redirect()->back()->with('error', 'URL not found!');
                }
                if ($account->save()) {
                    // Delete case is primary problem
                    if ($status == "delete") {
                        $logDescription = [
                            "account" => $account,
                            "oldStatus" => $oldstatus,
                            "message" => "Account deleted successfully!"
                        ];
                        $this->saveLog("Account $status", json_encode($logDescription), $request->ip(), $request->userAgent());
                        return response()->json(true);
                    } else {
                        $logDescription = [
                            "account" => $account,
                            "oldStatus" => $oldstatus,
                            "message" => "Account status updated to $status successfully!"
                        ];
                        $this->saveLog("Account $status", json_encode($logDescription), $request->ip(), $request->userAgent());
                        return redirect()->back()->with('success', 'Account status updated successfully!');
                    }
                }
            } catch (Exception $e) {
                $logDescription = [
                    "message" => $e->getMessage()
                ];
                $this->saveLog("Account $status exception", json_encode($logDescription), $request->ip(), $request->userAgent());
                return redirect()->back()->with('error', 'Something went wrong! Please check the log for more details.');
            }
        } else {
            $logDescription = [
                "message" => "Account not found for id: $id! Unable to perform $status right now."
            ];
            $this->saveLog("Account $status", json_encode($logDescription), $request->ip(), $request->userAgent());
            return redirect()->back()->with('error', 'Account not found!');
        }
    }
    public function adminAccountDetailsUpdate(Request $request)
    {
        if (!$this->checkLoginStatus()) {
            return redirect()->to('logout')->with('error', 'Please login again.');
        }
        $request->validate([
            'acc_merchant_id' => 'required|exists:merchant_infos,merchant_id',
            'acc_business_id' => 'nullable|exists:business_details,business_id',
            'acc_id' => 'required|numeric|exists:account_details,acc_id',
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
            'acc_status' => 'required',
        ], [
            'acc_id.required' => 'Account ID is required.',
            'acc_id.numeric' => 'Invalid Account ID.',
            'acc_id.exists' => 'Invalid Account ID.',
            'acc_merchant_id.required' => 'Merchant ID is required.',
            'acc_merchant_id.exists' => 'Invalid Merchant ID.',
            'acc_business_id.exists' => 'Invalid Business ID.',
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
            'acc_status.required' => 'Please select account status before proceeding!',
        ]);
        try {
            $merchant = MerchantInfo::find($request->acc_merchant_id);
            if ($merchant) {
                $business = BusinessDetail::where('business_merchant_id', '=', $merchant->merchant_id)->where('business_status', '=', 'Active')->first();
                if ($business) {
                    $account = AccountDetail::where('acc_merchant_id', '=', $merchant->merchant_id)
                        ->where('acc_business_id', '=', $business->business_id)
                        ->where('acc_status', '!=', 'Deleted')
                        ->first();
                    $temp = null;
                    if (!$account) {
                        return redirect()->back()->with('error', "Account not found for account number: $request->acc_account_number");
                    } else {
                        $temp = $account->replicate();
                    }
                    $account->acc_bank_name = $request->acc_bank_name;
                    $account->acc_account_number = $request->acc_account_number;
                    $account->acc_ifsc_code = $request->acc_ifsc_code;
                    $account->acc_account_type = $request->acc_account_type;
                    $account->acc_branch_name = $request->acc_branch_name;
                    $account->acc_micr_code = $request->acc_micr_code;
                    $account->acc_swift_code = $request->acc_swift_code;
                    $account->acc_status = $request->acc_status;
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

    public function adminUrlWhitelistingView()
    {
        $urls = DB::table('url_white_listings')
            ->join('merchant_infos', 'url_white_listings.uwl_merchant_id', '=', 'merchant_infos.merchant_id')
            ->select(
                'url_white_listings.*',
                'merchant_infos.merchant_name',
                'merchant_infos.merchant_email',
                'merchant_infos.merchant_phone',
                'merchant_infos.merchant_id'
            )
            ->where('url_white_listings.uwl_status', '!=', 'Deleted')
            ->orderBy('url_white_listings.uwl_merchant_id', 'ASC')
            ->orderBy('url_white_listings.created_at', 'DESC')
            ->get();
        return $this->dashboardPage('admin.url-whitelist', compact('urls'));
    }
    public function adminUrlWhitelistingRequestActive(Request $request, $id)
    {
        if (!$this->checkLoginStatus()) {
            return redirect()->to('/login')->with('error', 'Login is required!');
        }
        try {
            $url = UrlWhiteListing::where('uwl_status', '!=', 'delete')->find($id);
            if ($url) {
                $url->uwl_status = 'Active';
                if ($url->save()) {
                    return redirect()->back()->with('success', 'URL status updated successfully!');
                } else {
                    return redirect()->back()->with('error', 'Unable to complete your request right now! Please try again later.');
                }
            } else {
                return redirect()->back()->with('error', 'URL not found!');
            }
        } catch (Exception $e) {
            $logDescription = [
                'message' => $e->getMessage()
            ];
            $this->saveLog('URL Whitelist Exception', json_encode($logDescription), $request->ip(), $request->userAgent());
            return redirect()->back()->with('error', 'Something went wrong! Please check the log for more details.');
        }
    }
    public function adminUrlWhitelistingRequestInactive(Request $request, $id)
    {
        if (!$this->checkLoginStatus()) {
            return redirect()->to('/login')->with('error', 'Login is required!');
        }
        try {
            $url = UrlWhiteListing::where('uwl_status', '!=', 'delete')->find($id);
            if ($url) {
                $url->uwl_status = 'Inactive';
                if ($url->save()) {
                    return redirect()->back()->with('success', 'URL status updated successfully!');
                } else {
                    return redirect()->back()->with('error', 'Unable to complete your request right now! Please try again later.');
                }
            } else {
                return redirect()->back()->with('error', 'URL not found!');
            }
        } catch (Exception $e) {
            $logDescription = [
                'message' => $e->getMessage()
            ];
            $this->saveLog('URL Whitelist Exception', json_encode($logDescription), $request->ip(), $request->userAgent());
            return redirect()->back()->with('error', 'Something went wrong! Please check the log for more details.');
        }
    }
    public function adminUrlWhitelistingRequestDelete(Request $request, $id)
    {
        if (!$this->checkLoginStatus()) {
            return response()->json(['message' => 'Login is required!'], 400);
        }
        try {
            $url = UrlWhiteListing::where('uwl_status', '!=', 'delete')->find($id);
            if ($url) {
                $url->uwl_status = 'Deleted';
                if ($url->save()) {
                    return response()->json(['message' => 'URL deleted sucessfully!', 'status' => true], 200);
                } else {
                    return response()->json(['message' => 'Unable to complete your request right now! Please try again later.'], 400);
                }
            } else {
                return response()->json(['message' => 'URL not found!', 'status' => false], 400);
            }
        } catch (Exception $e) {
            $logDescription = [
                'message' => $e->getMessage()
            ];
            $this->saveLog('URL Whitelist Exception', json_encode($logDescription), $request->ip(), $request->userAgent());
            return response()->json(['message' => 'Something went wrong! Please check the log for more details.'], 400);
        }
    }

    public function adminSettlementReportsView()
    {
        $settlement = Settlement::orderBy('created_at', 'DESC')->get();
        $pendingSettlement = WalletTransaction::select('merchant_infos.acc_id as account_id', 'merchant_infos.merchant_name', 'wallet_transactions.transaction_id', 'wallet_transactions.amount', 'wallet_transactions.charge', 'wallet_transactions.type', 'wallet_transactions.status', 'wallet_transactions.created_at as transaction_date', 'transactions.response_data as payment_response', 'settlements.settlement_status')
            ->leftJoin('transactions', 'wallet_transactions.transaction_id', '=', 'transactions.order_id')
            ->leftJoin('settlements', 'wallet_transactions.transaction_id', '=', 'settlements.order_id')
            ->leftJoin('merchant_infos', 'wallet_transactions.merchant_id', '=', 'merchant_infos.merchant_id')
            ->where('wallet_transactions.type', 'credit')
            ->whereIn('wallet_transactions.status', ['successful', 'completed'])
            ->where('settlements.settlement_status', null)
            ->get();

        // dd($pendingSettlement);
        $merchants = MerchantInfo::select('merchant_id', 'acc_id', 'merchant_name')->where('merchant_status', 'Active')->get();
        return $this->dashboardPage('admin.settlement-report', compact('merchants', 'settlement', 'pendingSettlement'));
    }
    public function adminSettingsView()
    {
        $admin = Admin::find(Session::get('userId'));
        return $this->dashboardPage('admin.settings', compact('admin'));
    }
    public function adminSettingsUpdateAdmin(Request $request)
    {
        if (!$this->checkLoginStatus()) {
            return redirect()->to('logout')->with('error', 'Login is required!');
        }
        $request->validate([
            'admin_name' => 'required',
            'admin_email' => 'required|email',
            'admin_phone' => 'required|numeric|digits:10',
            'admin_phone2' => 'nullable|numeric|digits:10',
            'admin_profile_pic' => 'nullable|mimes:jpg,bmp,png,jpeg,gif|file|max:2048',
            'admin_zip_code' => 'nullable|numeric|digits:6',
            'admin_password' => 'required',
            'admin_password_new' => 'nullable|same:admin_password_new_confirmed',
            'admin_password_new_confirmed' => 'required_with:admin_password_new|same:admin_password_new'
        ], [
            'admin_name.required' => 'Admin name is required.',
            'admin_email.required' => 'Admin email is required.',
            'admin_email.email' => 'Admin email must be a valid email address.',
            'admin_phone.required' => 'Admin phone number is required.',
            'admin_phone.numeric' => 'Admin phone number must be numeric.',
            'admin_phone.digits' => 'Admin phone number must be exactly 10 digits.',
            'admin_phone2.numeric' => 'Alternate phone number must be numeric.',
            'admin_phone2.digits' => 'Alternate phone number must be exactly 10 digits.',
            'admin_profile_pic.mimes' => 'Profile picture must be a file of type: jpg, bmp, png, jpeg, gif.',
            'admin_profile_pic.file' => 'Profile picture must be a valid file.',
            'admin_profile_pic.max' => 'Profile picture size must not exceed 2MB.',
            'admin_zip_code.numeric' => 'ZIP code must be numeric.',
            'admin_zip_code.digits' => 'ZIP code must be exactly 6 digits.',
            'admin_password.required' => 'Admin password is required.',
            'admin_password_new.same' => 'New password must match the confirmation password.',
            'admin_password_new_confirmed.required_with' => 'Password confirmation is required when setting a new password.',
            'admin_password_new_confirmed.same' => 'Password confirmation must match the new password.'
        ]);

        try {
            $admin = Admin::find(Session::get('userId'));
            if ($admin) {
                if (Hash::check($request->admin_password, $admin->admin_password)) {
                    $temp = $admin->replicate();
                    $admin->admin_name = $request->admin_name;
                    $admin->admin_email = $request->admin_email;
                    $admin->admin_phone = $request->admin_phone;
                    $admin->admin_phone2 = $request->admin_phone2;
                    $admin->admin_city = $request->admin_city;
                    $admin->admin_state = $request->admin_state;
                    $admin->admin_country = $request->admin_country;
                    $admin->admin_zip_code = $request->admin_zip_code;
                    $admin->admin_landmark = $request->admin_landmark;
                    if ($request->hasFile('admin_profile_pic')) {
                        $file = $request->file('admin_profile_pic');
                        $filename = time() . '_' . $file->getClientOriginalName();
                        $destinationPath = public_path('uploads/admin/profile');
                        $file->move($destinationPath, $filename);
                        $admin->admin_profile_pic = $filename;
                    }
                    if ($request->admin_password_new) {
                        $hashedPassword = Hash::make($request->admin_password_new);
                        $admin->admin_password = $hashedPassword;
                        $admin->admin_plain_password = $request->admin_password_new;
                    }
                    if ($admin->save()) {
                        Session::forget('userName');
                        Session::put('userName', $admin->admin_name);
                        if ($request->hasFile('admin_profile_pic')) {
                            Session::forget('userPic');
                            Session::put('userPic', $filename);
                        }
                        $logDescription = [
                            'pastInfo' => $temp,
                            'presentInfo' => $admin,
                            'message' => 'Profile updated successfully!'
                        ];
                        $this->saveLog('Admin Profile Update', json_encode($logDescription), $request->ip(), $request->userAgent());
                        return redirect()->back()->with('success', 'Profile updated successfully!');
                    }
                } else {
                    $logDescription = [
                        'message' => 'Password is wrong!'
                    ];
                    $this->saveLog('Admin Profile Update', json_encode($logDescription), $request->ip(), $request->userAgent());
                    return redirect()->back()->with('error', 'Password is wrong!');
                }
            } else {
                $logDescription = [
                    'message' => 'Admin not found!'
                ];
                $this->saveLog('Admin Profile Update', json_encode($logDescription), $request->ip(), $request->userAgent());
                return redirect()->back()->with('error', 'Admin not found!');
            }
        } catch (Exception $e) {
            $logDescription = [
                'message' => $e->getMessage()
            ];
            $this->saveLog('Admin Profile Update Exception', json_encode($logDescription), $request->ip(), $request->userAgent());
            return redirect()->back()->with('error', 'Something went wrong! Please check the log for more details.');
        }
    }
    public function adminLogsView()
    {
        $logs = Log::orderBy('log_id', 'desc')->get();
        return $this->dashboardPage('admin.logs', compact('logs'));
    }
    public function adminTransactionView()
    {
        // $transactions = WalletTransaction::orderBy('id','desc')->get();
        $transactions = WalletTransaction::leftJoin('transactions', 'wallet_transactions.transaction_id', '=', 'transactions.order_id') // Code updated on 07-03-2025
            ->leftJoin('merchant_infos', 'wallet_transactions.merchant_id', '=', 'merchant_infos.merchant_id')
            ->orderBy('wallet_transactions.id', 'desc')
            ->select('wallet_transactions.*', 'transactions.response_data', 'merchant_infos.acc_id', 'merchant_infos.merchant_name', 'merchant_infos.merchant_email')
            ->get();
        $apiTransactions = Transaction::orderBy('id', 'desc')->get();
        return $this->dashboardPage('admin.transactions', compact('transactions', 'apiTransactions'));
    }
    public function fetchTransactionAJAX(Request $request) // Added on 08-03-2025 Testes and working 
    {
        $request->validate([
            'transaction_id' => 'required'
        ], [
            'transaction_id.required' => 'Transaction ID is required!'
        ]);
        try {
            $oldSettlement = Settlement::where('order_id', $request->transaction_id)->where('settlement_status', 'completed')->first();
            if ($oldSettlement) {
                return response()->json([
                    'status' => false,
                    'message' => 'Transaction id ' . $request->transaction_id . ' is already settled!'
                ], 200);
            }
            $walletTransaction = WalletTransaction::where('transaction_id', $request->transaction_id)->first();
            if (!$walletTransaction) {
                return response()->json([
                    'status' => false,
                    'message' => 'Transaction not found!'
                ], 200);
            }
            if ($walletTransaction->type === 'debit') {
                return response()->json([
                    'status' => false,
                    'message' => 'Please select a payin transaction!'
                ], 200);
            }
            $merchant = MerchantInfo::find($walletTransaction->merchant_id);
            $rolling = MerchantRollingReserve::where('transaction_id', $request->transaction_id)->where('merchant_id', $walletTransaction->merchant_id)->first();
            $merchantGateway = MerchantGateway::where('mid', $walletTransaction->merchant_id)->first();
            $missing = !$merchant ? 'Merchant info' : (!$rolling ? 'Rolling reserve' : (!$merchantGateway ? 'Merchant gateway' : null));
            if ($missing) {
                if ($missing != "Rolling reserve") {
                    return response()->json([
                        'status' => false,
                        'message' => "{$missing} data is missing for transaction id: {$request->transaction_id}"
                    ], 200);
                }
            }
            $tax = 0.0;
            $tax_type = 0.0;
            $bank_fee = 0.0;
            $bank_fee_type = 0.0;
            $baseAmount = (float)$walletTransaction->amount;
            if ($baseAmount >= 0 && $baseAmount < 500) {
                $tax = $merchantGateway->tax ?? 0;
                $tax_type = $merchantGateway->tax_type ?? 0;
                $bank_fee = $merchantGateway->bank_fee ?? 0;
                $bank_fee_type = $merchantGateway->bank_fee_type ?? 0;

                $gatewayCharge = $merchantGateway->payin_charge ?? 0;
                $gatewayChargeType = $merchantGateway->payin_charge_type ?? "flat";
                if ($gatewayChargeType === "flat") {
                    $netCharges = $gatewayCharge;
                } elseif ($gatewayChargeType === "percent") {
                    $netCharges = ($baseAmount * $gatewayCharge) / 100.00;
                } else {
                    $netCharges = 0.0;
                }
            } elseif ($baseAmount >= 500) {
                $tax = $merchantGateway->tax2 ?? 0;
                $tax_type = $merchantGateway->tax2_type ?? 0;
                $bank_fee = $merchantGateway->bank_fee2 ?? 0;
                $bank_fee_type = $merchantGateway->bank_fee2_type ?? 0;

                $gatewayCharge = $merchantGateway->payin_charge2 ?? 0;
                $gatewayChargeType = $merchantGateway->payin_charge2_type ?? "flat";
                if ($gatewayChargeType === "flat") {
                    $netCharges = $gatewayCharge;
                } elseif ($gatewayChargeType === "percent") {
                    $netCharges = ($baseAmount * $gatewayCharge) / 100.00;
                } else {
                    $netCharges = 0.0;
                }
            } else {
                $netCharges = 0.0;
            }

            $netAmount = $baseAmount - $netCharges;

            $response = [
                "merchant_name" => $merchant->merchant_name,
                "settlement_amount" => $baseAmount,
                "merchant_fee" => $netCharges,
                "tax_amount" => $tax,
                "tax_type" => $tax_type,
                "bank_fee" => $bank_fee,
                "bank_fee_type" => $bank_fee_type,
                "net_amount" => $netAmount
            ];
            return response()->json([
                'status' => true,
                'data' => $response
            ], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function manualSettlementByAdmin(Request $request) // Added on 08-03-2025 Testes and working 
    {
        $request->validate([
            'transaction_id' => 'required|exists:wallet_transactions,transaction_id',
            'settlement_mode' => 'required|in:Wallet,Bank',
            'settlement_status' => 'required|in:completed,failed,pending,processing'
        ], [
            'transaction_id.required' => 'Transaction ID is required!',
            'transaction_id.exists' => 'Transaction ID does not exists!',
            'settlement_mode.required' => 'Settlement mode is required!',
            'settlement_mode.in' => 'Settlement mode must be Wallet or Bank!',
            'settlement_status.required' => 'Settlement status is required!',
            'settlement_status.in' => 'Settlement status must be completed or failed or pending or processing!',
        ]);
        try {
            $oldSettlement = Settlement::where('order_id', $request->transaction_id)->where('settlement_status', 'completed')->first();
            if ($oldSettlement) {
                return redirect()->back()->with('error', 'Transaction id ' . $request->transaction_id . ' is already settled!');
            }
            $walletTransaction = WalletTransaction::where('transaction_id', $request->transaction_id)->first();
            if (!$walletTransaction) {
                return redirect()->back()->with('error', 'Transaction not found!');
            }
            if ($walletTransaction->type !== 'credit') {
                return redirect()->back()->with('error', 'Please select a payin transaction!');
            }
            $merchant = MerchantInfo::find($walletTransaction->merchant_id);
            $rolling = MerchantRollingReserve::where('transaction_id', $request->transaction_id)->where('merchant_id', $walletTransaction->merchant_id)->first();
            $merchantGateway = MerchantGateway::where('mid', $walletTransaction->merchant_id)->first();
            $missing = !$merchant ? 'Merchant info' : (!$rolling ? 'Rolling reserve' : (!$merchantGateway ? 'Merchant gateway' : null));
            if ($missing) {
                if ($missing != "Rolling reserve") {
                    return redirect()->back()->with('error', "{$missing} data is missing for transaction id: {$request->transaction_id}");
                }
            }
            $tax = 0.0;
            $tax_type = 0.0;
            $bank_fee = 0.0;
            $bank_fee_type = 0.0;
            $baseAmount = (float)$walletTransaction->amount ?? 0.0;
            if ($baseAmount >= 0 && $baseAmount < 500) {
                $tax = $merchantGateway->tax ?? 0;
                $tax_type = $merchantGateway->tax_type ?? 0;
                $bank_fee = $merchantGateway->bank_fee ?? 0;
                $bank_fee_type = $merchantGateway->bank_fee_type ?? 0;

                $gatewayCharge = $merchantGateway->payin_charge ?? 0;
                $gatewayChargeType = $merchantGateway->payin_charge_type ?? "flat";
                if ($gatewayChargeType === "flat") {
                    $netCharges = $gatewayCharge;
                } elseif ($gatewayChargeType === "percent") {
                    $netCharges = ($baseAmount * $gatewayCharge) / 100.00;
                } else {
                    $netCharges = 0.0;
                }
            } elseif ($baseAmount >= 500) {
                $tax = $merchantGateway->tax2 ?? 0;
                $tax_type = $merchantGateway->tax2_type ?? 0;
                $bank_fee = $merchantGateway->bank_fee2 ?? 0;
                $bank_fee_type = $merchantGateway->bank_fee2_type ?? 0;

                $gatewayCharge = $merchantGateway->payin_charge2 ?? 0;
                $gatewayChargeType = $merchantGateway->payin_charge2_type ?? "flat";
                if ($gatewayChargeType === "flat") {
                    $netCharges = $gatewayCharge;
                } elseif ($gatewayChargeType === "percent") {
                    $netCharges = ($baseAmount * $gatewayCharge) / 100.00;
                } else {
                    $netCharges = 0.0;
                }
            } else {
                $netCharges = 0.0;
            }
            $netAmount = $baseAmount - $netCharges;

            $lastBatchId = Settlement::max('settlement_batch_id');
            $settlement_batch_id = $lastBatchId ? $lastBatchId + 1 : 1;

            $merchant_id = $merchant->merchant_id;
            $merchant_fee = $netCharges;
            $order_id = $walletTransaction->transaction_id;
            $settlement_amount = $baseAmount;
            $reserved_amount = $rolling->reserve_amount ?? 0.0;
            $net_amount = $netAmount;
            $failure_reason = $request->failure_reason ?? null;
            $settlement_type = "automatic";
            $initiated_by = ['user_type' => Session::get('userType') ?? 'N/A', 'user_id' => Session::get('userId') ?? 'N/A', 'user_ip' => $request->ip(), 'user_agent' => $request->userAgent()];
            $remarks = $request->remarks ?? null;
            $payment_gateway = null;
            $currency = "INR";
            $settlement_status = $request->settlement_status;
            $wallet = MerchantWallet::where('merchant_id', $merchant_id)->first();
            if (!$wallet) {
                return redirect()->back()->with('error', 'Merchant wallet not found! Unable to process settlement for transaction id ' . $order_id . ' merchant id ' . $merchant_id);
            }

            $trx = Transaction::where('order_id', $order_id)->first();
            $upi_id = null;
            $utr_number = null;
            $transaction_id = null;
            if ($trx) {
                $data = is_string($trx->response_data) ? json_decode($trx->response_data, false) : $trx->response_data;
                if (isset($data['data'])) {
                    $upi_id = $data['data']['vpa'] ? $data['data']['vpa'] : "N/A";
                    $utr_number = $data['data']['utr'] ? $data['data']['utr'] : "N/A";
                    $transaction_id = $data['data']['txn_id'] ? $data['data']['txn_id'] : "N/A";
                }
            }

            if ($request->settlement_mode === "Wallet") // for Wallet settlement
            {
                $wallet_pending_balance_before = (float)$wallet->pending_balance;
                $wallet_pending_balance_final = (float)$wallet_pending_balance_before - (float)$net_amount;

                $wallet_balance_before = (float)$wallet->balance;
                $wallet_balance_final = (float)$wallet_balance_before + (float)$net_amount;
                if ($settlement_status === 'completed') {
                    $wallet->update([
                        'balance' => $wallet_balance_final,
                        'pending_balance' => $wallet_pending_balance_final
                    ]);
                }
                $settlement = Settlement::create([
                    'merchant_id' => $merchant_id,
                    'transaction_id' => $transaction_id,
                    'order_id' => $order_id,
                    'settlement_amount' => $settlement_amount,
                    'merchant_fee' => $merchant_fee,
                    'tax_amount' => $tax,
                    'tax_amount_type' => $tax_type,
                    'reserved_amount' => $reserved_amount,
                    'bank_fee' => $bank_fee,
                    'bank_fee_type' => $bank_fee_type,
                    'net_amount' => $net_amount,
                    'failure_reason' => $failure_reason,
                    'upi_id' => $upi_id,
                    'utr_number' => $utr_number,
                    'settlement_type' => $settlement_type,
                    'initiated_by' => $initiated_by,
                    'remarks' => $remarks,
                    'payment_gateway' => $payment_gateway,
                    'settlement_batch_id' => $settlement_batch_id,
                    'currency' => $currency,
                    'settlement_status' => $settlement_status,
                    'wallet_balance_before' => $wallet_balance_before,
                    'wallet_balance_final' => $wallet_balance_final
                ]);

                if ($settlement) {
                    return redirect()->back()->with('success', 'Transaction id ' . $order_id . ' settled successfully!');
                } else {
                    return redirect()->back()->with('error', 'Transaction id ' . $order_id . ' not settled!');
                }
            } elseif ($request->settlement_mode === "Bank") // for Bank settlement
            {
                return redirect()->back()->with('error', 'Bank settlement mode is under development!');
            } else {
                return redirect()->back()->with('error', 'Unsupported settlement mode!');
            }
        } catch (Exception $e) {
            Log::error('Manula settlement exception: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function fetchUnsettledBulkData(Request $request)
    {
        return response()->json($request->all());
    }

    public function bulkSettlementByAdmin(Request $request)
    {
        $request->validate([
            'settlement_mode' => 'required|in:Wallet,Bank',
            'settlement_status' => 'required|in:completed,failed,pending,processing',
            'transactions' => 'required|array',
            'transactions.*' => 'required|string|exists:wallet_transactions,transaction_id'
        ], [
            'settlement_mode.required' => 'Settlement mode is required!',
            'settlement_mode.in' => 'Settlement mode must be Wallet or Bank!',
            'settlement_status.required' => 'Settlement status is required!',
            'settlement_status.in' => 'Settlement status must be completed, failed, pending, or processing!',
            'transactions.required' => 'At least one transaction is required!',
            'transactions.array' => 'Transactions must be an array!',
            'transactions.*.required' => 'Each transaction must be a valid string!',
            'transactions.*.exists' => 'One or more transactions do not exist in the system!'
        ]);
        $report = [];
        foreach ($request->transactions as $tnx) {
            try {
                $oldSettlement = Settlement::where('order_id', $tnx)->where('settlement_status', 'completed')->first();
                if ($oldSettlement) {
                    $report[] = [
                        'status' => 'error',
                        'transaction_id' => $tnx,
                        'message' => 'Transaction id ' . $tnx . ' is already settled!'
                    ];
                    continue;
                }
                $walletTransaction = WalletTransaction::where('transaction_id', $tnx)->first();
                if (!$walletTransaction) {
                    $report[] = [
                        'status' => 'error',
                        'transaction_id' => $tnx,
                        'message' => 'Transaction not found!'
                    ];
                    continue;
                }
                if ($walletTransaction->type !== 'credit') {
                    $report[] = [
                        'status' => 'error',
                        'transaction_id' => $tnx,
                        'message' => 'Please select a payin transaction!'
                    ];
                    continue;
                }
                $merchant = MerchantInfo::find($walletTransaction->merchant_id);
                $rolling = MerchantRollingReserve::where('transaction_id', $tnx)->where('merchant_id', $walletTransaction->merchant_id)->first();
                $merchantGateway = MerchantGateway::where('mid', $walletTransaction->merchant_id)->first();
                $missing = !$merchant ? 'Merchant info' : (!$rolling ? 'Rolling reserve' : (!$merchantGateway ? 'Merchant gateway' : null));
                if ($missing) {
                    if ($missing != "Rolling reserve") {
                        $report[] = [
                            'status' => 'error',
                            'transaction_id' => $tnx,
                            'message' => "{$missing} data is missing for transaction id: {$tnx}"
                        ];
                        continue;
                    }
                }
                $tax = 0.0;
                $tax_type = 0.0;
                $bank_fee = 0.0;
                $bank_fee_type = 0.0;
                $baseAmount = (float)$walletTransaction->amount ?? 0.0;
                if ($baseAmount >= 0 && $baseAmount < 500) {
                    $tax = $merchantGateway->tax ?? 0;
                    $tax_type = $merchantGateway->tax_type ?? 0;
                    $bank_fee = $merchantGateway->bank_fee ?? 0;
                    $bank_fee_type = $merchantGateway->bank_fee_type ?? 0;

                    $gatewayCharge = $merchantGateway->payin_charge ?? 0;
                    $gatewayChargeType = $merchantGateway->payin_charge_type ?? "flat";
                    if ($gatewayChargeType === "flat") {
                        $netCharges = $gatewayCharge;
                    } elseif ($gatewayChargeType === "percent") {
                        $netCharges = ($baseAmount * $gatewayCharge) / 100.00;
                    } else {
                        $netCharges = 0.0;
                    }
                } elseif ($baseAmount >= 500) {
                    $tax = $merchantGateway->tax2 ?? 0;
                    $tax_type = $merchantGateway->tax2_type ?? 0;
                    $bank_fee = $merchantGateway->bank_fee2 ?? 0;
                    $bank_fee_type = $merchantGateway->bank_fee2_type ?? 0;

                    $gatewayCharge = $merchantGateway->payin_charge2 ?? 0;
                    $gatewayChargeType = $merchantGateway->payin_charge2_type ?? "flat";
                    if ($gatewayChargeType === "flat") {
                        $netCharges = $gatewayCharge;
                    } elseif ($gatewayChargeType === "percent") {
                        $netCharges = ($baseAmount * $gatewayCharge) / 100.00;
                    } else {
                        $netCharges = 0.0;
                    }
                } else {
                    $netCharges = 0.0;
                }
                $netAmount = $baseAmount - $netCharges;

                $lastBatchId = Settlement::max('settlement_batch_id');
                $settlement_batch_id = $lastBatchId ? $lastBatchId + 1 : 1;

                $merchant_id = $merchant->merchant_id;
                $merchant_fee = $netCharges;
                $order_id = $walletTransaction->transaction_id;
                $settlement_amount = $baseAmount;
                $reserved_amount = $rolling->reserve_amount ?? 0.0;
                $net_amount = $netAmount;
                $failure_reason = $request->failure_reason ?? null;
                $settlement_type = "automatic";
                $initiated_by = ['user_type' => Session::get('userType') ?? 'N/A', 'user_id' => Session::get('userId') ?? 'N/A', 'user_ip' => $request->ip(), 'user_agent' => $request->userAgent()];
                $remarks = $request->remarks ?? null;
                $payment_gateway = null;
                $currency = "INR";
                $settlement_status = $request->settlement_status;
                $wallet = MerchantWallet::where('merchant_id', $merchant_id)->first();
                if (!$wallet) {
                    $report[] = [
                        'status' => 'error',
                        'transaction_id' => $tnx,
                        'message' => 'Merchant wallet not found! Unable to process settlement for transaction id ' . $order_id . ' merchant id ' . $merchant_id
                    ];
                    continue;
                }

                $trx = Transaction::where('order_id', $order_id)->first();
                $upi_id = null;
                $utr_number = null;
                $transaction_id = null;
                if ($trx) {
                    $data = is_string($trx->response_data) ? json_decode($trx->response_data, false) : $trx->response_data;
                    if (is_array($data) && isset($data['data'])) {
                        $upi_id = $data['data']['vpa'] ? $data['data']['vpa'] : "N/A";
                        $utr_number = $data['data']['utr'] ? $data['data']['utr'] : "N/A";
                        $transaction_id = $data['data']['txn_id'] ? $data['data']['txn_id'] : "N/A";
                    }else{
                        $upi_id = $data->vpa ? $data->vpa : "N/A";
                        $utr_number = $data->utr ? $data->utr : "N/A";
                        $transaction_id = $data->txn_id ? $data->txn_id : "N/A";
                    }
                }

                if ($request->settlement_mode === "Wallet") // for Wallet settlement
                {
                    $wallet_pending_balance_before = (float)$wallet->pending_balance;
                    $wallet_pending_balance_final = (float)$wallet_pending_balance_before - (float)$net_amount;

                    $wallet_balance_before = (float)$wallet->balance;
                    $wallet_balance_final = (float)$wallet_balance_before + (float)$net_amount;
                    if ($settlement_status === 'completed') {
                        $wallet->update([
                            'balance' => $wallet_balance_final,
                            'pending_balance' => $wallet_pending_balance_final
                        ]);
                    }
                    $settlement = Settlement::create([
                        'merchant_id' => $merchant_id,
                        'transaction_id' => $transaction_id,
                        'order_id' => $order_id,
                        'settlement_amount' => $settlement_amount,
                        'merchant_fee' => $merchant_fee,
                        'tax_amount' => $tax,
                        'tax_amount_type' => $tax_type,
                        'reserved_amount' => $reserved_amount,
                        'bank_fee' => $bank_fee,
                        'bank_fee_type' => $bank_fee_type,
                        'net_amount' => $net_amount,
                        'failure_reason' => $failure_reason,
                        'upi_id' => $upi_id,
                        'utr_number' => $utr_number,
                        'settlement_type' => $settlement_type,
                        'initiated_by' => $initiated_by,
                        'remarks' => $remarks,
                        'payment_gateway' => $payment_gateway,
                        'settlement_batch_id' => $settlement_batch_id,
                        'currency' => $currency,
                        'settlement_status' => $settlement_status,
                        'wallet_balance_before' => $wallet_balance_before,
                        'wallet_balance_final' => $wallet_balance_final
                    ]);
                    
                    if ($settlement) {
                        // Added on 21-03-2025 by Ketan Gupta Start
                        $walletTransaction->update([
                            'settlement_status' => 'settled'
                        ]);
                        // Added on 21-03-2025 by Ketan Gupta End
                        $report[] = [
                            'status' => 'success',
                            'transaction_id' => $tnx,
                            'message' => 'Transaction id ' . $order_id . ' settled successfully!'
                        ];
                    } else {
                        $report[] = [
                            'status' => 'error',
                            'transaction_id' => $tnx,
                            'message' => 'Transaction id ' . $order_id . ' not settled!'
                        ];
                    }
                } elseif ($request->settlement_mode === "Bank") // for Bank settlement
                {
                    $report[] = [
                        'status' => 'error',
                        'transaction_id' => $tnx,
                        'message' => 'Bank settlement mode is under development!'
                    ];
                    continue;
                } else {
                    $report[] = [
                        'status' => 'error',
                        'transaction_id' => $tnx,
                        'message' => 'Unsupported settlement mode!'
                    ];
                    continue;
                }
            } catch (Exception $e) {
                $report[] = [
                    'status' => 'error',
                    'transaction_id' => $tnx,
                    'message' => 'Bulk settlement exception: ' . $e->getMessage()
                ];
                FacadesLog::error('Bulk settlement exception: ' . $e->getMessage(), ['exception' => $e]);
                continue;
            }
        }
        return response()->json(['message' => 'Job Completed', 'report' => $report], 200);
    }

    public function adminLoatWalletView()
    {
        $loadWallet = []; // LoadWalletRequest::select('load_wallet_requests.*','merchant_infos.acc_id as account_id','merchant_infos.merchant_name')->leftJoin('merchant_infos','load_wallet_requests.merchant_id','=','merchant_infos.merchant_id')->orderBy('created_at','DESC')->get();
        return $this->dashboardPage('admin.load-wallet', compact('loadWallet'));
    }

    public function adminBulkPayoutView()
    {
        $bulkPayout = []; // BulkPayoutRequest::select('bulk_payout_requests.*','merchant_infos.acc_id as account_id','merchant_infos.merchant_name')->leftJoin('merchant_infos','bulk_payout_requests.merchant_id','=','merchant_infos.merchant_id')->orderBy('created_at','DESC')->get();
        return $this->dashboardPage('admin.bulk-payout', compact('bulkPayout'));
    }

    public function changeTransactionVisibility(Request $request)
    {
        $request->validate([
            'transaction_id' => 'required|exists:wallet_transactions,transaction_id',
            'visibility' => 'required|in:hidden,visible'
        ]);

        $walletTransaction = WalletTransaction::where('transaction_id', $request->transaction_id)->first();
        if (!$walletTransaction) {
            return response()->json(['message' => 'Transaction not found!', 'status' => false], 404);
        }elseif($walletTransaction->type == 'debit') {
            return response()->json(['message' => 'Cannot change the visibility of payout transaction!', 'status' => false], 403);
        }
        $settlement = Settlement::where('order_id', $walletTransaction->transaction_id)->first();
        if ($settlement) {
            return response()->json(['message' => 'Cannot change the visibility of settled transaction!', 'status' => false], 403);
        }
        $merchant = MerchantInfo::where('merchant_id', $walletTransaction->merchant_id)->first();
        if (!$merchant) {
            return response()->json(['message' => 'Merchant not found!', 'status' => false], 404);
        }
        $merchantWallet = MerchantWallet::where('merchant_id', $merchant->merchant_id)->first();
        if (!$merchantWallet) {
            return response()->json(['message' => 'Merchant wallet not found!', 'status' => false], 404);
        }
        $merchantGateway = MerchantGateway::where('mid', $merchant->merchant_id)->where('status', 'active')->first();
        if (!$merchantGateway) {
            return response()->json(['message' => 'Merchant gateway not found!', 'status' => false], 404);
        }
        $ptGateway = PaymentGateway::find($merchantGateway->payin_gateway_id);
        if (!$ptGateway) {
            return response()->json(['message' => 'Payment gateway configuration error! Please contact support.', 'status' => false], 403);
        }
        if($walletTransaction->type == 'credit'){
            switch ($ptGateway->gateway_type) {
                case 'payin':
                case 'both':
                    break;
                default:
                    return response()->json(['message' => 'Payment gateway type error! Please contact support.'], 403);
            }
        }elseif($walletTransaction->type == 'debit'){
            return response()->json(['message' => 'Cannot change the visibility of payout transaction!', 'status' => false], 403);
        }else{
            return response()->json(['message' => 'Invalid transaction type!', 'status' => false], 403);
        }

        $oldPendingBalance = $merchantWallet->pending_balance;
        $adjustableAmount = (float)$walletTransaction->amount - (float)$walletTransaction->charge;
        $newPendingBalance = 0;

        if($request->visibility == 'visible'){
            $newPendingBalance = $oldPendingBalance + $adjustableAmount;
        }elseif($request->visibility == 'hidden'){
            $newPendingBalance = $oldPendingBalance - $adjustableAmount;
        }else{
            return response()->json(['message' => 'Invalid visibility value!', 'status' => false], 403);
        }

        $merchantWallet->update([
            'pending_balance' => $newPendingBalance
        ]);
        $walletTransaction->update([
            'visibility' => $request->visibility
        ]);

        return response()->json(['message' => 'Visibility Updated Successfully!', 'status' => true], 200);
    }

    public function fetchTransactionForEditAJAX(Request $request)
    {
        $request->validate([
            'transaction_id' => 'required|exists:transactions,order_id'
        ]);
        try {
            $transaction = Transaction::leftJoin('wallet_transactions', 'transactions.order_id', '=', 'wallet_transactions.transaction_id')
                ->leftJoin('merchant_infos', 'wallet_transactions.merchant_id', '=', 'merchant_infos.merchant_id')
                ->where('transactions.order_id', $request->transaction_id)
                ->select('merchant_infos.merchant_name', 'merchant_infos.merchant_email', 'merchant_infos.acc_id', 'transactions.order_id', 'transactions.trx_type as type', 'wallet_transactions.amount', 'wallet_transactions.charge', 'wallet_transactions.remarks', 'wallet_transactions.status as wallet_transaction_status', 'transactions.status as transaction_status', 'transactions.request_data as request_payload', 'transactions.response_data as response_payload')
                ->first();
            if (!$transaction) {
                return response()->json(['status' => false, 'message' => 'Transaction not found'], 404);
            }
            return response()->json(['status' => true, 'transaction' => $transaction], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Failed to fetch transaction details'], 500);
        }
    }

    public function updateTransactionManually(Request $request)
    {
        dd(json_decode($request->request_payload));
        $request->validate([
            'transaction_id' => 'required|exists:transactions,order_id'
        ]);

        $transaction = Transaction::where('order_id', $request->transaction_id)->first();
        $walletTransaction = WalletTransaction::where('transaction_id', $request->transaction_id)->first();

        if (!$transaction || !$walletTransaction) {
            return redirect()->back()->with('error', 'Transaction not found');
        }

        $merchant = MerchantInfo::where('merchant_id', $walletTransaction->merchant_id)->first();
        if (!$merchant) {
            return redirect()->back()->with('error', 'Merchant not found');
        }

        try {
            $updates = [];

            // Function to clean and properly format JSON
            function cleanJson($jsonString)
            {
                $jsonString = trim($jsonString, '"'); // Remove extra surrounding quotes
                $jsonString = stripslashes($jsonString); // Remove excessive backslashes

                $decodedJson = json_decode($jsonString, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    return json_encode($decodedJson, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                }

                return $jsonString; // If decoding fails, return as is
            }

            // Process request_payload
            if ($request->filled('request_payload')) {
                $cleanRequestPayload = cleanJson($request->request_payload);
                if ($cleanRequestPayload !== $transaction->request_data) {
                    $updates['request_data'] = $request->request_payload;
                }
            }

            // Process response_payload
            if ($request->filled('response_payload')) {
                $cleanResponsePayload = cleanJson($request->response_payload);
                if ($cleanResponsePayload !== $transaction->response_data) {
                    $updates['response_data'] = json_decode($request->response_payload);
                }
            }

            if ($request->filled('type') && $request->type !== $transaction->type) {
                $updates['type'] = $request->type;
            }
            if ($request->filled('transaction_status') && $request->transaction_status !== $transaction->status) {
                $updates['status'] = $request->transaction_status;
            }
            if (!empty($updates)) {
                $transaction->update($updates);
            }

            $walletUpdates = [];

            if ($request->filled('wallet_transaction_status') && $request->wallet_transaction_status !== $walletTransaction->status) {
                $walletUpdates['status'] = $request->wallet_transaction_status;
            }
            if ($request->filled('amount') && $request->amount !== $walletTransaction->amount) {
                $walletUpdates['amount'] = $request->amount;
            }
            if ($request->filled('charge') && $request->charge !== $walletTransaction->charge) {
                $walletUpdates['charge'] = $request->charge;
            }
            if (!empty($walletUpdates)) {
                $walletTransaction->update($walletUpdates);
            }

            return redirect()->back()->with('success', 'Transaction updated successfully');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function adminAgentsListView()
    {
        $agents = Agent::select('id', 'name', 'mobile', 'email', 'created_at')
        ->where('id','!=',0)
        ->where('status', '!=', 'deleted')->get();
        return $this->dashboardPage('admin.agents', compact('agents'));
    }

    public function agentDetailsView($id){
        $agent = Agent::find($id);
        if(!$agent){
            return redirect()->back()->with('error','Agent not found!');
        }
        $agentId = $agent->id;
        $beneficiaries = AgentBeneficiary::where('agent_id',$agentId)->get();
        return $this->dashboardPage('admin.agent-details', compact('agent','beneficiaries'));
    }

    public function beneficiaryUpdateStatus(Request $request)
    {
        $request->validate([
            'bene_id' => 'required|exists:agent_beneficiaries,id',
            'status' => 'required|in:pending,active,rejected,deleted'
        ]);

        try {
            $beneficiary = AgentBeneficiary::findOrFail($request->bene_id);
            $beneficiary->status = $request->status;
            $beneficiary->save();

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully.',
                'status' => $beneficiary->status
            ]);
        } catch (Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Failed to update status. ' . $e->getMessage()
            ], 500);
        }
    }

    
    public function adminMerchantSetting(Request $request, $id)
    {
        if (!$this->checkLoginStatus()) {
            return redirect()->to('/login')->with('error', 'Login is required!');
        }
        $agents = Agent::all();
        $payment_gateways = PaymentGateway::all();
        $merchant = MerchantInfo::where('merchant_status', '!=', 'Deleted')->find($id);
        $mGateway = MerchantGateway::where('status', '!=', 'blocked')->find($id);
        return $this->dashboardPage('admin.merchant-setting', compact('merchant', 'agents', 'mGateway','payment_gateways'));
    }

    public function adminMerchantSettingsUpdate(Request $request, $id)
    {
        if (!$this->checkLoginStatus()) {
            return redirect()->to('/login')->with('error', 'Login is required!');
        }
        $request->validate([
            'merchant_phone2' => 'nullable|numeric|digits:10',
            'merchant_zip' => 'nullable|numeric|digits:6',
            'merchant_profile' => 'nullable|mimes:png,jpg,jpeg,gif,svg,bmp|max:2048',
            'merchant_password_new' => 'nullable|min:8|different:merchant_password', // New password must be different
            'merchant_password_new_confirmed' => 'required_with:merchant_password_new|same:merchant_password_new',
            'rolling_charge' => 'required|numeric',
            'payout_v_charge' => 'required|numeric',
            'payout_failed_hits' => 'required|numeric',
            'payin_hit_charge' => 'required|numeric',
            'payin_failed_hits' => 'required|numeric',
            'payout_mode' => 'required|string',
            'settlement_type' => 'required|string',
            'callback_url' => 'nullable|url',
            'webhook_url' => 'nullable|url',
            'merchant_is_onboarded' => 'required|string',
            'merchant_is_verified' => 'required|string',
            'merchant_status' => 'required|string',
            'ip_protection' => 'required|string',
            'agent_id' => 'required|numeric',
        ], [
            'merchant_phone2.numeric' => 'The alternate phone number must be a numeric value.',
            'merchant_phone2.digits' => 'The alternate phone number must be exactly 10 digits.',
            'merchant_zip.numeric' => 'The zip code must be a numeric value.',
            'merchant_zip.digits' => 'The zip code must be exactly 6 digits.',
            'merchant_profile.mimes' => 'The profile picture must be of type: PNG, JPG, JPEG, GIF, SVG, or BMP.',
            'merchant_profile.max' => 'The profile picture size must not exceed 2MB.',
            'merchant_password_new.min' => 'The new password must be at least 8 characters.',
            'merchant_password_new.different' => 'The new password must be different from the old password.',
            'merchant_password_new_confirmed.required_with' => 'Please confirm the new password.',
            'merchant_password_new_confirmed.same' => 'The new password confirmation does not match.',
            'rolling_charge.required' => 'Rolling Charge is Required.',
            'rolling_charge.numeric' => 'The Rolling Charge must be a numeric value.',
            'payout_v_charge.required' => 'Payout V Charge is Required.',
            'payout_v_charge.numeric' => 'The Payout V Charge must be a numeric value.',
            'payout_failed_hits.required' => 'Payout Failed Hits is Required.',
            'payout_failed_hits.numeric' => 'The Payout Failed Hits must be a numeric value.',
            'payin_hit_charge.required' => 'Payout Hit Charge is Required.',
            'payin_hit_charge.numeric' => 'The Payout Hit Charge must be a numeric value.',
            'payin_failed_hits.required' => 'Payin Failed Hits is Required.',
            'payin_failed_hits.numeric' => 'The Payin Failed Hits must be a numeric value.',
            'payout_mode.required' => 'The Payout Mode is Required.',
            'payout_mode.string' => 'The Payout Mode must be a string value.',
            'settlement_type.required' => 'The Settlement Type is Required.',
            'settlement_type.string' => 'The Settlement Type must be a string value.',
            'callback_url.url' => 'The Callback Url must be a valid URL',
            'webhook_url.url' => 'The Webhook Url must be a valid URL',
            'merchant_is_onboarded.required' => 'The Merchant is Onboarded is Required.',
            'merchant_is_onboarded.string' => 'The Merchant is Onboarded must be a string value.',
            'merchant_is_verified.required' => 'The Merchant is Verified is Required.',
            'merchant_is_verified.string' => 'The Merchant is Verified must be a string value.',
            'merchant_status.required' => 'The Merchant Status is Required.',
            'merchant_status.string' => 'The Merchant Status must be a string value.',
            'ip_protection.required' => 'The IP Protection is Required.',
            'ip_protection.string' => 'The IP Protection must be a string value.',
            'agent_id.required' => 'The Agent ID is Required.',
            'agent_id.numeric' => 'The Agent ID must be a numeric value.',
        ]);

        try {
            $merchant = MerchantInfo::where('merchant_status', '!=', 'Deleted')->find($id);
            if ($merchant) {
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
                $merchant->rolling_charge = $request->rolling_charge;
                $merchant->payout_v_charge = $request->payout_v_charge;
                $merchant->payout_failed_hits = $request->payout_failed_hits;
                $merchant->payin_hit_charge = $request->payin_hit_charge;
                $merchant->payin_failed_hits = $request->payin_failed_hits;
                $merchant->payout_mode = $request->payout_mode;
                $merchant->settlement_type = $request->settlement_type;
                $merchant->callback_url = $request->callback_url;
                $merchant->webhook_url = $request->webhook_url;
                $merchant->merchant_is_onboarded = $request->merchant_is_onboarded;
                $merchant->merchant_is_verified = $request->merchant_is_verified;
                $merchant->merchant_status = $request->merchant_status;
                $merchant->ip_protection = $request->ip_protection;
                $merchant->agent_id = $request->agent_id;

                if ($request->merchant_password_new) {
                    $merchant->merchant_password = Hash::make($request->merchant_password_new);
                    $merchant->merchant_plain_password = $request->merchant_password_new;
                }
                if ($merchant->save()) {
                    $logDescription = [
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
    public function adminMerchantGatewaySettingsUpdate(Request $request, $id)
    {
        if (!$this->checkLoginStatus()) {
            return redirect()->to('/login')->with('error', 'Login is required!');
        }
        $request->validate([
            'mid' => 'required|numeric',
            'payin_gateway_id' => 'required|numeric',
            'payout_gateway_id' => 'required|numeric',
            'api_key' => 'nullable|string',
            'merchant_id' => 'nullable|string',
            'salt_key' => 'nullable|string',
            'payin_switch_amount' => 'required|numeric',
            'payin_charge' => 'required|numeric',
            'payin_charge_type' => 'required|string',
            'payin_charge2' => 'required|numeric',
            'payin_charge2_type' => 'required|string',
            'payout_switch_amount' => 'required|numeric',
            'payout_charge' => 'required|numeric',
            'payout_charge_type' => 'required|string',
            'payout_charge2' => 'required|numeric',
            'payout_charge2_type' => 'required|string',
            'tax_switch_amount' => 'required|numeric',
            'tax' => 'required|numeric',
            'tax_type' => 'required|string',
            'tax2' => 'required|numeric',
            'tax2_type' => 'required|string',
            'bank_fee_switch_amount' => 'required|numeric',
            'bank_fee' => 'required|numeric',
            'bank_fee_type' => 'required|string',
            'bank_fee2' => 'required|numeric',
            'bank_fee2_type' => 'required|string',
            'settlement_time' => 'nullable|string',
            'status' => 'required|string',
        ], [
            'mid.required' => 'The MID is required.',
            'mid.numeric' => 'The MID must be a numeric value.',
            'payin_gateway_id.required' => 'The Payin Gateway ID is required.',
            'payin_gateway_id.numeric' => 'The Payin Gateway ID must be a numeric value.',
            'payout_gateway_id.required' => 'The Payout Gateway ID is required.',
            'payout_gateway_id.numeric' => 'The Payout Gateway ID must be a numeric value.',
            'api_key.required' => 'The Api Key is Required.',
            'api_key.string' => 'The Api Key must be a string value.',
            'merchant_id.string' => 'The Merchant Id must be a string value.',
            'salt_key.string' => 'The Salt Key must be a string value.',
            'payin_switch_amount.required' => 'The Payin Switch Amount is required.',
            'payin_switch_amount.numeric' => 'The Payin Switch Amount must be a numeric value.',
            'payin_charge.required' => 'The Payin Charge is required.',
            'payin_charge.numeric' => 'The Payin Charge must be a numeric value.',
            'payin_charge_type.required' => 'The Payin Charge Type is required.',
            'payin_charge_type.string' => 'The Payin Charge Type must be a string.',
            'payin_charge2.required' => 'The Payin Charge2 is required.',
            'payin_charge2.numeric' => 'The Payin Charge2 must be a numeric value.',
            'payin_charge2_type.required' => 'The Payin Charge2 Type is required.',
            'payin_charge2_type.string' => 'The Payin Charge2 Type must be a string.',
            'payout_switch_amount.required' => 'The Payout Switch Amount is required.',
            'payout_switch_amount.numeric' => 'The Payout Switch Amount must be a numeric value.',
            'payout_charge.required' => 'The Payout Charge is required.',
            'payout_charge.numeric' => 'The Payout Charge must be a numeric value.',
            'payout_charge_type.required' => 'The Payout Charge Type is required.',
            'payout_charge_type.string' => 'The Payout Charge Type must be a string.',
            'payout_charge2.required' => 'The Payout Charge2 is required.',
            'payout_charge2.numeric' => 'The Payout Charge2 must be a numeric value.',
            'payout_charge2_type.required' => 'The Payout Charge2 Type is required.',
            'payout_charge2_type.string' => 'The Payout Charge2 Type must be a string.',
            'tax_switch_amount.required' => 'The Tax Switch Amount is required.',
            'tax_switch_amount.numeric' => 'The Tax Switch Amount must be a numeric value.',
            'tax.required' => 'The Tax is required.',
            'tax.numeric' => 'The Tax must be a numeric value.',
            'tax_type.required' => 'The Tax Type is required.',
            'tax_type.string' => 'The Tax Type must be a string.',
            'tax2.required' => 'The Tax2 is required.',
            'tax2.numeric' => 'The Tax2 must be a numeric value.',
            'tax2_type.required' => 'The Tax2 Type is required.',
            'tax2_type.string' => 'The Tax2 Type must be a string.',
            'bank_fee_switch_amount.required' => 'The Bank Fee Switch Amount is required.',
            'bank_fee_switch_amount.numeric' => 'The Bank Fee Switch Amount must be a numeric value.',
            'bank_fee.required' => 'The Bank Fee is required.',
            'bank_fee.numeric' => 'The Bank Fee must be a numeric value.',
            'bank_fee_type.required' => 'The Bank Fee Type is required.',
            'bank_fee_type.string' => 'The Bank Fee Type must be a string.',
            'bank_fee2.required' => 'The Bank Fee2 is required.',
            'bank_fee2.numeric' => 'The Bank Fee2 must be a numeric value.',
            'bank_fee2_type.required' => 'The Bank Fee2 Type is required.',
            'bank_fee2_type.string' => 'The Bank Fee2 Type must be a string.',
            'settlement_time.string' => 'The Settlement Time must be a string.',
            'status.required' => 'The Status is required.',
            'status.string' => 'The Status must be a string.'

        ]);

        try {
            $mGateway = MerchantGateway::where('status', '!=', 'blocked')->find($id);
            if ($mGateway) {
                $mGateway->mid = $request->mid;
                $mGateway->payin_gateway_id = $request->payin_gateway_id;
                $mGateway->payout_gateway_id = $request->payout_gateway_id;
                $mGateway->api_key = $request->api_key;
                $mGateway->merchant_id = $request->merchant_id;
                $mGateway->salt_key = $request->salt_key;
                $mGateway->payin_switch_amount = $request->payin_switch_amount;
                $mGateway->payin_charge = $request->payin_charge;
                $mGateway->payin_charge_type = $request->payin_charge_type;
                $mGateway->payin_charge2 = $request->payin_charge2;
                $mGateway->payin_charge2_type = $request->payin_charge2_type;
                $mGateway->payout_switch_amount = $request->payout_switch_amount;
                $mGateway->payout_charge = $request->payout_charge;
                $mGateway->payout_charge_type = $request->payout_charge_type;
                $mGateway->payout_charge2 = $request->payout_charge2;
                $mGateway->payout_charge2_type = $request->payout_charge2_type;
                $mGateway->tax_switch_amount = $request->tax_switch_amount;
                $mGateway->tax = $request->tax;
                $mGateway->tax_type = $request->tax_type;
                $mGateway->tax2 = $request->tax2;
                $mGateway->tax2_type = $request->tax2_type;
                $mGateway->bank_fee_switch_amount = $request->bank_fee_switch_amount;
                $mGateway->bank_fee = $request->bank_fee;
                $mGateway->bank_fee_type = $request->bank_fee_type;
                $mGateway->bank_fee2 = $request->bank_fee2;
                $mGateway->bank_fee2_type = $request->bank_fee2_type;
                $mGateway->settlement_time = $request->settlement_time;
                $mGateway->status = $request->status;

                if ($mGateway->save()) {
                    $logDescription = [
                        'presentInfo' => $mGateway,
                        'message' => 'Merchant Gateway updated successfully!'
                    ];
                    $this->saveLog('Merchant Gateway Update', json_encode($logDescription), $request->ip(), $request->userAgent());
                    return redirect()->back()->with('success', 'Merchant Gateway updated successfully!');
                } else {
                    $logDescription = [
                        'message' => 'Unable to update Merchant Gateway data into database right now! Please try again after sometimes.'
                    ];
                    $this->saveLog('Merchant Gateway Update', json_encode($logDescription), $request->ip(), $request->userAgent());
                    return redirect()->back()->with('error', 'Something went wrong! Please check activity log for more details.');
                }
            } else {
                $logDescription = [
                    'message' => 'Merchant not found!'
                ];
                $this->saveLog('Merchant Gateway Update', json_encode($logDescription), $request->ip(), $request->userAgent());
                return redirect()->back()->with('error', 'Something went wrong! Please check activity log for more details.');
            }
        } catch (Exception $e) {
            $logDescription = [
                'message' => $e->getMessage()
            ];
            $this->saveLog('Merchant Gateway Update Exception', json_encode($logDescription), $request->ip(), $request->userAgent());
            return redirect()->back()->with('error', 'Something went wrong! Please check activity log for more details.');
        }
    }
    
    public function updatePaymentLimits(Request $request){
        // return response()->json($request->all());
        $request->validate([
            'payin_min_amt' => 'required|numeric|min:1',
            'payin_max_amt' => 'required|numeric|min:1',
            'payout_min_amt' => 'required|numeric|min:1',
            'payout_max_amt' => 'required|numeric|min:1',
        ],[
            'payin_min_amt.required' => 'Payin minimum amount is required!',
            'payin_min_amt.numeric' => 'Payin minimum amount is required!',
            'payin_min_amt.min' => 'Payin minimum amount is required!',
        ]);
        $admin = Admin::find(Session::get('userId'));
        if($admin){
            try{
                if($request->payin_min_amt > $request->payin_max_amt){
                    return redirect()->back()->with('error','Payin minimum amount can not be greater then Payin maximum amount!');
                }
                if($request->payout_min_amt > $request->payout_max_amt){
                    return redirect()->back()->with('error','Payout minimum amount can not be greater then Payout maximum amount!');
                }
                $admin->update([
                    'payin_min_amt' => $request->payin_min_amt,
                    'payin_max_amt' => $request->payin_max_amt,
                    'payout_min_amt' => $request->payout_min_amt,
                    'payout_max_amt' => $request->payout_max_amt
                ]);
                return redirect()->back()->with('success','Payment limits updated successfully!');
            }catch(Exception $e){
                $this->saveLog("Update payment limits exception",$e->getMessage(),$request->ip(),$request->userAgent());
                return response()->json(['message' => $e->getMessage()],500);
            }
        }
    }

    // public function makeFirstAdmin()
    // {
    //     $name = 'Admin';
    //     $phone = '1234567890';
    //     $email = 'admin@gmail.com';
    //     $password = '1234';
    //     $type = 'Super Admin';

    //     $hashedPassword = Hash::make($password);
    //     $check = Admin::create([
    //         'admin_name' => $name,
    //         'admin_phone' => $phone,
    //         'admin_email' => $email,
    //         'admin_password' => $hashedPassword,
    //         'admin_plain_password' => $password,
    //         'admin_type' => $type,
    //     ]);
    //     return response()->json($check);
    // }
}
