<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Account Details</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">Account Details</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<!-- end page title -->
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <form action="{{ url('/merchant/account/details/update') }}" method="post">
                @csrf
                <input type="hidden" name="merchant_id" value="{{ Session::get('userId') }}">
                <input type="hidden" name="business_id"
                    value="@isset($account){{ $account->acc_business_id }}@endisset">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="form-floating">
                            <input type="text" name="acc_bank_name" id="acc_bank_name" class="form-control"
                                value="@isset($account){{ $account->acc_bank_name }}@endisset"
                                placeholder="Bank Name" @isset($account) readonly @endisset>
                            <label for="acc_bank_name">Bank Name <span class="text-danger">*</span></label>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-floating">
                            <input type="text" name="acc_branch_name" id="acc_branch_name" class="form-control"
                                value="@isset($account){{ $account->acc_branch_name }}@endisset"
                                placeholder="Branch Name">
                            <label for="acc_branch_name">Branch Name</label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <input type="text" name="acc_account_number" id="acc_account_number" class="form-control"
                                value="@isset($account){{ $account->acc_account_number }}@endisset"
                                placeholder="Account Number" @isset($account) readonly @endisset>
                            <label for="acc_account_number">Account Number <span class="text-danger">*</span></label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <input type="text" name="acc_ifsc_code" id="acc_ifsc_code" class="form-control"
                                value="@isset($account){{ $account->acc_ifsc_code }}@endisset"
                                placeholder="IFSC Code" @isset($account) readonly @endisset>
                            <label for="acc_ifsc_code">IFSC Code <span class="text-danger">*</span></label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <input type="text" name="acc_micr_code" id="acc_micr_code" class="form-control"
                                value="@isset($account){{ $account->acc_micr_code }}@endisset"
                                placeholder="MICR Code">
                            <label for="acc_micr_code">MICR Code</label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <input type="text" name="acc_swift_code" id="acc_swift_code" class="form-control"
                                value="@isset($account){{ $account->acc_swift_code }}@endisset"
                                placeholder="Swift Code">
                            <label for="acc_swift_code">Swift Code</label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <select name="acc_account_type" id="acc_account_type" class="form-control">
                                <option value="">Select</option>
                                @if (isset($account))
                                    <option value="{{ $account->acc_account_type }}" selected>
                                        {{ $account->acc_account_type }}</option>
                                @else
                                    <option value="Business">Business</option>
                                    <option value="Current">Current</option>
                                    <option value="Savings">Savings</option>
                                    <option value="Other">Other</option>
                                @endif
                            </select>
                            <label for="acc_account_type">Account Type <span class="text-danger">*</span></label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <h5>USDT Account Details</h5>
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="network_type">Network Type</label>
                        <select class="form-control" name="network_type" id="network_type">
                            <option>Select Network Type</option>
                            <option value="TRC20" @isset($account){{ $account->network_type == 'TRC20' ? 'selected' : '' }}@endisset>TRC20</option>
                            <option value="ERC20" @isset($account){{ $account->network_type == 'ERC20' ? 'selected' : '' }}@endisset>ERC20</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="wallet_address">Wallet Address</label>
                        <input type="text" name="wallet_address" id="wallet_address" class="form-control"
                          value="@isset($account){{ $account->wallet_address }}@endisset"  placeholder="Wallet Address">
                    </div>
                    <div class="col-md-12 text-end">
                        <input type="submit" value="Save/Update" class="btn btn-primary rounded-pill">
                    </div>
                </div>                
            </form>
        </div>
    </div>
</div>
