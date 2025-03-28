<style>
    table tr {
        vertical-align: middle;
        text-align: start
    }
</style>
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Agent Profile</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">Agent Profile</li>
                </ol>
            </div>

        </div>
    </div>
</div>
<!-- end page title -->
@if (isset($agent))
    <div class="card">
        <div class="card-body">
            <h3>Personal Info</h3>
            <form action="#" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <input type="text" name="agent_name" id="agent_name" class="form-control"
                                placeholder="Name" value="{{ $agent->name }}" readonly>
                            <label for="agent_name">Name <span class="text-danger">*</span></label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <input type="email" name="agent_email" id="agent_email" class="form-control"
                                placeholder="Email" value="{{ $agent->email }}" readonly>
                            <label for="agent_email">Email <span class="text-danger">*</span></label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <input type="text" name="agent_phone" id="agent_phone" class="form-control"
                                placeholder="Primary Phone" value="{{ $agent->mobile }}" readonly>
                            <label for="agent_phone">Mobile <span class="text-danger">*</span></label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <input type="file" name="agent_profile" id="agent_profile" accept="image/*"
                                class="form-control" placeholder="Profile Photo">
                            <label for="agent_profile">Profile Photo</label>
                        </div>
                    </div>
                </div>
                <h3>Payin Commission Configuration</h3>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <input type="text" name="payin_switch_amount" id="payin_switch_amount"
                                class="form-control" placeholder="Payin Switch Amount"
                                value="{{ $agent->payin_switch_amount }}">
                            <label for="payin_switch_amount">Payin Switch Amount</label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <input type="text" name="payin_commission_below" id="payin_commission_below"
                                class="form-control" placeholder="Payin Commission Below"
                                value="{{ $agent->payin_commission_below }}">
                            <label for="payin_commission_below">Payin Commission Below</label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <select name="payin_commission_type_below" id="payin_commission_type_below"
                                class="form-control" placeholder="Payin Commission Type Below">
                                <option value="">Select</option>
                                <option value="flat"
                                    {{ $agent->payin_commission_type_below == 'flat' ? 'selected' : '' }}>Flat</option>
                                <option value="percent"
                                    {{ $agent->payin_commission_type_below == 'percent' ? 'selected' : '' }}>Percent
                                </option>
                            </select>
                            <label for="payin_commission_type_below">Payin Commission Type Below</label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <input type="text" name="payin_commission_above" id="payin_commission_above"
                                class="form-control" placeholder="Payin Commission Above"
                                value="{{ $agent->payin_commission_above }}">
                            <label for="payin_commission_above">Payin Commission Above</label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <select name="payin_commission_type_above" id="payin_commission_type_above"
                                class="form-control" placeholder="Payin Commission Type Above">
                                <option value="">Select</option>
                                <option value="flat"
                                    {{ $agent->payin_commission_type_above == 'flat' ? 'selected' : '' }}>Flat</option>
                                <option value="percent"
                                    {{ $agent->payin_commission_type_above == 'percent' ? 'selected' : '' }}>Percent
                                </option>
                            </select>
                            <label for="payin_commission_type_above">Payin Commission Type Above</label>
                        </div>
                    </div>
                </div>
                <h3>Payout Commission Configuration</h3>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <input type="text" name="payout_switch_amount" id="payout_switch_amount"
                                class="form-control" placeholder="Payout Switch Amount"
                                value="{{ $agent->payout_switch_amount }}">
                            <label for="payout_switch_amount">Payout Switch Amount</label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <input type="text" name="payout_commission_below" id="payout_commission_below"
                                class="form-control" placeholder="Payout Commission Below"
                                value="{{ $agent->payout_commission_below }}">
                            <label for="payout_commission_below">Payout Commission Below</label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <select name="payout_commission_type_below" id="payout_commission_type_below"
                                class="form-control" placeholder="Payout Commission Type Below">
                                <option value="">Select</option>
                                <option value="flat"
                                    {{ $agent->payout_commission_type_below == 'flat' ? 'selected' : '' }}>Flat
                                </option>
                                <option value="percent"
                                    {{ $agent->payout_commission_type_below == 'percent' ? 'selected' : '' }}>Percent
                                </option>
                            </select>
                            <label for="payout_commission_type_below">Payout Commission Type Below</label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <input type="text" name="payout_commission_above" id="payout_commission_above"
                                class="form-control" placeholder="Payout Commission Above"
                                value="{{ $agent->payout_commission_above }}">
                            <label for="payout_commission_above">Payout Commission Above</label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <select name="payout_commission_type_above" id="payout_commission_type_above"
                                class="form-control" placeholder="Payout Commission Type Above">
                                <option value="">Select</option>
                                <option value="flat"
                                    {{ $agent->payout_commission_type_above == 'flat' ? 'selected' : '' }}>Flat
                                </option>
                                <option value="percent"
                                    {{ $agent->payout_commission_type_above == 'percent' ? 'selected' : '' }}>Percent
                                </option>
                            </select>
                            <label for="payout_commission_type_above">Payout Commission Type Above</label>
                        </div>
                    </div>
                </div>
                <h3>Password</h3>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <input type="password" name="agent_password_new" id="agent_password_new"
                                class="form-control" placeholder="New Password">
                            <label for="agent_password_new">New Password</label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <input type="password" name="agent_password_new_confirmed"
                                id="agent_password_new_confirmed" class="form-control"
                                placeholder="Confirm New Password">
                            <label for="agent_password_new_confirmed">Confirm New Password</label>
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
        <div class="card-body">
            <h3>Beneficiaries</h3>
            <div class="table-responsive overflow-auto">
                <table class="table table-hover table-bordered text-center" id="bene-table">
                    <thead class="table-light">
                        <tr>
                            <th>Name</i></th>
                            <th>Email</th>
                            <th>Mobile</th>
                            <th>Account No</th>
                            <th>IFSC Code</th>
                            <th>Bank Name</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (isset($beneficiaries))
                            @foreach ($beneficiaries as $bene)
                                <tr>
                                    <td class="text-capitalize">{{ $bene->name }}</td>
                                    <td>{{ $bene->email }}</td>
                                    <td>{{ $bene->mobile }}</td>
                                    <td>{{ $bene->account_no }}</td>
                                    <td>{{ $bene->ifsc }}</td>
                                    <td>{{ $bene->bank_name }}</td>
                                    <td>
                                        @if ($bene->status == 'pending')
                                            <span class="btn btn-primary py-0 px-2 rounded-pill text-bg-primary"
                                                style="cursor: auto !important">Pending</span>
                                        @elseif($bene->status == 'active')
                                            <span class="btn btn-success py-0 px-2 rounded-pill text-bg-success"
                                                style="cursor: auto !important">Active</span>
                                        @elseif($bene->status == 'rejected')
                                            <span class="btn btn-danger py-0 px-2 rounded-pill text-bg-danger"
                                                style="cursor: auto !important">Rejected</span>
                                        @endif
                                    </td>
                                    <td>
                                        <select class="form-select" name="bene_status" id="bene_status"
                                            data-id="{{ $bene->id }}">
                                            <option value="pending"
                                                {{ $bene->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="active" {{ $bene->status == 'active' ? 'selected' : '' }}>
                                                Active</option>
                                            <option value="rejected"
                                                {{ $bene->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                            <option value="deleted"
                                                {{ $bene->status == 'deleted' ? 'selected' : '' }}>Deleted</option>
                                        </select>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@else
    <h1>Something went wrong! Please contact developer Ketan Gupta. Contact number - <a href="tel:+918709250721"
            target="blank">8709250721</a></h1>
@endif
<script>
    $('#bene-table').dataTable();

    $("#bene_status").change(function() {
        let beneId = $(this).data("id");
        let status = $(this).val();

        $.ajax({
            url: "/admin/agents/beneficiary-status-update",
            type: "POST",
            data: {
                bene_id: beneId,
                status: status,
                _token: $('meta[name="csrf-token"]').attr("content")
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: "success",
                        title: "Success",
                        text: response.message
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: "warning",
                        title: "Error",
                        text: response.message
                    });
                }
            },
            error: function() {
                alert("Something went wrong! Please try again.");
            }
        });
    });
</script>
