<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Account Details View</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{url('/admin/account/details')}}">Account Details</a></li>
                    <li class="breadcrumb-item active">Account Details View</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<!-- end page title -->
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <form action="{{url('/admin/account/details/update')}}" method="post">
                @csrf
                <input type="hidden" name="acc_id" value="@isset($account){{$account->acc_id}}@endisset">
                <input type="hidden" name="acc_merchant_id" value="@isset($account){{$account->acc_merchant_id}}@endisset">
                <input type="hidden" name="acc_business_id" value="@isset($account){{$account->acc_business_id}}@endisset">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="form-floating">
                            <input type="text" name="acc_bank_name" id="acc_bank_name" class="form-control" value="@isset($account){{$account->acc_bank_name}}@endisset" placeholder="Bank Name">
                            <label for="acc_bank_name">Bank Name <span class="text-danger">*</span></label>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-floating">
                            <input type="text" name="acc_branch_name" id="acc_branch_name" class="form-control" value="@isset($account){{$account->acc_branch_name}}@endisset" placeholder="Branch Name">
                            <label for="acc_branch_name">Branch Name</label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <input type="text" name="acc_account_number" id="acc_account_number" class="form-control" value="@isset($account){{$account->acc_account_number }}@endisset" placeholder="Account Number">
                            <label for="acc_account_number">Account Number <span class="text-danger">*</span></label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <input type="text" name="acc_ifsc_code" id="acc_ifsc_code" class="form-control" value="@isset($account){{$account->acc_ifsc_code}}@endisset" placeholder="IFSC Code">
                            <label for="acc_ifsc_code">IFSC Code <span class="text-danger">*</span></label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <input type="text" name="acc_micr_code" id="acc_micr_code" class="form-control" value="@isset($account){{$account->acc_micr_code}}@endisset" placeholder="MICR Code">
                            <label for="acc_micr_code">MICR Code</label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <input type="text" name="acc_swift_code" id="acc_swift_code" class="form-control" value="@isset($account){{$account->acc_swift_code}}@endisset" placeholder="Swift Code">
                            <label for="acc_swift_code">Swift Code</label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <select name="acc_account_type" id="acc_account_type" class="form-control">
                                <option value="">Select</option>
                                <option value="Business" @isset($account) {{$account->acc_account_type == "Business" ? "selected" : ""}} @endisset>Business</option>
                                <option value="Current" @isset($account) {{$account->acc_account_type == "Current" ? "selected" : ""}} @endisset>Current</option>
                                <option value="Savings" @isset($account) {{$account->acc_account_type == "Savings" ? "selected" : ""}} @endisset>Savings</option>
                                <option value="Other" @isset($account) {{$account->acc_account_type == "Other" ? "selected" : ""}} @endisset>Other</option>
                            </select>
                            <label for="acc_account_type">Account Type <span class="text-danger">*</span></label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <select name="acc_status" id="acc_status" class="form-control">
                                <option value="">Select</option>
                                <option value="Active" @isset($account) {{$account->acc_status == "Active" ? "selected" : ""}} @endisset>Active</option>
                                <option value="Inactive" @isset($account) {{$account->acc_status == "Inactive" ? "selected" : ""}} @endisset>Inactive</option>
                                <option value="Suspended" @isset($account) {{$account->acc_status == "Suspended" ? "selected" : ""}} @endisset>Suspended</option>
                                <option value="Closed" @isset($account) {{$account->acc_status == "Closed" ? "selected" : ""}} @endisset>Closed</option>
                            </select>
                            <label for="acc_status">Account Type <span class="text-danger">*</span></label>
                        </div>
                    </div>
                    <div class="col-md-12 text-end">
                        <input type="submit" value="Update" class="btn btn-primary rounded-pill">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>