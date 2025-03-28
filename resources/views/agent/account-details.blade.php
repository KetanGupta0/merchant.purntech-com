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

<div class="row align-items-stretch">
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="m-0">Account</h5>
            </div>
            <div class="card-body">
                <form>
                    @csrf
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <div class="form-floating">
                                <input type="text" name="acc_bank_name" id="acc_bank_name" class="form-control"
                                    placeholder="Bank Name">
                                <label for="acc_bank_name">Bank Name <span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="form-floating">
                                <input type="text" name="acc_branch_name" id="acc_branch_name" class="form-control"
                                    placeholder="Branch Name">
                                <label for="acc_branch_name">Branch Name</label>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="form-floating">
                                <input type="text" name="acc_account_number" id="acc_account_number"
                                    class="form-control" placeholder="Account Number">
                                <label for="acc_account_number">Account Number <span
                                        class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-floating">
                                <input type="text" name="acc_ifsc_code" id="acc_ifsc_code" class="form-control"
                                    placeholder="IFSC Code">
                                <label for="acc_ifsc_code">IFSC Code <span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-floating">
                                <input type="text" name="acc_micr_code" id="acc_micr_code" class="form-control"
                                    placeholder="MICR Code">
                                <label for="acc_micr_code">MICR Code</label>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-floating">
                                <input type="text" name="acc_swift_code" id="acc_swift_code" class="form-control"
                                    placeholder="Swift Code">
                                <label for="acc_swift_code">Swift Code</label>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-floating">
                                <select name="acc_account_type" id="acc_account_type" class="form-control">
                                    <option value="">Select</option>
                                    <option value="Business">Business</option>
                                    <option value="Current">Current</option>
                                    <option value="Savings">Savings</option>
                                    <option value="Other">Other</option>
                                </select>
                                <label for="acc_account_type">Account Type <span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-12 text-end">
                            <input type="submit" value="Save/Update" class="btn btn-primary rounded-pill">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-6 my-4 my-lg-0">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="m-0">Profile</h5>
            </div>
            <div class="card-body h-100 align-items-center d-flex justify-content-center">
                @isset($agent)
                    <div class="text-center">
                        <div class="profile-user position-relative d-inline-block mx-auto  mb-4">
                            <img src="{{ !empty($agent->agent_profile) ? asset('uploads/agent/profile/' . $agent->agent_profile) : asset('assets/images/users/avatar-1.jpg') }}"
                                class="rounded-circle avatar-xl img-thumbnail user-profile-image" alt="user-profile-image">
                            {{-- <div class="avatar-xs p-0 rounded-circle profile-photo-edit">
                            <input id="profile-img-file-input" type="file" class="profile-img-file-input">
                            <label for="profile-img-file-input" class="profile-photo-edit avatar-xs">
                                <span class="avatar-title rounded-circle bg-light text-body">
                                    <i class="ri-camera-fill"></i>
                                </span>
                            </label>
                        </div> --}}
                        </div>
                        <h4 class="mb-1 text-capitalize">{{ $agent->name }}</h4>
                        <h6 class="mb-1">{{ $agent->mobile }}</h6>
                        <h6>{{ $agent->email }}</h6>
                    </div>
                @endisset
            </div>
            <div class="card-footer text-end">
                <a href="{{url('/agent/settings')}}" class="btn btn-primary">Profile Setting</a>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('assets/js/pages/profile-setting.init.js') }}"></script>
