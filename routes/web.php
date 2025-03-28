<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\api\v1\PaykuberController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MerchantController;
use App\Http\Controllers\WebController;
use Illuminate\Support\Facades\Route;

// Web Controller Routes
Route::controller(WebController::class)->group(function () {
    Route::get('/', 'homeView');
    Route::get('/contact-us', 'contactView');
  	Route::get('/about', 'aboutView');
    Route::get('/resources', 'resourcesView');
    Route::get('/merchants', 'merchantsView');
    Route::get('/payments', 'paymentsView');
    Route::get('/login', 'loginView');
    Route::get('/logout', 'logout');
});

// Auth Controller Routes
Route::controller(AuthController::class)->group(function () {
    Route::get('/dashboard', 'navigateToDashboard');
    Route::post('/login/submit', 'loginSubmit');
});

// Merchant Controller Routes
Route::prefix('/merchant')->controller(MerchantController::class)->group(function () {
    Route::get('/dashboard', 'merchantDashboardView');
    Route::get('/transaction', 'merchantTransactionView');
    Route::get('/settlement/report', 'merchantSettlementReportsView');
    Route::get('/logs', 'merchantLogsView');
    Route::get('/developer-section', 'developerSectionView');
    Route::get('/payin', 'merchantPayinView');
    Route::get('/payout', 'merchantPayoutView');
    Route::post('/add/beneficiary', 'addBeneficiary');
  	Route::put('/update/beneficiary/{id}', 'updateBeneficiary');
    Route::delete('/delete/beneficiary/{id}', 'deleteBeneficiary');
    Route::post('/initiate/payout', 'initiatePayout');
  	Route::get('/fund-request', 'fundRequestView');
  	Route::get('/beneficiaries', 'beneficiariesView');
    Route::get('/topup-details', 'topupDetailsView');
    Route::prefix('/onboarding')->group(function () {
        Route::get('/', 'merchantOnboardingView');
        Route::post('/step-{id}', 'merchantOnboardingStepsAJAX');
        Route::post('/check-{type}', 'merchantOnboardingDataCheckAJAX');
    });
    Route::prefix('/account/details')->group(function () {
        Route::get('/', 'merchantAccountDetailsView');
        Route::post('/update', 'merchantAccountDetailsUpdate');
    });
    Route::prefix('/url/whitelisting')->group(function () {
        Route::get('/', 'merchantUrlWhitelistingView');
        Route::post('/request-{type}', 'merchantUrlWhitelistingRequest');
    });
    Route::prefix('/settings')->group(function () {
        Route::get('/', 'merchantSettingsView');
        Route::post('/update', 'merchantSettingsUpdate');
    });
});

// Admin Controller Routes
Route::prefix('/admin')->controller(AdminController::class)->group(function () {
    Route::get('/dashboard', 'adminDashboardView');
    Route::get('/settlement/report', 'adminSettlementReportsView');
    Route::get('/logs', 'adminLogsView');
    Route::get('/transaction', 'adminTransactionView');
  	Route::get('/load-wallet', 'adminLoatWalletView');
    Route::get('/bulk-payout', 'adminBulkPayoutView');
    Route::post('/transaction/visibility/update', 'changeTransactionVisibility');
    Route::post('/transaction/fetch', 'fetchTransactionForEditAJAX');
    Route::post('transaction/update', 'updateTransactionManually');
    Route::prefix('/settlement')->group(function(){
        Route::post('/bulk/fetch','fetchUnsettledBulkData');
        Route::post('/bulk','bulkSettlementByAdmin');
        Route::post('/manual','manualSettlementByAdmin');
    });
    Route::prefix('/merchant')->group(function () {
        Route::get('/fetch', 'adminMerchantFetchAJAX');
        Route::post('/delete', 'adminMerchantDeleteAJAX');
        Route::post('/approval-{action}', 'adminMerchantApprovalAJAX');
        Route::prefix('/approval')->group(function () {
            Route::get('/', 'adminMerchantApprovalView');
            Route::get('/view-{id}', 'adminMerchantView');
            Route::post('/update/merchant-info', 'adminMerchantInfoUpdate');
            Route::post('/update/business-info', 'adminMerchantBusinessInfoUpdate');
            Route::post('/update/kyc-doc', 'adminMerchantKycDocUpdate');
            Route::get('/setting-{id}', 'adminMerchantSetting');
            Route::post('/setting-update-{id}', 'adminMerchantSettingsUpdate');
            Route::post('/gateway-setting-update-{id}', 'adminMerchantGatewaySettingsUpdate');
        });
    });
    Route::prefix('/account/details')->group(function () {
        Route::get('/', 'adminAccountDetailsView');
        Route::get('/view-{id}', 'adminAccountDetailsEditView');
        Route::get('/status/{status}-{id}', 'adminAccountDetailsChangeStatus');
        Route::post('/update', 'adminAccountDetailsUpdate');
    });
    Route::prefix('/url/whitelisting')->group(function () {
        Route::get('/', 'adminUrlWhitelistingView');
        Route::get('/request-active-{id}', 'adminUrlWhitelistingRequestActive');
        Route::get('/request-inactive{id}', 'adminUrlWhitelistingRequestInactive');
        Route::get('/request-delete-{id}', 'adminUrlWhitelistingRequestDelete');
    });
    Route::prefix('/settings')->group(function () {
        Route::get('/', 'adminSettingsView');
        Route::post('/update-admin', 'adminSettingsUpdateAdmin');
        Route::post('/update/payment/limits', 'updatePaymentLimits');
    });
    Route::prefix('/ajax')->group(function(){
        Route::prefix('/fetch')->group(function(){
            Route::post('/transaction','fetchTransactionAJAX');
        });
    });
    //Route added by Chandan Raj
    Route::prefix('/agents')->group(function(){
        Route::get('/', 'adminAgentsListView');
        Route::get('/view-{id}', 'agentDetailsView');
        Route::post('/beneficiary-status-update', 'beneficiaryUpdateStatus');
    });
});

// Agent Controller
Route::prefix('/agent')->controller(AgentController::class)->group(function (){
    Route::get('/dashboard',"dashbaordView");
    Route::get('/merchant/transactions',"merchantTransactions");
    // Route::get('/settings',"agentSetting");
    // Route::get('/create',"createAgent");

    //Route added by Chandan Raj
    Route::get('/account',"agentAccountView");
    Route::get('/merchants',"agentMerchantsView");
    Route::get('/merchants/view-{id}', 'agentMerchantProfile');
    Route::get('/payout',"agentPayoutView");
    Route::get('/payin-merchant',"agentMerchantPayinView");
    Route::get('/payout-merchant',"agentMerchantPayoutView");
    Route::get('/fetch-transactions-payin','fetchTransactionsPayin');
    Route::get('/fetch-transactions-payout','fetchTransactionsPayout');

    Route::get('/beneficiaries',"agentBeneficiariesView");
    Route::post('/add/beneficiary', 'addBeneficiary');
  	Route::put('/update/beneficiary/{id}', 'updateBeneficiary');
    Route::delete('/delete/beneficiary/{id}', 'deleteBeneficiary');
    
    Route::prefix('/settings')->group(function () {
        Route::get('/', 'agentSettingsView');
        Route::post('/update', 'agentSettingsUpdate');
    });
    Route::get('/logs', 'agentLogsView');
    Route::get('/merchants-payin/view-{id}', 'agentMerchantsPayinView');
    Route::get('/merchants-payout/view-{id}', 'agentMerchantsPayoutView');
    Route::get('/merchants-transactions/view-{id}', 'agentMerchantsTransactionsView');
});

Route::get('/pay-{id}',[PaykuberController::class,'paymentRedirecter']);

// Route::get('/make/first-admin',[AdminController::class,'makeFirstAdmin']);
