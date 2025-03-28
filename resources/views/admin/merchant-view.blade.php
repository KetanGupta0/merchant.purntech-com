<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Merchant View</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ url('/admin/merchant/approval') }}">Merchant Approval</a></li>
                    <li class="breadcrumb-item active">Merchant View</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<!-- end page title -->
<div class="container-fluid">
    @if (isset($merchant))
        <div class="card">
            <div class="card-body">
                <h2>Merchant Info</h2>
                <form id="merchant_form" action="{{url('/admin/merchant/approval/update/merchant-info')}}" method="post">
                    @csrf
                    <input type="hidden" name="merchant_id" id="merchant_id" value="{{$merchant->merchant_id}}">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="form-floating">
                                <input type="text" name="merchant_name" id="merchant_name" class="form-control" placeholder="Merchant Name"
                                       value="{{ $merchant->merchant_name }}">
                                <label for="merchant_name">Merchant Name <span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-floating">
                                <input type="email" name="merchant_email" id="merchant_email" class="form-control" placeholder="Merchant Email"
                                       value="{{ $merchant->merchant_email }}">
                                <label for="merchant_email">Merchant Email <span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-floating">
                                <input type="text" maxlength="10" name="merchant_phone" id="merchant_phone" class="form-control"
                                       placeholder="Merchant Primary Phone" value="{{ $merchant->merchant_phone }}">
                                <label for="merchant_phone">Merchant Primary Phone <span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-floating">
                                <input type="text" maxlength="10" name="merchant_phone2" id="merchant_phone2" class="form-control"
                                       placeholder="Merchant Secondary Phone" value="{{ $merchant->merchant_phone2 }}">
                                <label for="merchant_phone2">Merchant Secondary Phone</label>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-floating">
                                <input type="text" maxlength="12" name="merchant_aadhar_no" id="merchant_aadhar_no" class="form-control"
                                       placeholder="Merchant Aadhar Number" value="{{ $merchant->merchant_aadhar_no }}">
                                <label for="merchant_aadhar_no">Merchant Aadhar Number <span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-floating">
                                <input type="text" name="merchant_pan_no" id="merchant_pan_no" class="form-control" placeholder="Merchant PAN Number"
                                       style="text-transform: uppercase" value="{{ $merchant->merchant_pan_no }}">
                                <label for="merchant_pan_no">Merchant PAN Number <span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-floating">
                                <input type="text" name="merchant_city" id="merchant_city" class="form-control" placeholder="Merchant City" value="{{ $merchant->merchant_city }}">
                                <label for="merchant_city">Merchant City <span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-floating">
                                <input type="text" name="merchant_state" id="merchant_state" class="form-control" placeholder="Merchant State" value="{{ $merchant->merchant_state }}">
                                <label for="merchant_state">Merchant State <span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-floating">
                                <input type="text" name="merchant_country" id="merchant_country" class="form-control" placeholder="Merchant Country" value="{{ !$merchant->merchant_country ? "India" : $merchant->merchant_country }}">
                                <label for="merchant_country">Merchant Country <span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-floating">
                                <input type="text" name="merchant_zip" id="merchant_zip" class="form-control" placeholder="Merchant Zip Code" value="{{ $merchant->merchant_zip }}">
                                <label for="merchant_zip">Merchant Zip Code <span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-8 mb-3">
                            <div class="form-floating">
                                <input type="text" name="merchant_landmark" id="merchant_landmark" class="form-control" placeholder="Merchant Landmark" value="{{ $merchant->merchant_landmark }}">
                                <label for="merchant_landmark">Merchant Landmark <span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-floating">
                                <select name="merchant_is_onboarded" id="merchant_is_onboarded" class="form-control" aria-placeholder="Merchant Onboarding Completed">
                                    <option value="">Select</option>
                                    <option value="No" {{ $merchant->merchant_is_onboarded == 'No' ? 'selected' : '' }}>No</option>
                                    <option value="Yes" {{ $merchant->merchant_is_onboarded == 'Yes' ? 'selected' : '' }}>Yes</option>
                                </select>
                                <label for="merchant_is_onboarded">Merchant is Onboarded <span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-floating">
                                <select name="merchant_is_verified" id="merchant_is_verified" class="form-control" aria-placeholder="Merchant Approval">
                                    <option value="">Select</option>
                                    <option value="Approved" {{ $merchant->merchant_is_verified == 'Approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="Not Approved" {{ $merchant->merchant_is_verified == 'Not Approved' ? 'selected' : '' }}>Not Approved</option>
                                </select>
                                <label for="merchant_is_verified">Merchant Approval <span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-floating">
                                <select name="merchant_status" id="merchant_status" class="form-control" aria-placeholder="Merchant Status">
                                    <option value="">Select</option>
                                    <option value="Active" {{ $merchant->merchant_status == 'Active' ? 'selected' : '' }}>Active</option>
                                    <option value="Blocked" {{ $merchant->merchant_status == 'Blocked' ? 'selected' : '' }}>Blocked</option>
                                </select>
                                <label for="merchant_status">Merchant Status <span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-12 text-end">
                            <input type="submit" value="Update" class="btn btn-primary">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif

    @if (isset($business))
        <div class="card">
            <div class="card-body">
                <h2>Business Info</h2>
                <form id="business_form" action="{{url('/admin/merchant/approval/update/business-info')}}" method="post">
                    @csrf
                    <input type="hidden" name="business_id" id="business_id" value="{{$business->business_id}}">
                    <input type="hidden" name="business_merchant_id" id="business_merchant_id" value="{{$business->business_merchant_id}}">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="form-floating">
                                <input type="text" name="business_name" id="business_name" class="form-control" placeholder="Business Name"
                                       value="{{ $business->business_name }}">
                                <label for="business_name">Bisiness Name <span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-floating">
                                <select name="business_type" id="business_type" class="form-control" aria-placeholder="Business Type">
                                    <option value="">Select</option>
                                    <option value="Individual" {{ $business->business_type == 'Individual' ? 'selected' : '' }}>Individual</option>
                                    <option value="Limited" {{ $business->business_type == 'Limited' ? 'selected' : '' }}>Limited</option>
                                    <option value="OPC" {{ $business->business_type == 'OPC' ? 'selected' : '' }}>OPC</option>
                                    <option value="Private Limited" {{ $business->business_type == 'Private Limited' ? 'selected' : '' }}>Private Limited
                                    </option>
                                    <option value="Solo Proprietorship" {{ $business->business_type == 'Solo Proprietorship' ? 'selected' : '' }}>Solo
                                        Proprietorship</option>
                                </select>
                                <label for="business_type">Bisiness Type <span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="form-group">
                                <label for="business_type">Bisiness Address <span class="text-danger">*</span></label>
                                <textarea class="form-control" name="business_address" id="business_address" cols="30" rows="5" placeholder="Business Address">{{ $business->business_address }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="form-floating">
                                <input type="text" name="business_website" id="business_website" class="form-control" placeholder="Bisiness Website"
                                       value="{{ $business->business_website }}">
                                <label for="business_type">Bisiness Website <span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-floating">
                                <select name="business_is_verified" id="business_is_verified" class="form-control" aria-placeholder="Business Verification">
                                    <option value="">Select</option>
                                    <option value="Not Verified" {{ $business->business_is_verified == 'Not Verified' ? 'selected' : '' }}>Not Verified</option>
                                    <option value="Verified" {{ $business->business_is_verified == 'Verified' ? 'selected' : '' }}>Verified</option>
                                </select>
                                <label for="business_is_verified">Bisiness Verification <span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-floating">
                                <select name="business_status" id="business_status" class="form-control" aria-placeholder="Business Status">
                                    <option value="">Select</option>
                                    <option value="Active" {{ $business->business_status == 'Active' ? 'selected' : '' }}>Active</option>
                                    <option value="Blocked" {{ $business->business_status == 'Blocked' ? 'selected' : '' }}>Blocked</option>
                                </select>
                                <label for="business_status">Bisiness Status <span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-12 text-end">
                            <input type="submit" value="Update" class="btn btn-primary">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif

    @if (isset($documents))
        <div class="card">
            <div class="card-body">
                <h2>KYC Documents</h2>
                <div class="row">
                    @foreach ($documents as $doc)
                        <div class="col-md-3">
                            <div class="card" style="width: 18rem;">
                                <img src="{{ asset($doc->kyc_document_path . '/' . $doc->kyc_document_name) }}" class="card-img-top"
                                     alt="{{ $doc->kyc_document_type }}">
                                <div class="card-body">
                                    <h5 class="card-title" style="text-transform: uppercase">{{ $doc->kyc_document_type }}</h5>
                                    <a href="{{ asset($doc->kyc_document_path . '/' . $doc->kyc_document_name) }}" class="btn btn-primary"
                                       target="_blank">View</a>
                                    <button type="button" class="btn btn-primary doc-update-btn" doc-id="{{ $doc->kyc_id }}" doc-type="{{ $doc->kyc_document_type }}" data-bs-toggle="modal" data-bs-target="#staticBackdrop">Update</button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>
<!-- Button trigger modal -->

<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Modal title</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="kycDocForm" action="{{url('/admin/merchant/approval/update/kyc-doc')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="kyc_id" id="kyc_id" value="0">
                    <input type="hidden" name="kyc_merchant_id" id="kyc_merchant_id" value="0">
                    <input type="hidden" name="kyc_business_id" id="kyc_business_id" value="0">
                    <input type="hidden" name="kyc_document_type" id="kyc_document_type" value="none">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <input class="form-control" type="file" name="kyc_document_name" id="kyc_document_name" accept="image/*">
                                <label for="kyc_document_name"></label>
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
</div>
<script>
    $(document).ready(function() {
        // Restrict to numeric input for phone and Aadhar fields
        $('#merchant_phone, #merchant_phone2, #merchant_aadhar_no').on('keypress', function(e) {
            if (e.which < 48 || e.which > 57) { // Only allow numeric (0-9)
                e.preventDefault();
            }
        });

        // Enforce max length
        $('#merchant_phone, #merchant_phone2').attr('maxlength', 10);
        $('#merchant_aadhar_no').attr('maxlength', 12);
        $('#merchant_pan_no').attr('maxlength', 10); // Standard PAN length in India is 10

        // Capitalize PAN input
        $('#merchant_pan_no').on('input', function() {
            $(this).val($(this).val().toUpperCase());
        });

        // Validate fields on form submission
        $('#merchant_form').on('submit', function(e) {
            let isValid = true;
            let errorMessage = '';

            // Check required fields
            if (!$('#merchant_name').val()) {
                isValid = false;
                errorMessage += 'Merchant Name is required.<br>';
            }
            if (!$('#merchant_email').val()) {
                isValid = false;
                errorMessage += 'Merchant Email is required.<br>';
            }
            if (!$('#merchant_phone').val() || $('#merchant_phone').val().length < 10) {
                isValid = false;
                errorMessage += 'Merchant Primary Phone is required and should be 10 digits.<br>';
            }
            if (!$('#merchant_aadhar_no').val() || $('#merchant_aadhar_no').val().length < 12) {
                isValid = false;
                errorMessage += 'Merchant Aadhar Number is required and should be 12 digits.<br>';
            }
            if (!$('#merchant_pan_no').val() || $('#merchant_pan_no').val().length < 10) {
                isValid = false;
                errorMessage += 'Merchant PAN Number is required and should be 10 characters.<br>';
            }

            // Show error message if invalid
            if (!isValid) {
                // alert(errorMessage);
                e.preventDefault();
                Swal.fire({
                    icon: 'info',
                    title: 'Missing required fields',
                    html: errorMessage
                });
            }
        });


        $('#business_form').on('submit', function(e) {
            let isValid = true;
            let errorMessage = '';

            // Validate Business Name
            if (!$('#business_name').val()) {
                isValid = false;
                errorMessage += 'Business Name is required.<br>';
            }

            // Validate Business Type
            let businessType = $('#business_type').val();
            if (!businessType) {
                isValid = false;
                errorMessage += 'Business Type is required.<br>';
            }

            // Additional checks based on Business Type
            // if (businessType && businessType !== 'Individual' && businessType !== 'Solo Proprietorship') {
            //     if (!$('#merchant_pan_no').val() || $('#merchant_pan_no').val().length < 10) {
            //         isValid = false;
            //         errorMessage += 'PAN Number is required and should be 10 characters for selected Business Types.<br>';
            //     }
            //     if (!$('#merchant_cin_no').val()) {  // Add an ID for CIN field if present
            //         isValid = false;
            //         errorMessage += 'CIN Number is required for selected Business Types.<br>';
            //     }
            // }

            // Validate Business Address
            if (!$('#business_address').val()) {
                isValid = false;
                errorMessage += 'Business Address is required.<br>';
            }

            // Validate Business Website (optional, but if present should be a valid URL)
            const website = $('#business_website').val();
            if (website && !/^https?:\/\/[^\s$.?#].[^\s]*$/gm.test(website)) {
                isValid = false;
                errorMessage += 'Please enter a valid URL for Business Website.<br>';
            }

            // Show error message if invalid
            if (!isValid) {
                // alert(errorMessage);
                e.preventDefault();
                Swal.fire({
                    icon: 'info',
                    title: 'Missing required fields',
                    html: errorMessage
                });
            }
        });

        $(document).on('click','.doc-update-btn',function(){
            const kyc_id = $(this).attr('doc-id');
            const kyc_merchant_id = $('#merchant_id').val();
            const kyc_business_id = $('#business_id').val();
            const kyc_document_type = $(this).attr('doc-type');

            $('#kyc_id').val(kyc_id);
            $('#kyc_merchant_id').val(kyc_merchant_id);
            $('#kyc_business_id').val(kyc_business_id);
            $('#kyc_document_type').val(kyc_document_type);

            $('#staticBackdropLabel').text('Update '+kyc_document_type.toUpperCase());
        });

        $(document).on('click','.btn-close',function(){
            $('#kyc_id').val('0');
            $('#kyc_merchant_id').val('0');
            $('#kyc_business_id').val('0');
            $('#kyc_document_type').val('none');
            $('#staticBackdropLabel').text('Modal title');
        });
    });
</script>
