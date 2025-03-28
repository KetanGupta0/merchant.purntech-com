<style>
    table tr {
        vertical-align: middle;
        text-align: start
    }
    .header {
        background: linear-gradient(to bottom, #405289, #6a89cc);
        color: white;
        padding: 10px;
        text-align: left;
        height: 250px;
    }

    .dashboard-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        padding: 10px;
    }

    .dashboard-header .dashboard-button {
        border: 1px solid white;
        color: white;
        background: transparent;
        padding: 8px 20px;
        border-radius: 5px;
        font-size: 16px;
        cursor: pointer;
        transition: 0.3s;
    }

    .dashboard-button:hover {
        background: white;
        color: #a00000;
    }

    @media (max-width: 768px) {
        .dashboard-header {
            flex-direction: column;
            text-align: center;
        }

        .dashboard-header .dashboard-button {
            margin-top: 10px;
            width: 100%;
        }
    }
</style>

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Beneficiaries</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">Beneficiaries</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<!-- end page title -->


<!-- header section -->
<div class="container-fluid">
    <div class="header">
        <div class="dashboard-header">
            <div class="fw-bold fs-5">DASHBOARD</div>
            <button class="dashboard-button btn">Active balance
                    ₹0.00
            </button>
        </div>
    </div>
</div>

<!-- header section -->



<!-- body part -->

<div class="container-fluid mt-3">
    <div class="card shadow-lg">
        <div class="card-body">

            <!-- inner part -->

            <div class="container-fluid mt-4">
                <div class="table-container">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-3">
                        <h5>Beneficiaries</h5>
                        {{-- <button class="btn btn-primary mt-2 mt-md-0"><i class="fas fa-user-plus"></i> Add Beneficiary</button> --}}
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#staticBackdrop">Add Beneficiary</button>
                    </div>
                    <div class="table-responsive overflow-auto">
                        <table class="table table-hover table-bordered text-center" id="bene-table">
                            <thead class="table-light">
                                <tr>
                                    <th>Name <i class="fas fa-sort"></i></th>
                                    <th>Email</th>
                                    <th>Mobile</th>
                                    <th>Account No <i class="fas fa-sort"></i></th>
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
                                                @if($bene->status == 'pending')
                                                <span class="btn btn-primary py-0 px-2 rounded-pill text-bg-primary" style="cursor: auto !important">Pending</span>
                                                @elseif($bene->status == 'active')
                                                <span class="btn btn-success py-0 px-2 rounded-pill text-bg-success" style="cursor: auto !important">Active</span>
                                                @elseif($bene->status == 'rejected')
                                                <span class="btn btn-danger py-0 px-2 rounded-pill text-bg-danger" style="cursor: auto !important">Rejected</span>
                                                @endif                                                
                                            </td>
                                            <td>
                                                <button class="btn btn-warning btn-sm edit-btn"
                                                    data-id="{{ $bene->id }}" data-name="{{ $bene->name }}"
                                                    data-email="{{ $bene->email }}" data-mobile="{{ $bene->mobile }}"
                                                    data-account_no="{{ $bene->account_no }}"
                                                    data-ifsc="{{ $bene->ifsc }}"
                                                    data-bank_name="{{ $bene->bank_name }}"
                                                    data-address="{{ $bene->address }}"
                                                    data-country="{{ $bene->country }}"
                                                    data-state="{{ $bene->state }}" data-city="{{ $bene->city }}"
                                                    data-pincode="{{ $bene->pincode }}" data-bs-toggle="modal"
                                                    data-bs-target="#editBeneficiaryModal">
                                                    <i class="ri-edit-box-line"></i>
                                                </button>

                                                <!-- Delete Button -->
                                                <form action="{{ url('agent/delete/beneficiary', $bene->id) }}"
                                                    method="POST" class="d-inline delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-between flex-column flex-md-row">

                    </div>
                </div>
            </div>

            <!-- \end inner part -->
        </div>
    </div>
</div>


<!-- end body part -->

<!-- Button trigger modal -->

<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <form class="modal-content" method="POST" action="{{ url('agent/add/beneficiary') }}">
            @csrf
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Add Beneficiary</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group mb-3">
                    <label for="name"><span class="text-danger">*</span> Name</label>
                    <input type="text" name="name" id="name" class="form-control">
                </div>
                <div class="form-group mb-3">
                    <label for="email"><span class="text-danger">*</span> Email</label>
                    <input type="email" name="email" id="email" class="form-control">
                </div>
                <div class="form-group mb-3">
                    <label for="mobile"><span class="text-danger">*</span> Mobile</label>
                    <input type="text" name="mobile" id="mobile" class="form-control">
                </div>
                <div class="form-group mb-3">
                    <label for="account_no"><span class="text-danger">*</span> Account Number</label>
                    <input type="text" name="account_no" id="account_no" class="form-control">
                </div>
                <div class="form-group mb-3">
                    <label for="ifsc"><span class="text-danger">*</span> IFSC</label>
                    <input type="text" name="ifsc" id="ifsc" class="form-control">
                </div>
                <div class="form-group mb-3">
                    <label for="bank_name"><span class="text-danger">*</span> Bank Name</label>
                    <input type="text" name="bank_name" id="bank_name" class="form-control">
                </div>
                <div class="form-group mb-3">
                    <label for="address">Address</label>
                    <input type="text" name="address" id="address" class="form-control">
                </div>
                <div class="form-group mb-3">
                    <label for="country">Country</label>
                    <input type="text" name="country" id="country" class="form-control">
                </div>
                <div class="form-group mb-3">
                    <label for="state">State</label>
                    <input type="text" name="state" id="state" class="form-control">
                </div>
                <div class="form-group mb-3">
                    <label for="city">City</label>
                    <input type="text" name="city" id="city" class="form-control">
                </div>
                <div class="form-group mb-3">
                    <label for="pincode">Pincode</label>
                    <input type="text" name="pincode" id="pincode" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Add</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Beneficiary Modal -->
<div class="modal fade" id="editBeneficiaryModal" tabindex="-1" aria-labelledby="editBeneficiaryModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <form class="modal-content" method="POST" id="editBeneficiaryForm">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h1 class="modal-title fs-5">Edit Beneficiary</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" id="edit_id">
                <div class="form-group mb-3">
                    <label>Name</label>
                    <input type="text" name="name" id="edit_name" class="form-control">
                </div>
                <div class="form-group mb-3">
                    <label>Email</label>
                    <input type="email" name="email" id="edit_email" class="form-control">
                </div>
                <div class="form-group mb-3">
                    <label>Mobile</label>
                    <input type="text" name="mobile" id="edit_mobile" class="form-control">
                </div>
                <div class="form-group mb-3">
                    <label>Account Number</label>
                    <input type="text" name="account_no" id="edit_account_no" class="form-control">
                </div>
                <div class="form-group mb-3">
                    <label>IFSC</label>
                    <input type="text" name="ifsc" id="edit_ifsc" class="form-control">
                </div>
                <div class="form-group mb-3">
                    <label>Bank Name</label>
                    <input type="text" name="bank_name" id="edit_bank_name" class="form-control">
                </div>
                <div class="form-group mb-3">
                    <label>Address</label>
                    <input type="text" name="address" id="edit_address" class="form-control">
                </div>
                <div class="form-group mb-3">
                    <label>Country</label>
                    <input type="text" name="country" id="edit_country" class="form-control">
                </div>
                <div class="form-group mb-3">
                    <label>State</label>
                    <input type="text" name="state" id="edit_state" class="form-control">
                </div>
                <div class="form-group mb-3">
                    <label>City</label>
                    <input type="text" name="city" id="edit_city" class="form-control">
                </div>
                <div class="form-group mb-3">
                    <label>Pincode</label>
                    <input type="text" name="pincode" id="edit_pincode" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>
</div>


<script>
    $(document).ready(function() {
        $("#bene-table").DataTable();

        $(".edit-btn").click(function() {
            let id = $(this).data("id");

            $("#edit_id").val(id);
            $("#edit_name").val($(this).data("name"));
            $("#edit_email").val($(this).data("email"));
            $("#edit_mobile").val($(this).data("mobile"));
            $("#edit_account_no").val($(this).data("account_no"));
            $("#edit_ifsc").val($(this).data("ifsc"));
            $("#edit_bank_name").val($(this).data("bank_name"));
            $("#edit_address").val($(this).data("address"));
            $("#edit_country").val($(this).data("country"));
            $("#edit_state").val($(this).data("state"));
            $("#edit_city").val($(this).data("city"));
            $("#edit_pincode").val($(this).data("pincode"));

            $("#editBeneficiaryForm").attr("action", "/agent/update/beneficiary/" + id);
        });

        $(".delete-form").submit(function(event) {
            event.preventDefault();
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
    });
</script>
