<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Settings</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">Settings</li>
                </ol>
            </div>

        </div>
    </div>
</div>
<!-- end page title -->
@isset($merchant)
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <h3>Personal Info</h3>
                <form action="{{ url('/merchant/settings/update') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="form-floating">
                                <input type="text" name="merchant_name" id="merchant_name" class="form-control" placeholder="Name"
                                       value="{{ $merchant->merchant_name }}" readonly>
                                <label for="merchant_name">Name <span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-floating">
                                <input type="email" name="merchant_email" id="merchant_email" class="form-control" placeholder="Email"
                                       value="{{ $merchant->merchant_email }}" readonly>
                                <label for="merchant_email">Email <span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-floating">
                                <input type="text" name="merchant_phone" id="merchant_phone" class="form-control" placeholder="Primary Phone"
                                       value="{{ $merchant->merchant_phone }}" readonly>
                                <label for="merchant_phone">Primary Phone <span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-floating">
                                <input type="text" name="merchant_phone2" id="merchant_phone2" class="form-control" placeholder="Secondary Phone"
                                       value="{{ $merchant->merchant_phone2 }}">
                                <label for="merchant_phone2">Secondary Phone</label>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-floating">
                                <input type="text" name="merchant_aadhar_no" id="merchant_aadhar_no" class="form-control" placeholder="Aadhar Number"
                                       value="{{ $merchant->merchant_aadhar_no }}" readonly>
                                <label for="merchant_aadhar_no">Aadhar Number <span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-floating">
                                <input type="text" name="merchant_pan_no" id="merchant_pan_no" class="form-control" placeholder="PAN Number"
                                       value="{{ $merchant->merchant_pan_no }}" readonly>
                                <label for="merchant_pan_no">PAN Number <span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-floating">
                                <input type="file" name="merchant_profile" id="merchant_profile" accept="image/*" class="form-control"
                                       placeholder="Profile Photo">
                                <label for="merchant_profile">Profile Photo</label>
                            </div>
                        </div>
                    </div>
                    <h3>Address</h3>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="form-floating">
                                <input type="text" name="merchant_city" id="merchant_city" class="form-control" placeholder="City" value="{{$merchant->merchant_city}}">
                                <label for="admin_city">City</label>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-floating">
                                <input type="text" name="merchant_state" id="merchant_state" class="form-control" placeholder="State" value="{{$merchant->merchant_state}}">
                                <label for="merchant_state">State</label>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-floating">
                                <input type="text" name="merchant_country" id="merchant_country" class="form-control" placeholder="Country" value="{{!$merchant->merchant_country ? "India" : $merchant->merchant_country}}">
                                <label for="merchant_country">Country</label>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-floating">
                                <input type="text" name="merchant_zip" id="merchant_zip" class="form-control" placeholder="Zip Code" value="{{$merchant->merchant_zip}}">
                                <label for="merchant_zip">Zip Code</label>
                            </div>
                        </div>
                        <div class="col-md-8 mb-3">
                            <div class="form-floating">
                                <input type="text" name="merchant_landmark" id="merchant_landmark" class="form-control" placeholder="Landmark" value="{{$merchant->merchant_landmark}}">
                                <label for="merchant_landmark">Landmark</label>
                            </div>
                        </div>
                    </div>
                    <h3>Password</h3>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="form-floating">
                                <input type="password" name="merchant_password" id="merchant_password" class="form-control" placeholder="Current Password">
                                <label for="merchant_password">Current Password <span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-floating">
                                <input type="password" name="merchant_password_new" id="merchant_password_new" class="form-control" placeholder="New Password">
                                <label for="merchant_password_new">New Password</label>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-floating">
                                <input type="password" name="merchant_password_new_confirmed" id="merchant_password_new_confirmed" class="form-control" placeholder="Confirm New Password">
                                <label for="merchant_password_new_confirmed">Confirm New Password</label>
                            </div>
                        </div>
                        <div class="col-md-12 text-end">
                            <input type="submit" value="Update" class="btn btn-primary">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endisset

