<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Merchant Approval</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">Merchant Approval</li>
                </ol>
            </div>

        </div>
    </div>
</div>
<!-- end page title -->
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped" id="merchant_table">
                    <thead>
                        <tr>
                            <th>S. No.</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Joining Date</th>
                            <th>Is Onboarded</th>
                            <th>Approval Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="table_data">
                        @if (isset($merchants))
                            @foreach ($merchants as $merchant)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $merchant->merchant_name }}</td>
                                    <td>{{ $merchant->merchant_phone }}</td>
                                    <td>{{ $merchant->merchant_email }}</td>
                                    <td>{{ date('d M Y', strtotime($merchant->created_at)) }}</td>
                                    <td>{{ $merchant->merchant_is_onboarded }}</td>
                                    <td>{{ $merchant->merchant_is_verified }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-icon btn-sm fs-16 text-muted dropdown" type="button"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ri-more-fill"></i>
                                            </button>
                                            <ul class="dropdown-menu" style="">
                                                <li><a class="dropdown-item view"
                                                        href="{{ url('/admin/merchant/approval/view-') }}{{ $merchant->merchant_id }}"><i
                                                            class="ri-eye-fill text-muted me-2 align-bottom"></i>View</a>
                                                </li>
                                                @if ($merchant->merchant_is_verified == 'Not Approved')
                                                    <li><a class="dropdown-item approve" href="javascript:void(0);"
                                                            data-id="{{ $merchant->merchant_id }}"><i
                                                                class="ri-check-fill text-muted me-2 align-bottom"></i>Approve</a>
                                                    </li>
                                                @elseif($merchant->merchant_is_verified == 'Approved')
                                                    <li><a class="dropdown-item revoke" href="javascript:void(0);"
                                                            data-id="{{ $merchant->merchant_id }}"><i
                                                                class="ri-close-line text-muted me-2 align-bottom"></i>Revoke
                                                            Approval</a></li>
                                                @endif
                                                <li><a class="dropdown-item setting"
                                                        href="{{ url('/admin/merchant/approval/setting-') }}{{ $merchant->merchant_id }}"><i
                                                            class="ri-settings-5-line text-muted me-2 align-bottom"></i>Setting</a>
                                                </li>
                                                <li><a class="dropdown-item delete" href="javascript:void(0);"
                                                        data-id="{{ $merchant->merchant_id }}"><i
                                                            class="ri-delete-bin-5-fill text-muted me-2 align-bottom"></i>Delete</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    function checkBtn(merchant_id) {
        if (!merchant_id) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: "Something went wrong! Please reload the page and try again!"
            });
            return false;
        }
        return true;
    }

    function regenerateMerchantTable() {
        $.get("{{ url('admin/merchant/fetch') }}", function(res) {
            console.log(res); // Log the response to check the structure
            if (res.status) {
                $('#merchant_table').DataTable().destroy();
                $('#table_data').html('');
                $.each(res.data, function(key, value) {
                    let btn = '';
                    if (value.merchant_is_verified == 'Not Approved') {
                        btn =
                            `<li><a class="dropdown-item approve" href="javascript:void(0);" data-id="${value.merchant_id}"><i class="ri-check-fill text-muted me-2 align-bottom"></i>Approve</a></li>`;
                    } else if (value.merchant_is_verified == 'Approved') {
                        btn =
                            `<li><a class="dropdown-item revoke" href="javascript:void(0);" data-id="${value.merchant_id}"><i class="ri-close-line text-muted me-2 align-bottom"></i>Revoke Approval</a></li>`;
                    }
                    // Date formatting
                    let date = new Date(value.created_at);
                    let formattedDate = date.toLocaleDateString('en-GB', {
                        day: '2-digit',
                        month: 'short',
                        year: 'numeric'
                    });
                    $('#table_data').append(`
                    <tr>
                        <td>${key + 1}</td>
                        <td>${value.merchant_name}</td>
                        <td>${value.merchant_phone}</td>
                        <td>${value.merchant_email}</td>
                        <td>${formattedDate}</td>
                        <td>${value.merchant_is_onboarded}</td>
                        <td>${value.merchant_is_verified}</td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-icon btn-sm fs-16 text-muted dropdown" type="button" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                    <i class="ri-more-fill"></i>
                                </button>
                                <ul class="dropdown-menu" style="">
                                    <li><a class="dropdown-item view" href="{{ url('/admin/merchant/approval/view-') }}${value.merchant_id}"><i
                                                class="ri-eye-fill text-muted me-2 align-bottom"></i>View</a></li>
                                    ${btn}
                                    <li><a class="dropdown-item setting"
                                                        href="{{ url('/admin/merchant/approval/setting-') }}${value.merchant_id}"><i
                                                            class="ri-settings-5-line text-muted me-2 align-bottom"></i>Setting</a>
                                                </li>
                                    <li><a class="dropdown-item delete" href="javascript:void(0);" data-id="${value.merchant_id}"><i
                                                class="ri-delete-bin-5-fill text-muted me-2 align-bottom"></i>Delete</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                `);
                });
                $('#merchant_table').dataTable(); // Re-initialize DataTable
            }
        }).fail(function(error) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                html: error.responseJSON.message
            });
        });
    }

    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('#merchant_table').DataTable({
            destroy: true, // Destroy previous instance if any
            paging: true, // Enable pagination if needed
            searching: true // Enable search functionality
        });
        regenerateMerchantTable();
        // $(document).on('click', '.view', function() {
        //     const merchant_id = $(this).attr('data-id');
        //     if (!checkBtn(merchant_id)) {
        //         return;
        //     }
        // });
        $(document).on('click', '.approve', function() {
            const merchant_id = $(this).attr('data-id');
            if (!checkBtn(merchant_id)) {
                return;
            }
            $.post("{{ url('admin/merchant/approval-approve') }}", {
                merchant_id: merchant_id
            }, function(res) {
                if (res) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Merchant approved!'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Something went wrong! Please try again later.'
                    });
                }
                regenerateMerchantTable();
            }).fail(function(error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    html: error.responseJSON.message
                });
            });
        });
        $(document).on('click', '.revoke', function() {
            const merchant_id = $(this).attr('data-id');
            if (!checkBtn(merchant_id)) {
                return;
            }
            $.post("{{ url('admin/merchant/approval-revoke') }}", {
                merchant_id: merchant_id
            }, function(res) {
                if (res) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Merchant approval revoked!'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Something went wrong! Please try again later.'
                    });
                }
                regenerateMerchantTable();
            }).fail(function(error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    html: error.responseJSON.message
                });
            });
        });
        $(document).on('click', '.delete', function() {
            const merchant_id = $(this).attr('data-id');
            if (!checkBtn(merchant_id)) {
                return;
            }
            Swal.fire({
                html: `
                    <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" 
                            trigger="loop" 
                            colors="primary:#f7b84b,secondary:#f06548" 
                            style="width:100px;height:100px;margin: 14px 0;">
                    </lord-icon>
                    <div style='margin: 14px 0;'><b>Are you sure?</b></div>
                    <div>You won't be able to revert this!</div>
                `,
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post("{{ url('admin/merchant/delete') }}", {
                        merchant_id: merchant_id
                    }, function(response) {
                        if (response == true) {
                            Swal.fire({
                                title: "Deleted!",
                                text: "Your file has been deleted.",
                                icon: "success"
                            });
                            regenerateMerchantTable();
                        } else {
                            Swal.fire({
                                title: "Error!",
                                text: "Something went wrong! Please try again later.",
                                icon: "error"
                            });
                        }
                    }).fail(function(error) {
                        Swal.fire({
                            title: "Error!",
                            html: error.responseJSON.message,
                            icon: "error"
                        });
                    });
                }
            });
        });
    });
</script>
