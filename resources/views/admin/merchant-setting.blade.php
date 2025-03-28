<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Merchant Setting</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">Merchant Setting</li>
                </ol>
            </div>

        </div>
    </div>
</div>
<!-- end page title -->
@isset($merchant)
    <div class="card">
        <div class="card-body">
            <h3>Personal Info</h3>
            <form action="{{ url('/admin/merchant/approval/setting-update-' . $merchant->merchant_id) }}" method="post"
                enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <input type="text" name="merchant_name" id="merchant_name" class="form-control"
                                placeholder="Name" value="{{ $merchant->merchant_name }}" readonly>
                            <label for="merchant_name">Name <span class="text-danger">*</span></label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <input type="email" name="merchant_email" id="merchant_email" class="form-control"
                                placeholder="Email" value="{{ $merchant->merchant_email }}" readonly>
                            <label for="merchant_email">Email <span class="text-danger">*</span></label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <input type="text" name="merchant_phone" id="merchant_phone" class="form-control"
                                placeholder="Primary Phone" value="{{ $merchant->merchant_phone }}" readonly>
                            <label for="merchant_phone">Primary Phone <span class="text-danger">*</span></label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <input type="text" name="merchant_phone2" id="merchant_phone2" class="form-control"
                                placeholder="Secondary Phone" value="{{ $merchant->merchant_phone2 }}">
                            <label for="merchant_phone2">Secondary Phone</label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <input type="text" name="merchant_aadhar_no" id="merchant_aadhar_no" class="form-control"
                                placeholder="Aadhar Number" value="{{ $merchant->merchant_aadhar_no }}" readonly>
                            <label for="merchant_aadhar_no">Aadhar Number <span class="text-danger">*</span></label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <input type="text" name="merchant_pan_no" id="merchant_pan_no" class="form-control"
                                placeholder="PAN Number" value="{{ $merchant->merchant_pan_no }}" readonly>
                            <label for="merchant_pan_no">PAN Number <span class="text-danger">*</span></label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <input type="file" name="merchant_profile" id="merchant_profile" accept="image/*"
                                class="form-control" placeholder="Profile Photo">
                            <label for="merchant_profile">Profile Photo</label>
                        </div>
                    </div>
                </div>
                <h3>Address</h3>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <input type="text" name="merchant_city" id="merchant_city" class="form-control"
                                placeholder="City" value="{{ $merchant->merchant_city }}">
                            <label for="admin_city">City</label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <input type="text" name="merchant_state" id="merchant_state" class="form-control"
                                placeholder="State" value="{{ $merchant->merchant_state }}">
                            <label for="merchant_state">State</label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <input type="text" name="merchant_country" id="merchant_country" class="form-control"
                                placeholder="Country"
                                value="{{ !$merchant->merchant_country ? 'India' : $merchant->merchant_country }}">
                            <label for="merchant_country">Country</label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <input type="text" name="merchant_zip" id="merchant_zip" class="form-control"
                                placeholder="Zip Code" value="{{ $merchant->merchant_zip }}">
                            <label for="merchant_zip">Zip Code</label>
                        </div>
                    </div>
                    <div class="col-md-8 mb-3">
                        <div class="form-floating">
                            <input type="text" name="merchant_landmark" id="merchant_landmark" class="form-control"
                                placeholder="Landmark" value="{{ $merchant->merchant_landmark }}">
                            <label for="merchant_landmark">Landmark</label>
                        </div>
                    </div>
                </div>
                <h3>Password</h3>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <input type="password" name="merchant_password_new" id="merchant_password_new"
                                class="form-control" placeholder="New Password" autocomplete="new-password">
                            <label for="merchant_password_new">New Password</label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <input type="password" name="merchant_password_new_confirmed"
                                id="merchant_password_new_confirmed" class="form-control"
                                placeholder="Confirm New Password">
                            <label for="merchant_password_new_confirmed">Confirm New Password</label>
                        </div>
                    </div>
                </div>
                <h3>Others</h3>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <input type="text" name="rolling_charge" id="rolling_charge" class="form-control"
                                placeholder="Rolling Charge" value="{{ $merchant->rolling_charge }}">
                            <label for="rolling_charge">Rolling Charge</label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <input type="text" name="payout_v_charge" id="payout_v_charge" class="form-control"
                                placeholder="Payout V Charge" value="{{ $merchant->payout_v_charge }}">
                            <label for="payout_v_charge">Payout V Charge</label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <input type="text" name="payout_failed_hits" id="payout_failed_hits" class="form-control"
                                placeholder="Payout Failed Hits" value="{{ $merchant->payout_failed_hits }}">
                            <label for="payout_failed_hits">Payout Failed Hits</label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <input type="text" name="payin_hit_charge" id="payin_hit_charge" class="form-control"
                                placeholder="Payout Hit Charge" value="{{ $merchant->payin_hit_charge }}">
                            <label for="payin_hit_charge">Payout Hit Charge</label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <input type="text" name="payin_failed_hits" id="payin_failed_hits" class="form-control"
                                placeholder="Payin Failed Hits" value="{{ $merchant->payin_failed_hits }}">
                            <label for="payin_failed_hits">Payin Failed Hits</label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <select name="payout_mode" id="payout_mode" class="form-control" placeholder="Payout Mode">
                                <option>Select Payout Mode</option>
                                <option value="direct"
                                    @isset($merchant){{ $merchant->payout_mode == 'direct' ? 'selected' : '' }}@endisset>
                                    Direct</option>
                                <option value="processing"
                                    @isset($merchant){{ $merchant->payout_mode == 'processing' ? 'selected' : '' }}@endisset>
                                    Processing</option>
                            </select>
                            <label for="payout_mode">Payout Mode</label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <select name="settlement_type" id="settlement_type" class="form-control"
                                placeholder="settlement_type">
                                <option>Select Settlement Type</option>
                                <option value="time_range"
                                    @isset($merchant){{ $merchant->settlement_type == 'time_range' ? 'selected' : '' }}@endisset>
                                    Time Range</option>
                                <option value="fixed_hours"
                                    @isset($merchant){{ $merchant->settlement_type == 'fixed_hours' ? 'selected' : '' }}@endisset>
                                    Fixed Hours</option>
                                <option value="t_plus_days"
                                    @isset($merchant){{ $merchant->settlement_type == 't_plus_days' ? 'selected' : '' }}@endisset>
                                    T Plus Days</option>
                            </select>
                            <label for="settlement_type">Settlement Type</label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <input type="url" name="callback_url" id="callback_url" class="form-control"
                                placeholder="Callback Url" value="{{ $merchant->callback_url }}">
                            <label for="callback_url">Callback Url</label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <input type="url" name="webhook_url" id="webhook_url" class="form-control"
                                placeholder="Webhook Url" value="{{ $merchant->webhook_url }}">
                            <label for="webhook_url">Webhook Url</label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <select name="merchant_is_onboarded" id="merchant_is_onboarded" class="form-control"
                                placeholder="Merchant Onboarded">
                                <option>Select Merchant Onboarded</option>
                                <option value="Yes"
                                    @isset($merchant){{ $merchant->merchant_is_onboarded == 'Yes' ? 'selected' : '' }}@endisset>
                                    Yes</option>
                                <option value="No"
                                    @isset($merchant){{ $merchant->merchant_is_onboarded == 'No' ? 'selected' : '' }}@endisset>
                                    No</option>
                            </select>
                            <label for="merchant_is_onboarded">Merchant is Onboarded</label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <select name="merchant_is_verified" id="merchant_is_verified" class="form-control"
                                placeholder="Merchant Verified">
                                <option>Select Merchant Verified</option>
                                <option value="Approved"
                                    @isset($merchant){{ $merchant->merchant_is_verified == 'Approved' ? 'selected' : '' }}@endisset>
                                    Approved</option>
                                <option value="Not Approved"
                                    @isset($merchant){{ $merchant->merchant_is_verified == 'Not Approved' ? 'selected' : '' }}@endisset>
                                    Not Approved</option>
                            </select>
                            <label for="merchant_is_verified">Merchant is Verified</label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <select name="merchant_status" id="merchant_status" class="form-control"
                                placeholder="Merchant Status">
                                <option>Select Merchant Status</option>
                                <option value="Active"
                                    @isset($merchant){{ $merchant->merchant_status == 'Active' ? 'selected' : '' }}@endisset>
                                    Active</option>
                            </select>
                            <label for="merchant_status">Merchant Status</label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <select name="ip_protection" id="ip_protection" class="form-control"
                                placeholder="IP Protection">
                                <option>Select IP Protection</option>
                                <option value="on"
                                    @isset($merchant){{ $merchant->ip_protection == 'on' ? 'selected' : '' }}@endisset>
                                    On</option>
                                <option value="off"
                                    @isset($merchant){{ $merchant->ip_protection == 'off' ? 'selected' : '' }}@endisset>
                                    Off</option>
                            </select>
                            <label for="ip_protection">IP Protection</label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <select name="agent_id" id="agent_id" class="form-control" placeholder="Agent">
                                <option>Select Agent</option>
                                @foreach ($agents as $agent)
                                    <option value="{{ $agent->id }}"
                                        @isset($merchant) {{ $merchant->agent_id == $agent->id ? 'selected' : '' }} @endisset>
                                        {{ $agent->name }}
                                    </option>
                                @endforeach
                            </select>
                            <label for="agent_id">Agent</label>
                        </div>
                    </div>
                    <div class="col-md-12 text-end">
                        <input type="submit" value="Update" class="btn btn-primary">
                    </div>
                </div>
            </form>
        </div>
    </div>
@endisset

@isset($mGateway)
    <div class="card">
        <div class="card-body">
            <h3>Merchant Gateway</h3>
            <form action="{{ url('/admin/merchant/approval/gateway-setting-update-' . $mGateway->id) }}" method="post"
                enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <input type="hidden" name="mid" id="mid" class="form-control" placeholder="Name"
                    value="@isset($merchant){{ $merchant->merchant_id}}@endisset">
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <select name="payin_gateway_id" id="payin_gateway_id" class="form-control" placeholder="Payin Gateway ID">
                                <option>Select Payin Gateway ID</option>
                                @foreach ($payment_gateways as $pg)
                                    <option value="{{ $pg->id }}"
                                        @isset($mGateway) {{ $mGateway->payin_gateway_id == $pg->id ? 'selected' : '' }} @endisset>
                                        {{ $pg->gateway_name }}
                                    </option>
                                @endforeach
                            </select>
                            <label for="payin_gateway_id">Payin Gateway ID</label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <select name="payout_gateway_id" id="payout_gateway_id" class="form-control" placeholder="Payout Gateway ID">
                                <option>Select Payout Gateway ID</option>
                                @foreach ($payment_gateways as $pg)
                                    <option value="{{ $pg->id }}"
                                        @isset($mGateway) {{ $mGateway->payout_gateway_id == $pg->id ? 'selected' : '' }} @endisset>
                                        {{ $pg->gateway_name }}
                                    </option>
                                @endforeach
                            </select>
                            <label for="payout_gateway_id">Payout Gateway ID</label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <input type="text" name="api_key" id="api_key" class="form-control" placeholder="Name"
                                value="{{ $mGateway->api_key }}">
                            <label for="API Key">API Key</label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <input type="text" name="merchant_id" id="merchant_id" class="form-control"
                                placeholder="Merchant Id" value="{{ $mGateway->merchant_id }}">
                            <label for="merchant_id">Merchant Id</label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <input type="text" name="salt_key" id="salt_key" class="form-control"
                                placeholder="Salt Key" value="{{ $mGateway->salt_key }}">
                            <label for="salt_key">Salt Key</label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <input type="text" name="payin_switch_amount" id="payin_switch_amount"
                                class="form-control" placeholder="Payin Switch Amount"
                                value="{{ $mGateway->payin_switch_amount }}">
                            <label for="payin_switch_amount">Payin Switch Amount</label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <input type="text" name="payin_charge" id="payin_charge" class="form-control"
                                placeholder="Payin Charge" value="{{ $mGateway->payin_charge }}">
                            <label for="payin_charge">Payin Charge</label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <select name="payin_charge_type" id="payin_charge_type" class="form-control"
                                placeholder="Payin Charge Type">
                                <option>Select Payin Charge Type</option>
                                <option value="flat"
                                    @isset($mGateway){{ $mGateway->payin_charge_type == 'flat' ? 'selected' : '' }}@endisset>
                                    Flat</option>
                                <option value="percent"
                                    @isset($mGateway){{ $mGateway->payin_charge_type == 'percent' ? 'selected' : '' }}@endisset>
                                    Percent</option>
                            </select>
                            <label for="payin_charge_type">Payin Charge Type</label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <input type="text" name="payin_charge2" id="payin_charge2" class="form-control"
                                placeholder="Payin Charge 2" value="{{ $mGateway->payin_charge2 }}">
                            <label for="payin_charge2">Payin Charge 2</label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <select name="payin_charge2_type" id="payin_charge2_type" class="form-control"
                                placeholder="Payin Charge 2 Type">
                                <option>Select Payin Charge 2 Type</option>
                                <option value="flat"
                                    @isset($mGateway){{ $mGateway->payin_charge2_type == 'flat' ? 'selected' : '' }}@endisset>
                                    Flat</option>
                                <option value="percent"
                                    @isset($mGateway){{ $mGateway->payin_charge2_type == 'percent' ? 'selected' : '' }}@endisset>
                                    Percent</option>
                            </select>
                            <label for="payin_charge2_type">Payin Charge 2 Type</label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <input type="text" name="payout_switch_amount" id="payout_switch_amount"
                                class="form-control" placeholder="Payout Switch Amount"
                                value="{{ $mGateway->payout_switch_amount }}">
                            <label for="payout_switch_amount">Payout Switch Amount</label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <input type="text" name="payout_charge" id="payout_charge" class="form-control"
                                placeholder="Payout Charge" value="{{ $mGateway->payout_charge }}">
                            <label for="payout_charge">Payout Charge</label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <select name="payout_charge_type" id="payout_charge_type" class="form-control"
                                placeholder="Payout Charge Type">
                                <option>Select Payout Charge Type</option>
                                <option value="flat"
                                    @isset($mGateway){{ $mGateway->payout_charge_type == 'flat' ? 'selected' : '' }}@endisset>
                                    Flat</option>
                                <option value="percent"
                                    @isset($mGateway){{ $mGateway->payout_charge_type == 'percent' ? 'selected' : '' }}@endisset>
                                    Percent</option>
                            </select>
                            <label for="payout_charge_type">Payout Charge Type</label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <input type="text" name="payout_charge2" id="payout_charge2" class="form-control"
                                placeholder="Payout Charge 2" value="{{ $mGateway->payout_charge2 }}">
                            <label for="payout_charge2">Payout Charge 2</label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <select name="payout_charge2_type" id="payout_charge2_type" class="form-control"
                                placeholder="Payout Charge 2 Type">
                                <option>Select Payout Charge 2 Type</option>
                                <option value="flat"
                                    @isset($mGateway){{ $mGateway->payout_charge2_type == 'flat' ? 'selected' : '' }}@endisset>
                                    Flat</option>
                                <option value="percent"
                                    @isset($mGateway){{ $mGateway->payout_charge2_type == 'percent' ? 'selected' : '' }}@endisset>
                                    Percent</option>
                            </select>
                            <label for="payout_charge2_type">Payin Charge 2 Type</label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <input type="text" name="tax_switch_amount" id="tax_switch_amount" class="form-control"
                                placeholder="Tax Switch Amount" value="{{ $mGateway->tax_switch_amount }}">
                            <label for="tax_switch_amount">Tax Switch Amount</label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <input type="text" name="tax" id="tax" class="form-control" placeholder="Tax"
                                value="{{ $mGateway->tax }}">
                            <label for="tax">Tax</label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <select name="tax_type" id="tax_type" class="form-control" placeholder="Tax Type">
                                <option>Select Tax Type</option>
                                <option value="flat"
                                    @isset($mGateway){{ $mGateway->tax_type == 'flat' ? 'selected' : '' }}@endisset>
                                    Flat</option>
                                <option value="percent"
                                    @isset($mGateway){{ $mGateway->tax_type == 'percent' ? 'selected' : '' }}@endisset>
                                    Percent</option>
                            </select>
                            <label for="tax_type">Tax Type</label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <input type="text" name="tax2" id="tax2" class="form-control"
                                placeholder="Tax 2" value="{{ $mGateway->tax2 }}">
                            <label for="tax2">Tax 2</label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <select name="tax2_type" id="tax2_type" class="form-control" placeholder="Tax 2 Type">
                                <option>Select Tax 2 Type</option>
                                <option value="flat"
                                    @isset($mGateway){{ $mGateway->tax2_type == 'flat' ? 'selected' : '' }}@endisset>
                                    Flat</option>
                                <option value="percent"
                                    @isset($mGateway){{ $mGateway->tax2_type == 'percent' ? 'selected' : '' }}@endisset>
                                    Percent</option>
                            </select>
                            <label for="tax2_type">Tax 2 Type</label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <input type="text" name="bank_fee_switch_amount" id="bank_fee_switch_amount"
                                class="form-control" placeholder="Bank Fee Switch Amount"
                                value="{{ $mGateway->bank_fee_switch_amount }}">
                            <label for="bank_fee_switch_amount">Bank Fee Switch Amount</label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <input type="text" name="bank_fee" id="bank_fee" class="form-control"
                                placeholder="Bank Fee" value="{{ $mGateway->bank_fee }}">
                            <label for="bank_fee">Bank Fee</label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <select name="bank_fee_type" id="bank_fee_type" class="form-control"
                                placeholder="Bank Fee Type">
                                <option>Select Bank Fee Type</option>
                                <option value="flat"
                                    @isset($mGateway){{ $mGateway->bank_fee_type == 'flat' ? 'selected' : '' }}@endisset>
                                    Flat</option>
                                <option value="percent"
                                    @isset($mGateway){{ $mGateway->bank_fee_type == 'percent' ? 'selected' : '' }}@endisset>
                                    Percent</option>
                            </select>
                            <label for="bank_fee_type">Bank Fee Type</label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <input type="text" name="bank_fee2" id="bank_fee2" class="form-control"
                                placeholder="Bank Fee 2" value="{{ $mGateway->bank_fee2 }}">
                            <label for="bank_fee2">Bank Fee 2</label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <select name="bank_fee2_type" id="bank_fee2_type" class="form-control"
                                placeholder="Bank Fee 2 Type">
                                <option>Select Bank Fee 2 Type</option>
                                <option value="flat"
                                    @isset($mGateway){{ $mGateway->bank_fee2_type == 'flat' ? 'selected' : '' }}@endisset>
                                    Flat</option>
                                <option value="percent"
                                    @isset($mGateway){{ $mGateway->bank_fee2_type == 'percent' ? 'selected' : '' }}@endisset>
                                    Percent</option>
                            </select>
                            <label for="bank_fee2_type">Bank Fee 2 Type</label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <select name="status" id="mstatus" class="form-control"
                                placeholder="Status">
                                <option>Select Status</option>
                                <option value="active"
                                    @isset($mGateway){{ $mGateway->status == 'active' ? 'selected' : '' }}@endisset>
                                    Active</option>
                            </select>
                            <label for="status">Status</label>
                        </div>
                    </div>
                    <div class="col-md-12 text-end">
                        <input type="submit" value="Update" class="btn btn-primary">
                    </div>
                </div>
            </form>
        </div>
    </div>
@endisset
