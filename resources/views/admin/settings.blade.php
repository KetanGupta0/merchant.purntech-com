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
@isset($admin)
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <h3>Personal Info</h3>
                <form action="{{ url('/admin/settings/update-admin') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="form-floating">
                                <input type="text" name="admin_name" id="admin_name" class="form-control"
                                    placeholder="Name" value="{{ $admin->admin_name }}">
                                <label for="admin_name">Name <span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-floating">
                                <input type="email" name="admin_email" id="admin_email" class="form-control"
                                    placeholder="Email" value="{{ $admin->admin_email }}">
                                <label for="admin_email">Email <span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-floating">
                                <input type="text" name="admin_phone" id="admin_phone" class="form-control"
                                    placeholder="Primary Phone" value="{{ $admin->admin_phone }}">
                                <label for="admin_phone">Primary Phone <span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-floating">
                                <input type="text" name="admin_phone2" id="admin_phone2" class="form-control"
                                    placeholder="Secondary Phone" value="{{ $admin->admin_phone2 }}">
                                <label for="admin_phone2">Secondary Phone</label>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-floating">
                                <input type="file" name="admin_profile_pic" id="admin_profile_pic" accept="image/*"
                                    class="form-control" placeholder="Profile Photo">
                                <label for="admin_profile_pic">Profile Photo</label>
                            </div>
                        </div>
                    </div>
                    <h3>Address</h3>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="form-floating">
                                <input type="text" name="admin_city" id="admin_city" class="form-control"
                                    placeholder="City" value="{{ $admin->admin_city }}">
                                <label for="admin_city">City</label>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-floating">
                                <input type="text" name="admin_state" id="admin_state" class="form-control"
                                    placeholder="State" value="{{ $admin->admin_state }}">
                                <label for="admin_state">State</label>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-floating">
                                <input type="text" name="admin_country" id="admin_country" class="form-control"
                                    placeholder="Country" value="{{ $admin->admin_country }}">
                                <label for="admin_country">Country</label>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-floating">
                                <input type="text" name="admin_zip_code" id="admin_zip_code" class="form-control"
                                    placeholder="Zip Code" value="{{ $admin->admin_zip_code }}">
                                <label for="admin_zip_code">Zip Code</label>
                            </div>
                        </div>
                        <div class="col-md-8 mb-3">
                            <div class="form-floating">
                                <input type="text" name="admin_landmark" id="admin_landmark" class="form-control"
                                    placeholder="Landmark" value="{{ $admin->admin_landmark }}">
                                <label for="admin_landmark">Landmark</label>
                            </div>
                        </div>
                    </div>
                    <h3>Password</h3>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="form-floating">
                                <input type="password" name="admin_password" id="admin_password" class="form-control"
                                    placeholder="Current Password">
                                <label for="admin_password">Current Password <span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-floating">
                                <input type="password" name="admin_password_new" id="admin_password_new"
                                    class="form-control" placeholder="New Password">
                                <label for="admin_password_new">New Password</label>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-floating">
                                <input type="password" name="admin_password_new_confirmed"
                                    id="admin_password_new_confirmed" class="form-control"
                                    placeholder="Confirm New Password">
                                <label for="admin_password_new_confirmed">Confirm New Password</label>
                            </div>
                        </div>
                        <div class="col-md-12 text-end">
                            <input type="submit" value="Update" class="btn btn-primary">
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="card">
            <h5 class="card-header">Gateway Configuration</h5>
            <div class="card-body">.
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Gateway</th>
                                <th>API Key</th>
                                <th>Merchant ID</th>
                                <th>Salt Key</th>
                                <th>Gateway Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td id="gateway-1">Paykuber</td>
                                <td>
                                    <div class="form-floating">
                                        <input type="text" name="" id="apikey-1" class="form-control"
                                            placeholder="API Key">
                                        <label for="">API Key</label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-floating">
                                        <input type="text" name="" id="mid-1" class="form-control"
                                            placeholder="Merchant ID(MID)">
                                        <label for="">Merchant ID(MID)</label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-floating">
                                        <input type="text" name="" id="salt-1" class="form-control"
                                            placeholder="Salt Key">
                                        <label for="">Salt Key</label>
                                    </div>
                                </td>
                                <td id="status-1">Active</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-icon btn-sm fs-16 text-muted dropdown" type="button"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ri-more-fill"></i>
                                        </button>
                                        <ul class="dropdown-menu" style="">
                                            <li>
                                                <a class="dropdown-item view" style="cursor: pointer;" data-id="1"
                                                    data-bs-toggle="modal" data-bs-target="#viewGateway">
                                                    <i class="ri-eye-fill text-muted me-2 align-bottom"></i>View
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item charge" style="cursor: pointer;" data-id="1"
                                                    data-bs-toggle="modal" data-bs-target="#chargeConfigModal">
                                                    <i class="ri-eye-fill text-muted me-2 align-bottom"></i>Charges
                                                    Configuration
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item inactive" style="cursor: pointer;"
                                                    data-id="1">
                                                    <i class="ri-eye-fill text-muted me-2 align-bottom"></i>Mark as
                                                    Inactive
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item save" style="cursor: pointer;" data-id="1">
                                                    <i class="ri-eye-fill text-muted me-2 align-bottom"></i>Save Changes
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td id="gateway-2">PhonePe</td>
                                <td>
                                    <div class="form-floating">
                                        <input type="text" name="" id="apikey-2" class="form-control"
                                            placeholder="API Key">
                                        <label for="">API Key</label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-floating">
                                        <input type="text" name="" id="mid-2" class="form-control"
                                            placeholder="Merchant ID(MID)">
                                        <label for="">Merchant ID(MID)</label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-floating">
                                        <input type="text" name="" id="salt-2" class="form-control"
                                            placeholder="Salt Key">
                                        <label for="">Salt Key</label>
                                    </div>
                                </td>
                                <td id="status-2">Inactive</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-icon btn-sm fs-16 text-muted dropdown" type="button"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ri-more-fill"></i>
                                        </button>
                                        <ul class="dropdown-menu" style="">
                                            <li>
                                                <a class="dropdown-item view" style="cursor: pointer;" data-id="2"
                                                    data-bs-toggle="modal" data-bs-target="#viewGateway">
                                                    <i class="ri-eye-fill text-muted me-2 align-bottom"></i>View
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item charge" style="cursor: pointer;" data-id="2"
                                                    data-bs-toggle="modal" data-bs-target="#chargeConfigModal">
                                                    <i class="ri-eye-fill text-muted me-2 align-bottom"></i>Charges
                                                    Configuration
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item sactive" style="cursor: pointer;" data-id="2">
                                                    <i class="ri-eye-fill text-muted me-2 align-bottom"></i>Mark as Active
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item save" style="cursor: pointer;" data-id="2">
                                                    <i class="ri-eye-fill text-muted me-2 align-bottom"></i>Save Changes
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td id="gateway-3">Zynte</td>
                                <td>
                                    <div class="form-floating">
                                        <input type="text" name="" id="apikey-3" class="form-control"
                                            placeholder="API Key">
                                        <label for="">API Key</label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-floating">
                                        <input type="text" name="" id="mid-3" class="form-control"
                                            placeholder="Merchant ID(MID)">
                                        <label for="">Merchant ID(MID)</label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-floating">
                                        <input type="text" name="" id="salt-3" class="form-control"
                                            placeholder="Salt Key">
                                        <label for="">Salt Key</label>
                                    </div>
                                </td>
                                <td id="status-3">Inactive</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-icon btn-sm fs-16 text-muted dropdown" type="button"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ri-more-fill"></i>
                                        </button>
                                        <ul class="dropdown-menu" style="">
                                            <li>
                                                <a class="dropdown-item view" style="cursor: pointer;" data-id="3"
                                                    data-bs-toggle="modal" data-bs-target="#viewGateway">
                                                    <i class="ri-eye-fill text-muted me-2 align-bottom"></i>View
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item charge" style="cursor: pointer;" data-id="3"
                                                    data-bs-toggle="modal" data-bs-target="#chargeConfigModal">
                                                    <i class="ri-eye-fill text-muted me-2 align-bottom"></i>Charges
                                                    Configuration
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item sactive" style="cursor: pointer;" data-id="3">
                                                    <i class="ri-eye-fill text-muted me-2 align-bottom"></i>Mark as Active
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item save" style="cursor: pointer;" data-id="3">
                                                    <i class="ri-eye-fill text-muted me-2 align-bottom"></i>Save Changes
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td id="gateway-4">INR Pay</td>
                                <td>
                                    <div class="form-floating">
                                        <input type="text" name="" id="apikey-4" class="form-control"
                                            placeholder="API Key">
                                        <label for="">API Key</label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-floating">
                                        <input type="text" name="" id="mid-4" class="form-control"
                                            placeholder="Merchant ID(MID)">
                                        <label for="">Merchant ID(MID)</label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-floating">
                                        <input type="text" name="" id="salt-4" class="form-control"
                                            placeholder="Salt Key">
                                        <label for="">Salt Key</label>
                                    </div>
                                </td>
                                <td id="status-4">Inactive</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-icon btn-sm fs-16 text-muted dropdown" type="button"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ri-more-fill"></i>
                                        </button>
                                        <ul class="dropdown-menu" style="">
                                            <li>
                                                <a class="dropdown-item view" style="cursor: pointer;" data-id="4"
                                                    data-bs-toggle="modal" data-bs-target="#viewGateway">
                                                    <i class="ri-eye-fill text-muted me-2 align-bottom"></i>View
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item charge" style="cursor: pointer;" data-id="4"
                                                    data-bs-toggle="modal" data-bs-target="#chargeConfigModal">
                                                    <i class="ri-eye-fill text-muted me-2 align-bottom"></i>Charges
                                                    Configuration
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item sactive" style="cursor: pointer;" data-id="4">
                                                    <i class="ri-eye-fill text-muted me-2 align-bottom"></i>Mark as Active
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item save" style="cursor: pointer;" data-id="4">
                                                    <i class="ri-eye-fill text-muted me-2 align-bottom"></i>Save Changes
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td id="gateway-5">Other</td>
                                <td>
                                    <div class="form-floating">
                                        <input type="text" name="" id="apikey-5" class="form-control"
                                            placeholder="API Key">
                                        <label for="">API Key</label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-floating">
                                        <input type="text" name="" id="mid-5" class="form-control"
                                            placeholder="Merchant ID(MID)">
                                        <label for="">Merchant ID(MID)</label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-floating">
                                        <input type="text" name="" id="salt-5" class="form-control"
                                            placeholder="Salt Key">
                                        <label for="">Salt Key</label>
                                    </div>
                                </td>
                                <td id="status-5">Inactive</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-icon btn-sm fs-16 text-muted dropdown" type="button"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ri-more-fill"></i>
                                        </button>
                                        <ul class="dropdown-menu" style="">
                                            <li>
                                                <a class="dropdown-item view" style="cursor: pointer;" data-id="5"
                                                    data-bs-toggle="modal" data-bs-target="#viewGateway">
                                                    <i class="ri-eye-fill text-muted me-2 align-bottom"></i>View
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item charge" style="cursor: pointer;" data-id="5"
                                                    data-bs-toggle="modal" data-bs-target="#chargeConfigModal">
                                                    <i class="ri-eye-fill text-muted me-2 align-bottom"></i>Charges
                                                    Configuration
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item sactive" style="cursor: pointer;" data-id="5">
                                                    <i class="ri-eye-fill text-muted me-2 align-bottom"></i>Mark as Active
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item save" style="cursor: pointer;" data-id="5">
                                                    <i class="ri-eye-fill text-muted me-2 align-bottom"></i>Save Changes
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @isset($gateways)
                                @foreach ($gateways as $gate)
                                    <tr>
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td>
                                            <div class="form-floating">
                                                <input type="text" name="" id="" class="form-control"
                                                    placeholder="Input">
                                                <label for="">Input</label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-floating">
                                                <input type="text" name="" id="" class="form-control"
                                                    placeholder="Input">
                                                <label for="">Input</label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-floating">
                                                <input type="text" name="" id="" class="form-control"
                                                    placeholder="Input">
                                                <label for="">Input</label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-floating">
                                                <input type="text" name="" id="" class="form-control"
                                                    placeholder="Input">
                                                <label for="">Input</label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-floating">
                                                <input type="text" name="" id="" class="form-control"
                                                    placeholder="Input">
                                                <label for="">Input</label>
                                            </div>
                                        </td>
                                        <td>
                                            <button type="button">Update</button>
                                        </td>
                                    </tr>
                                @endforeach
                            @endisset
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">Note: You can set indivisual merchant charges <a
                    href="{{ url('/admin/merchant/approval') }}">here</a>.</div>
        </div>
        <div class="card">
            <h5 class="card-header">Payment Setting</h5>
            <div class="card-body">
                <form action="{{url('/admin/settings/update/payment/limits')}}" class="row" method="POST">
                    @csrf
                    <div class="col-md-3 mb-3">
                        <div class="form-floating">
                            <input type="text" name="payin_min_amt" id="payin_min_amt" class="form-control" placeholder="" value="{{$admin->payin_min_amt}}">
                            <label for="payin_min_amt">Payin Min Amount</label>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="form-floating">
                            <input type="text" name="payin_max_amt" id="payin_max_amt" class="form-control" placeholder="" value="{{$admin->payin_max_amt}}">
                            <label for="payin_max_amt">Payin Max Amount</label>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="form-floating">
                            <input type="text" name="payout_min_amt" id="payout_min_amt" class="form-control" placeholder="" value="{{$admin->payout_min_amt}}">
                            <label for="payout_min_amt">Payout Min Amount</label>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="form-floating">
                            <input type="text" name="payout_max_amt" id="payout_max_amt" class="form-control" placeholder="" value="{{$admin->payout_max_amt}}">
                            <label for="payout_max_amt">Payout Max Amount</label>
                        </div>
                    </div>
                    <div class="col-md-12 text-end">
                        <input type="submit" value="Update" class="btn btn-primary">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Gateway Viewer Modal -->
    <div class="modal fade" id="viewGateway" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="viewGatewayLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="viewGatewayLabel">Gateway Viewer</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="apiKeyView" value=""
                                    placeholder="API Key" readonly>
                                <label for="apiKeyView">API Key</label>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="midView" value=""
                                    placeholder="Merchant ID" readonly>
                                <label for="midView">Merchant ID</label>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="saltView" value=""
                                    placeholder="Salt Key" readonly>
                                <label for="saltView">Salt Key</label>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-floating">
                                <select class="form-control" id="chargeTypeView" placeholder="Charges Type" readonly>
                                    <option value="" readonly>Select</option>
                                    <option value="fixed" readonly>Fixed</option>
                                    <option value="percent" readonly>Percent</option>
                                </select>
                                <label for="chargeTypeView">Charges Type</label>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="saltView" value="0"
                                    placeholder="Charges" readonly>
                                <label for="saltView">Charges</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Understood</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Charges Configuration Modal -->
    <div class="modal fade" id="chargeConfigModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="chargeConfigModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="chargeConfigModalLabel">Charges Configuration</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Charges Configuration
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Understood</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $(document).on('click', '.view', function() {});
            $(document).on('click', '.charge', function() {});
            $(document).on('click', '.sactive', function() {
                const id = $(this).attr('data-id');
                $('#status-' + id).html('Active');
                $(this).removeClass('sactive');
                $(this).addClass('inactive');
                $(this).html('<i class="ri-eye-fill text-muted me-2 align-bottom"></i>Mark as inactive');
                Swal.fire({
                    icon: 'success',
                    title: 'Status Updated',
                    html: 'Gateway status changed to active!'
                });
            });
            $(document).on('click', '.inactive', function() {
                const id = $(this).attr('data-id');
                $('#status-' + id).html('Inactive');
                $(this).removeClass('inactive');
                $(this).addClass('sactive');
                $(this).html('<i class="ri-eye-fill text-muted me-2 align-bottom"></i>Mark as active');
                Swal.fire({
                    icon: 'success',
                    title: 'Status Updated',
                    html: 'Gateway status changed to inactive!'
                });
            });
            $(document).on('click', '.save', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Gateway Updated',
                    html: 'Gateway configuration updated successfully!'
                });
            });
        });
    </script>
@endisset
