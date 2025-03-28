<?php

namespace App\Http\Middleware;

use App\Models\ApiLog;
use Closure;
use Illuminate\Http\Request;
use App\Models\ApiToken;
use App\Models\MerchantApiHitLimit;
use App\Models\MerchantInfo;
use App\Models\UrlWhiteListing;
use Illuminate\Support\Carbon;

class ApiAuthMiddleware
{
    private function checkOverallHits(MerchantApiHitLimit $apiHits)
    {
        $overallHits = $apiHits->overall_hit_limit;
        $usedPayinHits = $apiHits->payin_hits;
        $usedPayoutHits = $apiHits->payout_hits;
        $usedBalanceCheckHits = $apiHits->balance_check_hits;
        $usedTransactionCheckHits = $apiHits->transaction_check_hits;
        $usedWebhookHits = $apiHits->webhook_hits;
        $usedCallbackHits = $apiHits->callback_hits;
        $usedOverallHits = $usedPayinHits + $usedPayoutHits + $usedBalanceCheckHits + $usedTransactionCheckHits + $usedWebhookHits + $usedCallbackHits;
        return $usedOverallHits >= $overallHits;
    }

    private function payinHitLimitCounter(MerchantApiHitLimit $apiHits, $accid)
    {
        $limit = $apiHits->payin_hit_limit;
        $time = $apiHits->payin_hit_time;
        // Count Payin Hits in the last 'payin_hit_time' minutes
        $previousHits = ApiLog::where('event', 'Paykuber Payin Hit')
            ->where('request_payload', 'like', "%$accid%")
            ->where('created_at', '>=', Carbon::now()->subMinutes($time))
            ->count();
        // If hits exceed limit, block the request
        if ($previousHits >= $limit || $this->checkOverallHits($apiHits)) {
            return response()->json(['message' => 'Payin hit limit reached'], 429);
        }
        // Increment Payin Hits
        $apiHits->increment('payin_hits');
        return null;
    }

    private function payoutHitLimitCounter(MerchantApiHitLimit $apiHits, $accid)
    {
        $limit = $apiHits->payout_hit_limit;
        $time = $apiHits->payout_hit_time;

        // Count Payout Hits in the last 'payout_hit_time' minutes
        $previousHits = ApiLog::where('event', 'Paykuber Payout Request')
            ->where('request_payload', 'like', "%$accid%")
            ->where('created_at', '>=', Carbon::now()->subMinutes($time))
            ->count();

        // If hits exceed limit, block the request
        if ($previousHits >= $limit || $this->checkOverallHits($apiHits)) {
            return response()->json(['message' => 'Payout hit limit reached'], 429);
        }

        // Increment Payout Hits
        $apiHits->increment('payout_hits');
        return null;
    }

    private function trxStatusHitLimitCounter(MerchantApiHitLimit $apiHits, $accid) 
    {
        $limit = $apiHits->transaction_check_hits_limit;
        $time = $apiHits->transaction_check_hits_time;

        // Count Transaction Status Hits in the last 'transaction_check_hits_time' minutes
        $previousHits = ApiLog::where('event', 'Paykuber Transaction Status')
            ->where('request_payload', 'like', "%$accid%")
            ->where('created_at', '>=', Carbon::now()->subMinutes($time))
            ->count();

        // If hits exceed limit, block the request
        if ($previousHits >= $limit || $this->checkOverallHits($apiHits)) {
            return response()->json(['message' => 'Transaction Status hit limit reached'], 429);
        }

        // Increment Transaction Status Hits
        $apiHits->increment('transaction_check_hits');
        return null;
    }

    private function balanceCheckHitLimitCounter(MerchantApiHitLimit $apiHits, $accid) 
    {
        $limit = $apiHits->balance_check_hits_limit;
        $time = $apiHits->balance_check_hits_time;

        // Count Balance Check Hits in the last 'balance_check_hits_time' minutes
        $previousHits = ApiLog::where('event', 'Balance Check Request')
            ->where('request_payload', 'like', "%$accid%")
            ->where('created_at', '>=', Carbon::now()->subMinutes($time))
            ->count();

        // If hits exceed limit, block the request
        if ($previousHits >= $limit || $this->checkOverallHits($apiHits)) {
            return response()->json(['message' => 'Balance Check hit limit reached'], 429);
        }

        // Increment Balance Check Hits
        $apiHits->increment('balance_check_hits');
        return null;
    }

    public function handle(Request $request, Closure $next)
    {
        $token = $request->header('Authorization');

        if (!$token) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $token = str_replace('Bearer ', '', $token);
        $apiToken = ApiToken::where('token', $token)->first();

        if (!$apiToken) {
            return response()->json(['message' => 'Invalid token'], 401);
        }

        if ($apiToken->isExpired()) {
            return response()->json(['message' => 'Token expired'], 401);
        }

        $merchant = MerchantInfo::where('acc_id', $request->acc_id)->first();

        if (!$merchant) {
            return response()->json(['message' => 'Merchant not found!'], 401);
        }

        if ($merchant->ip_protection === 'on') {
            $ipAllowed = UrlWhiteListing::where('uwl_merchant_id', $merchant->merchant_id)
                ->where('uwl_ip_address', $request->server('REMOTE_ADDR'))
                ->exists();
            if (!$ipAllowed) {
                return response()->json(['message' => 'IP not whitelisted!'], 401);
            }
        }
        $merchantApiHitLimit = MerchantApiHitLimit::where('merchant_id', $merchant->merchant_id)->where('status', 'active')->first();
        if ($merchantApiHitLimit) {
            if ($request->getRequestUri() == "/api/v1/pay-request") {
                $checkLimit = $this->payinHitLimitCounter($merchantApiHitLimit, $request->acc_id);
                if($checkLimit){
                    return $checkLimit;
                }
            }
            if ($request->getRequestUri() == "/api/v1/payout-request") {
                $checkLimit = $this->payoutHitLimitCounter($merchantApiHitLimit, $request->acc_id);
                if($checkLimit){
                    return $checkLimit;
                }
            }
            if ($request->getRequestUri() == "/api/v1/seamless/txn-status") {
                $checkLimit = $this->trxStatusHitLimitCounter($merchantApiHitLimit, $request->acc_id);
                if($checkLimit){
                    return $checkLimit;
                }
            }
            if ($request->getRequestUri() == "/api/v1/balance") {
                $checkLimit = $this->balanceCheckHitLimitCounter($merchantApiHitLimit, $request->acc_id);
                if($checkLimit){
                    return $checkLimit;
                }
            }
        }

        // Attach token data to the request for further use
        $request->attributes->set('api_token', $apiToken);

        return $next($request);
    }
}
