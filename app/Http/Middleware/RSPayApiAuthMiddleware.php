<?php

namespace App\Http\Middleware;

use App\Models\ApiLog;
use App\Models\MerchantApiHitLimit;
use App\Models\MerchantGateway;
use App\Models\MerchantInfo;
use App\Models\UrlWhiteListing;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\Response;

class RSPayApiAuthMiddleware
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
        $previousHits = ApiLog::where('event', 'Payin Hit')
            ->where('request_payload', 'like', "%$accid%")
            ->where('created_at', '>=', Carbon::now()->subMinutes($time))
            ->count();
        if ($previousHits >= $limit || $this->checkOverallHits($apiHits)) {
            return response()->json(['message' => 'Payin hit limit reached'], 429);
        }
        $apiHits->increment('payin_hits');
        return null;
    }
    private function payoutHitLimitCounter(MerchantApiHitLimit $apiHits, $accid)
    {
        $limit = $apiHits->payout_hit_limit;
        $time = $apiHits->payout_hit_time;
        $previousHits = ApiLog::where('event', 'Payout Request')
            ->where('request_payload', 'like', "%$accid%")
            ->where('created_at', '>=', Carbon::now()->subMinutes($time))
            ->count();
        if ($previousHits >= $limit || $this->checkOverallHits($apiHits)) {
            return response()->json(['message' => 'Payout hit limit reached'], 429);
        }
        $apiHits->increment('payout_hits');
        return null;
    }
    private function trxStatusHitLimitCounter(MerchantApiHitLimit $apiHits, $accid) 
    {
        $limit = $apiHits->transaction_check_hits_limit;
        $time = $apiHits->transaction_check_hits_time;
        $previousHits = ApiLog::where('event', 'Transaction Status')
            ->where('request_payload', 'like', "%$accid%")
            ->where('created_at', '>=', Carbon::now()->subMinutes($time))
            ->count();
        if ($previousHits >= $limit || $this->checkOverallHits($apiHits)) {
            return response()->json(['message' => 'Transaction Status hit limit reached'], 429);
        }
        $apiHits->increment('transaction_check_hits');
        return null;
    }
    private function balanceCheckHitLimitCounter(MerchantApiHitLimit $apiHits, $accid) 
    {
        $limit = $apiHits->balance_check_hits_limit;
        $time = $apiHits->balance_check_hits_time;
        $previousHits = ApiLog::where('event', 'Balance Check Request')
            ->where('request_payload', 'like', "%$accid%")
            ->where('created_at', '>=', Carbon::now()->subMinutes($time))
            ->count();
        if ($previousHits >= $limit || $this->checkOverallHits($apiHits)) {
            return response()->json(['message' => 'Balance Check hit limit reached'], 429);
        }
        $apiHits->increment('balance_check_hits');
        return null;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header('Authorization');
        if (!$token) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $token = str_replace('Bearer ', '', $token);
        $apiToken = MerchantGateway::leftJoin('merchant_infos','merchant_gateways.mid','=','merchant_infos.merchant_id')
            ->where('merchant_gateways.api_key', $token)
            ->where('merchant_infos.acc_id',$request->acc_id)
            ->select('merchant_gateways.*')
            ->first();
        if (!$apiToken || $apiToken->status != 'active') {
            return response()->json(['message' => 'You are not allowed to perform any request! Please contact admin for more details.'], 401);
        }
        $merchant = MerchantInfo::where('merchant_id', $apiToken->mid)->first();
        if(!$merchant || $merchant->merchant_status != 'Active'){
            return response()->json(['message' => 'You are not allowed to perform any request! Please contact admin for more details.'], 401);
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
            if ($request->getRequestUri() == "/api/pay/v1/pay-request") {
                $checkLimit = $this->payinHitLimitCounter($merchantApiHitLimit, $request->acc_id);
                if($checkLimit){
                    return $checkLimit;
                }
            }
            if ($request->getRequestUri() == "/api/pay/v1/payout-request") {
                $checkLimit = $this->payoutHitLimitCounter($merchantApiHitLimit, $request->acc_id);
                if($checkLimit){
                    return $checkLimit;
                }
            }
            if ($request->getRequestUri() == "/api/pay/v1/seamless/txn-status") {
                $checkLimit = $this->trxStatusHitLimitCounter($merchantApiHitLimit, $request->acc_id);
                if($checkLimit){
                    return $checkLimit;
                }
            }
            if ($request->getRequestUri() == "/api/pay/v1/balance") {
                $checkLimit = $this->balanceCheckHitLimitCounter($merchantApiHitLimit, $request->acc_id);
                if($checkLimit){
                    return $checkLimit;
                }
            }
        }
        $request->attributes->set('api_token', $apiToken);
        return $next($request);
    }
}
